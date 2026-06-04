@extends('layout')

@section('title', 'Quản lý danh mục bài viết')

@section('content')
<main>
  <section class="page-main">
    <div class="header-filter-shared">
      <ul>
        <li>
          <a href="{{ route('catepost')}}" class="{{ request()->query('query') == null ? 'active' : '' }}">
            Tất cả
          </a>
        </li>

        <li>
          <a href="{{ route('catepost', ['query' => 'hidden']) }}"
            class="{{ request()->query('query') == 'hidden' ? 'active' : '' }}">
            Danh mục ẩn
          </a>
        </li>
        <li>
          <a href="{{ route('catepost', ['query' => 'deleted']) }}"
            class="{{ request()->query('query') == 'deleted' ? 'active' : '' }}">
            Danh mục xóa
          </a>
        </li>
      </ul>
      <div class="cr-sp">
        <button id="openModalBtn"> <i class="fa-solid fa-circle-plus"></i>Thêm danh mục</button>
      </div>
    </div>
    </div>
    {{-- popup thêm danh mục --}}
    <div id="addCategoryModal" style="
display:none; 
position:fixed; 
top:0; left:0; right:0; bottom:0; 
background:rgba(0,0,0,0.5); 
justify-content:center; 
align-items:center;
z-index: 1;
">
      <div style="background:#fff; padding:20px; border-radius:12px; width:600px; position:relative;">
        <!-- Nút đóng -->
        <button id="closeAddCategoryModalBtn" style="
  position:absolute; top:10px; right:10px; 
  background:none; border:none; 
  font-size:22px; cursor:pointer;">&times;
        </button>
        <!-- Form thêm danh mục -->
        <form action="{{ route('category.store') }}" method="POST">
          @csrf
          <div class="create-form">
            <h3 style="margin-bottom: 16px;">Thêm danh mục</h3>

            <div class="form-group" style="margin-bottom: 12px;">
              <label for="categoryName">Tên Danh Mục</label>
              <input type="text" name="name" class="form-control" id="categoryName" required>
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

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="form-group">
              <button type="submit" class="btn btn-primary">Thêm mới</button>
            </div>
          </div>
        </form>

      </div>
    </div>

    {{-- popup sửa danh mục --}}
    <div id="editCategoryModal" style="
