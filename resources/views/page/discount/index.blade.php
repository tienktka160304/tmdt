@extends('layout')

@section('title','Quản lý khuyến mãi')

@section('content')
<main>
  <section class="page-main">

    <div class="header-filter-shared">
      <ul>
        <li>
          <a href="{{ route('khuyenmai')}}" class="{{ request()->query('query') == null ? 'active' : '' }}">
            Tất cả
          </a>
        </li>
        <li>
          <a href="{{ route('khuyenmai', ['query' => 'active']) }}"
            class="{{ request()->query('query') == 'active' ? 'active' : '' }}">
            Đang khuyến mãi
          </a>
        </li>
        <li>
          <a href="{{ route('khuyenmai', ['query' => 'ended']) }}"
            class="{{ request()->query('query') == 'ended' ? 'active' : '' }}">
            Khuyến mãi kết thúc
          </a>
        </li>
        <li>
          <a href="{{ route('khuyenmai', ['query' => 'hidden']) }}"
            class="{{ request()->query('query') == 'hidden' ? 'active' : '' }}">
            Đã ẩn
          </a>
        </li>
        <li>
          <a href="{{ route('khuyenmai', ['query' => 'deleted']) }}"
            class="{{ request()->query('query') == 'deleted' ? 'active' : '' }}">
            Đã xóa
          </a>
        </li>
      </ul>
    </div>


    <div class="navbar">
      <form method="GET" class="glass-us">
        <div class="sr-container">
          <input type="text" name="key" placeholder="Tìm kiếm khuyến mãi" value="{{ request()->key }}">
          <i class="fa-solid fa-magnifying-glass"></i>
        </div>
      </form>

      <div class="filter-addbtn-right">
        <form method="GET" id="thutu1" class="xs-us">
          <div class="sr-container">
            <select name="sort" onchange="this.form.submit()">
              <option value="">Lọc khuyến mãi</option>
              <option value="value_asc" {{ request()->sort == 'value_asc' ? 'selected' : '' }}>% khuyến mãi tăng dần</option>
              <option value="value_desc" {{ request()->sort == 'value_desc' ? 'selected' : '' }}>% khuyến mãi giảm dần</option>
            </select>
          </div>
        </form>
        <div class="cr-sp">
          <button id="openModalBtn"> <i class="fa-solid fa-circle-plus"></i>Thêm khuyến mãi</button>
        </div>
      </div>
    </div>

    <div id="brandModal" class="modal-overlay">
      <div class="modal-content">
        <h3>Thêm khuyến mãi</h3>
        <button class="close-modal" id="closeModalBtn">&times;</button>
        <form id="khuyenmaiForm" action="{{ route('khuyenmai.store') }}" method="POST">
          @csrf
          <div class="form-group-edit">
            <label for="name" style="font-weight: 600;">Tên khuyến mãi</label>
            <input type="text" class="form-control" name="name" id="name" required>
          </div>
          <div class="flex-sort-status">
            <div class="sort-cate" style="margin-bottom: 20px;">
              <div style="display: flex; flex-direction: column; gap: 8px;">
              <label for="value" style="font-weight: 600  ;">Phần trăm giảm giá (%)</label>
              <input type="number" class="form-control" name="value" id="value" required min="1" max="100">
              </div>
            </div>
            <div class="status-cate toggle-group">
              <div  style="display: flex; flex-direction: column; gap: 8px;">
              <label for="status" style="font-weight: 600;">Trạng thái</label>
              <input type="hidden" name="status" value="0">
              <label class="switch">
                <input type="checkbox" name="status" value="1" id="status">
                <span class="slider round"></span>
              </label>
            </div>
          </div>
          </div>
          
          <div class="form-group-edit">
            <label for="description" style="font-weight: 600;">Nội dung khuyến mãi</label>
            <textarea type="text" class="form-control" name="description" id="description" required></textarea>
          </div>
          <div class="time-km">
          <div class="form-group-edit">
            <label for="time_start" style="font-weight: 600;">Ngày bắt đầu</label>
            <input type="datetime-local" step="1" class="form-control" name="time_start" id="time_start" required>
          </div>
          <div class="form-group-edit">
            <label for="time_end" style="font-weight: 600;">Ngày kết thúc (Không bắt buộc)</label>
            <input type="datetime-local" step="1" class="form-control" name="time_end" id="time_end">
          </div>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-primary">Thêm mới</button>
        </div>
        </form>
      </div>
    </div>

    <div id="editModal" class="modal-overlay">
      <div class="modal-content">
        <h3>Chỉnh sửa khuyến mãi</h3>
        <button class="close-modal" id="closeEditModalBtn">&times;</button>
        <form id="editForm" method="POST">
          @csrf
          @method('PUT')
          <input type="hidden" name="id" id="edit_id">
          <div class="form-group-edit">
            <label for="edit_name" style="font-weight: 600;">Tên khuyến mãi</label>
            <input type="text" class="form-control" name="name" id="edit_name" required>
          </div>

          <div class="flex-sort-status">
            <div class="sort-cate" style="margin-bottom: 20px;">
              <div style="display: flex; flex-direction: column; gap: 8px;">
              <label for="value" style="font-weight: 600;">Phần trăm giảm giá (%)</label>
              <input type="number" class="form-control" name="value" id="edit_value" required min="1" max="100">
              </div>
            </div>
            <div class="status-cate toggle-group">
              <div  style="display: flex; flex-direction: column; gap: 8px;">
              <label for="status" style="font-weight: 600;">Trạng thái</label>
              <input type="hidden" name="status" value="0">
              <label class="switch">
                <input type="checkbox" name="status" value="1" id="edit_status">
                <span class="slider round"></span>
              </label>
            </div>
          </div>
          </div>
          <div class="form-group-edit">
            <label for="edit_description" style="font-weight: 600;">Nội dung khuyến mãi</label>
            <textarea type="text" class="form-control" name="description" id="edit_description" required></textarea>
          </div>
          <div class="time-km">
          <div class="form-group-edit">
            <label for="edit_time_start" style="font-weight: 600;">Ngày bắt đầu</label>
            <input type="datetime-local" step="1" class="form-control" name="time_start" id="edit_time_start" required>
          </div>
          <div class="form-group-edit">
            <label for="edit_time_end" style="font-weight: 600;">Ngày kết thúc</label>
            <input type="datetime-local" step="1" class="form-control" name="time_end" id="edit_time_end">
          </div>
          </div>
          {{-- <div class="form-group-edit">
            <label for="edit_status">Trạng thái</label>
            <select name="status" class="form-control" id="edit_status">
              <option value="1">Hoạt động</option>
              <option value="0">Không hoạt động</option>
            </select>
          </div> --}}
          <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
      </div>
    </div>

    <div class="grid_table_th_shared grid-discount-layout">
      <div>ID</div>
      <div>Tên khuyến mãi</div>
      <div>Nội dung</div>
      <div>KM(%)</div>
      <div>Bắt đầu</div>
      <div>Kết thúc</div>
      <div>Trang thái</div>
      <div>Hành động</div>
    </div>

    @foreach($discounts as $km)
    <div class="grid_table_tb_shared grid-discount-layout ">
      <div class="gird-css ct" style="font-weight: 600">{{$km->id}}</div>
      <div class="gird-css ct">{{$km->name}}</div>
      <div class="gird-css ct" style="text-wrap:wrap;">{{$km->description}}</div>
      <div class="gird-css ct">{{$km->value}} %</div>
      <div class="gird-css ct" style="text-align: center; display: block">
        @if($km->time_start)

        <p style="padding-bottom: 5px">{{ \Carbon\Carbon::parse($km->time_start)->format('d/m/Y') }}</p>
        <p> {{ \Carbon\Carbon::parse($km->time_start)->format('H:i:s') }}</p>
        @else
        <p>Không có</p>
        @endif
      </div>
      <div class="gird-css ct" style="text-align: center; display: block">
        @if($km->time_end)

        <p style="padding-bottom: 5px">{{ \Carbon\Carbon::parse($km->time_end)->format('d/m/Y') }}</p>
        <p> {{ \Carbon\Carbon::parse($km->time_end)->format('H:i:s') }}</p>
        @else
        <p>Không có</p>
        @endif
      </div>

      <div class="gird-css ct">
        {{-- <form action="{{ route('khuyenmai.togglestatus', $km->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn thay đổi trạng thái?')">
          @csrf
          <button type="submit" style="background:none; border:none; cursor:pointer;">
            @if($km->status == 1)
            <button class="icon-status-btns icon-status-mount hover_scale_btn" title="Nhấn để ẩn">
              <i class="fa-solid fa-eye"></i>
            </button>
            @else
            <button class="icon-status-btns icon-status-unmount hover_scale_btn" title="Nhấn để hiện">
              <i class="fa-solid fa-eye-slash"></i>
            </button>
            @endif
          </button>
        </form> --}}
        <form action="{{ route('khuyenmai.togglestatus', $km->id) }}"
          method="POST"
          id="form-toggle-{{ $km->id }}"
          style="display:inline;">
      @csrf
      {{-- <button type="button"
              class="btn-toggle-status"
              data-id="{{ $km->id }}"
              title="Nhấn để thay đổi trạng thái"
              style="background:none; border:none; cursor:pointer;">
        @if($km->status == 1)
          <button class="icon-status-btns icon-status-mount">
            <i class="fa-solid fa-eye"></i>
          </button>
        @else
          <button class="icon-status-btns icon-status-unmount">
            <i class="fa-solid fa-eye-slash"></i>
          </button>
        @endif
      </button> --}}
      <button type="button"
      class="btn-toggle-status icon-status-btns hover_scale_btn {{ $km->status == 1 ? 'icon-status-mount' : 'icon-status-unmount' }}"
      data-id="{{ $km->id }}"
      title="{{ $km->status == 1 ? 'Nhấn để ẩn' : 'Nhấn để hiện' }}"
      style="background:none; border:none; cursor:pointer;">
