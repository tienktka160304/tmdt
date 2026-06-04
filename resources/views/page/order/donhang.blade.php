@extends('layout')

@section('title', 'DANH SÁCH ĐƠN HÀNG')

@section('content')

<main>
  <section class="page-main">
    <div class="donhang">
      <div class="header-filter-shared ">
        <ul>
          <li><a href="{{ route('donhang', ['orderquery' => 'all']) }}" class="{{ request()->routeIs('donhang') && request()->query('orderquery') == 'all' ? 'active' : ''  }}">Tất cả</a></li>
          <li><a href="{{ route('donhang', ['orderquery' => '3']) }}" class="{{ request()->routeIs('donhang') && request()->query('orderquery') == '3' ? 'active' : ''  }}">Hoàn Thành</a></li>
          <li><a href="{{ route('donhang', ['orderquery' => '1']) }}" class="{{ request()->routeIs('donhang') && request()->query('orderquery') == '1' ? 'active' : ''  }}">Đang xử lý</a></li>
          <li><a href="{{ route('donhang', ['orderquery' => '2']) }}" class="{{ request()->routeIs('donhang') && request()->query('orderquery') == '2' ? 'active' : ''  }}">Đang Giao</a></li>
          <li><a href="{{ route('donhang', ['orderquery' => '0']) }}" class="{{ request()->routeIs('donhang') && request()->query('orderquery') == '0' ? 'active' : ''  }}">Đã Hủy</a></li>
        </ul>
      </div>
    </div>

    <div class="h1-key">
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
            <input type="hidden" name="orderquery" value="{{ request('orderquery') }}">
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
        <div class="hienthi_detail bot-icon hover_scale_btn" data-order-id="{{$dh->id}}"><i class="fa-solid fa-plus "></i>{{$dh->id}}</div>
        <div class="left">{{$dh->payment->payment_method}}</div>
        
        <div class="order-status-container">
          {{-- Khối logic xử lý nút bấm duyệt đơn --}}
          <form id="form-{{$dh->id}}" class="order_form_status" action="{{route('orders.updateStatus')}}" method="POST">
            @csrf
            <input type="hidden" name="order_id" value="{{$dh->id}}">
            <input type="hidden" name="status" id="hidden-status-{{$dh->id}}">
            <input type="hidden" name="thanh_toan" id="hidden-payment-{{$dh->id}}" value="{{$dh->thanh_toan}}">
            
            <div class="status-flex">
              @if($dh->status == 1)
                <div class="right-action">
                  <button type="button" class="button border-blue btn-change-status" data-order-id="{{$dh->id}}" data-status="2">Duyệt & Giao</button>
                </div>
                <div class="center-status vang">Đang xử lý</div>
                <div class="left-action">
                  <button type="button" class="button border-red btn-change-status" data-order-id="{{$dh->id}}" data-status="0">Hủy đơn</button>
                </div>

              @elseif($dh->status == 2)
                <div class="center-status blue">Đang giao</div>
                <div class="right-action">
                  <button type="button" class="button border-green btn-change-status" data-order-id="{{$dh->id}}" data-status="3" data-payment="1">Hoàn thành</button>
                </div>

              @else
                <div class="center-status {{$dh->status == 3 ? 'gr' : 'red'}}">
                  {{$dh->status == 3 ? 'Hoàn thành' : 'Đã Hủy'}}
                </div>
              @endif
            </div>
          </form>
        </div>

        <div class="{{$dh->thanh_toan == 0 ? 'vang' : 'gr'}}">
          {{$dh->thanh_toan == 0 ? 'Chưa thanh toán' : 'Đã thanh toán'}}
        </div>
        <div>{{$dh->phone}}</div>
        <div class="left">{{$dh->address }}</div>
        <div>
          <div>{{$time->diffForHumans()}}</div>
          <div class="subtable_th">{{$time->isoFormat('D MMMM')}}</div>
        </div>
        <div>{{number_format($dh->total_price,0,'.','.')}}đ</div>
      </div>

      {{-- Chi tiết đơn hàng --}}
      <div class="an-dh chitiet_donhang" id="an-dh-{{$dh->id}}" style="margin: 0 50px; font-size: 14px">
        <div class="ct-dh" style="font-size: 16px">Chi tiết đơn hàng</div>
      </div>
      <div class="grid-suborder-layout grid_table_th_shared_sb th_donhang subtable_th" id="detail-{{$dh->id}}"
        style="margin: 0 50px;font-weight: 600; font-size: 14px">
        <div></div>
        <div>MCT</div>
        <div class="left">Tên Sản Phẩm</div>
        <div>Giá</div>
        <div>Số lượng</div>
        <div class="actions-d grid-css ct">
          <a href="{{route('donhang_chitiet', ['id' => $dh->id])}}" title="Xem chi tiết" class="border-green hover_scale_btn">
            <i class="fa-solid fa-eye"></i>
          </a>
        </div>
        <div></div>
      </div>

      @foreach($dh->orders_detail as $detail)
      <div class="grid-suborder-layout grid_table_tb_shared_sb subtable_tr detail_donhang" id="details-{{$dh->id}}"
        style="margin: 0 50px; padding:20px 0; font-size: 14px; ">
        <div></div>
        <div>MCT{{$detail->productVariant->id}}</div>
        <div class="left">
          <div>{{$detail->productVariant->product->name}} ({{$detail->productVariant->option}})</div>
        </div>
        <div>{{number_format($detail->price,0,'.','.')}}</div>
        <div>{{$detail->quantity}}</div>
      </div>
      @endforeach

      @endforeach
    </div>

    {{-- Phần phân trang --}}
    <div class="end-user">
      <div class="left">
        <form method="GET" id="show1" class="show-sl">
          <label for="">Hiển Thị:</label>
          <select name="show" id="show">
            <option value="10" {{request('show')==10 ? 'selected' : '' }}>10</option>
            <option value="2" {{request('show')==2 ? 'selected' : '' }}>2</option>
            <option value="30" {{request('show')==30 ? 'selected' : '' }}>30</option>
            <option value="50" {{request('show')==50 ? 'selected' : '' }}>50</option>
          </select>
          <input type="hidden" name="key" value="{{ request('key') }}">
          <input type="hidden" name="thutu" value="{{ request('thutu') }}">
          <input type="hidden" name="orderquery" value="{{ request('orderquery') }}">
        </form>
      </div>
    </div>
  </section>
