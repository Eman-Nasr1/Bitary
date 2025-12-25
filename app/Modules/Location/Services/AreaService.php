<?php

namespace App\Modules\Location\Services;

use App\Modules\Location\Repositories\AreaRepository;
use App\Modules\Location\Requests\ListAllAreasRequest;
use App\Modules\Location\Resources\AreaCollection;

class AreaService
{
    public function __construct(private AreaRepository $areaRepository)
    {
    }

    public function listAllAreas($queryParameters)
    {
        // If no limit is specified, set a large limit to get all areas (for API endpoints)
        if (!isset($queryParameters['limit']) && !isset($queryParameters['offset'])) {
            $queryParameters['limit'] = 1000; // Large enough to get all areas
        }
        
        // Set default sorting by ID ascending if not specified
        if (!isset($queryParameters['sortBy'])) {
            $queryParameters['sortBy'] = 'id';
        }
        if (!isset($queryParameters['sort'])) {
            $queryParameters['sort'] = 'ASC';
        }
        
        $listAllAreas = (new ListAllAreasRequest)->constructQueryCriteria($queryParameters);
        $Areas = $this->areaRepository->getAllAreas($listAllAreas);

        return [
            'data' => new AreaCollection($Areas['data']),
            'count' => $Areas['count']
        ];
    }
}
