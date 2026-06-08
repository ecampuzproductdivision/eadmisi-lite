<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Regency;
use Illuminate\Http\Request;

class RegencyController extends Controller
{
    public function select2(Request $request)
    {
        $search = $request->get('q', '');
        $page = $request->get('page', 1);
        $limit = 20;

        $query = Regency::where('is_active', true)
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('province', 'like', "%{$search}%");
            })
            ->orderBy('province')
            ->orderBy('name');

        $total = $query->count();
        $results = $query->skip(($page - 1) * $limit)->take($limit)->get();

        $items = $results->map(function ($regency) {
            return [
                'id' => $regency->id,
                'text' => $regency->type . ' ' . $regency->name . ', ' . $regency->province,
            ];
        });

        return response()->json([
            'results' => $items,
            'pagination' => [
                'more' => ($page * $limit) < $total,
            ],
        ]);
    }
}