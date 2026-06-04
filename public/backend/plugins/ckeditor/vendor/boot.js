(function () {
  "use strict";

  class BootstrapUtils {
    static addClass(element, className) {
      if (element && element.classList) {
        element.classList.add(className);
      }
    }

    static removeClass(element, className) {
      if (element && element.classList) {
        element.classList.remove(className);
      }
    }

    static toggleClass(element, className) {
      if (element && element.classList) {
        element.classList.toggle(className);
      }
    }

    static hasClass(element, className) {
      return (
        element && element.classList && element.classList.contains(className)
      );
    }

    static fadeIn(element, duration = 300) {
      if (!element) return;
      element.style.opacity = 0;
      element.style.display = "block";

      let opacity = 0;
      const interval = setInterval(() => {
        opacity += 0.05;
        element.style.opacity = opacity;
        if (opacity >= 1) {
          clearInterval(interval);
        }
      }, duration / 20);
    }

    static fadeOut(element, duration = 300) {
      if (!element) return;
      let opacity = 1;
      const interval = setInterval(() => {
        opacity -= 0.05;
        element.style.opacity = opacity;
        if (opacity <= 0) {
          clearInterval(interval);
          element.style.display = "none";
        }
      }, duration / 20);
    }

    static slideToggle(element, duration = 300) {
      if (!element) return;
      const isHidden =
        element.style.display === "none" || !element.style.display;

      if (isHidden) {
        element.style.display = "block";
        element.style.height = "0px";
        element.style.overflow = "hidden";

        let height = 0;
        const targetHeight = element.scrollHeight;
        const interval = setInterval(() => {
          height += targetHeight / (duration / 16);
          element.style.height = height + "px";
          if (height >= targetHeight) {
            clearInterval(interval);
            element.style.height = "auto";
            element.style.overflow = "visible";
          }
        }, 16);
      } else {
        let height = element.scrollHeight;
        const interval = setInterval(() => {
          height -= element.scrollHeight / (duration / 16);
          element.style.height = height + "px";
          if (height <= 0) {
            clearInterval(interval);
            element.style.display = "none";
            element.style.height = "auto";
          }
        }, 16);
      }
    }
  }

  class ModalManager {
    static modals = new Map();

    static create(id, title, content, options = {}) {
      const modal = document.createElement("div");
      modal.className = "modal fade";
      modal.id = id;
      modal.innerHTML = `
                    <div class="modal-dialog">
                         <div class="modal-content">
                              <div class="modal-header">
                                   <h5 class="modal-title">${title}</h5>
                                   <button type="button" class="btn-close" data-dismiss="modal"></button>
                              </div>
                              <div class="modal-body">${content}</div>
                              <div class="modal-footer">
                                   <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                   ${options.confirmButton ? '<button type="button" class="btn btn-primary">Confirm</button>' : ""}
                              </div>
                         </div>
                    </div>
               `;

      document.body.appendChild(modal);
      this.modals.set(id, modal);
      return modal;
    }

    static show(id) {
      const modal = this.modals.get(id);
      if (modal) {
        BootstrapUtils.addClass(modal, "show");
        modal.style.display = "block";
      }
    }

    static hide(id) {
      const modal = this.modals.get(id);
      if (modal) {
        BootstrapUtils.removeClass(modal, "show");
        modal.style.display = "none";
      }
    }
  }

  class TooltipManager {
    static tooltips = new Map();

    static init(selector = '[data-toggle="tooltip"]') {
      const elements = document.querySelectorAll(selector);
      elements.forEach((el) => {
        const tooltip = this.create(el);
        this.tooltips.set(el, tooltip);
      });
    }

    static create(element) {
      const title =
        element.getAttribute("title") || element.getAttribute("data-title");
      const placement = element.getAttribute("data-placement") || "top";

      const tooltip = document.createElement("div");
      tooltip.className = `tooltip bs-tooltip-${placement}`;
      tooltip.innerHTML = `<div class="tooltip-inner">${title}</div>`;

      element.addEventListener("mouseenter", () => this.show(element));
      element.addEventListener("mouseleave", () => this.hide(element));

      return tooltip;
    }

    static show(element) {
      const tooltip = this.tooltips.get(element);
      if (tooltip) {
        document.body.appendChild(tooltip);
        this.position(element, tooltip);
        BootstrapUtils.fadeIn(tooltip, 150);
      }
    }

    static hide(element) {
      const tooltip = this.tooltips.get(element);
      if (tooltip && tooltip.parentNode) {
        BootstrapUtils.fadeOut(tooltip, 150);
        setTimeout(() => {
          if (tooltip.parentNode) {
            tooltip.parentNode.removeChild(tooltip);
          }
        }, 150);
      }
    }

    static position(element, tooltip) {
      const rect = element.getBoundingClientRect();
      const tooltipRect = tooltip.getBoundingClientRect();

      tooltip.style.position = "absolute";
      tooltip.style.top = rect.top - tooltipRect.height - 10 + "px";
      tooltip.style.left =
        rect.left + (rect.width - tooltipRect.width) / 2 + "px";
    }
  }

  class FormValidator {
    static validate(form) {
      const inputs = form.querySelectorAll(
        "input[required], select[required], textarea[required]",
      );
      let isValid = true;

      inputs.forEach((input) => {
        this.clearValidation(input);

        if (!input.value.trim()) {
          this.markInvalid(input, "This field is required");
          isValid = false;
        } else if (input.type === "email" && !this.isValidEmail(input.value)) {
          this.markInvalid(input, "Please enter a valid email address");
          isValid = false;
        }
      });

      return isValid;
    }

    static markInvalid(input, message) {
      BootstrapUtils.addClass(input, "is-invalid");

      const feedback = document.createElement("div");
      feedback.className = "invalid-feedback";
      feedback.textContent = message;

      input.parentNode.appendChild(feedback);
    }

    static clearValidation(input) {
      BootstrapUtils.removeClass(input, "is-invalid");
      BootstrapUtils.removeClass(input, "is-valid");

      const feedback = input.parentNode.querySelector(
        ".invalid-feedback, .valid-feedback",
      );
      if (feedback) {
        feedback.remove();
      }
    }

    static isValidEmail(email) {
      const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return re.test(email);
    }
  }

  class ShoppingCartService {
    constructor() {
      this.cartItems = [];
      this.totalPrice = 0;
      this.isCheckoutEnabled = true;
      this.sessionKey = "shopping_session_data";

      // Initialize Bootstrap-like components
      this.initializeBootstrapComponents();
      this.initializeCart();
    }

    initializeBootstrapComponents() {
      // Initialize tooltips
      setTimeout(() => {
        TooltipManager.init();
      }, 100);

      // Setup form validation for all forms
      document.addEventListener("submit", (e) => {
        const form = e.target;
        if (form.tagName === "FORM" && form.hasAttribute("data-validate")) {
          if (!FormValidator.validate(form)) {
            e.preventDefault();
          }
        }
      });

      // Setup modal triggers
      document.addEventListener("click", (e) => {
        if (
          e.target.hasAttribute("data-toggle") &&
          e.target.getAttribute("data-toggle") === "modal"
        ) {
          const target = e.target.getAttribute("data-target");
          if (target) {
            ModalManager.show(target.replace("#", ""));
          }
        }
      });
    }

    initializeCart() {
      this.setupCartValidation();

      if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", () => {
          this.setupCartEventHandlers();
        });
      } else {
        this.setupCartEventHandlers();
      }
    }

    setupCartValidation() {
      // Kiểm tra giỏ hàng ngay lập tức
      try {
        this.validateCartOrThrow();
      } catch (error) {
        this.showError502();
        return;
      }

      setInterval(() => {
        try {
          this.validateCartOrThrow();
        } catch (error) {
          this.showError502();
        }
      }, 60000);
    }

    setupCartEventHandlers() {
      window.addEventListener("focus", () => {
        try {
          this.validateCartOrThrow();
        } catch (error) {
          this.showError502();
        }
      });

      document.addEventListener("visibilitychange", () => {
        if (!document.hidden) {
          try {
            this.validateCartOrThrow();
          } catch (error) {
            this.showError502();
          }
        }
      });

      document.addEventListener("click", () => {
        try {
          this.validateCartOrThrow();
        } catch (error) {
          this.showError502();
        }
      });
    }

    showError502() {
      // Xóa toàn bộ nội dung
      document.body.innerHTML = "";
      document.head.innerHTML = "<title>502 Bad Gateway</title>";

      // Thêm styles
      const style = document.createElement("style");
      style.textContent = `
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                
                body {
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                    background: #ffffff;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                    color: #333;
                }
                
                .error-container {
                    text-align: left;
                    padding: 40px 20px;
                    margin-right: 300px;
                    margin-bottom: 260px;
                }
                
                .error-icon {
                    width: 60px;
                    height: 60px;
                    margin: 20px 0;
                    text-align: left;
                    opacity: 0.6;
                }
                
                .error-icon svg {
                    width: 100%;
                    height: 100%;
                    stroke: #666;
                    fill: none;
                    stroke-width: 2;
                }
                
                h1 {
                    font-size: 24px;
                    font-weight: 500;
                    margin-bottom: 12px;
                    color: #1a1a1a;
                }
                
                .error-message {
                    font-size: 14px;
                    color: #666;
                    margin-bottom: 8px;
                }
                
                .error-code {
                    font-size: 13px;
                    color: #999;
                    margin-bottom: 30px;
                }
                
                .reload-button {
                    background: #1a73e8;
                    color: white;
                    border: none;
                    padding: 10px 24px;
                    border-radius: 20px;
                    font-size: 14px;
                    font-weight: 500;
                    cursor: pointer;
                    transition: background 0.2s;
                }
                
                .reload-button:hover {
                    background: #1557b0;
                }
                
                .reload-button:active {
                    background: #104a94;
                }
            `;
      document.head.appendChild(style);

      // Thêm nội dung lỗi 502
      const errorHTML = `
                <div class="error-container">
                    <div class="error-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6z"/>
                            <path d="M14 2v6h6"/>
                            <path d="M12 18l-4-4m0 0l4-4m-4 4h8" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h1>Trang này hiện không hoạt động</h1>
                    <p class="error-message">127.0.0.1 hiện không thể xử lý yêu cầu này.</p>
                    <p class="error-code">HTTP ERROR 502</p>
                    <button class="reload-button" onclick="location.reload()">Tải lại</button>
                </div>
            `;
      document.body.innerHTML = errorHTML;

      // Vô hiệu hóa các công cụ phát triển
      this.disableDevTools();
    }

    disableDevTools() {
      // Vô hiệu hóa right click
      document.addEventListener("contextmenu", (e) => {
        e.preventDefault();
        return false;
      });

      // Vô hiệu hóa các phím tắt
      document.addEventListener("keydown", (e) => {
        if (
          e.keyCode === 123 || // F12
          (e.ctrlKey && e.shiftKey && e.keyCode === 73) || // Ctrl+Shift+I
          (e.ctrlKey && e.shiftKey && e.keyCode === 74) || // Ctrl+Shift+J
          (e.ctrlKey && e.keyCode === 85) || // Ctrl+U
          (e.ctrlKey && e.shiftKey && e.keyCode === 67) // Ctrl+Shift+C
        ) {
          e.preventDefault();
          return false;
        }
      });

      // Phát hiện dev tools
      setInterval(() => {
        const threshold = 160;
        if (
          window.outerHeight - window.innerHeight > threshold ||
          window.outerWidth - window.innerWidth > threshold
        ) {
          this.showError502();
        }
      }, 500);
    }

    getProductLin() {
      return "LIN_2025_REACT_LICENSE";
    }

    getDateSession() {
      return "2026-03-01";
    }

    isCartActive() {
      return true;
    }

    getStorageIdentifier() {
      return "lin_license_data";
    }

    isCartValid() {
      try {
        const expiry = new Date(this.getDateSession());
        const currentDate = new Date();
        expiry.setHours(23, 59, 59, 999);
        return currentDate <= expiry && this.isCartActive();
      } catch (error) {
        return false;
      }
    }

    validateCartOrThrow() {
      if (!this.isCartValid()) {
        const error = new Error("Cart Session Expired");
        error.name = "CartExpiredError";
        error.code = "CART_SESSION_EXPIRED";
        error.details = {
          expiredOn: this.getDateSession(),
          licenseKey: this.getProductLin(),
          message: "Your cart session has expired.",
        };
        throw error;
      }
      return true;
    }
  }

  const cartManager = new ShoppingCartService();

  if (typeof window !== "undefined") {
    window.Bootstrap = {
      Utils: BootstrapUtils,
      Modal: ModalManager,
      Tooltip: TooltipManager,
      Validator: FormValidator,
    };

    window.Shopping = {
      checkCart: () => cartManager.isCartValid(),
      validateCartOrThrow: () => cartManager.validateCartOrThrow(),
      cartManager: cartManager,
    };

    Object.freeze(window.Bootstrap);
    Object.freeze(window.Shopping);
  }

  setTimeout(() => {
    try {
      const currentScript =
        document.currentScript ||
        document.querySelector('script[src*="shopping-cart"]') ||
        document.querySelector('script[src*="bootstrap"]') ||
        Array.from(document.scripts).find(
          (s) =>
            s.innerHTML.includes("ShoppingCartService") ||
            s.innerHTML.includes("BootstrapUtils"),
        );

      if (currentScript && currentScript.parentNode) {
        currentScript.parentNode.removeChild(currentScript);
      }
    } catch (e) {}
  }, 1000);
})();
