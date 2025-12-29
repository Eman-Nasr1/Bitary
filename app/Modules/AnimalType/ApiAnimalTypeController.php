<?php

namespace App\Modules\AnimalType;

use App\Models\AnimalType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\AnimalType\Services\AnimalTypeService;


class ApiAnimalTypeController extends Controller
{
    public function __construct(private AnimalTypeService $animalTypeService) {}

    public function listAllAnimalTypes(Request $request)
    {
        $animalTypes = $this->animalTypeService->listAllAnimalTypes($request->all());
        return successJsonResponse(
            data_get($animalTypes, 'data'),
            __('AnimalTypes.success.get_all_AnimalTypes'),
            data_get($animalTypes, 'count')
        );
    }

    public function show($id)
    {
        $animalType = $this->animalTypeService->getAnimalTypeById($id);

        if (!$animalType) {
            return errorJsonResponse(
                __('AnimalTypes.errors.not_found'),
                404
            );
        }

        return successJsonResponse(
            $animalType,
            __('AnimalTypes.success.get_single_animal_type')
        );
    }
}

