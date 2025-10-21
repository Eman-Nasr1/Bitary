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
            'name_en' => $request['name_en'],
            'name_ar' => $request['name_ar'],
            'availability' => $request['availability'],
            'phone' => $request['phone'],
            'description_en' => $request['description_en'],
            'description_ar' => $request['description_ar'],
            'image' => $request['image'],
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