</main>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Xử lý nút thay đổi trạng thái đơn hàng
    const actionButtons = document.querySelectorAll('.btn-change-status');
    
    actionButtons.forEach(button => {
      button.addEventListener('click', function() {
        const orderId = this.getAttribute('data-order-id');
        const status = this.getAttribute('data-status');
        const paymentData = this.getAttribute('data-payment'); // Lấy dữ liệu payment từ nút (nếu có)
        const actionName = this.innerText;

        if(confirm(`Bạn có chắc chắn muốn thực hiện thao tác: ${actionName}?`)) {
          const hiddenInputStatus = document.getElementById(`hidden-status-${orderId}`);
          const hiddenInputPayment = document.getElementById(`hidden-payment-${orderId}`);
          const targetForm = document.getElementById(`form-${orderId}`);
          
          if(hiddenInputStatus && targetForm) {
            hiddenInputStatus.value = status; // Gán status mới (vd: 3)
            
            // Nếu nút bấm là Hoàn thành (có data-payment = 1), thì chuyển luôn thanh_toan = 1
            if(paymentData == "1" && hiddenInputPayment) {
              hiddenInputPayment.value = 1;
            }

            targetForm.submit();
          }
        }
      });
    });

    // Auto submit form khi đổi số lượng hiển thị
    document.getElementById('show')?.addEventListener('change', function() {
      document.getElementById('show1').submit();
    });
    
    document.getElementById('thutu')?.addEventListener('change', function() {
      document.getElementById('thutu1').submit();
    });
  });
</script>

<script src="{{ asset('/js/Alerts.js') }}"></script>
@endsection