<?php

namespace App\Modules\ProviderRequest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\ProviderRequest\Services\ProviderRequestService;
use App\Modules\ProviderRequest\Requests\UpdateProviderRequestStatusRequest;

class AdminProviderRequestController extends Controller
{
    public function __construct(private ProviderRequestService $providerRequestService)
    {
    }

    public function index(Request $request)
    {
        // Set default pagination values
        $limit = max(1, (int)$request->get('limit', 10));
        $offset = max(0, (int)$request->get('offset', 0));
        
        // Set default sorting
        $requestData = $request->all();
        if (!isset($requestData['sortBy'])) {
            $requestData['sortBy'] = 'id';
        }
        if (!isset($requestData['sort'])) {
            $requestData['sort'] = 'DESC';
        }
        
        $requests = $this->providerRequestService->getAllRequests($requestData);

        $totalCount = $requests['count'];
        $totalPages = $totalCount > 0 ? max(1, ceil($totalCount / $limit)) : 1;
        $currentPage = ($offset / $limit) + 1;

        return view('dashboard.ProviderRequests.index', [
            'requests' => $requests['data'],
            'totalCount' => $totalCount,
            'limit' => $limit,
            'offset' => $offset,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
        ]);
    }

    public function show($id)
    {
        $request = $this->providerRequestService->getRequestById($id);
        
        if (!$request) {
            return redirect()->route('dashboard.provider-requests.index')
                ->with('error', 'Provider request not found!');
        }

        return view('dashboard.ProviderRequests.show', [
            'request' => $request
        ]);
    }

    public function approve(UpdateProviderRequestStatusRequest $request, $id)
    {
        try {
            $adminId = auth('admin')->id();
            $adminNote = $request->input('admin_note');
            
            $providerRequest = $this->providerRequestService->approveRequest($id, $adminId, $adminNote);
            
            return redirect()->route('dashboard.provider-requests.index')
                ->with('success', 'Provider request approved successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    public function reject(UpdateProviderRequestStatusRequest $request, $id)
    {
        try {
            $adminId = auth('admin')->id();
            $adminNote = $request->input('admin_note');
            
            $providerRequest = $this->providerRequestService->rejectRequest($id, $adminId, $adminNote);
            
            return redirect()->route('dashboard.provider-requests.index')
                ->with('success', 'Provider request rejected successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}

