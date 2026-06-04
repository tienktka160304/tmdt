//avatar
document.getElementById("avatar").addEventListener("click", function () {
  document.getElementById("accountMenu").classList.toggle("active");
});
// Đóng menu khi click ra ngoài
document.addEventListener("click", function (event) {
  let menu = document.getElementById("accountMenu");
  let avatar = document.getElementById("avatar");
  if (!menu.contains(event.target) && !avatar.contains(event.target)) {
    menu.classList.remove("active");
  }
});

// mở bảng chi tiết theo đơn hàng
document.querySelectorAll(".hienthi_detail").forEach(function (button) {
  button.addEventListener("click", function () {
    var orderid = button.getAttribute("data-order-id");
    var detailSec = document.getElementById("detail-" + orderid);
    var detailRow = document.querySelectorAll("#details-" + orderid);
    var tonghd = document.getElementById("an-dh-" + orderid);
    var icon = button.querySelector("i");

    detailSec.classList.toggle("hien");
    detailRow.forEach(function (row) {
      row.classList.toggle("hien");
    });

    button.classList.toggle("active");
    if (icon.classList.contains("fa-plus")) {
      icon.classList.remove("fa-plus");
      icon.classList.add("fa-minus");
    } else {
      icon.classList.remove("fa-minus");
      icon.classList.add("fa-plus");
    }

    var row = button.closest(".modonhang-user");
    row.classList.toggle("active");

    var kiemtra = Array.from(detailRow).some((row) =>
      row.classList.contains("hien")
    );
    tonghd.style.display = kiemtra ? "grid" : "none";
  });
});

//form show theo số lượng
const show = document.getElementById("show");
const show1 = document.getElementById("show1");
if (show && show1) {
  show.addEventListener("change", function () {
    show1.submit();
  });
}

//form sắp xếp
const thutu = document.getElementById("thutu");
const thutu1 = document.getElementById("thutu1");
if (thutu && thutu1) {
  thutu.addEventListener("change", function () {
    thutu1.submit();
  });
}
//form thongke trang chủ
const thongke = document.getElementById("thongke");
const thongke1 = document.getElementById("thongke1");
if (thongke && thongke1) {
  thongke.addEventListener("change", function () {
    thongke1.submit();
  });
}

//cấm mở danh sách select
document.querySelectorAll("#stop-click").forEach((select) => {
  select.addEventListener("mousedown", function (event) {
    event.preventDefault();
  });
});

// kiểm tra mật khẩu
const pwFil = document.getElementById("pw-cre");
const pwCFI = document.getElementById("pwcf-cre");
const er = document.getElementById("erop");
const form = document.getElementById("checkpass");
if (pwFil && pwCFI && er && form) {
  const checkPass = () => {
    const mismath = pwFil.value.trim() !== pwCFI.value.trim();
    er.textContent = mismath ? "Mật khẩu không khớp!" : "";
    er.style.display = mismath ? "block" : "none";
    return mismath;
  };
  form.addEventListener("submit", (e) => {
    if (checkPass()) {
      e.preventDefault();
    }
  });
}

function previImg(event) {
  const file = event.target.files[0];
  const read = new FileReader();

  read.onload = function () {
    const imgPreDIV = document.getElementById("imgpre-ad");
    imgPreDIV.innerHTML = `<img src="${read.result}" alt="Lỗi...">`;
  };
  if (file) {
    read.readAsDataURL(file);
  }
}



const query = document.getElementById("date_fillter");
const query_mon = document.getElementById("month-fill");
if (query && query_mon) {
  query.addEventListener("change", function () {
    query_mon.submit();
  });
}

