<?php

namespace App\Modules\Animal\Services;

use App\Models\Animal;

use App\Modules\Animal\Resources\AnimalCollection;
use App\Modules\Animal\Repositories\AnimalsRepository;
use App\Modules\Animal\Requests\ListAllAnimalsRequest;

class AnimalService
{
    public function __construct(private AnimalsRepository $animalsRepository)
    {
    }
  public function listAllAnimals(array $queryParameters)
    {

        $listAllAnimals= (new ListAllAnimalsRequest)->constructQueryCriteria($queryParameters);

        $animals= $this->animalsRepository->findAllBy($listAllAnimals );
        return [
            'data' => new AnimalCollection($animals['data']),
            'count' => $animals['count']
        ];
    }

    public function constructAnimalModel($request)
    {
        $animalModel = [
            'name_en' => $request['name_en'],
            'name_ar' => $request['name_ar'],
            'image' => $request['image'],


        ];
        return $animalModel;
    }
    public function createAnimal($request)
    {

        $animal = $this->constructAnimalModel($request);

        return $this->animalsRepository->create($animal);
    }

    public function updateAnimal($id, $request)
    {


        $animal = $this->constructAnimalModel($request);

        return $this->animalsRepository->update($id, $animal);
    }

    public function deleteAnimal($id)
    {
        return $this->animalsRepository->delete($id);
    }



    public function getAnimalById($id)
    {
        return $this->animalsRepository->find($id);
    }



}
