<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MarketPrice;
use Illuminate\Http\Request;

class MarketPriceController extends Controller
{
    /**
     * Display a listing of market prices (latest first).
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

        // Order by updated_at (latest first)
        $query->orderBy('updated_at', 'DESC');

        // Paginate
        $perPage = $request->get('per_page', 50);
        $prices = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $prices->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_name_ar' => $item->product_name_ar,
                    'product_name_en' => $item->product_name_en,
                    'price' => (float) $item->price,
                    'currency' => $item->currency,
                    'change_percent' => $item->change_percent !== null ? (float) $item->change_percent : null,
                    'trend' => $item->trend,
                    'updated_at' => $item->updated_at->format('Y-m-d H:i:s'),
                    'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'meta' => [
                'current_page' => $prices->currentPage(),
                'last_page' => $prices->lastPage(),
                'per_page' => $prices->perPage(),
                'total' => $prices->total(),
            ],
        ]);
    }
}
