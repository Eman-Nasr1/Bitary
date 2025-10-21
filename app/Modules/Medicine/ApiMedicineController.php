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


class ApiMedicineController extends Controller
{
    use HandlesImages;
    public function __construct(private MedicineService $medicineService, private AnimalService  $animalService, private CategoryService $categoryService, private SellerService $sellerService) {}

    public function listAllMedicines(Request $request)
    {
        $medicines = $this->medicineService->listAllMedicines($request->all(), ['category', 'seller', 'animals']);
        return successJsonResponse(
            data_get($medicines, 'data'),
            __('Medicines.success.get_all_Medicines'),
            data_get($medicines, 'count')
        );
    }

    public function show($id)
    {
        $medicine = $this->medicineService->getMedicineById($id);

        if (!$medicine) {
            return errorJsonResponse(
                __('Medicines.errors.not_found'),
                404
            );
        }

        return successJsonResponse(
            $medicine,
            __('Medicines.success.get_single_medicine')
        );
    }
}
