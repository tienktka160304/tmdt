@extends('layout')

@section('title', 'Chỉnh sửa bài viết')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">

<main>
    <form action="{{ route('page.post.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="content-wrapper">
            <div class="left-section">
                <label for="title" class="label-posts">Tiêu Đề</label>
                <input type="text" class="input-posts @error('title') is-invalid @enderror" id="title"
                    placeholder="Tiêu đề bài viết" name="title" value="{{ old('title', $post->title) }}">
                @error('title')
                <span class="baoloi">{{ $message }}</span>
                @enderror

                <div class="row-post">
                    <div class="">
                        <label class="label-posts">Tải ảnh lên</label>
                        <div class="upload-img-container">
                            <div class="preview-container" id="previewContainer"
                                style="display: {{ $post->image ? 'block' : 'none' }};">
                                <img id="previewImage" src="{{ $post->image ? asset($post->image) : '' }}"
                                    alt="Xem trước ảnh">
                                <button type="button" id="removeImage">✖</button>
                            </div>
                            <label for="upload" class="custom-file-upload" id="uploadLabel"
                                style="display: {{ $post->image ? 'none' : 'block' }};">
                                <i class="fa-solid fa-image preview-icon" style="font-size: 48px; color: #aaa;cursor: pointer;"></i>
                            </label>
                            <input type="file" id="upload" class="input-postss" name="image" accept="image/*">
                            <input type="hidden" name="remove_image" id="removeImageInput" value="0">
                        </div>
                        @error('image')
                        <span class="baoloi" style="font-size: 15px;">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- test --}}
                    <div>
                        <div class="cate-hot-posts">
                            <div>
                                <label for="category" class="label-posts" style="margin-left: 6px;">Danh mục</label>
                                <select id="category" class="select-posts" name="id_category">
                                    @foreach ($postCategories as $postCategorie)
                                    <option value="{{ $postCategorie->id }}" {{ $post->id_category == $postCategorie->id
                                        ? 'selected' : '' }}>
                                        {{ $postCategorie->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- hot --}}
                            <div class="product-checkbox-container toggle-container-post">
                                <label class="status-label" for="hot">Bài viết hot</label>

                                <input type="hidden" name="hot" value="0">

                                <input type="checkbox" id="product-hot" name="hot" value="1" {{ old('hot', $post->hot) ?
                                'checked' : '' }}>

                                <label for="product-hot" class="toggle-btn-post">🔥 HOT 🔥</label>
                            </div>
                            {{-- --}}
                        </div>
                        <div class="sub-post">
                            <div>
                                <label for="published_date" class="label-posts">Ngày xuất bản</label>
                                <input type="date" id="published_date" class="input-posts" name="published_date"
                                    value="{{ old('published_date', $post->published_date) }}" required>
                                @error('published_date')
                                <span class="baoloi" style="font-size: 14px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="toggle-status-container">
                                <label for="statuss" class="status-label">Trạng thái</label>
                                <div class="status-switch-wrapper">
                                    <input type="hidden" name="status" value="0">

                                    <label class="status-switch">
                                        <input type="checkbox" id="statuss" class="status-checkbox" name="status"
                                            value="1" {{ $post->status == '1' ? 'checked' : '' }}>
                                        <span class="status-slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <div class="right-section">

                <label for="short_description" class="label-posts">Mô tả ngắn</label>
                <textarea id="short_description" class="content" placeholder="Nhập mô tả bài viết"
                    name="short_description"
                    required>{{ old('short_description', $post->short_description) }}</textarea>
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
                    value="{{ old('content', $post->content) }}">
                <div id="editor"></div>

            </div>
        </div>
        <button class="submit-btn" type="submit">
            <i class="fa-solid fa-circle-plus"></i> Cập Nhật
        </button>
    </form>


</main>
{{-- code moi --}}

<script src="{{ asset('/js/QuillEditText.js') }}" defer></script>

<script>
    // xư lý xme trc hình ảnh
document.addEventListener("DOMContentLoaded", function () {
    var uploadInput = document.getElementById("upload");
    var uploadLabel = document.getElementById("uploadLabel");
    var previewContainer = document.getElementById("previewContainer");
    var previewImage = document.getElementById("previewImage");
    var removeImageBtn = document.getElementById("removeImage");
    var removeImageInput = document.getElementById("removeImageInput");

    // Khi chọn ảnh từ input file
    uploadInput.addEventListener("change", function (event) {
        var file = event.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                previewImage.src = e.target.result;
                previewContainer.style.display = "block";
                uploadLabel.style.display = "none"; 
            };
            reader.readAsDataURL(file);
        }
        // Khi người dùng chọn ảnh mới, đảm bảo ảnh không bị xóa
        removeImageInput.value = "0"; 
    });

    // Khi nhấn nút xóa ảnh
    removeImageBtn.addEventListener("click", function () {
        previewImage.src = "";
        previewContainer.style.display = "none";
        uploadLabel.style.display = "block"; 
        uploadInput.value = ""; 
        removeImageInput.value = "1"; // Đánh dấu ảnh cần xóa
    });
});

</script>
@endsection