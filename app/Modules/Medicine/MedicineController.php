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
        $user = auth('admin')->user() ?? auth('web')->user();
        $isProvider = $user && method_exists($user, 'isProvider') && $user->isProvider();
        $isAdmin = $user && (auth('admin')->check() || (method_exists($user, 'isAdmin') && $user->isAdmin()));

        $filters = $request->all();
        
        // If provider, only show their medicines
        if ($isProvider && !$isAdmin) {
            $filters['provider_id'] = $user->id;
        }

        $medicines = $this->medicineService->listAllMedicines($filters, ['category', 'seller', 'animals', 'provider']);
        $categories = $this->categoryService->listAllCategories($request->all());
        $animals = $this->animalService->listAllAnimals($request->all());
        $sellers = $this->sellerService->listAllSellers($request->all());
        
        return view('dashboard.Medicines.index', [
            'medicines' => $medicines['data'],
            'animals' => $animals['data'],
            'categories' => $categories['data'],
            'sellers' => $sellers['data'],
            'isProvider' => $isProvider && !$isAdmin,
        ]);
    }


    public function store(Request $request)
    {
        $user = auth('admin')->user() ?? auth('web')->user();
        $isProvider = $user && method_exists($user, 'isProvider') && $user->isProvider();
        $isAdmin = $user && (auth('admin')->check() || (method_exists($user, 'isAdmin') && $user->isAdmin()));

        $data = $request->all();

        // Set provider_id if user is provider
        if ($isProvider && !$isAdmin) {
            $data['provider_id'] = $user->id;
        }

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image'] = $this->uploadImage($request->file('image'), 'medicines');
        }
        
        $this->medicineService->createMedicine($data);

        return redirect()->back()->with('success', 'Product created successfully!');
    }



    public function update(Request $request, $id)
    {
        $user = auth('admin')->user() ?? auth('web')->user();
        $isProvider = $user && method_exists($user, 'isProvider') && $user->isProvider();
        $isAdmin = $user && (auth('admin')->check() || (method_exists($user, 'isAdmin') && $user->isAdmin()));

        $medicine = Medicine::findOrFail($id);

        // Check ownership if provider
        if ($isProvider && !$isAdmin && $medicine->provider_id != $user->id) {
            abort(403, 'You do not have permission to update this product.');
        }

        $data = $request->all();

        // Ensure provider_id doesn't change for providers
        if ($isProvider && !$isAdmin) {
            $data['provider_id'] = $user->id;
        }

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
        $user = auth('admin')->user() ?? auth('web')->user();
        $isProvider = $user && method_exists($user, 'isProvider') && $user->isProvider();
        $isAdmin = $user && (auth('admin')->check() || (method_exists($user, 'isAdmin') && $user->isAdmin()));

        $medicine = Medicine::findOrFail($id);

        // Check ownership if provider
        if ($isProvider && !$isAdmin && $medicine->provider_id != $user->id) {
            abort(403, 'You do not have permission to delete this product.');
        }

        $this->medicineService->deleteMedicine($id);
        return redirect()->back()->with('success', 'Product deleted successfully!');
    }
}
