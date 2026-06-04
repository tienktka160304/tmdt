// thông báo khi xóa ảnh sản phẩm
function showDeleteConfirm() {
  return Swal.fire({
    title: "Bạn có chắc muốn xóa ảnh này?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: " Đồng ý, Xóa",
    cancelButtonText: "Hủy",
    confirmButtonColor: "#e3342f",
    cancelButtonColor: "#6c757d",
  });
}

// thông báo xóa biến thể
function confirmDeletion(
  message = "Bạn có chắc chắn muốn xóa mục này?",
  confirmText = "Đồng ý,Xóa"
) {
  return Swal.fire({
    title: message,
    text: "Hành động này không thể hoàn tác!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: confirmText,
    cancelButtonText: "Hủy",
  });
}

// xóa danh mục chính (Quản lý danh mục sản phẩm)
document.querySelectorAll(".btn-delete-category").forEach((button) => {
  button.addEventListener("click", function (e) {
    e.preventDefault();
    const id = this.getAttribute("data-id");
    confirmDeletion("Bạn có chắc muốn xóa danh mục này?", "Đồng ý, Xóa").then(
      (result) => {
        if (result.isConfirmed) {
          document.getElementById("form-delete-category-" + id).submit();
        }
      }
    );
  });
});

// xóa thương hiệu( quản lý thương hiệu)
document.querySelectorAll(".btn-delete").forEach((button) => {
  button.addEventListener("click", function (e) {
    e.preventDefault();
    const id = this.getAttribute("data-id");
    confirmDeletion("Bạn có chắc chắn muốn xóa mục này?", "Đồng ý, Xóa").then(
      (result) => {
        if (result.isConfirmed) {
          document.getElementById("form-delete-" + id).submit();
        }
      }
    );
  });
});
// xóa danh mục phụ(Quản lý danh mục sản phẩm)
document.querySelectorAll(".btn-delete-sub").forEach((button) => {
  button.addEventListener("click", function (e) {
    e.preventDefault();
    const id = this.getAttribute("data-id");
    confirmDeletion("Bạn có chắc muốn xóa danh mục này?", "Đồng ý, Xóa").then(
      (result) => {
        if (result.isConfirmed) {
          document.getElementById("form-delete-sub-" + id).submit();
        }
      }
    );
  });
});

// khôi phục danh mục phục(Quản lý danh mục sản phẩm)
document.querySelectorAll(".btn-restore-category").forEach((button) => {
  button.addEventListener("click", function (e) {
    e.preventDefault();
    confirmAction({
      title: "Bạn có muốn khôi phục không?",
      text: "Thao tác này không thể hoàn tác!",
      confirmText: "Đồng ý",
      cancelText: "Hủy",
      confirmColor: "#28a745",
      cancelColor: "#dc3545",
      icon: "warning",
    }).then((result) => {
      if (result.isConfirmed) {
        button.closest("form").submit();
      }
    });
  });
});

//  thông báo xóa (đang dùng xóa vĩnh viễn danh mục bài viết + ql khuyến mãi + xóa thuộc tính edit)
function confirmDelete(message = "Bạn có chắc chắn muốn xóa mục này?") {
  return Swal.fire({
    title: message,
    text: "Thao tác này không thể hoàn tác!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Đồng ý,Xóa",
    cancelButtonText: "Hủy",
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
  });
}

// hàm khôi phục
function confirmAction({
  title = "Bạn có chắc chắn?",
  text = "Thao tác này không thể hoàn tác!",
  confirmText = "Đồng ý",
  cancelText = "Hủy",
  confirmColor = "#d33",
  cancelColor = "#3085d6",
  icon = "warning",
} = {}) {
  return Swal.fire({
    title,
    text,
    icon,
    showCancelButton: true,
    confirmButtonText: confirmText,
    cancelButtonText: cancelText,
    confirmButtonColor: confirmColor,
    cancelButtonColor: cancelColor,
    reverseButtons: true,
  });
}

// thay đổi trạng thái HOT(quản lý sản phẩm + quản lý bài viết)
document.addEventListener("DOMContentLoaded", function () {
  const toggleHotForms = document.querySelectorAll(".toggle-hot-form");
  toggleHotForms.forEach((form) => {
    form.addEventListener("submit", function (event) {
      event.preventDefault();

      confirmAction({
        title: "Bạn có chắc chắn thay đổi trạng thái?",
        text: "Thao tác này không thể hoàn tác!",
        confirmText: "Đồng ý",
        cancelText: "Hủy",
        confirmColor: "#d9dd00",
        cancelColor: "#3085d6",
        icon: "warning",
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit(); // Chỗ này submit đúng form đang click
        }
      });
    });
  });
});

