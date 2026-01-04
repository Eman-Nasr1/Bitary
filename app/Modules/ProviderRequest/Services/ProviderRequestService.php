<?php

namespace App\Modules\ProviderRequest\Services;

use App\Models\User;
use App\Modules\ProviderRequest\Repositories\ProviderRequestsRepository;

class ProviderRequestService
{
    public function __construct(private ProviderRequestsRepository $providerRequestsRepository)
    {
    }

    public function createProviderRequest(array $data, int $userId): \App\Models\ProviderRequest
    {
        // Check if user already has a pending request
        $existingPendingRequest = $this->providerRequestsRepository->findPendingRequestByUserId($userId);
        
        if ($existingPendingRequest) {
            throw new \Exception('You already have a pending provider request.');
        }

        // Add user_id to data
        $data['user_id'] = $userId;
        $data['status'] = 'pending';

        return $this->providerRequestsRepository->create($data);
    }

    public function getUserProviderRequest(int $userId): ?\App\Models\ProviderRequest
    {
        return $this->providerRequestsRepository->findByUserId($userId);
    }

    public function getAllRequests(array $queryParameters = []): array
    {
        // Set default sorting
        if (!isset($queryParameters['sortBy'])) {
            $queryParameters['sortBy'] = 'id';
        }
        if (!isset($queryParameters['sort'])) {
            $queryParameters['sort'] = 'DESC';
        }

        $requests = $this->providerRequestsRepository->findAllBy($queryParameters, ['user', 'reviewedBy']);

        return [
            'data' => $requests['data'],
            'count' => $requests['count']
        ];
    }

    public function getRequestById(int $id): ?\App\Models\ProviderRequest
    {
        return \App\Models\ProviderRequest::with(['user', 'reviewedBy'])->find($id);
    }

    public function approveRequest(int $requestId, int $adminId, ?string $adminNote = null): \App\Models\ProviderRequest
    {
        $request = $this->providerRequestsRepository->find($requestId);
        
        if (!$request) {
            throw new \Exception('Provider request not found.');
        }

        if ($request->status !== 'pending') {
            throw new \Exception('This request has already been reviewed.');
        }

        // Update request
        $request->update([
            'status' => 'approved',
            'admin_note' => $adminNote,
            'reviewed_by' => $adminId,
            'reviewed_at' => now(),
        ]);

        // Make user a provider
        $user = User::find($request->user_id);
        if ($user) {
            $user->update([
                'is_provider' => true,
                'role' => 'provider'
            ]);
        }

        return $request->fresh(['user', 'reviewedBy']);
    }

    public function rejectRequest(int $requestId, int $adminId, ?string $adminNote = null): \App\Models\ProviderRequest
    {
        $request = $this->providerRequestsRepository->find($requestId);
        
        if (!$request) {
            throw new \Exception('Provider request not found.');
        }

        if ($request->status !== 'pending') {
            throw new \Exception('This request has already been reviewed.');
        }

        // Update request
        $request->update([
            'status' => 'rejected',
            'admin_note' => $adminNote,
            'reviewed_by' => $adminId,
            'reviewed_at' => now(),
        ]);

        return $request->fresh(['user', 'reviewedBy']);
    }
}
