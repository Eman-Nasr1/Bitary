<?php

namespace App\Modules\Medicine;

use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Traits\HandlesImages;
use App\Http\Controllers\Controller;
use App\Modules\Animal\Services\AnimalService;
use App\Modules\Seller\Services\SellerService;
use App\Modules\Category\Services\CategoryService;
use App\Modules\Medicine\Services\MedicineService;
use App\Modules\Medicine\Requests\StoreMedicineRequest;
use App\Modules\Medicine\Requests\UpdateMedicineRequest;


class MedicineController extends Controller
{
    use HandlesImages;
    public function __construct(private MedicineService $medicineService, private AnimalService  $animalService, private CategoryService $categoryService,private SellerService $sellerService) {}

    public function index(Request $request)
    {

        $medicines = $this->medicineService->listAllMedicines($request->all(), ['category', 'seller', 'animals']);
        $categories = $this->categoryService->listAllCategories($request->all());
        $animals = $this->animalService->listAllAnimals($request->all());
        $sellers = $this->sellerService->listAllSellers($request->all());
        return view('dashboard.Medicines.index', [
            'medicines' => $medicines['data'],
            'animals' => $animals['data'],
            'categories' => $categories['data'],
            'sellers' => $sellers['data'],
        ]);
    }


    public function store(Request $request)
    {

        $data = $request->all();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image'] = $this->uploadImage($request->file('image'), 'medicines');
        }
        $this->medicineService->createMedicine($data);


        return redirect()->back()->with('success', 'Product created successfully!');
    }



    public function update(Request $request, $id)
    {

        $medicine = Medicine::findOrFail($id);

        $data = $request->all();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            $data['image'] = $this->updateImage($medicine->image, $request->file('image'), 'medicines');
       
        } else {
            $data['image'] = $medicine->image;
        }

        $this->medicineService->updateMedicine($id, $data);

        return redirect()->back()->with('success', 'Product updated successfully!');
    }


    public function destroy($id)
    {
        $this->medicineService->deleteMedicine($id);
        return redirect()->back()->with('success', 'Product deleted successfully!');
    }
}
