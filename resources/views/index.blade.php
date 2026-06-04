@extends('layout')

@section('title', 'TRANG CHỦ')
@section('content')
<main>
    <div class="thongke">
        <h2>Thống kê</h2>
        <form method="GET" id="month-fill">
            <select name="date_fillter" id="date_fillter" onchange="this.form.submit()">
                <option value="">Hôm nay</option>
                <option value="homqua" {{ request('date_fillter')=='homqua' ? 'selected' : '' }}>Hôm qua</option>
                <option value="tuannay" {{ request('date_fillter')=='tuannay' ? 'selected' : '' }}>Tuần này</option>
                <option value="tuantruoc" {{ request('date_fillter')=='tuantruoc' ? 'selected' : '' }}>Tuần trước</option>
                <option value="thangnay" {{ request('date_fillter')=='thangnay' ? 'selected' : '' }}>Tháng này</option>
                <option value="thangtruoc" {{ request('date_fillter')=='thangtruoc' ? 'selected' : '' }}>Tháng trước</option>
            </select>
        </form>
    </div>

    <section class="alert-dasboarh">
        <div class="box1">
            <div>
                <div class="new_red">
                    <span>Đơn hàng</span>
                    @if($dhm>0)
                    <div></div>
                    @else
                    @endif
                </div>
                <div class="new_ico red">
                    <p>{{$dhm}}</p>
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
            </div>
        </div>
        <div class="box1">
            <div>
                <div class="new_red">
                    <span>Người dùng</span>
                    @if($ust>0)
                    <div></div>
                    @else
                    @endif
                </div>
                <div class="new_ico gr">
                    <p>{{$ust}}</p>
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
        </div>
        <div class="box1">
            <div>
                <div class="new_red">
                    <span>Đánh giá</span>
                    @if($usRe>0)
                    <div></div>
                    @else
                    @endif
                </div>
                <div class="new_ico blue">
                    <p>{{$usRe}}</p>
                    <i class="fa-solid fa-user-pen"></i>
                </div>
            </div>
        </div>
        <div class="box1">
            <div>
                <span>Tổng thu</span>
                @if($total_price > 0)
                <p class="gr">{{ number_format($total_price, 0, '.', '.') }}đ</p>
                @else
                <p class="gr">0đ</p>
                @endif
            </div>
        </div>
    </section>

    <section class="producttop">
        <div class="container-productTop">
            <div class="left-product">
                <h3>Biểu đồ Doanh Thu Hàng Tháng</h3>
                
                <div style="position: relative; width: 100%; min-height: 350px;">
                    <canvas id="canvas_order"></canvas>
                </div>
                
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    var canvasLabel = @json($label ?? []);
                    var canvasData = @json($data_month ?? []);

                    document.addEventListener("DOMContentLoaded", function() {
                        var ctx = document.getElementById('canvas_order').getContext('2d');
                        new Chart(ctx, {
                            type: 'bar', // Cột
                            data: {
                                labels: canvasLabel,
                                datasets: [{
                                    label: 'Tổng thu (VNĐ)',
                                    data: canvasData,
                                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false, // Để biểu đồ fill vừa div cha
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    });
                </script>
            </div>
            <div class="right-product">
                <div>
                    <h3>Sản phẩm bán chạy</h3>
                    <div>
                        <div class="grid-container grid_table_th_shared ">
                            <div>ID</div>
                            <div>Sản phẩm</div>
                            <div>Bán</div>
                        </div>
                        @foreach ($sptop as $sp)
                        <div class="grid-container grid_table_tb_shared">
                            <div>{{ $sp->id }}</div>
                            <div class="name-product textLeft fle">
                                <div class="right" style="font-size: 14px">
                                    {{ $sp->name }}
                                </div>
                            </div>
                            <div>{{ $sp->total_buy ?? '0'}}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="producttop">
        <div class="container-productTop">
            <div class="left-product">
                <h3>Sản phẩm sắp hết hàng</h3>
                <div class="grid-container_var grid_table_th_shared ">
                    <div>ID</div>
                    <div>Tên sản phẩm</div>
                    <div>Ảnh sản phẩm</div>
                    <div>Giá tiền</div>
                    <div>Số lượng</div>
                </div>
                @foreach ($sphh as $bt)
                <div class="grid-container_var grid_table_tb_shared">
                    <div>{{ $bt->id }}</div>
                    <div class="textLeft">{{ $bt->product->name }} ({{ $bt->option }})</div>
                    <div>
                        <img src="{{ asset($bt->image) }}" alt="Lỗi...">
                    </div>
                    <div class="flex-index">
                        <div class="flex1 red">{{ number_format($bt->price, 0, '.', '.') }}đ</div>
                    </div>
                    <div>{{ $bt->stock}}</div>
                </div>
                @endforeach
                <div class="end-user">
                    <div class="left">
                    </div>
                    <div class="right">
                        @if ($use->lastPage() > 1)
                        <ul>
                            <li class="{{ $pageht == 1 ? 'disable' : '' }}">
                                <a href="{{$use->appends(request()->query())->previousPageUrl()}}"><i
                                        class="fa-solid fa-caret-left"></i></a>
                            </li>
                            @if ($start > 1)
                            <li><a href="{{$use->appends(request()->query())->url(1)}}">1</a>
                            </li>
                            @if($start > 2)
                            <li class="disabled"><span>...</span></li>
                            @endif
                            @endif
                            @for ($i = $start; $i <= $end; $i++) 
                                <li class="{{($pageht == $i) ? 'active' : ''}}">
                                    <a href="{{$use->appends(request()->query())->url($i) }}">{{$i}}</a>
                                </li>
                            @endfor
                            @if ($end < $lapa) 
                                @if ($end < $lapa - 1) 
                                    <li class="disabled"><span>...</span></li>
                                @endif
                                <li><a href="{{$use->appends(request()->query())->url($lapa)}}">{{$lapa}}</a></li>
                            @endif

                            <li class="{{($pageht == $lapa) ? 'disabled' : ''}}">
                                @if($pageht == $lapa)
                                <a href="{{$use->appends(request()->query())->url(1)}}"><i
                                        class="fa-solid fa-caret-right"></i></a>
                                @else
                                <a href="{{$use->appends(request()->query())->nextPageUrl()}}"><i
                                        class="fa-solid fa-caret-right"></i></a>
                                @endif
                            </li>
                        </ul>
                        @endif
                    </div>
                </div>
            </div>
            <div class="right-product">
                <h3>Người dùng mới</h3>
                @if($us->isNotEmpty())
                <div class="grid-container__user grid_table_th_shared ">
                    <div>ID</div>
                    <div>Tên</div>
                    <div>Ảnh</div>
                </div>
                @foreach($us as $user)
                <div class="grid-container__user grid_table_tb_shared">
                    <div class="ct">{{$user->id}}</div>
                    <div class=" left">
                        <div class="right-tren">{{$user->last_name}} {{$user->first_name}} </div>
                    </div>
                    <div class=" right">
                        <img src="{{ asset($user->avatar) }}" alt="avatar"
                            onerror="this.onerror=null; this.src='{{ asset('img/user/default-avatar.png') }}';">
                    </div>
                </div>
                @endforeach
                @else
                <div class="tracsic">
                    <div>Chưa có người dùng mới</div>
                </div>
                @endif
                <div class="end-user">
                    <div class="left">
                    </div>
                    <div class="right">
                        @if ($usernew->lastPage() > 1)
                        <ul>
                            <li class="{{ $pageht_user == 1 ? 'disable' : '' }}">
                                <a href="{{$usernew->appends(request()->query())->previousPageUrl()}}"><i
                                        class="fa-solid fa-caret-left"></i></a>
                            </li>
                            @if ($start_user > 1)
                            <li><a href="{{$usernew->appends(request()->query())->url(1)}}">1</a>
                            </li>
                            @if($start_user > 2)
                            <li class="disabled"><span>...</span></li>
                            @endif
                            @endif
                            @for ($i = $start_user; $i <= $end_user; $i++) 
                                <li class="{{($pageht_user == $i) ? 'active' : ''}}">
                                    <a href="{{$usernew->appends(request()->query())->url($i) }}">{{$i}}</a>
                                </li>
                            @endfor
                            @if ($end_user < $lapa_user) 
                                @if ($end_user < $lapa_user - 1) 
                                    <li class="disabled"><span>...</span></li>
                                @endif
                                <li><a href="{{$usernew->appends(request()->query())->url($lapa_user)}}">{{$lapa_user}}</a></li>
                            @endif

                            <li class="{{($pageht_user == $lapa_user) ? 'disabled' : ''}}">
                                @if($pageht_user == $lapa_user)
                                <a href="{{$usernew->appends(request()->query())->url(1)}}"><i
                                        class="fa-solid fa-caret-right"></i></a>
                                @else
                                <a href="{{$usernew->appends(request()->query())->nextPageUrl()}}"><i
                                        class="fa-solid fa-caret-right"></i></a>
                                @endif
                            </li>
                        </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection