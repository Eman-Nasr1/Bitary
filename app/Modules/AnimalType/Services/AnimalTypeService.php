<?php

namespace App\Modules\AnimalType\Services;

use App\Models\AnimalType;

use App\Modules\AnimalType\Resources\AnimalTypeCollection;
use App\Modules\AnimalType\Repositories\AnimalTypesRepository;
use App\Modules\AnimalType\Requests\ListAllAnimalTypesRequest;

class AnimalTypeService
{
    public function __construct(private AnimalTypesRepository $animalTypesRepository)
    {
    }
  public function listAllAnimalTypes(array $queryParameters)
    {

        $listAllAnimalTypes= (new ListAllAnimalTypesRequest)->constructQueryCriteria($queryParameters);

        $animalTypes= $this->animalTypesRepository->findAllBy($listAllAnimalTypes );
        return [
            'data' => new AnimalTypeCollection($animalTypes['data']),
            'count' => $animalTypes['count']
        ];
    }

    public function constructAnimalTypeModel($request)
    {
        $animalTypeModel = [
            'name_en' => $request['name_en'],
            'name_ar' => $request['name_ar'],
            'image' => $request['image'],


        ];
        return $animalTypeModel;
    }
    public function createAnimalType($request)
    {

        $animalType = $this->constructAnimalTypeModel($request);

        return $this->animalTypesRepository->create($animalType);
    }

    public function updateAnimalType($id, $request)
    {


        $animalType = $this->constructAnimalTypeModel($request);

        return $this->animalTypesRepository->update($id, $animalType);
    }

    public function deleteAnimalType($id)
    {
        return $this->animalTypesRepository->delete($id);
    }



    public function getAnimalTypeById($id)
    {
        return $this->animalTypesRepository->find($id);
    }



}

