<?php

namespace App\Modules\City;

use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\City\Services\CityService;
use App\Modules\City\Requests\StoreCityRequest;
use App\Modules\City\Requests\UpdateCityRequest;


class CityController extends Controller
{
    public function __construct(private CityService $cityService) {}

    public function index(Request $request)
    {
        // Set default pagination values
        $limit = max(1, (int)$request->get('limit', 10)); // Ensure limit is at least 1
        $offset = max(0, (int)$request->get('offset', 0)); // Ensure offset is at least 0
        
        // Set default sorting by ID ascending if not specified
        $requestData = $request->all();
        if (!isset($requestData['sortBy'])) {
            $requestData['sortBy'] = 'id';
        }
        if (!isset($requestData['sort'])) {
            $requestData['sort'] = 'ASC';
        }
        
        $cities = $this->cityService->listAllCities($requestData);

        $totalCount = $cities['count'];
        $totalPages = $totalCount > 0 ? max(1, ceil($totalCount / $limit)) : 1;
        $currentPage = ($offset / $limit) + 1;

        return view('dashboard.Cities.index', [
            'cities' => $cities['data'],
            'totalCount' => $totalCount,
            'limit' => $limit,
            'offset' => $offset,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
        ]);
    }


    public function store(StoreCityRequest $request)
    {

        $data = $request->validated();

        $this->cityService->createCity($data);


        return redirect()->back()->with('success', 'City created successfully!');
    }



    public function update(UpdateCityRequest $request, $id)
    {
        $data = $request->validated();

        $this->cityService->updateCity($id, $data);

        return redirect()->back()->with('success', 'City updated successfully!');
    }


    public function destroy($id)
    {
        $this->cityService->deleteCity($id);
        return redirect()->back()->with('success', 'City deleted successfully!');
    }
}

