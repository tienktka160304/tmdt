<div class="modal_create {{ ($errors->any() && old('form_type') == 'edit_'.$banner->id) ? '' : 'hidden' }}"
    id="modal_banner_edit_{{ $banner->id }}">
    <div class="banner_content">
        <div style="text-align: end">
            <button class="closeModalBnEdit btn-x">&times;</button>
        </div>

        <div class="banner_tilte">
            <h2>Cập nhật banner {{ $banner->id }}</h2>
        </div>

        <div class="banneForm">
            <form id="bannerForm_{{ $banner->id }}" action="{{ route('banner.update', $banner->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="form_type" value="edit_{{ $banner->id }}">
                <input type="hidden" name="banner_id" value="{{ $banner->id }}">

                <div class="bn_1">
                    <div class="up">
                        <label style="display: inline-block; font-weight: 600; padding-bottom: 10px; padding-top: 10px;">Link</label>
                        <input type="text" name="link" value="{{$banner->link}}">
                    </div>
                    <div class="down">
                        <label style="display: inline-block; font-weight: 600; padding-bottom: 10px; padding-top: 10px;">Ảnh</label>
                        <input id="file_image_{{ $banner->id }}" type="file" accept="image/*" name="image" onchange="bannerImg(event, {{ $banner->id }})">
                        <label for="file_image_{{ $banner->id }}">
                            <div id="image_banner_{{ $banner->id }}">
                                <img src="{{ asset($banner->image) }}" style="width: 350px" alt="chưa xác định">
                            </div>
                        </label>
                        @if(old('form_type') == 'edit_'.$banner->id)
                        @error('image')
                        <span id="erop1">{{ $message }}</span>
                        @enderror
                        @endif
                    </div>
                </div>

                <div class="bn_2">
                    <div class="left">
                        <div class="up">
                            <label style="display: inline-block; font-weight: 600; padding-bottom: 10px; padding-top: 10px;">Vị trí:</label>
                            <select name="position">
                                <option value="2" {{$banner->position == 2 ? 'selected' : '' }}>Banner trong SP</option>
                                <option value="1" {{$banner->position == 1 ? 'selected' : '' }}>Banner Lớn</option>
                                <option value="3" {{$banner->position == 3 ? 'selected' : '' }}>Banner Sale</option>
                            </select>
                        </div>
                        <div class="down">
                            <label style="display: inline-block; font-weight: 600; padding-bottom: 10px; padding-top: 10px;">Thứ tự</label>
                            <input type="number" name="sort" min="1" step="1" oninput="validatePrice(this)" value="{{ $banner->sort}}">
                        </div>
                    </div>

                    <div class="right">
                        <div class="up">
                            <label style="display: inline-block; font-weight: 600; padding-bottom: 10px; padding-top: 10px;">Tiêu đề 1:</label>
                            <input type="text" name="title1" value="{{ $banner->title1}}">
                            @error('title1')
                            <span id="erop1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="up">
                            <label style="display: inline-block; font-weight: 600; padding-bottom: 10px; padding-top: 10px;">Tiêu đề 2:</label>
                            <input type="text" name="title2" value="{{ $banner->title2}}">
                        </div>
                    </div>

                    <div class="right_2">
                        <button type="submit">Cập nhật</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>