<?php

namespace App\Modules\Animal;

use App\Models\Animal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Animal\Services\AnimalService;
use App\Modules\Animal\Requests\ListAllAnimalsRequest;


class ApiAnimalController extends Controller
{
    public function __construct(private AnimalService $animalService) {}

    public function listAllAnimals(Request $request)
    {
        $animals = $this->animalService->listAllAnimals($request->all());
        return successJsonResponse(
            data_get($animals, 'data'),
            __('Animals.success.get_all_Animals'),
            data_get($animals, 'count')
        );
    }

    public function show($id)
    {
        $animal = $this->animalService->getAnimalById($id);

        if (!$animal) {
            return errorJsonResponse(
                __('Animals.errors.not_found'),
                404
            );
        }

        return successJsonResponse(
            $animal,
            __('Animals.success.get_single_animal')
        );
    }
}
