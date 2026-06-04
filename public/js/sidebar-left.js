// chưa thấy dùngdùng
function ClickMenu(id, button) {
  let menu = document.getElementById(id);
  let mo = menu.style.display === "none" || menu.style.display === "";

  document.querySelectorAll(".submenu").forEach(function (submenu) {
    if (submenu.id !== id) {
      submenu.style.display = "none";
    }
  });
  if (mo) {
    menu.style.display = "block";
    button.classList.add("active");
  } else {
    menu.style.display = "none";
    button.classList.remove("active");
  }
}

let isOpen = true;
function toggleSidebar() {
  const root = document.documentElement;
  const icon = this.querySelector("i");
  if (isOpen) {
    root.style.setProperty("--wleft", "0px");
    icon.classList.remove("fa-bars");
    icon.classList.add("fa-bars-staggered");
  } else {
    root.style.setProperty("--wleft", "250px");
    icon.classList.remove("fa-bars-staggered");
    icon.classList.add("fa-bars");
  }
  isOpen = !isOpen;
}

document.querySelector("#toggleBtn").addEventListener("click", toggleSidebar);

let deletedVariantIds = [];

// xóa biến thể (add sản phẩm) code mới
document.addEventListener("DOMContentLoaded", function () {
  const formAdd = document.getElementById("form-add-product");
  if (!formAdd) return; // Nếu không phải form thêm thì dừng
  const variantsContainer = formAdd.querySelector(".product-variants");
  if (!variantsContainer) return;
  variantsContainer.addEventListener("click", async function (event) {
    const button = event.target.closest(".delete-variant");
    if (!button) return;
    event.preventDefault();
    const confirmDeleteVariant = await confirmDelete(
      "Bạn có chắc chắn muốn xóa biến thể này?"
    );
    if (confirmDeleteVariant.isConfirmed) {
      const parentGroup = button.closest(".product-variants");
      if (parentGroup) parentGroup.remove();
    }
  });
});

// xóa biến thể (edit sản phẩm -> thông báo Alerts.js)
document.addEventListener("DOMContentLoaded", function () {
  let container = document.getElementById("variants-product");

  if (container) {
    container.addEventListener("click", function (event) {
      let deleteBtn = event.target.closest(".delete-variant");

      if (deleteBtn) {
        event.preventDefault();
        let row = deleteBtn.closest(".product-variants");
        let inputId = row.querySelector('input[name="variants[id][]"]');
        if (!inputId || !inputId.value) {
          row.remove();
          return;
        }

        const variantId = inputId.value;

        // Gọi Ajax kiểm tra trước khi cho xóa
        fetch(`/check-variant-in-orders/${variantId}`)
          .then((response) => response.json())
          .then((data) => {
            if (data.exists) {
              Swal.fire({
                icon: "error",
                title: `Không thể xóa`,
                html: `Biến thể này đang nằm trong đơn hàng: <b>${data.orders.join(
                  ", "
                )}</b>`,
              });
            } else {
              confirmDeletion(
                "Bạn có chắc chắn muốn xóa biến thể này?",
                "Đồng ý,Xóa"
              ).then((result) => {
                if (result.isConfirmed) {
                  deletedVariantIds.push(variantId);
                  const hiddenInput =
                    document.getElementById("deleted_variants");
                  if (hiddenInput) {
                    hiddenInput.value = deletedVariantIds.join(",");
                  }
                  row.remove();
                }
              });
            }
          })
          .catch((error) => {
            console.error("Lỗi khi kiểm tra biến thể:", error);
          });
      }
    });
  }
});

// hết

// thêm biến thể
function Variants() {
  let container = document.getElementById("variants-product");
  if (!container) {
    console.error("Lỗi: Không tìm thấy phần tử #variants-product");
    return;
  }
  let div = document.createElement("div");
  div.classList.add("product-variants");
  div.innerHTML = `
                       <!-- Cột trái -->
                            <div class="product-variants-left">
                                <input class="input-text" type="text" placeholder="Tên Biến Thể" name="variants[option][]">
                    
                                <div class="product-variants-row">
                                    <input class="input-text" type="number" placeholder="Số Lượng" min="1" name="variants[stock][]"  oninput="validatePrice(this)">
                                    <input class="input-text" type="text" min="1000" placeholder="Giá trị" name="variants[price][]"  oninput="validatePrice(this)">
                                </div>
                            </div>
                    
                            <!-- Cột phải -->
                            <div class="product-upload-status-variants">
                                <div class="product-upload-imggg">
                                    <div class="box-img-uploadd">
                                        <img class="preview-image" id="preview-image-variant-0"
                                            style="display: none; width: 100%; height: auto;" alt="Xem trước ảnh">
                                        <i class="fa-solid fa-image preview-icon" style="font-size: 48px; color: #aaa;"></i>
                                        <label style="position: absolute; inset: 0; cursor: pointer;">
                                            <input class="input-text custom__input-file" type="file"
                                                name="variants[image][]" onchange="previewImage(this, 0)"
                                                style="opacity: 0; width: 100%; height: 100%; cursor: pointer;">
                                        </label>
                                    </div>
                    
                                    <label class="label-img">
                                        <input class="input-text custom__input-file" type="file" name="variants[image][]" onchange="previewImage(this)" style="opacity: 0; width: 100%; height: 100%; cursor: pointer;">
                                    </label>
                                </div>
                            </div>
                    
                            <!-- Nút xóa -->
                            <div class="button-product-variants">
                                <a href="#" type="button" class="delete-variant">Xóa</a>
                            </div>
    `;
  container.appendChild(div);
}

// xử lý ảnh
function previewImage(input) {
  let file = input.files[0];
  if (file) {
    let reader = new FileReader();
    let img = input
      .closest(".product-upload-status-variants")
      .querySelector(".preview-image");
    let icon = input
      .closest(".product-upload-status-variants")
      .querySelector(".preview-icon");

    reader.onload = function (e) {
      img.src = e.target.result;
      img.style.display = "block";
      icon.style.display = "none";
    };
    reader.readAsDataURL(file);
  }
}

// tets   code mới
// xử lý ảnh và lưu vào sessionStorage
function previewImage(input) {
  const file = input.files[0];
  if (file) {
    const reader = new FileReader();
    const container = input.closest(".product-upload-status-variants");
    const img = container.querySelector(".preview-image");
    const icon = container.querySelector(".preview-icon");

    const index = [
      ...document.querySelectorAll(".product-upload-status-variants"),
    ].indexOf(container);

    reader.onload = function (e) {
      const imageData = e.target.result;

      // 1. Hiển thị xem trước
      img.src = imageData;
      img.style.display = "block";
      if (icon) icon.style.display = "none";

      // 2. Lưu tạm trong sessionStorage
      sessionStorage.setItem(`variant_image_${index}`, imageData);
    };
    reader.readAsDataURL(file);
  }
}

// Khôi phục ảnh khi reload trang (submit lỗi)
window.addEventListener("DOMContentLoaded", () => {
  document
    .querySelectorAll(".product-upload-status-variants")
    .forEach((container, index) => {
      const imageData = sessionStorage.getItem(`variant_image_${index}`);
      if (imageData) {
        const img = container.querySelector(".preview-image");
        const icon = container.querySelector(".preview-icon");
        if (img) {
          img.src = imageData;
          img.style.display = "block";
        }
        if (icon) {
          icon.style.display = "none";
        }
      }
    });

  // Sau khi hiển thị lại, có thể xoá nếu muốn
  sessionStorage.clear();
});

//xóa thuộc tính add sản (thông báo -> Alerts.js)  code mới
document.addEventListener("DOMContentLoaded", function () {
  const formAdd = document.getElementById("form-add-product");
  if (!formAdd) return; // Nếu không phải form thêm thì dừng
  const attributeContainer = formAdd.querySelector("#attribute-container");
  if (!attributeContainer) return;
  attributeContainer.addEventListener("click", async function (event) {
    const button = event.target.closest(".delete-attribute");
    if (!button) return;
    event.preventDefault();
    const confirmResult = await confirmDelete(
      "Bạn có chắc chắn muốn xóa thuộc tính này?"
    );
    if (confirmResult.isConfirmed) {
      const parentGroup = button.closest(".product-input-group");
      if (parentGroup) parentGroup.remove();
    }
  });
});
// hết

//chức năng xóa thuộc tính edit (thông báo -> Alerts.js)
document.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("attribute-container");
  const deletedInput = document.getElementById("deleted_attributes");
  const deletedIds = [];
  if (!container || !deletedInput) return;
  container.addEventListener("click", async (e) => {
    const btn = e.target.closest(".delete-attribute");
    if (!btn) return;
    e.preventDefault();
    const row = btn.closest(".product-input-group");
    if (!row) return console.error("Không tìm thấy phần tử cần xóa!");
    const result = await confirmDelete(
      "Bạn có chắc chắn muốn xóa thuộc tính này?"
    );
    if (!result.isConfirmed) return;
    const idInput = row.querySelector("input[name='attributes[id][]']");
    const id = idInput?.value;
    if (id) {
      deletedIds.push(id);
      deletedInput.value = deletedIds.join(",");
    }
    row.remove();
  });
});

// thêm thuộc tính tron add/edit sản phẩm
function addAttributeField() {
  let container = document.getElementById("attribute-container");
  if (!container) {
    console.error("Lỗi: Không tìm thấy phần tử #attribute-container");
    return;
  }

  let index = container.children.length;

  let div = document.createElement("div");
  div.classList.add("product-input-group");
  div.innerHTML = `
        <input type="hidden" name="attributes[id][]" value="">

        <div class="attributes-key">
            <input id="attribute-key-${index}" class="input-text attribute-input" 
                type="text" name="attributes[key][]" placeholder="Nhập thuộc tính sản phẩm">
        </div>

        <div class="attributes-value">
            <input id="attribute-value-${index}" class="input-text value-input" 
                type="text" name="attributes[value][]" placeholder="Giá trị thuộc tính">
        </div>

        <div class="delete-container">
            <a href="#" type="button" class="delete-attribute " >
                Xóa
            </a>
        </div>
    `;

  container.appendChild(div);
}

// chặng kh cho nhập chữ
function validatePrice(input) {
  // Loại bỏ mọi ký tự không phải số
  input.value = input.value.replace(/[^0-9]/g, "");

  // Ngăn nhập số 0 ở đầu (trừ trường hợp "0" duy nhất)
  if (input.value.length > 1 && input.value.startsWith("0")) {
    input.value = input.value.replace(/^0+/, "");
  }
}

// thêm sản phẩm (hình ảnh)
let imageCount = 1;
// Hàm thêm ô upload ảnh mới
function addNewImageUpload() {
  const container = document.getElementById("image-upload-container");
  const currentImages = container.querySelectorAll(
    ".product-upload-img"
  ).length;

  // Tạo div chứa phần upload ảnh mới ở form thêm sp)
  const newImageUpload = document.createElement("div");
  newImageUpload.classList.add("product-upload-img");
  newImageUpload.setAttribute("data-index", imageCount);
  newImageUpload.innerHTML = `
         <div class="box-img-upload" >
            <!-- Ảnh preview, sẽ được hiển thị khi chọn ảnh -->
            <img class="preview-image" id="preview-image-${imageCount}" alt="Xem trước ảnh" style="display: none; width: 100%; height: auto;">
            <i id="preview-icon-${imageCount}" class="fa-solid fa-image"
                style="font-size: 48px; color: #aaa;"></i>
            <label style="position: absolute; inset: 0; cursor: pointer;">
                <input class="input-text custom__input-file" type="file" name="img_products[]"
                    accept="image/*" onchange="previewImagesForProductForm(event, ${imageCount})"
                    style="opacity: 0; width: 100%; height: 100%; cursor: pointer;">
            </label>
              <!-- Dấu X để xóa hình ảnh, chỉ hiển thị khi đã có ảnh -->
              <button type="button" class="remove-image" id="remove-image-0" onclick="removeImage(${imageCount})"
            >❌</button>
        </div>
    `;

  container.insertBefore(
    newImageUpload,
    document.querySelector(".add-more-image")
  );
  imageCount++;
}

// Hàm xem trước ảnh
function previewImagesForProductForm(event, index) {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      document.getElementById(`preview-image-${index}`).src = e.target.result;
      document.getElementById(`preview-image-${index}`).style.display = "block";
      document.getElementById(`preview-icon-${index}`).style.display = "none";
      document.querySelector(
        `[data-index="${index}"] .remove-image`
      ).style.display = "flex";
    };
    reader.readAsDataURL(file);
  }
}

// Hàm xóa ảnh
function removeImage(index) {
  const imageElement = document.querySelector(`[data-index="${index}"]`);
  if (imageElement) {
    imageElement.remove();
  }
}
// heets

// edit ảnh sản phẩm
let editImageIndex = document.querySelectorAll(".product-upload-img").length;
document.addEventListener("DOMContentLoaded", function () {
  editRebindAddImageButton();
});

// Gán lại sự kiện cho nút "+"
function editRebindAddImageButton() {
  let addImageButton = document.getElementById("add-image-button");
  if (addImageButton) {
    addImageButton.removeEventListener("click", editAddNewImageUpload);
    addImageButton.addEventListener("click", editAddNewImageUpload);
  }
}

// Hàm thêm ảnh mới
function editAddNewImageUpload() {
  const container = document.getElementById("image-upload-container");
  // Xóa ô "+" cũ trước khi thêm mới
  const oldPlusIcon = document.querySelector(".add-more-image");
  if (oldPlusIcon) {
    oldPlusIcon.remove();
  }

  // Tạo div chứa phần upload ảnh mới ở form edit sản phẩm
  let uniqueId = editImageIndex++;
  const newImageUpload = document.createElement("div");
  newImageUpload.classList.add("product-upload-img");
  newImageUpload.setAttribute("id", `product-img-${uniqueId}`);
  newImageUpload.innerHTML = `
        <div class="box-img-upload">
            <img class="preview-image" id="preview-image-${uniqueId}" 
                style="display: none; width: 100%; height: auto;" 
                alt="Xem trước ảnh">
            <i id="preview-icon-${uniqueId}" class="fa-solid fa-image"  style="font-size: 48px; color: #aaa;"></i>
             <label style="position: absolute; inset: 0; cursor: pointer;">
            <input class="input-text custom__input-file" type="file" 
                name="img_products[]" accept="image/*"
                onchange="editPreviewImages(event, ${uniqueId})">
        </label>
            <button type="button" class="remove-image" id="remove-btn-${uniqueId}" onclick="editRemoveImage(${uniqueId})" >❌</button>
        </div>
       
    `;

  // Thêm vào container
  container.appendChild(newImageUpload);

  // Tạo lại ô dấu cộng mới
  const newPlusIcon = document.createElement("div");
  newPlusIcon.classList.add("product-upload-img-i", "add-more-image");
  newPlusIcon.id = "add-image-button";
  newPlusIcon.innerHTML = `
        <div class="box-img-upload plus-iconn">
            <i class="fa-solid fa-plus"></i>
        </div>
    `;

  // Gán sự kiện click cho nút "+"
  newPlusIcon.addEventListener("click", editAddNewImageUpload);

  // Thêm ô "+"
  container.appendChild(newPlusIcon);
  // Cập nhật lại sự kiện
  editRebindAddImageButton();
}

// Hàm xem trước ảnh
function editPreviewImages(event, index) {
  const fileInput = event.target;
  const file = fileInput.files[0];
  const previewImg = document.getElementById(`preview-image-${index}`);
  const removeBtn = document.getElementById(`remove-btn-${index}`);

  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      previewImg.src = e.target.result;
      previewImg.style.display = "block";
      removeBtn.style.display = "inline-block";
    };
    reader.readAsDataURL(file);
  } else {
    previewImg.src = "";
    previewImg.style.display = "none";
    removeBtn.style.display = "none";
  }
}

// Hàm xóa ảnh và dồn ảnh (edit ảnh sản phẩm)
function editRemoveImage(index) {
  showDeleteConfirm().then((result) => {
    if (!result.isConfirmed) return;

    const container = document.getElementById("image-upload-container");
    const imageToRemove = document.getElementById(`product-img-${index}`);
    const fileInput = document.getElementById(`product-upload-${index}`);

    if (imageToRemove) {
      imageToRemove.remove();
    }

    let imageId = fileInput?.dataset?.id;
    let deletedImages = [];
    if (imageId) {
      deletedImages.push(imageId);
      document.getElementById("deleted_images").value = deletedImages.join(",");
      console.log("Danh sách ảnh bị xóa:", deletedImages);
    }

    let imageList = document.querySelectorAll(".product-upload-img");
    imageList.forEach((image, newIndex) => {
      image.setAttribute("id", `product-img-${newIndex}`);
      image
        .querySelector(".preview-image")
        .setAttribute("id", `preview-image-${newIndex}`);
      image
        .querySelector(".fa-image")
        .setAttribute("id", `preview-icon-${newIndex}`);
      image
        .querySelector(".remove-image")
        .setAttribute("id", `remove-btn-${newIndex}`);
      image
        .querySelector(".remove-image")
        .setAttribute("onclick", `editRemoveImage(${newIndex})`);
      let fileInput = image.querySelector(".custom__input-file");
      fileInput.setAttribute("id", `product-upload-${newIndex}`);
      fileInput.setAttribute(
        "onchange",
        `editPreviewImages(event, ${newIndex})`
      );
    });

    imageIndex = imageList.length;

    // Thông báo thành công (có thể tách riêng tiếp nếu thích)
    showToast("Đã xóa ảnh thành công!");
  });
}

// hết ảnh sản phẩm > (edit)
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".variant-btn").forEach((button) => {
    button.addEventListener("click", function () {
      const variantId = this.getAttribute("data-id");

      // Tìm biến thể trong danh sách đã lưu trên trình duyệt
      const selectedVariant = productVariants.find(
        (variant) => variant.id == variantId
      );

      if (selectedVariant) {
        // Cập nhật thông tin trên giao diện
        document.getElementById("product-price").innerHTML =
          new Intl.NumberFormat("vi-VN").format(selectedVariant.price) + " đ";
        document.getElementById("product-stock").innerHTML =
          selectedVariant.stock;
        document.getElementById("main-image").src = selectedVariant.image;
      } else {
        console.error("Không tìm thấy biến thể!");
      }
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const mainImage = document.getElementById("main-image");
  const thumbnails = document.querySelectorAll(".thumbnail");

  if (mainImage && thumbnails.length > 0) {
    thumbnails.forEach((thumbnail) => {
      thumbnail.addEventListener("click", function () {
        let newSrc = this.getAttribute("src");
        mainImage.src = newSrc;
      });
    });
  }
});
