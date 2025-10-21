<?php

namespace App\Modules\Category\Repositories;

use App\Models\Category;
use App\Modules\Shared\Repositories\BaseRepository;

class CategoriesRepository extends BaseRepository
{
    public function __construct(private Category $model)
    {
        parent::__construct($model);
    }


}
