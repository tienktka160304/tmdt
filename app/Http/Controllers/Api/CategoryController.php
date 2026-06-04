<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MainCategory;

use App\Models\SubCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $categories = SubCategory::where('status', '>', 0)
            ->whereHas('products', function ($query) {
                $query->where('status', '>', 0)
                    ->whereHas('brand', function ($query) {
                        $query->where('status', '>', 0);
                    });
            })
            ->withCount(['products' => function ($query) {
                $query->where('status', '>', 0)
                    ->whereHas('brand', function ($query) {
                        $query->where('status', '>', 0);
                    });
            }])
            ->orderBy("sort", 'asc')
            ->get();


        return response()->json([
            'status' => 'success',
            'data' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function mainCategoryWithSub()
    {
        $categories = MainCategory::with(['sub_category' => function ($query) {
            $query->select(["id", "id_main_category", "name", "slug", "image", "status", "sort"])->where('status', '>', 0)
                ->orderBy("sort", 'asc');
        }])->get();
        return response()->json([
            'status' => 'success',
            'data' => $categories
        ]);
    }
}
