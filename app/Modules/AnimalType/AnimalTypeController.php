<?php

namespace App\Modules\AnimalType;

use App\Models\AnimalType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\AnimalType\Services\AnimalTypeService;
use App\Modules\AnimalType\Requests\StoreAnimalTypeRequest;
use App\Modules\AnimalType\Requests\UpdateAnimalTypeRequest;
use App\Traits\HandlesImages;


class AnimalTypeController extends Controller
{
    use HandlesImages;
    public function __construct(private AnimalTypeService $animalTypeService) {}

    public function index(Request $request)
    {
       
        $animalTypes = $this->animalTypeService->listAllAnimalTypes($request->all());

        return view('dashboard.AnimalTypes.index', [
            'AnimalTypes' => $animalTypes['data'],
        ]);
    }


    public function store(StoreAnimalTypeRequest $request)
    {

        $data = $request->validated();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image'] = $this->uploadImage($request->file('image'), 'animal_types');
        }
        $this->animalTypeService->createAnimalType($data);


        return redirect()->back()->with('success', 'Animal Type created successfully!');
    }



    public function update(UpdateAnimalTypeRequest $request, $id)
    {

        $animalType = AnimalType::findOrFail($id);

        $data = $request->validated();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image'] = $this->updateImage($animalType->image, $request->file('image'), 'animal_types');
        } else {
            $data['image'] = $animalType->image;
        }

        $this->animalTypeService->updateAnimalType($id, $data);

        return redirect()->back()->with('success', 'Animal Type updated successfully!');
    }


    public function destroy($id)
    {
        $this->animalTypeService->deleteAnimalType($id);
        return redirect()->back()->with('success', 'Animal Type deleted successfully!');
    }
}

