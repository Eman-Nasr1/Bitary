<?php

namespace App\Modules\ProviderRequest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\ProviderRequest\Services\ProviderRequestService;
use App\Modules\ProviderRequest\Requests\StoreProviderRequestRequest;
use App\Traits\HandlesImages;
use Illuminate\Support\Facades\Storage;

class ApiProviderRequestController extends Controller
{
    use HandlesImages;

    public function __construct(private ProviderRequestService $providerRequestService)
    {
    }

    public function store(StoreProviderRequestRequest $request)
    {
        try {
            $user = auth('sanctum')->user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated'
                ], 401);
            }

            // Check if user is already a provider
            if ($user->is_provider) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You are already a provider.'
                ], 422);
            }

            $data = $request->validated();

            // Handle image upload
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $data['image'] = $this->uploadImage($request->file('image'), 'provider-requests');
            }

            // Handle file uploads
            if ($request->hasFile('id_document')) {
                $data['id_document'] = $request->file('id_document')->store('provider-documents', 'public');
            }

            if ($request->hasFile('license_document')) {
                $data['license_document'] = $request->file('license_document')->store('provider-documents', 'public');
            }

            $providerRequest = $this->providerRequestService->createProviderRequest($data, $user->id);

            return response()->json([
                'status' => 'success',
                'message' => 'Provider request submitted successfully. It will be reviewed by an administrator.',
                'data' => [
                    'id' => $providerRequest->id,
                    'entity_name' => $providerRequest->entity_name,
                    'status' => $providerRequest->status,
                    'created_at' => $providerRequest->created_at,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function status(Request $request)
    {
        $user = auth('sanctum')->user();
        
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated'
            ], 401);
        }

        $providerRequest = $this->providerRequestService->getUserProviderRequest($user->id);

        if (!$providerRequest) {
            return response()->json([
                'status' => 'success',
                'message' => 'No provider request found',
                'data' => null
            ]);
        }

        $responseData = [
            'id' => $providerRequest->id,
            'entity_name' => $providerRequest->entity_name,
            'specialty' => $providerRequest->specialty,
            'degree' => $providerRequest->degree,
            'phone' => $providerRequest->phone,
            'whatsapp' => $providerRequest->whatsapp,
            'email' => $providerRequest->email,
            'address' => $providerRequest->address,
            'google_maps_link' => $providerRequest->google_maps_link,
            'image_url' => $providerRequest->image_url,
            'status' => $providerRequest->status,
            'admin_note' => $providerRequest->admin_note,
            'reviewed_at' => $providerRequest->reviewed_at,
            'created_at' => $providerRequest->created_at,
            'updated_at' => $providerRequest->updated_at,
        ];

        // Add document URLs if they exist
        if ($providerRequest->id_document) {
            $responseData['id_document_url'] = url('/storage/' . $providerRequest->id_document);
        }

        if ($providerRequest->license_document) {
            $responseData['license_document_url'] = url('/storage/' . $providerRequest->license_document);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Provider request status retrieved successfully',
            'data' => $responseData
        ]);
    }

    /**
     * Get all approved providers (doctors or clinics)
     */
    public function getProviders(Request $request)
    {
        $query = \App\Models\ProviderRequest::with('user')
            ->where('status', 'approved')
            ->whereIn('provider_type', ['doctor', 'clinic']);

        // Filter by provider type if specified
        if ($request->has('provider_type')) {
            $providerType = $request->get('provider_type');
            if (in_array($providerType, ['doctor', 'clinic'])) {
                $query->where('provider_type', $providerType);
            }
        }

        // Filter by city_id
        if ($request->has('city_id')) {
            $cityId = $request->get('city_id');
            $query->whereHas('user', function($userQuery) use ($cityId) {
                $userQuery->where('city_id', $cityId);
            });
        }

        // Search
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('entity_name', 'LIKE', "%{$search}%")
                  ->orWhere('specialty', 'LIKE', "%{$search}%")
                  ->orWhere('address', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%")
                                ->orWhere('email', 'LIKE', "%{$search}%")
                                ->orWhere('phone', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $providers = $query->orderBy('id', 'DESC')->paginate($perPage);

        // Format response data
        $formattedProviders = $providers->map(function($providerRequest) {
            $user = $providerRequest->user;
            
            return [
                'id' => $providerRequest->id,
                'provider_type' => $providerRequest->provider_type,
                'entity_name' => $providerRequest->entity_name,
                'specialty' => $providerRequest->specialty,
                'degree' => $providerRequest->degree,
                'phone' => $providerRequest->phone,
                'whatsapp' => $providerRequest->whatsapp,
                'email' => $providerRequest->email,
                'address' => $providerRequest->address,
                'google_maps_link' => $providerRequest->google_maps_link,
                'image_url' => $providerRequest->image_url,
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'family_name' => $user->family_name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'city_id' => $user->city_id,
                ] : null,
                'created_at' => $providerRequest->created_at,
                'updated_at' => $providerRequest->updated_at,
            ];
        });

        return successJsonResponse(
            $formattedProviders->toArray(),
            'Providers retrieved successfully',
            $providers->total()
        );
    }

    /**
     * Get single provider by ID
     */
    public function getProvider($id)
    {
        $providerRequest = \App\Models\ProviderRequest::with('user')
            ->where('status', 'approved')
            ->whereIn('provider_type', ['doctor', 'clinic'])
            ->find($id);

        if (!$providerRequest) {
            return errorJsonResponse(
                'Provider not found',
                404
            );
        }

        $user = $providerRequest->user;

        $formattedProvider = [
            'id' => $providerRequest->id,
            'provider_type' => $providerRequest->provider_type,
            'entity_name' => $providerRequest->entity_name,
            'specialty' => $providerRequest->specialty,
            'degree' => $providerRequest->degree,
            'phone' => $providerRequest->phone,
            'whatsapp' => $providerRequest->whatsapp,
            'email' => $providerRequest->email,
            'address' => $providerRequest->address,
            'google_maps_link' => $providerRequest->google_maps_link,
            'image_url' => $providerRequest->image_url,
            'user' => $user ? [
                'id' => $user->id,
                'name' => $user->name,
                'family_name' => $user->family_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'city_id' => $user->city_id,
            ] : null,
            'created_at' => $providerRequest->created_at,
            'updated_at' => $providerRequest->updated_at,
        ];

        // Add document URLs if they exist
        if ($providerRequest->id_document) {
            $formattedProvider['id_document_url'] = url('/storage/' . $providerRequest->id_document);
        }

        if ($providerRequest->license_document) {
            $formattedProvider['license_document_url'] = url('/storage/' . $providerRequest->license_document);
        }

        return successJsonResponse(
            $formattedProvider,
            'Provider retrieved successfully'
        );
    }
}

