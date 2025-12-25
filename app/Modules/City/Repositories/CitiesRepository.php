<?php

namespace App\Modules\City\Repositories;

use App\Models\City;
use App\Modules\Shared\Repositories\BaseRepository;

class CitiesRepository extends BaseRepository
{
    public function __construct(private City $model)
    {
        parent::__construct($model);
    }
   

}

