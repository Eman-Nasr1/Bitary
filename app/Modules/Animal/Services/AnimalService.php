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
        // Handle search parameter (searches in name_en and name_ar)
        $searchTerm = null;
        if (isset($queryParameters['search'])) {
            $searchTerm = $queryParameters['search'];
        } elseif (isset($queryParameters['title'])) {
            $searchTerm = $queryParameters['title'];
        }

        // If search term exists, build query directly
        if ($searchTerm) {
            $query = Animal::with(['animalType']);
            
            // Search in both name_en and name_ar
            $query->where(function($q) use ($searchTerm) {
                $q->where('name_en', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('name_ar', 'LIKE', "%{$searchTerm}%");
            });

            // Apply other filters if they exist
            $listAllAnimals = (new ListAllAnimalsRequest)->constructQueryCriteria($queryParameters);
            $filters = data_get($listAllAnimals, 'filters', []);
            if (!empty($filters)) {
                foreach ($filters as $field => $value) {
                    if (!empty($value)) {
                        $query->where($field, 'LIKE', '%' . $value . '%');
                    }
                }
            }

            // Apply pagination and sorting
            $limit = data_get($listAllAnimals, 'limit', 10);
            $offset = data_get($listAllAnimals, 'offset', 0);
            $sortBy = data_get($listAllAnimals, 'sortBy', 'id');
            $sort = data_get($listAllAnimals, 'sort', 'DESC');

            $count = $query->count();
            $data = $query->skip($offset)->take($limit)->orderBy($sortBy, $sort)->get();

            return [
                'data' => new AnimalCollection($data),
                'count' => $count
            ];
        }

        // Normal flow without search
        $listAllAnimals= (new ListAllAnimalsRequest)->constructQueryCriteria($queryParameters);

        $animals= $this->animalsRepository->findAllBy($listAllAnimals, ['animalType']);
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
            'animal_type_id' => $request['animal_type_id'] ?? null,


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