// khôi phục sản phẩm (Quản lý sản phẩm)
document.querySelectorAll(".btn-restore").forEach((button) => {
  button.addEventListener("click", function (e) {
    e.preventDefault();
    const form = button.closest("form");
    confirmAction({
      title: "Khôi phục sản phẩm?",
      text: "Thao tác này không thể hoàn tác!",
      confirmText: "Đồng ý khôi phục",
      cancelText: "Hủy",
      confirmColor: "#28a745",
      cancelColor: "#dc3545",
      icon: "warning",
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit();
      }
    });
  });
});

// khôi phục lại thương hiệu(quản lý thương hiệu)
document.addEventListener("DOMContentLoaded", function () {
  const restoreButtons = document.querySelectorAll(".btn-restore-brand");
  restoreButtons.forEach((button) => {
    button.addEventListener("click", function () {
      confirmAction({
        title: "Bạn có chắc muốn khôi phục danh mục này?",
        text: "Thao tác này không thể hoàn tác!",
        confirmText: "Đồng ý",
        cancelText: "Hủy",
        confirmColor: "#28a745", // màu xanh cho nút Đồng ý
        cancelColor: "#d33", // màu đỏ cho nút Hủy
        icon: "warning",
      }).then((result) => {
        if (result.isConfirmed) {
          this.closest("form").submit();
        }
      });
    });
  });
});

//  xóa bình luận (Quản lý bình luận)
document.addEventListener("DOMContentLoaded", function () {
  const deleteButtons = document.querySelectorAll(".btn-delete-comment");

  deleteButtons.forEach((button) => {
    button.addEventListener("click", function () {
      confirmDelete("Bạn có chắc chắn muốn xóa bình luận này?").then(
        (result) => {
          if (result.isConfirmed) {
            this.closest("form").submit();
          }
        }
      );
    });
  });
});

// thông báo xóa index sản phẩm
document.addEventListener("DOMContentLoaded", function () {
  const deleteForms = document.querySelectorAll(".delete-form");
  deleteForms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      Swal.fire({
        title: "Bạn có chắc chắn muốn xóa?",
        text: "Thao tác này không thể hoàn tác!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Đồng ý, xóa!",
        cancelButtonText: "Hủy",
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });
});
// thông báo hủy đơn hàng
const buttons2 = document.querySelectorAll(".change-stauts_1");
buttons2.forEach((button) => {
  button.addEventListener("click", function (event) {
    event.preventDefault();
    const idOrder = button.getAttribute("data-order-status");
    const Satuts = button.getAttribute("data-status");
    Swal.fire({
      title: "Bạn có chắc chắn hủy đơn hàng?",
      text: "Bạn có chắc chắn hủy đơn hàng?",
      icon: "error",
      showCancelButton: true,
      confirmButtonText: "Đồng ý, hủy",
      cancelButtonText: "Quay lại",
      reverseButtons: true,
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.getElementById("form-" + idOrder);
        const hidden = document.getElementById("hidden-status-" + idOrder);
        if (hidden) {
          hidden.value = Satuts;
        }
        form.submit();
      }
    });
  });
});

// thông báo đơn hàng (đang giao)
const buttons5 = document.querySelectorAll(".change-stauts_2");
buttons5.forEach((button) => {
  button.addEventListener("click", function (event) {
    event.preventDefault();
    const idOrder = button.getAttribute("data-order-status");
    const Satuts = button.getAttribute("data-status");
    Swal.fire({
      title: "Đơn hàng đang được xử lý ?",
      text: "Bạn có muốn chuyển trạng thái không ?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Đồng ý",
      cancelButtonText: "Quay lại",
      reverseButtons: true,
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.getElementById("form-" + idOrder);
        const hidden = document.getElementById("hidden-status-" + idOrder);
        if (hidden) {
          hidden.value = Satuts;
        }
        form.submit();
      }
    });
  });
});

