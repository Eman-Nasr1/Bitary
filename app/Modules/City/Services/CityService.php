<?php

namespace App\Modules\City\Services;

use App\Models\City;

use App\Modules\City\Resources\CityCollection;
use App\Modules\City\Repositories\CitiesRepository;
use App\Modules\City\Requests\ListAllCitiesRequest;

class CityService
{
    public function __construct(private CitiesRepository $citiesRepository)
    {
    }
  public function listAllCities(array $queryParameters)
    {
        // Set default sorting by ID ascending if not specified
        if (!isset($queryParameters['sortBy'])) {
            $queryParameters['sortBy'] = 'id';
        }
        if (!isset($queryParameters['sort'])) {
            $queryParameters['sort'] = 'ASC';
        }

        $listAllCities= (new ListAllCitiesRequest)->constructQueryCriteria($queryParameters);

        $cities= $this->citiesRepository->findAllBy($listAllCities );
        return [
            'data' => new CityCollection($cities['data']),
            'count' => $cities['count']
        ];
    }

    public function constructCityModel($request)
    {
        $cityModel = [
            'name' => $request['name'],
        ];
        return $cityModel;
    }
    public function createCity($request)
    {

        $city = $this->constructCityModel($request);

        return $this->citiesRepository->create($city);
    }

    public function updateCity($id, $request)
    {


        $city = $this->constructCityModel($request);

        return $this->citiesRepository->update($id, $city);
    }

    public function deleteCity($id)
    {
        return $this->citiesRepository->delete($id);
    }



    public function getCityById($id)
    {
        return $this->citiesRepository->find($id);
    }



}

