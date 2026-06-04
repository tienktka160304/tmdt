<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Post_Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Post::with(["post_category", 'user']);
        //
        if ($request->has('category')) {
            $id = Post_Category::where('slug', $request->category)->value('id');
            $query->where('id_category', $id);
        }

        if ($request->has('ct')) {
            if ($request->ct == "hot") {
                $query->where('hot', 1);
            }

            if ($request->ct == "views") {
                $query->orderBy('views', 'desc');
            }
        }

        $baiviets = $query->orderBy('published_date', 'desc')->paginate($request->get('limit', 8));
        return response()->json([
            'status' => 'success',
            'data' => $baiviets
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
    public function show(string $slug)
    {

        $id = Post::where('slug', $slug)->value('id');
        $baiviet = Post::withWhereHas("post_category")->with("user")->where('status', 1)->where("id", $id)->first();





        if ($baiviet) {
            $baiviet->increment('views');
            return response()->json([
                'status' => 'success',
                'data' => $baiviet
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Không có bài viết'
            ], 404);
        }
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

    public function  mainPostCategory()
    {
        //
        $main = Post_Category::orderBy('sort', "ASC")->get();
        return response()->json([
            'status' => 'success',
            'data' => $main
        ]);
    }


    public function HotAndViewsPost()
    {
        $hotPosts = Post::with(["post_category", "user"])
            ->where("hot", 1)
            ->orderBy("published_date", "desc")
            ->limit(8)
            ->get();

        $viewedPosts = Post::with(["post_category", "user"])
            ->orderBy("views", "desc")
            ->limit(8)
            ->get();

        return response()->json([
            "status" => "success",
            "hot" => $hotPosts,
            "views" => $viewedPosts
        ]);
    }
}
