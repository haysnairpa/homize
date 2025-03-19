<?php

namespace App\Http\Controllers;

use App\Models\Services;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query');
        
        if (!$query) {
            return response()->json([]);
        }

        // Fuzzy & partial search with SQL LIKE
        $services = Services::where('name', 'LIKE', "%{$query}%")
            ->orWhere(function($q) use ($query) {
                // Split query menjadi kata-kata for partial matching
                $words = explode(' ', $query);
                foreach($words as $word) {
                    $q->orWhere('name', 'LIKE', "%{$word}%");
                }
            })
            ->with(['shop_services.shop.category'])
            ->limit(5)
            ->get();

        // Format response untuk autocomplete
        $results = $services->map(function($service) {
            return [
                'id' => $service->id,
                'name' => $service->name,
                'price' => $service->price,
                'image' => $service->image_url ?? asset('images/service-default.jpg'),
                'category' => $service->shop_services->first()?->shop->category->name ?? 'Uncategorized',
                'url' => route('services.show', $service->id)
            ];
        });

        return response()->json($results);
    }
}