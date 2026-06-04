<div class="modal_create {{($errors->any()&&old('form_type') == 'create') ? '' : 'hidden'}}" id="modal_banner">
    <div class="banner_content">
        <div style="text-align: end">
            <button id="closeModalBnCr" class="btn-x">&times;</button>
        </div>
        <div class="banner_tilte">
            <h2>Thêm Banner</h2>
        </div>
        <div class="banneForm">
            <form id="bannerForm" action="{{route('banner.create')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="form_type" value="create">
                <div class="bn_1">
                    <div class="up">
                        <label
                            style="display: inline-block; font-weight: 600; padding-bottom: 10px; padding-top: 10px;">Link</label>
                        <input type="text" name="link" value="{{old('link')}}">
                        @if(old('form_type') == 'create')
                        @error('link')
                        <span id="erop1">{{ $message }}</span>
                        @enderror
                        @endif
                    </div>
                    <div class="down">
                        <input id="file_image" type="file" accept="image/*" name="image" onchange="bannerImgCr(event)">
                        <label for="file_image">
                            <div id="image_banner">
                                <img id="preview_image_banner" src="" alt="" style="max-width: 100%; display: none;">
                                <span style="opacity: 0.5 ; font-size: 20px ; font-weight: 600">Thêm ảnh</span>
                            </div>
                        </label>
                        @if(old('form_type') == 'create')
                        @error('image')
                        <span id="erop1">{{ $message }}</span>
                        @enderror
                        @endif
                    </div>
                </div>
                <div class="bn_2">
                    <div class="left">
                        <div class="up">
                            <label
                                style="display: inline-block; font-weight: 600; padding-bottom: 10px; padding-top: 10px;">Vị
                                trí:</label>
                            <select name="position">
                                <option value="2" {{ old('position')== 2 ? 'selected' : '' }}>Banner trong SP</option>
                                <option value="1" {{ old('position')== 1 ? 'selected' : '' }}>Banner Lớn</option>
                                <option value="3" {{ old('position')== 3 ? 'selected' : '' }}>Sale</option>
                            </select>
                        </div>
                        <div class="down">
                            <label
                                style="display: inline-block; font-weight: 600; padding-bottom: 10px; padding-top: 10px;">Thứ
                                tự</label>
                            <input type="number" name="sort" min="1" step="1" oninput="validatePrice(this)"
                                value="{{old('sort')}}">
                        </div>
                    </div>
                    <div class="right">
                        <div class="up">
                            <label
                                style="display: inline-block; font-weight: 600; padding-bottom: 10px; padding-top: 10px;">Tiêu
                                đề 1:</label>
                            <input type="text" name="title1" value="{{old('title1')}}">
                            @if(old('form_type') == 'create')
                            @error('title1')
                            <span id="erop1">{{ $message }}</span>
                            @enderror
                            @endif
                        </div>
                        <div class="up">
                            <label
                                style="display: inline-block; font-weight: 600; padding-bottom: 10px; padding-top: 10px;">Tiêu
                                đề 2:</label>
                            <input type="text" name="title2" value="{{old('title2')}}">
                        </div>
                    </div>
                    <div class="right_2">
                        <button type="submit">Thêm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>