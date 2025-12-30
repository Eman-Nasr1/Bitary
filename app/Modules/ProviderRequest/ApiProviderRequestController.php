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
            'status' => $providerRequest->status,
            'admin_note' => $providerRequest->admin_note,
            'reviewed_at' => $providerRequest->reviewed_at,
            'created_at' => $providerRequest->created_at,
            'updated_at' => $providerRequest->updated_at,
        ];

        // Add document URLs if they exist
        if ($providerRequest->id_document) {
            $responseData['id_document_url'] = Storage::disk('public')->url($providerRequest->id_document);
        }

        if ($providerRequest->license_document) {
            $responseData['license_document_url'] = Storage::disk('public')->url($providerRequest->license_document);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Provider request status retrieved successfully',
            'data' => $responseData
        ]);
    }
}

