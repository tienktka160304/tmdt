<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\UserReview;
use Illuminate\Http\Request;

class UserReviewController extends Controller
{
    public function index(Request $request)
    {
        $sotrang = $request->input('show', 5);
        $userReview = UserReview::with('user');
        if ($request->has('userReview')) {
            switch ($request->query('userReview')) {
                case 0:
                    $userReview->whereDate('created_at', Carbon::today());
                    break;
            }
        }
        if ($key = request()->key) {
            $userReview->where(function ($tim) use ($key) {
                $tim->where('id', 'like', '%' . $key . '%')
                    ->orWhereHas('user', function ($q) use ($key) {
                        $q->Where('last_name', 'like', '%' . $key . '%')
                            ->orWhere('first_name', 'like', '%' . $key . '%');
                    });
            });
        }
        $userRe = $userReview->orderBy('created_at', 'DESC')->paginate($sotrang)->appends([
            'show' => $sotrang,
            'key' => request()->key,
        ]);
        $pageht = $userRe->currentPage();
        $lapa = $userRe->lastPage();
        $start = max($pageht - 1, 1);
        $end = min($pageht + 1, $lapa);
        return view('page.user.user_review', compact('userRe', 'sotrang', 'pageht', 'lapa', 'start', 'end'));
    }
    public function changeStatus(Request $request)
    {
        $idUserRe = $request->input('id');
        $idUser = UserReview::find($idUserRe);
        if ($idUser) {
            $idUser->status = $idUser->status == 1 ? 0 : 1;
            $idUser->save();
            return redirect()->back();
        }
        return redirect()->back();
    }
}
