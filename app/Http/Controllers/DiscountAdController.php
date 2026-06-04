<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Rules\ValidDateRange;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class DiscountAdController extends Controller
{
    public function index(Request $request)
    {
        $sotrang = $request->input('show', 10);
        $discountQuery = Discount::query();
        $title = 'Tất cả khuyến mãi';

        if ($request->has('query')) {
            $filter = $request->query('query');

            switch ($filter) {
                case 'hidden':
                    $discountQuery->where('status', 0);
                    $title = "Khuyến mãi đã ẩn";
                    break;

                case 'active':
                    $discountQuery->where('status', 1)
                        ->where(function ($q) {
                            $q->whereNull('time_end')
                                ->orWhere('time_end', '>=', now());
                        })
                        ->where('time_start', '<=', now());
                    $title = "Khuyến mãi đang diễn ra";
                    break;

                case 'ended':
                    $discountQuery->whereNotNull('time_end')
                        ->where('time_end', '<', now());
                    $title = "Khuyến mãi đã kết thúc";
                    break;

                case 'deleted':
                    $discountQuery->onlyTrashed();
                    $title = "Khuyến mãi đã xóa";
                    break;
            }
        }

        if ($key = $request->key) {
            $discountQuery->where('name', 'like', "%{$key}%");
        }

        if ($request->has('sort')) {
            if ($request->sort === 'value_asc') {
                $discountQuery->orderBy('value', 'asc');
            } elseif ($request->sort === 'value_desc') {
                $discountQuery->orderBy('value', 'desc');
            }
        }

        $discounts = $discountQuery->paginate($sotrang)->appends([
            'show' => $sotrang,
            'key' => $request->key,
            'query' => $request->query('query'),
        ]);

        $pageht = $discounts->currentPage();
        $lapa = $discounts->lastPage();
        $start = max($pageht - 1, 1);
        $end = min($pageht + 1, $lapa);

        return view('page.discount.index', compact('discounts', 'start', 'end', 'pageht', 'lapa', 'sotrang', 'title'));
    }


    // thêm discount
    public function create()
    {
        return view('page.discount.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'value' => 'required|numeric|min:1|max:100',
            'description' => 'nullable|string|max:1000',
            'time_start' => 'required|date',
            'time_end' => [
                'nullable',
                'date',
                new ValidDateRange($request->time_start),
            ],
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            flash()->error($validator->errors()->first());
            return redirect()->back()->withInput();
        }

        Discount::create([
            'name' => $request->name,
            'value' => $request->value,
            'description' => $request->description,
            'time_start' => $request->time_start,
            'time_end' => $request->time_end,
            'status' => $request->status,
        ]);

        flash()->success('Thêm khuyến mãi thành công!');
        return redirect()->route('khuyenmai');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'value' => 'required|numeric|min:1|max:100',
            'description' => 'nullable|string|max:1000',
            'time_start' => 'required|date',
            'time_end' => [
                'nullable',
                'date',
                new ValidDateRange($request->time_start),
            ],
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            flash()->error($validator->errors()->first());
            return redirect()->back()->withInput();
        }

        $discount = Discount::findOrFail($id);
        $discount->update([
            'name' => $request->name,
            'value' => $request->value,
            'description' => $request->description,
            'time_start' => $request->time_start,
            'time_end' => $request->time_end,
            'status' => $request->status,
        ]);

        flash()->success('Cập nhật giảm giá thành công!');
        return redirect()->route('khuyenmai');
    }
    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);

        // Kiểm tra nếu có sản phẩm đang sử dụng khuyến mãi này
        if ($discount->products()->exists()) {
            return redirect()->back()->with('error', 'Không thể xóa vì khuyến mãi đang được áp dụng cho sản phẩm.');
        }

        $discount->delete(); // xóa mềm
        return redirect()->back()->with('success', 'Xóa khuyến mãi thành công!');
    }

    public function toggleStatus($id)
    {
        $discount = Discount::findOrFail($id);
        $discount->status = !$discount->status;
        $discount->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
    }

    public function restore($id)
    {
        $discount = Discount::withTrashed()->findOrFail($id);
        $discount->restore();

        return redirect()->back()->with('success', 'Khôi phục khuyến mãi thành công');
    }
}