<i class="fa-solid {{ $km->status == 1 ? 'fa-eye' : 'fa-eye-slash' }}"></i>
</button>

    </form>

   
    
        
      </div>
      <div class="actions-d ct">
        @if(request()->query('query') === 'deleted')
        
        <form action="{{ route('khuyenmai.restore', $km->id) }}" method="POST" style="display:inline;" class="form-restore-khuyenmai">
          @csrf
          <button type="submit" title="Khôi phục" class="border-green hover_scale_btn">
            <i class="fa-solid fa-rotate-left"></i>
            {{-- <p>Khôi phục</p> --}}
          </button>
        </form>
        
        @else
        <button class="border-yellow editBtn hover_scale_btn" title="Sửa khuyến mãi" data-id="{{$km->id}}" data-name="{{$km->name}}"
          data-description="{{$km->description}}" data-value="{{$km->value}}" data-time_start="{{$km->time_start}}"
          data-time_end="{{$km->time_end}}" data-status="{{$km->status}}">
          <i class="fa-regular fa-pen-to-square"></i>
        </button>

        <form action="{{ route('khuyenmai.delete', $km->id) }}" method="POST" style="display: inline;" id="form-delete-km-{{ $km->id }}">
          @csrf
          @method('DELETE')
          <button type="submit" title="Xóa khuyến mãi" class="border-red btn-delete-km hover_scale_btn" data-id="{{ $km->id }}">
            <i class="fa-regular fa-trash-can"></i>
          </button>
        </form>
        
    
        @endif
      </div>
    </div>
    @endforeach
    <div class="end-user">
      <div class="left">
        <form method="GET" id="show1" class="show-sl">
          <label for="">Hiển Thị:</label>

          <select name="show" id="show">
            <option value="10" {{request('show')==10 ? 'selected' : '' }}>10</option>
            <option value="2" {{request('show')==2 ? 'selected' : '' }}>2</option>
            <option value="30" {{request('show')==30 ? 'selected' : '' }}>30</option>
            <option value="50" {{request('show')==50 ? 'selected' : '' }}>50</option>
            <option value="100" {{request('show')==100 ? 'selected' : '' }}>100</option>
            <option value="150" {{request('show')==150 ? 'selected' : '' }}>150</option>
          </select>

          <input type="hidden" name="key" value="{{ request('key') }}">
          <input type="hidden" name="thutu" value="{{ request('thutu') }}">
        </form>

      </div>
      <div class="right">
        @if ($discounts->lastPage() > 1)
        <ul>
          <li class="{{$pageht}} == 1 ? 'disable' : ''">
            <a href="{{$discounts->appends(request()->query())->previousPageUrl()}}"><i
                class="fa-solid fa-caret-left"></i></a>
          </li>
          @if ($start > 1)
          <li><a href="{{$discounts->appends(request()->query())->url(1)}}">1</a>
          </li>
          @if($start > 2)
          <li class="disabled"><span>...</span></li>
          @endif
          @endif
          @for ($i = $start; $i <= $end; $i++) <li class="{{($pageht == $i) ? 'active' : ''}}"><a
              href="{{$discounts->appends(request()->query())->url($i) }}">{{$i}}</a></li>
            @endfor


            @if ($end < $lapa) @if ($end < $lapa - 1) <li class="disabled"><span>...</span></li>
              @endif
              <li><a href="{{$discounts->appends(request()->query())->url($lapa)}}">{{$lapa}}</a></li>
              @endif

              <li class="{{($pageht == $lapa) ? 'disabled' : ''}}">
                @if($pageht == $lapa)
                <a href="{{$discounts->appends(request()->query())->url(1)}}"><i
                    class="fa-solid fa-caret-right"></i></a>
                @else
                <a href="{{$discounts->appends(request()->query())->nextPageUrl()}}"><i
                    class="fa-solid fa-caret-right"></i></a>
                @endif
              </li>
        </ul>
        @endif
      </div>
    </div>
  </section>

  <script>
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.getElementById('closeModalBtn');
    const modal = document.getElementById('brandModal');

    openBtn.onclick = () => modal.style.display = 'flex';
    closeBtn.onclick = () => modal.style.display = 'none';
    window.onclick = (e) => { if (e.target === modal) modal.style.display = 'none'; };

    const editModal = document.getElementById('editModal');
    const closeEditModalBtn = document.getElementById('closeEditModalBtn');
    const editForm = document.getElementById('editForm');

    document.querySelectorAll('.editBtn').forEach(btn => {
      btn.onclick = () => {
        document.getElementById('edit_id').value = btn.dataset.id;
        document.getElementById('edit_name').value = btn.dataset.name;
        document.getElementById('edit_description').value = btn.dataset.description;
        document.getElementById('edit_value').value = btn.dataset.value;
        document.getElementById('edit_time_start').value = btn.dataset.time_start;
        document.getElementById('edit_time_end').value = btn.dataset.time_end;
        document.getElementById('edit_status').checked = btn.dataset.status;
        editForm.action = `/khuyenmai/update/${btn.dataset.id}`;
        editModal.style.display = 'flex';
      };
    });
    console.log("thanhhoa");
    closeEditModalBtn.onclick = () => editModal.style.display = 'none';
    window.onclick = (e) => { if (e.target === editModal) editModal.style.display = 'none'; };

    function validateForm() {
    let name = document.getElementById("name").value.trim();
    let value = document.getElementById("value").value.trim();
    let description = document.getElementById("description").value.trim();
    let timeStart = document.getElementById("time_start").value;
    let timeEnd = document.getElementById("time_end").value;

    if (name === "") {
        alert("Vui lòng nhập tên khuyến mãi!");
        return false;
    }
    if (value === "" || isNaN(value) || value < 1 || value > 100) {
        alert("Phần trăm giảm giá phải từ 1 đến 100!");
        return false;
    }
    if (description === "") {
        alert("Vui lòng nhập nội dung khuyến mãi!");
        return false;
    }
    if (timeStart === "") {
        alert("Vui lòng chọn ngày bắt đầu!");
        return false;
    }
    if (timeEnd && new Date(timeEnd) < new Date(timeStart)) {
        alert("Ngày kết thúc không thể trước ngày bắt đầu!");
        return false;
    }
    return true;
}

function submitForm() {
    if (validateForm()) {
        document.getElementById("khuyenmaiForm").submit();
    }
}

// Submit form khi chọn số hiển thị
document.getElementById('show').addEventListener('change', function () {
    document.getElementById('show1').submit();
  });


  </script>

  <style>
    .modal-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
      z-index: 1;
    }

    .modal-content {
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      width: 600px;
      position: relative;

      >h3 {
        text-align: center;
      }
    }

    .modal-content > label{
      margin-bottom: 8px !important;
    }
    .close-modal {
      position: absolute;
      top: 10px;
      right: 10px;
      background: none;
      border: none;
      font-size: 40px;
      cursor: pointer;
    }
  </style>
</main>

<script src="{{ asset('/js/Alerts.js') }}"></script>
@endsection