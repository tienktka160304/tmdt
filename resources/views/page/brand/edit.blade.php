@extends('layout')

@section('title', 'chỉnh sửa thương hiệu')

@section('content')
<main class="edit-brand">
    <h1>Chỉnh Sửa Thương Hiệu</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('brand.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group-edit">
            <label for="name" class="form-label">Tên Thương Hiệu</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ $brand->name }}" required>
        </div>

        <div class="form-group-edit">
            <label for="logo" class="form-label">Logo</label>
            <input type="file" name="logo" class="form-control" id="logo">
            @if ($brand->logo)
                <div class="mt-2">
                    <img src="{{ asset($brand->logo) }}" alt="Brand Logo" width="300">
                </div>
            @endif
        </div>

        <div class="form-group-edit">
            <label class="form-label">Trạng Thái</label>
            <select name="status" class="form-control">
                <option value="1" {{ $brand->status ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ !$brand->status ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Cập Nhật</button>
    </form>
</main>
@endsection