<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserReview;
use Illuminate\Http\Request;


class UserReviewController extends Controller
{
    //
    public function postReviewWeb(Request $request)
    {
        $validated = $request->validate([
            'content' => 'nullable|string',
        ]);

        UserReview::create([
            'id_user' => $request->id_user,
            'content' => $validated['content'],

        ]);

        return response()->json(['message' => 'Đánh giá thành công'], 200);
    }
    public function getReviewWeb()
    {
        $reviews = UserReview::with('user')->where("status", 1)->orderBy('id', 'desc')->limit(8)->get();
        return response()->json(['data' => $reviews], 200);
    }
}
