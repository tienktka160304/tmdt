<?php

namespace App\Http\Controllers\Api;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        //
        $query = Banner::query()->orderBy('id', 'desc');

        $query->when($request->has('position'), function ($q) use ($request) {
            $q->where('position', $request->position);

            // Giới hạn số lượng tùy vào position
            $limit = match ((int) $request->position) {
                1 => 4,
                2 => 2,
                3 => 1,
                default => 10, // Nếu không khớp, lấy tối đa 10 banners
            };

            $q->limit($limit);
        });


        $banners = $query->get();
        return response()->json([
            'status' => 'success',
            'data' =>  $banners
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
}
