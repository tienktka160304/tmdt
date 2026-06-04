@extends('layout')

@section('title','Chi tiết bài viết')

@section('content')
<main>
  <section class="page-main">

    <div class="header-filter-shared">
      <ul>
        <li>
          <a href="{{ route('baiviet')}}" class="{{ request()->query('query') == null ? 'active' : '' }}">
            Tất cả
          </a>
        </li>
        <li>
          <a href="{{ route('baiviet', ['query' => 'hot']) }}"
            class="{{ request()->query('query') == 'hot' ? 'active' : '' }}">
            Bài viết nổi bật
          </a>
        </li>
        <li>
          <a href="{{ route('baiviet', ['query' => 'hidden']) }}"
            class="{{ request()->query('query') == 'hidden' ? 'active' : '' }}">
            Bài viết ẩn
          </a>
        </li>
        <li>
          <a href="{{ route('baiviet', ['query' => 'deleted']) }}"
            class="{{ request()->query('query') == 'deleted' ? 'active' : '' }}">
            Bài viết xóa
          </a>
        </li>
      </ul>
    </div>

    <div style="margin: 40px auto; width: 850px">
      <div>
        <h2>Loại tin: {{$post->post_category->name}}</h2>
      </div>
      <h1 style="font-size: 30px; padding-top: 10px;">{{ $post->title }}</h1>
      <div class="title-post-detail">
        <p><strong>Người đăng: </strong>{{$post->user->last_name}}{{$post->user->first_name}}</p>
        <p><strong>Ngày đăng:</strong> {{ $post->created_at->format('d/m/Y') }}</p>
        <p><strong>Lượt xem: </strong>{{$post->views}}</p>
      </div>
      @if($post->image)
      <img src="{{ asset( $post->image)}}" alt="{{ $post->title }}"
        style="width:800px; margin: 20px 0; border-radius: 5px;">
      @endif
      <div>
        <p>{{$post->short_description}}</p>
        <div style="width: 100% " class="content-html-wrapper">{!!$post->content!!}</div>
      </div>
      <div>
      </div>
      <a href="{{ route('baiviet') }}"
        style="display: inline-block; margin-top: 30px; padding: 10px 15px; background: #c4ffd1; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1); border-radius: 4px; font-weight: bold;">←
        Quay lại danh
        sách</a>
    </div>
  </section>
</main>
@endsection