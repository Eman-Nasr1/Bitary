<?php

namespace App\Modules\Animal;

use App\Models\Animal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Animal\Services\AnimalService;
use App\Modules\Animal\Requests\StoreAnimalRequest;
use App\Modules\Animal\Requests\UpdateAnimalRequest;
use App\Modules\AnimalType\Services\AnimalTypeService;
use App\Traits\HandlesImages;


class AnimalController extends Controller
{
    use HandlesImages;
    public function __construct(
        private AnimalService $animalService,
        private AnimalTypeService $animalTypeService
    ) {}

    public function index(Request $request)
    {
       
        $animals = $this->animalService->listAllAnimals($request->all());
        $animalTypes = $this->animalTypeService->listAllAnimalTypes([]);

        return view('dashboard.Animals.index', [
            'Animals' => $animals['data'],
            'animalTypes' => $animalTypes['data'],
        ]);
    }


    public function store(StoreAnimalRequest $request)
    {

        $data = $request->validated();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image'] = $this->uploadImage($request->file('image'));
        }
        $this->animalService->createAnimal($data);


        return redirect()->back()->with('success', 'Animal created successfully!');
    }



    public function update(UpdateAnimalRequest $request, $id)
    {

        $animal = Animal::findOrFail($id);

        $data = $request->validated();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image'] = $this->updateImage($animal->image, $request->file('image'));
        } else {
            $data['image'] = $animal->image;
        }

        $this->animalService->updateAnimal($id, $data);

        return redirect()->back()->with('success', 'Animal updated successfully!');
    }


    public function destroy($id)
    {
        $this->animalService->deleteAnimal($id);
        return redirect()->back()->with('success', 'Animal deleted successfully!');
    }
}