// thông báo hoàn thành đơn hàng
const buttons6 = document.querySelectorAll(".change-stauts");
buttons6.forEach((button) => {
  button.addEventListener("click", function (event) {
    event.preventDefault();
    const idOrder = button.getAttribute("data-order-status");
    const Satuts = button.getAttribute("data-status");
    Swal.fire({
      title: "Đơn hàng đã hoàn thành?",
      text: "Bạn có chắc chắn đã hoàn thành?",
      icon: "success",
      showCancelButton: true,
      confirmButtonText: "Đồng ý",
      cancelButtonText: "Quay lại",
      reverseButtons: true,
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.getElementById("form-" + idOrder);
        const hidden = document.getElementById("hidden-status-" + idOrder);
        if (hidden) {
          hidden.value = Satuts;
        }
        form.submit();
      }
    });
  });
});

// thay đổi trạng thái(Quản lý sản phẩm + quản lý trang bài viết)
document.addEventListener("DOMContentLoaded", function () {
  const toggleForms = document.querySelectorAll(".form-toggle-status");
  toggleForms.forEach((form) => {
    form.addEventListener("submit", function (event) {
      event.preventDefault(); // Chặn submit thẳng
      confirmAction({
        title: "Xác nhận thay đổi trạng thái?",
        text: "Bạn có chắc muốn thực hiện?",
        confirmText: "Đồng ý",
        cancelText: "Hủy",
        confirmColor: "#008000",
        cancelColor: "#3085d6",
        icon: "warning",
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit(); // Nếu đồng ý thì submit form
        }
      });
    });
  });
});

// thay đổi trạng thái (quản lý đánh giá )
document.addEventListener("DOMContentLoaded", function () {
  const buttons = document.querySelectorAll(".change-status");
  buttons.forEach((button) => {
    button.addEventListener("click", function (event) {
      event.preventDefault();
      const idUser = button.getAttribute("data-id");
      Swal.fire({
        title: "Thay đổi trạng thái!",
        text: "Bạn muốn thay đổi trạng thái?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Đồng ý",
        cancelButtonText: "Quay lại",
        reverseButtons: true,
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById("idInput").value = idUser;
          document.getElementById("changeUserReview").submit();
        }
      });
    });
  });

  // thay đổi trạng thái(quản lý người dùng)
  const buttons1 = document.querySelectorAll(".acc-lock-user");
  buttons1.forEach((button) => {
    button.addEventListener("click", function (event) {
      event.preventDefault();
      const idUser = button.getAttribute("data-id-user");
      Swal.fire({
        title: "Thay đổi trạng thái tài khoản",
        text: "Bạn muốn thay đổi trạng thái tài khoản này?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Đồng ý",
        cancelButtonText: "Quay lại",
        reverseButtons: true,
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById("idUserInput").value = idUser;
          document.getElementById("changeUserLock").submit();
        }
      });
    });
  });

  // Xóa banner
  const buttons3 = document.querySelectorAll(".delete-bn");
  buttons3.forEach((button) => {
    button.addEventListener("click", function (event) {
      event.preventDefault();
      const BanerId = button.getAttribute("data-id-bn");
      const from = document.getElementById("form-delete-" + BanerId);
      Swal.fire({
        title: "Bạn muốn xóa?",
        text: "Bạn chắc chắn xóa không?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Đồng ý",
        cancelButtonText: "Quay lại",
        reverseButtons: true,
      }).then((result) => {
        if (result.isConfirmed) {
          from.submit();
        }
      });
    });
  });

  // Khôi phục banner
  const buttons4 = document.querySelectorAll(".delete-bn-kp");
  buttons4.forEach((button) => {
    button.addEventListener("click", function (event) {
      event.preventDefault();
      const BanerId = button.getAttribute("data-id-bnres");
      const from = document.getElementById("form-delete-kp-" + BanerId);
      Swal.fire({
        title: "Bạn muốn khôi phục banner?",
        text: "Bạn chắc chắn khôi phục không?",
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "Đồng ý",
        cancelButtonText: "Quay lại",
        reverseButtons: true,
      }).then((result) => {
        if (result.isConfirmed) {
          from.submit();
        }
      });
    });
  });
});

