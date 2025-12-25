<?php

namespace App\Modules\Location\Services;

use App\Modules\Location\Repositories\CityRepository;
use App\Modules\Location\Requests\ListAllCitiesRequest;
use App\Modules\Location\Resources\CityCollection;

class CityService
{
    public function __construct(private CityRepository $cityRepository)
    {
    }

    public function listAllCities($queryParameters)
    {
        // If no limit is specified, set a large limit to get all cities (for API endpoints)
        if (!isset($queryParameters['limit']) && !isset($queryParameters['offset'])) {
            $queryParameters['limit'] = 1000; // Large enough to get all cities
        }
        
        // Set default sorting by ID ascending if not specified
        if (!isset($queryParameters['sortBy'])) {
            $queryParameters['sortBy'] = 'id';
        }
        if (!isset($queryParameters['sort'])) {
            $queryParameters['sort'] = 'ASC';
        }
        
        // Construct Query Criteria
        $listAllCities = (new ListAllCitiesRequest)->constructQueryCriteria($queryParameters);
        
        // Get Cities from Database
        $cities = $this->cityRepository->findAllBy($listAllCities);
         
        return [
            'data' => new CityCollection($cities['data']),
            'count' => $cities['count']
        ];
    }


    public function getCityByName($name){
        return $this->cityRepository->getCity($name);
    }

}