display:none; 
position:fixed; 
top:0; left:0; right:0; bottom:0; 
background:rgba(0,0,0,0.5); 
justify-content:center; 
align-items:center;
z-index: 2;
">
      <div style="background:#fff; padding:20px; border-radius:12px; width:600px; position:relative;">
        <!-- Nút đóng -->
        <button id="closeEditCategoryModalBtn" style="
    position:absolute; top:10px; right:10px; 
    background:none; border:none; 
    font-size:22px; cursor:pointer;">&times;
        </button>
        <!-- Form sửa danh mục -->
        <form id="editCategoryForm" method="POST">
          @csrf
          @method('PUT')
          <div class="create-form">
            <h3 style="margin-bottom: 16px;">Sửa danh mục</h3>

            <div class="form-group" style="margin-bottom: 12px;">
              <label for="editCategoryName">Tên Danh Mục</label>
              <input type="text" name="name" class="form-control" id="editCategoryName" required>
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

            <div class="form-group">
              <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
          </div>
        </form>
      </div>
    </div>


    {{-- table --}}
    <div class=" grid_table_th_shared grid-catepost-layout">
      <div>ID</div>
      <div>Tên danh mục</div>
      <div>Slug</div>
      <div>Vị trí</div>
      <div>Trạng thái</div>
      <div>Hành động</div>
    </div>
    @forEach($categories as $catepost)
    <div class="grid_table_tb_shared grid-catepost-layout">
      <div class="grid-css ct" style="font-weight: 600">{{$catepost->id}}</div>
      <div class="grid-css ct">{{$catepost->name}}</div>
      <div class="grid-css ct">{{$catepost->slug}}</div>
      <div class="grid-css ct">{{$catepost->sort}}</div>
      <div class="grid-css ct">
         
          <form id="form-toggle-{{ $catepost->id }}" action="{{ route('catepost.togglestatus', $catepost->id) }}" method="POST" style="display:inline;">
            @csrf
            
            @if($catepost->status == 1)
              <button type="button" class="icon-status-btns icon-status-mount btn-toggle-status hover_scale_btn" data-id="{{ $catepost->id }}" title="Nhấn để ẩn">
                <i class="fa-solid fa-eye"></i>
              </button>
            @else
              <button type="button" class="icon-status-btns icon-status-unmount btn-toggle-status hover_scale_btn" data-id="{{ $catepost->id }}" title="Nhấn để hiện">
                <i class="fa-solid fa-eye-slash"></i>
              </button>
            @endif
          </form>
          
        
      </div>
      <div class="actions-d ct">
        @if(request()->query('query') === 'deleted')
        <form action="{{ route('category.restore', $catepost->id) }}" method="POST" id="form-restore-{{ $catepost->id }}" style="display:inline;">
          @csrf
          <button
            type="submit"
            title="Khôi phục"
            class="border-green restore-btn hover_scale_btn"
            data-id-res="{{ $catepost->id }}"
          >
            <i class="fa-solid fa-rotate-left"></i>
          </button>
        </form>
        

        <form action="{{ route('category.forceDelete', $catepost->id) }}" method="POST"
            style="display:inline; margin-left: 4px;" class="force-delete-form" data-id="{{ $catepost->id }}">
          @csrf
          @method('DELETE')

          <button type="submit" title="Xóa vĩnh viễn" class="border-red force-delete-btn hover_scale_btn">

            <i class="fa-solid fa-trash-can"></i>
          </button>
        </form>
    
        @else
        <button class="border-yellow editBtn hover_scale_btn" title="Sửa danh mục" data-id="{{ $catepost->id }}" data-name="{{ $catepost->name }}"
          data-sort="{{ $catepost->sort }}" data-status="{{ $catepost->status }}">
          <i class="fa-regular fa-pen-to-square"></i>
        </button>
        <form action="{{ route('category.destroy', $catepost->id) }}" method="POST" style="display:inline;" class="delete-form">
          @csrf
          @method('DELETE')
          <button type="button" title="Xóa danh mục" class="border-red delete-btn hover_scale_btn">
            <i class="fa-regular fa-trash-can"></i>
          </button>
        </form>

        @endif
      </div>

    </div>
    @endforeach

  </section>
</main>
<script>
  // Popup thêm danh mục
  const openCategoryModalBtn = document.getElementById('openModalBtn');
  const closeAddCategoryModalBtn = document.getElementById('closeAddCategoryModalBtn');
  const addCategoryModal = document.getElementById('addCategoryModal');

  openCategoryModalBtn.onclick = () => {
    addCategoryModal.style.display = 'flex';
  };

  closeAddCategoryModalBtn.onclick = () => {
    addCategoryModal.style.display = 'none';
  };

  window.onclick = (e) => {
    if (e.target === addCategoryModal) {
      addCategoryModal.style.display = 'none';
    }
  };
  // Popup sửa
  const editCategoryModal = document.getElementById('editCategoryModal');
  const closeEditCategoryModalBtn = document.getElementById('closeEditCategoryModalBtn');
  const editCategoryForm = document.getElementById('editCategoryForm');
  const editNameInput = document.getElementById('editCategoryName');
  const editSortInput = document.getElementById('editSort');
  const editStatusCheckbox = document.getElementById('editStatus');

  document.querySelectorAll('.editBtn').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.getAttribute('data-id');
      const name = btn.getAttribute('data-name');
      const sort = btn.getAttribute('data-sort');
      const status = btn.getAttribute('data-status');

      editCategoryForm.action = `/post-category/${id}`; // chỉnh lại route nếu cần
      editNameInput.value = name;
      editSortInput.value = sort;
      editStatusCheckbox.checked = status == 1;

      editCategoryModal.style.display = 'flex';
    });
  });

  closeEditCategoryModalBtn.onclick = () => {
    editCategoryModal.style.display = 'none';
  };

  window.onclick = (e) => {
    if (e.target === addCategoryModal) {
      addCategoryModal.style.display = 'none';
    }
    if (e.target === editCategoryModal) {
      editCategoryModal.style.display = 'none';
    }
  };
</script>

<script src="{{ asset('/js/Alerts.js') }}"></script>
@endsection