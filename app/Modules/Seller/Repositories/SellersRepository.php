<?php

namespace App\Modules\Seller\Repositories;

use App\Models\Seller;
use App\Modules\Shared\Repositories\BaseRepository;

class SellersRepository extends BaseRepository
{
    public function __construct(private Seller $model)
    {
        parent::__construct($model);
    }


}
