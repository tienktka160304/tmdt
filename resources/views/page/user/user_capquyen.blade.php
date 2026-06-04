<div class="modal_CrAd {{$errors->any() ? '' : 'hidden'}}" id="modal_CrAd_{{$us->id}}">
    <div class="Crad_content">
        <div style="text-align: end">
            <button class="closeCreateAdmin btn-x" data-userAD-id="{{$us->id}}">&times;</button>
        </div>
        <div class="banner_tilte">
            <h2>CẤP QUYỀN CHO NGƯỜI DÙNG</h2>
        </div>
        <div class="banneForm">
            <div class="edit-admin">
                <div class="sec1">
                    <label>
                        <div id="imgpre-adRole" style="margin-top: 10px;">
                            <img src="{{ asset($us->avatar) }}" alt="" onerror="this.onerror=null; this.src='{{ asset('img/user/default-avatar.png') }}';">
                        </div>
                    </label>
                </div>
                <div class="sec-cen">
                    <label>Họ và Tên : </label>
                    <p>{{$us->last_name}} {{$us->first_name}}</p>
                </div>
                <div class="sec2">
                    <div class="sec22">
                        <div class="sec2-1">
                            <label class="m100">Trạng thái:</label>
                            <p>{{$us->account_lock == '1' ? 'Hoạt động' : 'Khóa'}}</p>
                        </div>
                        <div class="sec2-2">
                            <div class="sec2-22">
                                <div class="left">
                                    <label>Ngày sinh:</label>
                                    @if(is_null($us->dob))
                                    <p>Chưa có</p>
                                    @else
                                    <p>{{$us->dob}}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sec22">
                        <div class="sec2-1">
                            <label class="m100">Số điện thoại:</label>
                            <p>{{$us->phone}}</p>
                        </div>
                        <div class="sec2-2">
                            <label>Email:</label>
                            <p>{{$us->email}}</p>
                        </div>
                    </div>
                    <div class="sec22">
                        <div class="sec2-1">
                            <label class="m100">Giới tính:</label>
                            <p>{{ $us->gender == '1' ? 'Nam' : ($us->gender == '2' ? 'Nữ' : 'Không') }}</p>
                        </div>
                        <div class="sec2-2">
                            <label>Địa chỉ:</label>
                            <p>{{$us->address}}</p>
                        </div>
                    </div>
                    <div class="sec22">
                        <div class="sec2-1">
                            <span class="m100">Cập nhật lúc:</span>
                            {{-- Đã thêm dấu ? để tránh lỗi khi dữ liệu trống --}}
                            <div>{{$us->updated_at?->format('d/m/Y H:i:s')}}</div>
                        </div>
                        <div class="sec2-2">
                            <span>Thời gian tạo:</span>
                            {{-- Đã thêm dấu ? để tránh lỗi khi dữ liệu trống --}}
                            <div>{{$us->created_at?->format('d/m/Y H:i:s')}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="capquyen_user">
                <form action="{{ route('user.UpRole', $us->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="form_type" value="editquyen_{{ $us->id }}">
                    <input type="hidden" name="user_id" value="{{ $us->id }}">
                    <input type="hidden" name="roles" value="2">
                    <button type="submit" class="border-yellow">Cấp quyền Quản lý</button>
                </form>

                <form action="{{ route('user.UpRole', $us->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="form_type" value="editquyen_{{ $us->id }}">
                    <input type="hidden" name="user_id" value="{{ $us->id }}">
                    <input type="hidden" name="roles" value="3">
                    <button type="submit" class="border-green">Cấp quyền Nhân viên</button>
                </form>
            </div>
        </div>
    </div>
</div>