<?php

namespace App\Modules\ProviderRequest\Repositories;

use App\Models\ProviderRequest;
use App\Modules\Shared\Repositories\BaseRepository;

class ProviderRequestsRepository extends BaseRepository
{
    public function __construct(private ProviderRequest $model)
    {
        parent::__construct($model);
    }

    public function create($attributes): ProviderRequest
    {
        return ProviderRequest::create($attributes);
    }

    public function findPendingRequestByUserId(int $userId): ?ProviderRequest
    {
        return ProviderRequest::where('user_id', $userId)
            ->where('status', 'pending')
            ->first();
    }

    public function findByUserId(int $userId): ?ProviderRequest
    {
        return ProviderRequest::where('user_id', $userId)
            ->latest()
            ->first();
    }
}

