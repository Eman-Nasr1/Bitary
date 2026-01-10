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

        // Auto-calculate trend if change_percent is provided
        if (isset($data['change_percent'])) {
            if ($data['change_percent'] > 0) {
                $data['trend'] = 'up';
            } elseif ($data['change_percent'] < 0) {
                $data['trend'] = 'down';
            } else {
                $data['trend'] = 'stable';
            }
        } else {
            $data['trend'] = 'stable';
        }

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

        // Auto-calculate trend if change_percent is provided
        if (isset($data['change_percent'])) {
            if ($data['change_percent'] > 0) {
                $data['trend'] = 'up';
            } elseif ($data['change_percent'] < 0) {
                $data['trend'] = 'down';
            } else {
                $data['trend'] = 'stable';
            }
        } elseif (!isset($data['trend'])) {
            // If change_percent is not provided and trend is not set, use existing trend
            $data['trend'] = $price->trend;
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
