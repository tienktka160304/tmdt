@extends('layout')

@section('title', 'Thêm mới khuyến mãi')

@section('content')
<main class="create-discount">

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('khuyenmai.store') }}" method="POST">
        @csrf

        <div class="form-group-edit">
            <label for="name">Tên khuyến mãi</label>
            <input type="text" class="form-control" name="name" required>
        </div>

        <div class="form-group-edit">
            <label for="value">Phần trăm giảm giá (%)</label>
            <input type="number" class="form-control" name="value" required>
        </div>

        <div class="form-group-edit">
            <label for="time_start">Ngày bắt đầu</label>
            <input type="date" class="form-control" name="time_start" required>
        </div>

        <div class="form-group-edit">
            <label for="time_end">Ngày kết thúc</label>
            <input type="date" class="form-control" name="time_end">
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

        <button type="submit" class="btn btn-success">Thêm mới</button>
    </form>
</main>
@endsection