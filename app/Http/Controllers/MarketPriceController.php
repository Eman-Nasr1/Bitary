<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MarketPrice;
use App\Http\Requests\StoreMarketPriceRequest;
use App\Http\Requests\UpdateMarketPriceRequest;
use Illuminate\Http\Request;

class MarketPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MarketPrice::query();

        // Search by product name
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('product_name_ar', 'LIKE', "%{$search}%")
                  ->orWhere('product_name_en', 'LIKE', "%{$search}%");
            });
        }

        // Filter by trend
        if ($request->has('trend') && $request->trend != '') {
            $query->where('trend', $request->trend);
        }

        $prices = $query->orderBy('updated_at', 'DESC')->paginate(15);

        return view('dashboard.market-prices.index', [
            'prices' => $prices,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.market-prices.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMarketPriceRequest $request)
    {
        $data = $request->validated();

        // Remove trend from data - let the Model handle it automatically
        unset($data['trend']);

        MarketPrice::create($data);

        return redirect()->route('dashboard.market-prices.index')
            ->with('success', 'Market price created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $price = MarketPrice::findOrFail($id);
        return view('dashboard.market-prices.show', compact('price'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $price = MarketPrice::findOrFail($id);
        return view('dashboard.market-prices.edit', compact('price'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMarketPriceRequest $request, string $id)
    {
        $price = MarketPrice::findOrFail($id);
        $data = $request->validated();

        // Remove trend from data - let the Model handle it automatically based on change_percent
        // Only keep trend if change_percent is not being updated
        if (!isset($data['change_percent']) || $data['change_percent'] === null) {
            // If change_percent is not provided, keep existing trend
            unset($data['trend']);
        } else {
            // If change_percent is provided, remove trend to let Model calculate it
            unset($data['trend']);
        }

        $price->update($data);

        return redirect()->route('dashboard.market-prices.index')
            ->with('success', 'Market price updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $price = MarketPrice::findOrFail($id);
        $price->delete();

        return redirect()->route('dashboard.market-prices.index')
            ->with('success', 'Market price deleted successfully!');
    }
}
