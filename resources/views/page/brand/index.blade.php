@extends('layout')

@section('title','QUẢN LÝ THƯƠNG HIỆU')

@section('content')
<main>

  <section class="page-main">

    <div class="header-filter-shared">
      <ul>
        <li>
          <a href="{{ route('page.brand.index')}}" class="{{ request()->query('query') == null ? 'active' : '' }}">
            Tất cả
          </a>
        </li>
        <li>
          <a href="{{ route('page.brand.index', ['query' => 'hidden']) }}"
            class="{{ request()->query('query') == 'hidden' ? 'active' : '' }}">
            Đã ẩn
          </a>
        </li>
        <li>
          <a href="{{ route('page.brand.index', ['query' => 'deleted']) }}"
            class="{{ request()->query('query') == 'deleted' ? 'active' : '' }}">
            Đã xóa
          </a>
        </li>
      </ul>
    </div>

    <div class="navbar">
      <form method="GET" class="glass-us">
        <div class="sr-container">
          <input type="text" name="key" placeholder="Tìm kiếm thương hiệu" value="{{ request()->key }}">
          <i class="fa-solid fa-magnifying-glass"></i>
        </div>
      </form>

      <div class="filter-addbtn-right">
        <form method="GET" id="thutu1" class="xs-us">
          <div class="sr-container">
            <select name="sort" onchange="this.form.submit()">
              <option value="">Lọc thương hiệu</option>
              <option value="newest" {{ request()->sort == 'newest' ? 'selected' : '' }}>Mới nhất</option>
              <option value="oldest" {{ request()->sort == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
              <option value="lowest" {{ request()->sort == 'lowest' ? 'selected' : ''}}> Thứ tự cao đến thấp</option>
              <option value="highest" {{ request()->sort == 'highest' ? 'selected' : ''}}> Thứ tự từ thấp đến cao</option>
            </select>
          </div>
        </form>
        <div class="cr-sp">
          <button id="openModalBtn"> <i class="fa-solid fa-circle-plus"></i>Thêm thương hiệu</button>
        </div>
      </div>

    </div>

    <!--Create Popup modal -->
    <div id="brandModal" style="
      display:none; 
      position:fixed; 
      top:0; left:0; right:0; bottom:0; 
      background:rgba(0,0,0,0.5); 
      justify-content:center; 
      align-items:center;
      z-index: 2;
  ">
      <div style="background:#fff; padding:20px; border-radius:12px; width:600px; position:relative;">
        <!-- Close button -->
        <button id="closeModalBtn"
          style="position:absolute; top:10px; right:10px; background:none; border:none; font-size:40px; cursor: pointer;">&times;</button>

        <!-- Form -->
        <form action="{{ route('brand.store') }}" method="POST" enctype="multipart/form-data" id="createBrandForm">
          @csrf
          <div class="create-form">
            <h3>Thêm thương hiệu</h3>
            <div>
              <label for="name" class="form-label">Tên Thương Hiệu</label>
              <input type="text" name="name" class="form-control" id="name">
              <span class="text-danger" id="nameError"
                style="display: none; font-size: 14px; color:red; padding-bottom:10px;">Vui lòng nhập tên thương
                hiệu</span>
            </div>

            <div class="flex-sort-status">
              <div class="sort-cate" style="margin-bottom: 12px;">
                <label for="sort">Vị trí</label>
                <input type="number" name="sort" class="form-control" id="sort" required step="1" min="1" max="50">
              </div>

              <div class="status-cate toggle-group">
                <label for="status">Trạng thái</label>
                <input type="hidden" name="status" value="0">
                <label class="switch">
                  <input type="checkbox" name="status" value="1" id="status" checked>
                  <span class="slider round"></span>
                </label>
              </div>
            </div>

            <div>
              <label for="logo" class="form-label">Logo</label>
              <input type="file" name="logo" class="form-control" id="logo">
              <span class="text-danger" id="nameError"
                style="display: none; font-size: 14px; color:red; padding-bottom:10px;">Vui lòng chọn logo</span>
            </div>



            <div></div>

          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary">Thêm</button>

          </div>

        </form>
      </div>
    </div>



    <!-- edit popup modal-->
    <div id="editBrandModal" style="
      display:none; 
      position:fixed; 
      z-index: 1;
      top:0; left:0; right:0; bottom:0; 
      background:rgba(0,0,0,0.5); 
      justify-content:center; 
      align-items:center;
  ">
      <div style="background:#fff; padding:20px; border-radius:12px; width:600px; position:relative;">
        <!-- Close button -->
        <button id="closeEditModalBtn"
          style="position:absolute; top:10px; right:10px; background:none; border:none; font-size:40px; cursor: pointer;">&times;</button>

        <!-- Form -->
        <form id="editBrandForm" method="POST" enctype="multipart/form-data">
          @csrf
          @method('POST')
          <div class="create-form">
            <h3>Sửa thương hiệu</h3>
            <div>
              <label for="editName" class="form-label">Tên Thương Hiệu</label>
              <input type="text" name="name" class="form-control" id="editName" required>
            </div>
            <div class="flex-sort-status">
              <div class="sort-cate" style="margin-bottom: 12px;">
                <label for="editSort">Vị trí</label>
                <input type="number" name="sort" class="form-control" id="editSort" required min="0" step="1">
              </div>

              <div class="status-cate toggle-group">
                <label for="editStatus">Trạng thái</label>
                <input type="hidden" name="status" value="0">
                <label class="switch">
                  <input type="checkbox" name="status" value="1" id="editStatus">
                  <span class="slider round"></span>
                </label>
              </div>
            </div>
            <div>
              <label for="editLogo" class="form-label">Logo</label>
              <input type="file" name="logo" class="form-control" id="editLogo">
              <img id="previewLogo" src="" alt="Logo preview" width="100px" style="margin-top:10px;">
            </div>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
          </div>
        </form>
      </div>
    </div>

    <div class="grid-brand-layout grid_table_th_shared ">
      <div>ID</div>
      <div>Tên thương hiệu</div>
      <div>Logo</div>
      <div>Thứ Tự</div>
      <div>Số lượng SP</div>
      <div>Trạng thái</div>
      <div>Hành động</div>
    </div>

    @foreach($brands as $th)
    <div class="grid-brand-layout grid_table_tb_shared">
      <div class="gird-css ct" style="font-weight: 600">{{$th->id}}</div>
      <div class="gird-css ct">{{$th->name}}</div>
      <div class="gird-css ct">
        @if ($th->logo)
        <img src="{{ asset($th->logo) }}" alt="Loading..." width="100px" />

        @else
        <p style="opacity: 0.4">Chưa có</p>
        @endif

      </div>
      <div class="gird-css ct">{{$th->sort }}</div>
      <div class="grid-css ct"> {{ $th->products_count }}</div>
      <div class="gird-css ct">
          <form class="form-toggle-status" id="form-toggle-{{ $th->id }}" action="{{ route('brand.togglestatus', $th->id) }}" method="POST" style="display:inline;">
            @csrf
            <button type="button" class="btn-toggle-status hover_scale_btn" data-id="{{ $th->id }}" style="background:none; border:none; cursor:pointer;">
              @if($th->status == 1)
                <i class="fa-solid fa-eye icon-status-btns icon-status-mount" title="Nhấn để ẩn"></i>
              @else
                <i class="fa-solid fa-eye-slash icon-status-btns icon-status-unmount" title="Nhấn để hiện"></i>
              @endif
            </button>
          </form>
          
      </div>
      <div class="actions-d  ct">
        @if(request()->query('query') === 'deleted')
        <form action="{{ route('brand.restore', $th->id) }}" method="POST" style="display:inline;" class="form-restore-brand">
          @csrf
          @method('PUT')
          <button type="button" title="Khôi phục" class="border-green btn-restore-brand hover_scale_btn">
            <i class="fa-solid fa-rotate-left"></i>
            {{-- <p>Khôi phục</p> --}}
          </button>
        </form>
        
        @else
        <button class="border-yellow editBrandModal hover_scale_btn" title="Sửa thương hiệu"
          data-id="{{ $th->id }}"
          data-name="{{ $th->name }}"
          data-status="{{ $th->status }}"
          data-logo="{{ asset($th->logo) }}"
          data-sort="{{$th->sort}}">
          <i class="fa-regular fa-pen-to-square"></i>
          {{-- <p>Sửa</p> --}}
        </button>
        <form class="form-delete" id="form-delete-{{ $th->id }}" action="{{ route('brand.destroy', $th->id) }}" method="POST" >
          @csrf
          @method('DELETE')

          <button type="button" class="btn-delete border-red hover_scale_btn" data-id="{{ $th->id }}" title="Xóa thương hiệu" >

            <i class="fa-regular fa-trash-can"></i>
            {{-- <p>Xóa</p> --}}
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
          <!-- giữ lại tham số -->
          <input type="hidden" name="key" value="{{ request('key') }}">
          <input type="hidden" name="thutu" value="{{ request('thutu') }}">
        </form>

      </div>
      <div class="right">
        @if ($brands->lastPage() > 1)
        <ul>
          <!-- nút left -->
          <li class="{{$pageht}} == 1 ? 'disable' : ''">
            <a href="{{$brands->appends(request()->query())->previousPageUrl()}}"><i
                class="fa-solid fa-caret-left"></i></a>
          </li>
          <!-- các trang -->
          @if ($start > 1)
          <li><a href="{{$brands->appends(request()->query())->url(1)}}">1</a>
          </li>
          @if($start > 2)
          <li class="disabled"><span>...</span></li>
          @endif
          @endif
          @for ($i = $start; $i <= $end; $i++) <li class="{{($pageht == $i) ? 'active' : ''}}"><a
              href="{{$brands->appends(request()->query())->url($i) }}">{{$i}}</a></li>
            @endfor


            @if ($end < $lapa) @if ($end < $lapa - 1) <li class="disabled"><span>...</span></li>
              @endif
              <li><a href="{{$brands->appends(request()->query())->url($lapa)}}">{{$lapa}}</a></li>
              @endif

              <li class="{{($pageht == $lapa) ? 'disabled' : ''}}">
                @if($pageht == $lapa)
                <a href="{{$brands->appends(request()->query())->url(1)}}"><i class="fa-solid fa-caret-right"></i></a>
                @else
                <a href="{{$brands->appends(request()->query())->nextPageUrl()}}"><i
                    class="fa-solid fa-caret-right"></i></a>
                @endif
              </li>
        </ul>
        @endif
      </div>
    </div>
  </section>


  <!-- JavaScript -->
  <script>
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.getElementById('closeModalBtn');
    const modal = document.getElementById('brandModal');
  
    openBtn.onclick = () => {
      modal.style.display = 'flex';
    };
    closeBtn.onclick = () => {
      modal.style.display = 'none';
    };
    window.onclick = (e) => {
      if (e.target === modal) {
        modal.style.display = 'none';
      }
    };
  
    // Validate form thêm mới
    const createForm = document.getElementById('createBrandForm');
    createForm.addEventListener('submit', function (e) {
      let isValid = true;
      const nameInput = document.getElementById('name');
      const nameError = document.getElementById('nameError');
  
      if (nameInput.value.trim() === '') {
        nameInput.style.border = '1px solid red';
        nameError.style.display = 'block';
        isValid = false;
      } else {
        nameInput.style.border = '';
        nameError.style.display = 'none';
      }
  
      if (!isValid) {
        e.preventDefault();
      }
    });
  
    // Xử lý popup sửa thương hiệu
    const editModal = document.getElementById('editBrandModal');
    const closeEditBtn = document.getElementById('closeEditModalBtn');
  
    closeEditBtn.onclick = () => {
      editModal.style.display = 'none';
    };
    window.onclick = (e) => {
      if (e.target === editModal) {
        editModal.style.display = 'none';
      }
    };
  
    function openEditModal(brand) {
      const form = document.getElementById('editBrandForm');
      form.action = `/thuonghieu/${brand.id}/update`;
      document.getElementById('editName').value = brand.name;
      // document.getElementById('editStatus').value = brand.status;
      document.getElementById('editSort').value = brand.sort;
      const editStatusCheckbox = document.getElementById('editStatus');

  
      const previewLogo = document.getElementById('previewLogo');
      if (brand.logo) {
        previewLogo.src = brand.logo;
        previewLogo.style.display = 'block';
      } else {
        previewLogo.style.display = 'none';
      }
  
      editModal.style.display = 'flex';
  
      // Debug nếu cần
      // console.log('Editing brand:', brand);
    }
  
    // Bắt sự kiện click để mở modal sửa
    document.querySelectorAll('.editBrandModal').forEach(button => {
      button.addEventListener('click', () => {
        const brand = {
          id: button.getAttribute('data-id'),
          name: button.getAttribute('data-name'),
          status: button.getAttribute('data-status'),
          logo: button.getAttribute('data-logo'),
          sort: button.getAttribute('data-sort')
        };
        openEditModal(brand);
      });
    });
  
    // Gửi form khi thay đổi số hiển thị
    document.getElementById('show').addEventListener('change', function () {
      document.getElementById('show1').submit();
    });
  </script>
  


</main>
<script src="{{ asset('/js/Alerts.js') }}"></script>
@endsection