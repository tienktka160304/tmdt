@extends('layout')

@section('title','Chỉnh sửa giảm giá')

@section('content')
<main class="edit-discount">


    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('khuyenmai.update', $discount->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group-edit">
            <label for="name">Tên khuyến mãi</label>
            <input type="text" class="form-control" name="name" value="{{ $discount->name }}" required>
        </div>

        <div class="form-group-edit">
            <label for="value">Phần trăm giảm giá (%)</label>
            <input type="number" class="form-control" name="value" value="{{ $discount->value }}" required>
        </div>

        <div class="form-group-edit">
            <label for="description">Nội dung khuyến mãi</label>
            <input type="text" class="form-control" name="description" value="{{$discount->description}}" required>
        </div>

        <div class="form-group-edit">
            <label for="time_start">Ngày bắt đầu</label>
            <input type="date" class="form-control" name="time_start" value="{{ $discount->time_start }}" required>
        </div>

        <div class="form-group-edit">
            <label for="time_end">Ngày kết thúc</label>
            <input type="date" class="form-control" name="time_end" value="{{ $discount->time_end }}">
            @error('time_end')
                <span class="text-danger">Thời gian kết thúc phải sau thời gian bắt đầu.</span>
            @enderror
        </div>

        <div class="form-group-edit">
            <label for="status">Trạng thái</label>
            <select name="status" class="form-control">
                <option value="1">Hoạt động</option>
                <option value="0">Không hoạt động</option>
            </select>
        </div>

        <button type="submit" class="btn">Cập nhật</button>
    </form>
</main>
@endsection