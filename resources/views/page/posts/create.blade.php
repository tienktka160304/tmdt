@extends('layout')

@section('title', 'Thêm bài viết')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
{{-- --}}
<main>
    <form action="{{ route('page.post.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="content-wrapper">
            <div class="left-section">
                <label for="title" class="label-posts">Tiêu Đề</label>
                <input type="text" class="input-posts @error('title') is-invalid @enderror" id="title"
                    placeholder="Tiêu đề bài viết" name="title" value="{{ old('title') }}">
                @error('title')
                <span class="baoloi">{{ $message }}</span>
                @enderror

                <div class="row-post">
                    <div class="">
                        <label class="label-posts">Tải ảnh lên</label>
                        <div class="upload-img-container">
                            <div class="preview-container" id="previewContainer" style="display: none;">
                                <img id="previewImage" src="" alt="Xem trước ảnh">
                                <button type="button" id="removeImage">✖</button>
                            </div>
                            <label for="upload" class="custom-file-upload">
                                <i class="fa-solid fa-image preview-icon"
                                    style="font-size: 48px; color: #aaa;cursor: pointer;"></i>
                            </label>
                            <input type="file" id="upload" class="input-postss" name="image" accept="image/*">
                        </div>
                        @error('image')
                        <span class="baoloi" style="font-size: 15px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <div class="cate-hot-posts">
                            <div>
                                <label for="category" class="label-posts" style="margin-left: 6px;">Danh mục</label>
                                <select id="category" class="select-posts" name="id_category">
                                    @foreach ($postCategories as $postCategorie)
                                    <option value="{{ $postCategorie->id }}" {{ old('id_category')==$postCategorie->id ?
                                        'selected' : '' }}>
                                        {{ $postCategorie->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- hot --}}
                            <div class="product-checkbox-container toggle-container-post">
                                <label class="status-label" for="hot">Bài viết hot</label>

                                <input type="hidden" name="hot" value="0">

                                <input type="checkbox" id="product-hot" name="hot" value="1" {{ old('hot') ? 'checked'
                                    : '' }}>

                                <label for="product-hot" class="toggle-btn-post">🔥 HOT 🔥</label>
                            </div>

                            {{-- --}}
                        </div>
                        <div class="sub-post">
                            <div>
                                <label for="published_date" class="label-posts">Ngày xuất bản</label>
                                <input type="date" id="published_date"
                                    class="input-posts  @error('published_date') is-invalid @enderror"
                                    name="published_date" value="{{ old('published_date') }}">
                            </div>
                            <div class="toggle-status-container">
                                <label for="statuss" class="status-label">Trạng thái</label>
                                <div class="status-switch-wrapper">
                                    <label class="status-switch">
                                        <input type="checkbox" id="status-toggle" class="status-checkbox" {{
                                            old('status',1)=='1' ? 'checked' : '' }}>
                                        <span class="status-slider"></span>
                                    </label>
                                    <input type="hidden" id="statuss" name="status" value="{{ old('status', '1') }}">
                                </div>
                            </div>
                        </div>
                        @error('published_date')
                        <span class="baoloi" style="font-size: 14px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="right-section">
                <label for="description" class="label-posts">Mô tả ngắn</label>
                <textarea id="description" class="content @error('short_description') is-invalid @enderror"
                    placeholder="Nhập mô tả bài viết" name="short_description">{{ old('short_description') }}</textarea>
                @error('short_description')
                <span class="baoloi">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="descrip-post">
            <label for="description" class="label-posts">Mô tả</label>
            <div style="height: 500px; margin-top: 10px;">
                <!-- Input ẩn để lưu dữ liệu -->
                <input type="hidden" name="content" id="hidden-description"
                    value="{{ old('content', $post->content ?? '') }}">
                <div id="editor"></div>

            </div>
        </div>
        <button class="submit-btn" type="submit">
            <i class="fa-solid fa-circle-plus"></i> Thêm bài viết
        </button>
    </form>


</main>
<script src="{{ asset('/js/QuillEditText.js') }}" defer></script>

<script>
    // xư lý xme trc hình ảnh
    document.addEventListener("DOMContentLoaded", function () {
    const uploadInput = document.getElementById("upload"),
          previewContainer = document.getElementById("previewContainer"),
          previewImage = document.getElementById("previewImage"),
          removeImageBtn = document.getElementById("removeImage"),
          fileUploadLabel = document.querySelector(".custom-file-upload"),
          titleLabel = document.querySelector(".label-posts");

    uploadInput.addEventListener("change", function () {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = e => {
            previewImage.src = e.target.result;
            [previewContainer, removeImageBtn].forEach(el => el.style.display = "block");
            [fileUploadLabel, titleLabel].forEach(el => el.style.display = "none");
        };
        reader.readAsDataURL(file);
    });

    removeImageBtn.addEventListener("click", function () {
        previewImage.src = "";
        uploadInput.value = "";
        [previewContainer, removeImageBtn].forEach(el => el.style.display = "none");
        [fileUploadLabel, titleLabel].forEach(el => el.style.display = "block");
    });
});


</script>
@endsection