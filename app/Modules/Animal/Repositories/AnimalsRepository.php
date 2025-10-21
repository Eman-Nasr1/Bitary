<?php

namespace App\Modules\Animal\Repositories;

use App\Models\Animal;
use App\Modules\Shared\Repositories\BaseRepository;

class AnimalsRepository extends BaseRepository
{
    public function __construct(private Animal $model)
    {
        parent::__construct($model);
    }
   

}
