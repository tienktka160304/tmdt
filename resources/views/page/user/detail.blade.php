@extends('layout')

@section('title', 'CHI TIẾT NGƯỜI DÙNG')
@section('content')
<main>
    <section class="page-main">
        <div class="boxuser">
            <div class="box-us1">
                <div class="box-us1-1">
                    <div class="left">
                        <img src="{{ asset($user->avatar) }}" alt="avatar"
                            onerror="this.onerror=null; this.src='{{ asset('img/user/default-avatar.png') }}';">
                    </div>
                    <div class="right">
                        <div class="right-name">{{$user->last_name}} {{$user->first_name}}</div>
                        <div class="right-email">{{$user->email}}</div>
                    </div>
                </div>
                <div class="box-us1-2">
                    <span>THÔNG TIN CÁ NHÂN</span>
                    <div class="box-us">
                        <div class="box-us">Số điện thoại</div>
                        <div>{{$user->phone}}</div>
                    </div>
                    <div class="box-us">
                        <div class="box-us">Giới tính</div>
                        <div>{{$user->genders == 1 ? 'Nam' : ($user->genders == 2 ? 'Nữ' : 'Chưa xác định')}}</div>
                    </div>
                    <div class="box-us">
                        <div class="box-us">Trạng thái</div>
                        <div>{{$user->account_lock == 1 ? 'Hoạt động' : 'Đang khóa'}}</div>
                    </div>
                </div>
                <div class="box-us1-3">
                    <span>ĐỊA CHỈ GIAO HÀNG</span>
                    <div class="ngat_hang">{{$user->address}}</div>
                    <div class="box-order-3">
                        <div>
                            <p style="text-align: center; font-weight: 600">{{$dht}}</p>
                            <span>Tổng Đơn Hàng</span>
                        </div>
                        <div>
                            <p style="text-align: center; font-weight: 600">{{$dhht}}</p>
                            <span>Đơn Hoàn Thành</span>
                        </div>
                        <div>
                            <p style="text-align: center; font-weight: 600">{{$dhhuy}}</p>
                            <span>Đơn Hàng Hủy</span>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <hr>
            <br>

        </div>

        <div class="header-filter-shared">
            <ul>
                <li><a href="{{ route('dtus', ['id'=>$user->id,'orderquery' => 'all']) }}"
                        class="{{ request()->routeIs('dtus') && request()->query('orderquery') == 'all' ? 'active' : ''  }}">Tất
                        cả</a></li>
                <li><a href="{{ route('dtus', ['id'=>$user->id,'orderquery' => '3']) }}"
                        class="{{ request()->routeIs('dtus') && request()->query('orderquery') == '3' ? 'active' : ''  }}">Hoàn
                        Thành</a></li>
                <li><a href=" {{ route('dtus', ['id'=>$user->id,'orderquery' => '0']) }}"
                        class="{{ request()->routeIs('dtus') && request()->query('orderquery') == '0' ? 'active' : ''  }}">Hủy</a>
                </li>
            </ul>
        </div>

        <div class="h1-key navbar">
            <form method="GET" class="glass-us">
                <div class="sr-container">
                    <input type="text" name="key" placeholder="ID Đơn Hàng.." value="{{ request()->key }}">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
            </form>
            <div class="filter-addbtn-right">
                <form method="GET" id="thutu1" class="xs-us">
                    <div class="sr-container">
                        <select name="thutu" id="thutu">
                            <option value="" {{ request('thutu')=='' ? 'selected' : '' }}>Đơn hàng mới nhất</option>
                            <option value="1" {{ request('thutu')=='1' ? 'selected' : '' }}>Trạng thái</option>
                            <option value="2" {{ request('thutu')=='2' ? 'selected' : '' }}>Tổng tiền</option>
                        </select>
                        <input type="hidden" name="key" value="{{ request('key') }}">
                        <input type="hidden" name="show" value="{{ request('show') }}">
                    </div>
                </form>
            </div>

        </div>

        <div class="grid-donhang">
            <div class="grid-tieudedh grid-order-layout grid_table_th_shared table__th">
                <div>MDH </div>
                <div>Phương Thức</div>
                <div>Trạng Thái</div>
                <div>Thanh Toán</div>
                <div>Điện Thoại</div>
                <div>Địa Chỉ</div>
                <div>Ngày Đặt</div>
                <div>Tổng Tiền</div>
            </div>
            @foreach($order as $dh)
            @php
            $time = \Carbon\Carbon::parse($dh->order_date)->setTimezone(config('app.timezone'));
            @endphp
            <div class="grid-rowdh grid-order-layout grid_table_tb_shared subtable_tr modonhang-user">
                <div class="hienthi_detail bot-icon" data-order-id="{{$dh->id}}"><i
                        class="fa-solid fa-plus"></i>MĐH{{$dh->id}}</div>
                <div class="left">{{$dh->payment->payment_method}}</div>
                <div class="{{$dh->status == 1 ? 'vang' : ($dh->status == 2 ? 'blue' : ($dh->status == 3 ? 'gr' : 'red'))}} status_order"
                    data-order-status-id="{{$dh->id}}">
                    {{$dh->status == 1 ? 'Chưa xử lý' : ($dh->status == 2 ? 'Đang giao' : ($dh->status == 3 ? 'Hoàn
                    thành' : 'Hủy'))}}
                    @if(($dh->status == 1 || $dh->status == 2) && $dh->thanh_toan == 1)
                    <i class="fa-solid fa-sort-down row_ic"></i>
                    <form id="form-{{$dh->id}}" class="order_form_status" action="{{route('orders.updateStatus')}}"
                        method="POST">
                        @csrf
                        <input type="hidden" name="order_id" value="{{$dh->id}}">
                        <input type="hidden" name="status" id="hidden-status-{{$dh->id}}">
                        @if($dh->status == 1)
                        <button class="button border-red change-stauts" data-order-status="{{$dh->id}}"
                            data-status="0">Hủy</button>
                        <button class="button border-blue change-stauts" data-order-status="{{$dh->id}}"
                            data-status="2">Đang giao</button>
                        @elseif($dh->status == 2)
                        <button class="button border-green change-stauts" data-order-status="{{$dh->id}}"
                            data-status="3">Hoàn Thành</button>
                        @endif
                    </form>
                    @endif
                </div>
                <div class="{{$dh->thanh_toan == 0 ? 'vang' : 'gr'}}">{{$dh->thanh_toan == 0 ? 'Chưa thanh toán' : 'Đã
                    thanh toán'}}</div>
                <div>{{$dh->phone}}</div>
                <div class="left">{{$dh->address }}</div>
                <div>
                    <div>{{$time->diffForHumans()}}</div>
                    <div class="subtable_th">{{$time->isoFormat('D MMMM')}}</div>
                </div>
                <div>{{number_format($dh->total_price,0,'.','.')}}đ</div>
            </div>
            <div class="an-dh chitiet_donhang" id="an-dh-{{$dh->id}}">
                <div class="ct-dh">Chi tiết đơn hàng</div>

            </div>
            <div class="subgrid-th th_donhang subtable_th" id="detail-{{$dh->id}}">
                <div></div>
                <div>MCT</div>
                <div class="left">Tên Sản Phẩm</div>
                <div>Giá</div>
                <div>Số lượng</div>
                <div class="actions-d grid-css ct">
                    <a href="{{route('donhang_chitiet', ['id' => $dh->id])}}" class="border-green">
                        <i class="fa-solid fa-eye"></i>
                        <p>Xem</p>
                    </a>
                </div>
                <div></div>
            </div>
            @foreach($dh->orders_detail as $detail)
            <div class="subgrid-row subtable_tr detail_donhang" id="details-{{$dh->id}}">
                <div></div>
                <div>MCT{{$detail->productVariant->id}}</div>
                <div class="left">
                    <div>{{$detail->productVariant->product->name}} ({{$detail->productVariant->option}})</div>
                </div>
                <div>{{number_format($detail->price,0,'.','.')}}</div>
                <div>{{$detail->quantity}}</div>
                <div></div>
            </div>
            @endforeach
            @endforeach
        </div>


    </section>
</main>
@endsection