// KHÔI PHỤC DANH MỤC (Quản lý danh mục bài viết)
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".restore-btn").forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      const form = this.closest("form");
      confirmAction({
        title: "Bạn muốn khôi phục danh mục này?",
        text: "Danh mục sẽ được khôi phục lại.",
        confirmText: "Đồng ý, Khôi phục",
        confirmColor: "#28a745",
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });

  // KHÔI PHỤC BÀI VIẾT(Quản lý bài viết)
  document.querySelectorAll(".restore-post-btn").forEach((button) => {
    button.addEventListener("click", function (event) {
      event.preventDefault();
      const id = this.getAttribute("data-restore-id");
      const form = document.getElementById("form-restore-" + id);
      confirmAction({
        title: "Bạn muốn khôi phục bài viết này?",
        text: "Bài viết sẽ được khôi phục.",
        confirmText: "Đồng ý, Khôi phục",
        confirmColor: "#28a745",
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });
});

//  khôi phục khuyến mãi(quản lý khuyến mãi)
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".form-restore-khuyenmai").forEach((form) => {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      confirmAction({
        title: "Bạn muốn khôi phục khuyến mãi này?",
        text: "Khuyến mãi sẽ được khôi phục và hiện lại trên hệ thống.",
        icon: "info",
        confirmText: "Đồng ý, khôi phục",
        cancelText: "Hủy",
        confirmColor: "#28a745",
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });
});

// thông báo xóa bài viết
async function confirmDelete_post(id) {
  const res = await Swal.fire({
    title: "Bạn có chắc xóa bài viết này?",
    text: "Bài viết sẽ được chuyển vào thùng rác!",
    icon: "error",
    confirmButtonText: "Đồng ý",
    showCancelButton: true,
    cancelButtonText: "Hủy",
    reverseButtons: true,
  });
  if (res.isConfirmed) {
    document.getElementById(`form-delete-${id}`).submit();
  } else {
    // Không làm gì nếu hủy
  }
}

// xóa khuyến mãi (quản lý khuyến mãi)
document.querySelectorAll(".btn-delete-km").forEach((button) => {
  button.addEventListener("click", function (e) {
    e.preventDefault();
    const id = this.getAttribute("data-id");
    confirmDeletion(
      "Bạn có chắc chắn muốn xóa khuyến mãi này?",
      "Đồng ý, Xóa"
    ).then((result) => {
      if (result.isConfirmed) {
        document.getElementById("form-delete-km-" + id).submit();
      }
    });
  });
});

// khóa trang (quản lý nhân viên)
const disable = document.querySelectorAll(".acc-disable");
disable.forEach((button) => {
  button.addEventListener("click", function () {
    Swal.fire({
      title: "Bạn không thể khóa!",
      text: "Bạn không thể khóa tài khoản của chính mình!",
      icon: "error",
      confirmButtonText: "Đồng ý",
      reverseButtons: true,
    });
  });
});

// thay đổi trạng thái (quản lý danh mục bài viết + quản lý khuyến mãi + Quản lý danh mục sản phẩm)
document.querySelectorAll(".btn-toggle-status").forEach((button) => {
  button.addEventListener("click", function (e) {
    e.preventDefault();
    const id = button.getAttribute("data-id");
    Swal.fire({
      title: "Thay đổi trạng thái!",
      text: "Bạn có chắc muốn thay đổi trạng thái?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Đồng ý",
      cancelButtonText: "Hủy",
      reverseButtons: true,
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById("form-toggle-" + id).submit();
      }
    });
  });
});
// cập nhật trạng thái khóa/mở (Quản lý bình luận)
document.addEventListener("DOMContentLoaded", function () {
  const toggleButtons = document.querySelectorAll(".alerts-lock-open");

  toggleButtons.forEach((button) => {
    button.addEventListener("click", function () {
      confirmAction({
        title: "Bạn có chắc muốn thay đổi?",
        text: "Thao tác này sẽ cập nhật trạng thái bình luận.",
        confirmText: "Đồng ý",
        cancelText: "Hủy",
        icon: "warning",
        confirmColor: "#d9aa00",
        cancelColor: "#3085d6",
      }).then((result) => {
        if (result.isConfirmed) {
          this.closest("form").submit();
        }
      });
    });
  });
});

// xóa vĩnh viễn(quản lý danh mục bài)
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".force-delete-btn").forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const form = this.closest("form");
      confirmDelete("Bạn có chắc muốn xóa vĩnh viễn danh mục này?").then(
        (result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        }
      );
    });
  });
});

// XÓA DANH MỤC (quản lý danh mục bài viết)
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".delete-btn").forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.preventDefault(); // ngăn submit mặc định
      const form = this.closest("form");
      confirmDelete("Bạn có chắc muốn xóa danh mục này?").then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });

  // XÓA THUỘC TÍNH (thông báo form edit sản phẩm)
  document.querySelectorAll(".delete-attribute-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const row = this.closest(".attribute-row");
      confirmDelete("Bạn có chắc muốn xóa thuộc tính này?").then((result) => {
        if (result.isConfirmed) {
          row.remove();
        }
      });
    });
  });
});
