<?php

namespace App\Http\Controllers;

use App\Http\Requests\BannerRequest;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerAdController extends Controller
{
    public function index(Request $request)
    {
        $sotrang = $request->input('show', 5);
        $banner = Banner::query();
        if ($request->has('banner')) {
            switch ($request->query('banner')) {
                case 0:
                    $banner->where('position', 3);
                    break;
                case 1:
                    $banner->where('position', 1);
                    break;
                case 2:
                    $banner->where('position', 2);
                    break;
                case 3:
                    $banner->onlyTrashed();
                    break;
                case 'all':
                    $banner->orderBy('created_at', 'DESC');
                    break;
            }
        }
        if ($key = request()->key) {
            $banner->where(function ($tim) use ($key) {
                $tim->where('bannes.id', 'like', '%' . $key . '%');
            });
        }
        switch ($thutu = request()->thutu) {
            case 1:
                $banner->orderBy('updated_at', 'DESC');
                break;
            case 2:
                $banner->orderBy('sort', 'ASC');
                break;
            default:
                $banner->orderBy('created_at', 'DESC');
                break;
        }
        $banner = $banner->paginate($sotrang)->appends([
            'show' => $sotrang,
            'key' => request()->key,
        ]);
        $pageht = $banner->currentPage();
        $lapa = $banner->lastPage();
        $start  = max($pageht - 1, 1);
        $end = min($pageht + 1, $lapa);
        return view('page.banner.banne', compact('banner', 'start', 'end', 'pageht', 'lapa', 'sotrang'));
    }
    public function create_banner(BannerRequest $request)
    {
        $vali = $request->validated();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('/img/banner'), $imageName);
            $imagePath = '/img/banner/' . $imageName;
        }
        $ad = Banner::create([
            'position'    => $vali['position'],
            'title1'     => $vali['title1'],
            'title2'         => $vali['title2'],
            'sort'         => $vali['sort'],
            'image'         =>  $imagePath,
            'link'         =>  $vali['link'],
            'created_at' => now()
        ]);
        return redirect()->route('banner.show')->with('success', 'Đã thêm Banner thành công!');
    }
    public function update_banner(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);
        $request->validate([
            'position' => 'required|integer',
            'title1' => 'nullable|string|max:255',
            'title2' => 'nullable|string|max:255',
            'sort' => 'required|integer|min:1',
            'link' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $banner->position = $request->position;
        $banner->title1 = $request->title1;
        $banner->title2 = $request->title2;
        $banner->sort = $request->sort;
        $banner->link = $request->link;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img/banner'), $imageName);
            $banner->image = '/img/banner/' . $imageName;
        }

        $banner->save();

        return redirect()->route('banner.show')->with('success', 'Cập nhật Banner' . $id . ' thành công!');
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();
        return redirect()->route('banner.show')->with('success', 'BN' . $id . ' đã vào thùng rác!');
    }
    public function restore($id)
    {
        $banner = Banner::withTrashed()->findOrFail($id);
        $banner->restore();
        return redirect()->route('banner.show')->with('success', 'BN' . $id . ' đã được phục hồi');
    }
}
