<?php

namespace App\Modules\Seller;

use App\Models\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Seller\Services\SellerService;
use App\Modules\Seller\Requests\StoreSellerRequest;
use App\Modules\Seller\Requests\UpdateSellerRequest;
use App\Traits\HandlesImages;


class SellerController extends Controller
{
    use HandlesImages;
    public function __construct(private SellerService $sellerService) {}

    public function index(Request $request)
    {

        $sellers = $this->sellerService->listAllSellers($request->all());

        return view('dashboard.Sellers.index', [
            'sellers' => $sellers['data'],
        ]);
    }


    public function store(StoreSellerRequest $request)
    {

        $data = $request->validated();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image'] = $this->uploadImage($request->file('image'));
        }
        $this->sellerService->createSeller($data);


        return redirect()->back()->with('success', 'Seller created successfully!');
    }



    public function update(UpdateSellerRequest $request, $id)
    {

        $seller = Seller::findOrFail($id);

        $data = $request->validated();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image'] = $this->updateImage($seller->image, $request->file('image'));
        } else {
            $data['image'] = $seller->image;
        }

        $this->sellerService->updateSeller($id, $data);

        return redirect()->back()->with('success', 'Seller updated successfully!');
    }


    public function destroy($id)
    {
        $this->sellerService->deleteSeller($id);
        return redirect()->back()->with('success', 'Seller deleted successfully!');
    }
}
