<?php

namespace App\Modules\Seller\Services;

use App\Models\Seller;

use App\Modules\Seller\Resources\SellerCollection;
use App\Modules\Seller\Repositories\SellersRepository;
use App\Modules\Seller\Requests\ListAllSellersRequest;

class SellerService
{
    public function __construct(private SellersRepository $sellersRepository)
    {
    }
  public function listAllSellers(array $queryParameters)
    {

        $listAllSellers= (new ListAllSellersRequest)->constructQueryCriteria($queryParameters);

        $sellers= $this->sellersRepository->findAllBy($listAllSellers );
        return [
            'data' => new SellerCollection($sellers['data']),
            'count' => $sellers['count']
        ];
    }

    public function constructSellerModel($request)
    {
        $sellerModel = [
            'name_en' => $request['name_en'] ?? null,
            'name_ar' => $request['name_ar'] ?? null,
            'availability' => $request['availability'] ?? '24/7',
            'phone' => $request['phone'] ?? null,
            'description_en' => $request['description_en'] ?? null,
            'description_ar' => $request['description_ar'] ?? null,
            'image' => $request['image'] ?? null,
        ];
        return $sellerModel;
    }
    public function createSeller($request)
    {

        $seller = $this->constructSellerModel($request);

        return $this->sellersRepository->create($seller);
    }

    public function updateSeller($id, $request)
    {


        $seller = $this->constructSellerModel($request);

        return $this->sellersRepository->update($id, $seller);
    }

    public function deleteSeller($id)
    {
        return $this->sellersRepository->delete($id);
    }



    public function getSellerById($id)
    {
        return $this->sellersRepository->find($id);
    }



}
