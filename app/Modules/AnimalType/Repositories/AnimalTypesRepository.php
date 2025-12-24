<?php

namespace App\Modules\AnimalType\Repositories;

use App\Models\AnimalType;
use App\Modules\Shared\Repositories\BaseRepository;

class AnimalTypesRepository extends BaseRepository
{
    public function __construct(private AnimalType $model)
    {
        parent::__construct($model);
    }
   

}

