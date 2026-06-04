@extends('layout')

@section('title', 'Quản lý Payment')

@section('content')
<main>
  <section class="page-main">
    <div class="payment-section" style="text-align: right">
      <button class=" btn-primary" onclick="openModal()">
        <i class="fa-solid fa-circle-plus"></i>
        Thêm phương thức
      </button>
    </div>

    <div class="payment-grid-container  ">
      <div class="grid-payment-layout grid_table_th_shared header">
        <div>ID</div>
        <div>Phương thức</div>
        <div>Ngân hàng</div>
        <div>Số tài khoản</div>
        <div>Trạng thái</div>
        <div>Hành động</div>
      </div>

      @foreach($payments as $key => $pay)
      <div class="grid-payment-layout grid_table_tb_shared  body">
        <div>{{ $key + 1 }}</div>
        <div style="justify-self: start">{{ $pay->payment_method }}</div>
        <div>
          @if (!empty($pay->bank))
          {{ $pay->bank }}
          @else
          <p style="opacity: 0.4">Chưa có thông tin</p>
          @endif
        </div>

        <div>
          @if (!empty($pay->bank_number))
          {{ $pay->bank_number }}
          @else
          <p style="opacity: 0.4">Chưa có thông tin</p>
          @endif
        </div>
        <div>
          <form action="{{ route('payments.toggleStatus', $pay->id) }}" method="POST" id="form-toggle-{{ $pay->id }}" style="display:inline;">
            @csrf
            @method('PUT')
            <button type="button" class="btn-toggle-status icon-status-btns {{ $pay->status == 0 ? 'icon-status-unmount' : 'icon-status-mount' }}"
              data-id="{{ $pay->id }}"
              title="{{ $pay->status == 0 ? 'Nhấn để hiện' : 'Nhấn để ẩn' }}">
              <i class="fa-solid {{ $pay->status == 0 ? 'fa-eye-slash' : 'fa-eye' }}"></i>
            </button>
          </form>
        </div>
        <div class="action-pament action-d grid-css ct">
          <a href="javascript:void(0)" onclick="openEditModal({{ $pay->id }})" title="Sửa thanh toán" class="border-yellow hover_scale_btn">
            <i class="fa-regular fa-pen-to-square"></i>
          </a>

        </div>
      </div>
      @endforeach

    </div>
  </section>
</main>

{{-- Modal thêm --}}
<!-- Tự động mở popup nếu có lỗi -->
@if ($errors->any())
<script>
  window.onload = function() {
      document.getElementById('paymentModal').style.display = 'block';
    }
</script>
@endif

<div id="paymentModal" class="modal" style="display: none;">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <h2>Thêm phương thức thanh toán</h2>
    <form action="{{ route('payments.store') }}" method="POST">
      @csrf

      <div class="form-group-payment">
        <label>Phương thức thanh toán</label>
        <input type="text" name="payment_method" value="{{ old('payment_method') }}">
        @error('payment_method')
        <div class="baoloi">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group-payment">
        <label>Ngân hàng</label>
        <input type="text" name="bank" value="{{ old('bank') }}">
      </div>
      <div class="form-group-payment">
        <label>Số tài khoản</label>
        <input type="number" name="bank_number" value="{{ old('bank_number') }}">
      </div>
      <div class="form-group-payment">
        <label>Trạng thái</label>
        <select name="status">
          <option value="1" {{ old('status')=='1' ? 'selected' : '' }}>Hiện</option>
          <option value="0" {{ old('status')=='0' ? 'selected' : '' }}>Ẩn</option>
        </select>
      </div>

      <div class="btn-payment">
        <button type="submit" class="btn-success">
          <i class="fa-solid fa-circle-plus"></i> Thêm phương thức
        </button>
      </div>
    </form>
  </div>
</div>
{{-- Modal sửa --}}
<div id="editPaymentModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeEditModal()">&times;</span>
    <h2>Sửa phương thức thanh toán</h2>
    <form id="editPaymentForm" action="{{ route('payments.update',  $pay->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="form-group-payment">
        <label>Phương thức thanh toán</label>
        <input type="text" name="payment_method" id="payment_method" required>
      </div>
      <div class="form-group-payment">
        <label>Ngân hàng</label>
        <input type="text" name="bank" id="bank">
      </div>
      <div class="form-group-payment">
        <label>Số tài khoản</label>
        <input type="number" name="bank_number" id="bank_number">
      </div>
      <div class="form-group-payment">
        <label>Trạng thái</label>
        <select name="status" id="statuss">
          <option value="1">Hiện</option>
          <option value="0">Ẩn</option>
        </select>
      </div>
      <div class="btn-payment">
        <button type="submit" class="btn-success">
          <i class="fa-solid fa-pen-to-square"></i>
          Cập nhật
        </button>
      </div>
    </form>
  </div>
</div>





<script>
  function openModal() {
    document.getElementById('paymentModal').style.display = 'block';
  }

  function closeModal() {
    document.getElementById('paymentModal').style.display = 'none';
  }

  function openEditModal(id) {
  const paymentData = @json($payments);
  const payment = paymentData.find(payment => payment.id == id);  

  document.getElementById('payment_method').value = payment.payment_method;
  document.getElementById('bank').value = payment.bank;
  document.getElementById('bank_number').value = payment.bank_number;
  document.getElementById('statuss').value = payment.status;
  
  document.getElementById('editPaymentForm').action = `/payments/${id}`;

  // Hiển thị modal
  document.getElementById('editPaymentModal').style.display = 'block';
}

function closeEditModal() {
  document.getElementById('editPaymentModal').style.display = 'none';
}
window.onclick = function (event) {
  const modal = document.getElementById('editPaymentModal');
  if (event.target === modal) {
    closeEditModal();
  }
};
</script>
<script src="{{ asset('/js/Alerts.js') }}"></script>
@endsection