// status orders
document.querySelectorAll(".status_order").forEach(function (orderSic) {
  orderSic.addEventListener("click", function () {
    var orderId = orderSic.getAttribute("data-order-status-id");
    var form = document.getElementById("form-" + orderId);
    var icon = orderSic.querySelector("i");
    var isHidden = form.style.display === "none" || form.style.display === "";
    form.style.display = isHidden ? "block" : "none";
  });
});
//modal add admin
const oppenCrAd = document.getElementById("oppenCreateAdmin");
const closeCrAd = document.getElementById("closeCreateAdmin");
const modalCrAd = document.getElementById("modal_CrAd");
if(oppenCrAd && closeCrAd &&modalCrAd){
  oppenCrAd.addEventListener('click',function(q){
    q.preventDefault();
    modalCrAd.classList.remove('hidden');
  });
  closeCrAd.addEventListener('click',function(){
    modalCrAd.classList.add('hidden');
  });
  modalCrAd.addEventListener('click',function(q){
    if(q.target === modalCrAd){
      modalCrAd.classList.add('hidden');
    }
  });
}
document.querySelectorAll('.oppenCreateAdmin').forEach(button => {
  button.addEventListener('click',function(){
    const userId = this.getAttribute('data-userAD-id');
    document.getElementById('modal_CrAd_'+userId).classList.remove('hidden');
  });
});
document.querySelectorAll('.closeCreateAdmin').forEach(button => {
  button.addEventListener('click',function(){
    const userId = this.getAttribute('data-userAD-id');
    document.getElementById('modal_CrAd_'+userId).classList.add('hidden');
  });
});

document.querySelectorAll('.EditAdmin').forEach(button => {
  button.addEventListener('click',function(){
    const userId = this.getAttribute('data-user_id');
    document.getElementById('modal_EdAd_'+userId).classList.remove('hidden');
  });
});
document.querySelectorAll('.closeEditAdmin').forEach(button => {
  button.addEventListener('click',function(){
    const userId = this.getAttribute('data-user_id');
    document.getElementById('modal_EdAd_'+userId).classList.add('hidden');
  });
});

// modal banner
const oppen = document.getElementById("openModalBnCr");
const closeBn = document.getElementById("closeModalBnCr");
const modal = document.getElementById("modal_banner");
if (oppen && closeBn && modal) {
  oppen.addEventListener("click", function (e) {
    e.preventDefault();
    modal.classList.remove("hidden");
  });
  closeBn.addEventListener("click", function () {
    modal.classList.add("hidden");
  });
  modal.addEventListener("click", function (e) {
    if (e.target === modal) {
      modal.classList.add("hidden");
    }
  });
}
// Mở popup chỉnh sửa banner
document.querySelectorAll(".openModalBnEdit").forEach((button) => {
  button.addEventListener("click", function () {
    const bannerId = this.getAttribute("data-banner-id");
    const modal = document.getElementById("modal_banner_edit_" + bannerId);
    if (modal) {
      modal.classList.remove("hidden");
    }
  });
});

// Đóng popup chỉnh sửa banner
document.querySelectorAll(".closeModalBnEdit").forEach((button) => {
  button.addEventListener("click", function () {
    const modal = this.closest(".modal_create");
    if (modal) {
      modal.classList.add("hidden");
    }
  });
});

// Hiển thị ảnh preview khi chọn file ảnh
function bannerImg(event, bannerId) {
  const imagePreview = document.querySelector(`#image_banner_${bannerId} img`);
  if (event.target.files && event.target.files[0]) {
    const reader = new FileReader();
    reader.onload = function (e) {
      imagePreview.src = e.target.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  }
}
function bannerImgCr(event) {
  const file = event.target.files[0];
  const read = new FileReader();
  read.onload = function () {
    const imgPreDIV = document.getElementById("image_banner");
    imgPreDIV.innerHTML = `<img src="${read.result}" alt="Lỗi...">`;
  };
  if (file) {
    read.readAsDataURL(file);
  }
}

// Validate chỉ cho phép nhập số nguyên dương
function validatePrice(input) {
  input.value = input.value.replace(/[^0-9]/g, "");
}

// canvas home
var ctx = document.getElementById("canvas_order").getContext("2d");
var canvasChart = new Chart(ctx, {
  type: "bar",
  data: {
    labels: canvasLabel,
    datasets: [
      {
        label: "Tổng thu (VNĐ)",
        data: canvasData,
        backgroundColor: "rgba(0, 153, 255, 0.6)",
        borderColor: "rgba(54, 162, 235, 1)",
        borderWidth: 0,
        barThickness: 20,
      },
    ],
  },
  options: {
    responsive: true,
    scales: {
      y: {
        ticks: {
          callback: function (value) {
            return value.toLocaleString("vi-VN") + " đ";
          },
        },
      },
      x: {
        grid: {
          drawBorder: false,
          display: false,
        },
        ticks: {
          display: true,
        },
      },
    },
    plugins: {
      tooltip: {
        callbacks: {
          label: function (tooltipiItem) {
            var value = Math.round(tooltipiItem.raw);
            return value.toLocaleString("vi-VN") + " đ";
          },
        },
      },
    },
  },
});
