@foreach($ad as $us)
<div id="modal_EdAd_{{$us->id}}" class="modal_CrAd {{($errors->any()&&old('form_type') == 'edit_'.$us->id) ? '' : 'hidden'}}">
    <div class="Crad_content">
        <div style="text-align: end">
            <button class="closeEditAdmin btn-x" data-user_id="{{$us->id}}">&times;</button>
        </div>
        <div class="banner_tilte">
            <h2>CHỈNH SỬA TÀI KHOẢN QUẢN LÝ</h2>
        </div>
        <div class="banneForm">
            <form class="edit-admin" action="{{ route('upad',$us->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="form_type" value="edit_{{ $us->id }}">
                <input type="hidden" name="user_id" value="{{ $us->id }}">
                <div class="sec1">
                    <input id="file" type="file" accept="image/*" name="avatar" onchange="previImg(event)">
                    <label for="file">
                        @if($us->avatar)
                        <div id="imgpre-ad" style="margin-top: 10px;">
                            <img src="{{ asset($us->avatar) }}" alt="chưa xác định">
                            <span class="plus-icon"><i class="fa-solid fa-plus"></i></span>
                        </div>
                        @endif
                        @if($us->avatar == '')
                        <div id="imgpre-ad" style="margin-top: 10px;">
                            <span class="plus-icon"><i class="fa-solid fa-plus"></i></span>
                        </div>
                        @endif
                    </label>
                    <span>Thêm ảnh #id:{{ $us->id }}</span>
                    @if(old('form_type') == 'edit_'.$us->id)
                    @error('avatar')
                    <span id="erop1">{{ $message }}</span>
                    @enderror
                    @endif
                </div>
                <div class="sec-cen">
                    <label>Họ và Tên :</label>
                    <input type="text" name="last_name" placeholder="Nhập họ" value="{{$us->last_name}}">
                    @if(old('form_type') == 'edit_'.$us->id)
                    @error('last_name')
                    <span id="erop1">{{ $message }}</span>
                    @enderror
                    @endif
                    <input type="text" name="first_name" placeholder="Nhập tên" value="{{$us->first_name}}">
                    @if(old('form_type') == 'edit_'.$us->id)
                    @error('first_name')
                    <span id="erop1">{{ $message }}</span>
                    @enderror
                    @endif
                </div>
                <div class="sec2">
                    <div class="sec22">
                        <div class="sec2-1">
                            <label class="m100">Trạng thái:</label>
                            <select name="account_lock" class="">
                                <option value="1" {{ $us->account_lock == '1' ? 'selected' : '' }}>Hoạt
                                    động</option>
                                <option value="0" {{ $us->account_lock == '0' ? 'selected' : '' }}>Đang
                                    khóa</option>
                            </select>
                        </div>
                        <div class="sec2-2">
                            <div class="sec2-22">
                                <div class="left">
                                    <label>Ngày sinh:</label>
                                    <input type="date" name="dob" max="{{date('Y-m-d')}}" value="{{$us->dob}}">
                                </div>
                                <div class="right">
                                    <label>Cấp:</label>
                                    <select name="roles" class="">
                                        <option value="1" {{ $us->roles == '1' ? 'selected' : '' }}>Người dùng</option>
                                        <option value="2" {{ $us->roles == '2' ? 'selected' : '' }}>Quản lý</option>
                                        <option value="3" {{ $us->roles == '3' ? 'selected' : '' }}>Nhân viên</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sec22">
                        <div class="sec2-1">
                            <label class="m100">Số điện thoại:</label>
                            <input type="phone" name="phone" placeholder="Nhập SĐT" value="{{$us->phone}}">
                        </div>
                        <div class="sec2-2">
                            <label>Email:</label>
                            <input type="email" name="email" placeholder="Nhập Email" value="{{$us->email}}">
                        </div>
                    </div>
                    <div class="sec-er">
                        <div class="sec2-1">
                            @if(old('form_type') == 'edit_'.$us->id)
                            @error('phone')
                            <span id="erop1">{{ $message }}</span>
                            @enderror
                            @endif
                        </div>
                        <div class=" sec2-2">
                            @if(old('form_type') == 'edit_'.$us->id)
                            @error('email')
                            <span id="erop1">{{ $message }}</span>
                            @enderror
                            @endif
                        </div>
                    </div>
                    <div class="sec22">
                        <div class="sec2-1">
                            <label class="m100">Giới tính:</label>
                            <select name="gender" class="">
                                <option value="1" {{ $us->gender == '1' ? 'selected' : '' }}>Nam</option>
                                <option value="2" {{ $us->gender == '2' ? 'selected' : '' }}>Nữ</option>
                            </select>
                        </div>
                        <div class="sec2-2">
                            <label>Địa chỉ:</label>
                            <input type="address" name="address" placeholder="Nhập địa chỉ"
                                value="{{$us->address}}">
                        </div>
                    </div>
                    <div class="sec-er">
                        <div class="sec2-1">
                        </div>
                        <div class=" sec2-2">
                            @if(old('form_type') == 'edit_'.$us->id)
                            @error('address')
                            <span id="erop1">{{ $message }}</span>
                            @enderror
                            @endif
                        </div>
                    </div>
                    <div class="sec22">
                        <div class="sec2-1">
                            <span class="m100">Cập nhật lúc:</span>
                            <div>{{$us->updated_at->format('d/m/Y H:i:s')}}</div>
                        </div>
                        <div class="sec2-2">
                            <span>Thời gian tạo:</span>
                            <div>{{$us->created_at->format('d/m/Y H:i:s')}}</div>
                        </div>
                    </div>
                </div>
                <div class="sec4">
                    <button type="submit">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach