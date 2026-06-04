let quill;
function imageHandler() {
  const input = document.createElement("input");
  input.setAttribute("type", "file");
  input.setAttribute("accept", "image/*");
  input.click();
  input.onchange = async () => {
    const file = input.files[0];
    if (file) {
      const formData = new FormData();
      formData.append("image", file);

      try {
        const response = await fetch("/upload-quill-image", {
          method: "POST",
          headers: {
            "X-CSRF-TOKEN": document
              .querySelector('meta[name="csrf-token"]')
              .getAttribute("content"),
          },
          body: formData,
        });

        const data = await response.json();
        if (data.url) {
          const range = quill.getSelection();
          quill.insertEmbed(range.index, "image", data.url);
        } else {
          alert("Lỗi upload ảnh");
        }
      } catch (err) {
        console.error("Upload failed", err);
        alert("Không thể upload ảnh");
      }
    }
  };
}

document.addEventListener("DOMContentLoaded", function () {
  // Khởi tạo Quill
  quill = new Quill("#editor", {
    theme: "snow",
    placeholder: "Nhập mô tả sản phẩm...",
    modules: {
      toolbar: {
        container: [
          // [{ header: [1, 2, false] }],
          // ["bold", "italic", "underline"],
          // [{ list: "ordered" }, { list: "bullet" }],
          // ["link", "image", "code-block", "video"],
          // ["clean"],
          [{ header: [1, 2, false] }],
          ["bold", "italic", "underline", "strike"],
          ["blockquote", "code-block"],
          [{ list: "ordered" }, { list: "bullet" }],
          ["link", "image", "video"],
          ["clean"],
          [{ align: [] }],
        ],
        handlers: {
          image: imageHandler,
        },
      },
    },
  });
  // Lấy nội dung cũ từ input ẩn
  var oldContent = document.getElementById("hidden-description").value;
  if (oldContent) {
    quill.root.innerHTML = oldContent;
  }
  quill.on("text-change", function () {
    document.getElementById("hidden-description").value = quill.root.innerHTML;
  });
});
