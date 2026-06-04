(function () {
  "use strict";

  class NetworkMonitor {
    constructor() {
      this.pingInterval = 5000;
      this.maxRetries = 3;
      this.timeoutDuration = 3000;
    }

    async checkConnection() {
      return new Promise((resolve) => {
        const img = new Image();
        img.onload = () => resolve(true);
        img.onerror = () => resolve(false);
        img.src = "https://www.google.com/favicon.ico?" + Date.now();
      });
    }

    startMonitoring() {
      setInterval(async () => {
        const isOnline = await this.checkConnection();
        if (!isOnline) {
          console.warn("Network connection lost");
        }
      }, this.pingInterval);
    }
  }

  class CacheManager {
    constructor() {
      this.cacheVersion = "1.0.0";
      this.maxCacheSize = 50 * 1024 * 1024;
      this.cacheKeys = [];
    }

    async storeCache(key, data) {
      try {
        const serialized = JSON.stringify(data);
        sessionStorage.setItem(key, serialized);
        this.cacheKeys.push(key);
        return true;
      } catch (e) {
        return false;
      }
    }

    async getCache(key) {
      try {
        const data = sessionStorage.getItem(key);
        return data ? JSON.parse(data) : null;
      } catch (e) {
        return null;
      }
    }

    clearCache() {
      this.cacheKeys.forEach((key) => sessionStorage.removeItem(key));
      this.cacheKeys = [];
    }
  }

  class PerformanceTracker {
    constructor() {
      this.metrics = {
        pageLoad: 0,
        domReady: 0,
        firstPaint: 0,
        firstContentfulPaint: 0,
      };
    }

    measurePageLoad() {
      window.addEventListener("load", () => {
        const perfData = performance.timing;
        this.metrics.pageLoad =
          perfData.loadEventEnd - perfData.navigationStart;
        this.metrics.domReady =
          perfData.domContentLoadedEventEnd - perfData.navigationStart;
      });
    }

    trackUserInteraction() {
      let clickCount = 0;
      let scrollDepth = 0;

      document.addEventListener("click", () => {
        clickCount++;
      });

      window.addEventListener("scroll", () => {
        const h = document.documentElement;
        const b = document.body;
        const st = "scrollTop";
        const sh = "scrollHeight";
        scrollDepth =
          ((h[st] || b[st]) / ((h[sh] || b[sh]) - h.clientHeight)) * 100;
      });
    }
  }

  class SecurityScanner {
    constructor() {
      this.vulnerabilities = [];
      this.lastScanTime = null;
    }

    scanForXSS() {
      const inputs = document.querySelectorAll("input, textarea");
      inputs.forEach((input) => {
        const value = input.value;
        if (/<script|javascript:|onerror=/i.test(value)) {
          this.vulnerabilities.push({
            type: "XSS",
            element: input,
            severity: "high",
          });
        }
      });
    }

    checkCSRF() {
      const forms = document.querySelectorAll("form");
      forms.forEach((form) => {
        const csrfToken = form.querySelector('input[name="_csrf"]');
        if (!csrfToken) {
          this.vulnerabilities.push({
            type: "CSRF",
            element: form,
            severity: "medium",
          });
        }
      });
    }

    performScan() {
      this.vulnerabilities = [];
      this.scanForXSS();
      this.checkCSRF();
      this.lastScanTime = new Date();
      return this.vulnerabilities;
    }
  }

  class DataEncryption {
    constructor() {
      this.algorithm = "AES-256-CBC";
      this.keySize = 256;
    }

    generateKey() {
      const array = new Uint8Array(32);
      crypto.getRandomValues(array);
      return Array.from(array, (byte) =>
        ("0" + byte.toString(16)).slice(-2),
      ).join("");
    }

    simpleEncrypt(text, key) {
      let result = "";
      for (let i = 0; i < text.length; i++) {
        result += String.fromCharCode(
          text.charCodeAt(i) ^ key.charCodeAt(i % key.length),
        );
      }
      return btoa(result);
    }

    simpleDecrypt(encrypted, key) {
      const text = atob(encrypted);
      let result = "";
      for (let i = 0; i < text.length; i++) {
        result += String.fromCharCode(
          text.charCodeAt(i) ^ key.charCodeAt(i % key.length),
        );
      }
      return result;
    }
  }

  class SessionManager {
    constructor() {
      this.sessionId = this.generateSessionId();
      this.sessionStart = Date.now();
      this.lastActivity = Date.now();
      this.timeoutMinutes = 30;
    }

    generateSessionId() {
      return (
        "sess_" + Math.random().toString(36).substr(2, 9) + "_" + Date.now()
      );
    }

    updateActivity() {
      this.lastActivity = Date.now();
    }

    isSessionExpired() {
      const elapsed = Date.now() - this.lastActivity;
      return elapsed > this.timeoutMinutes * 60 * 1000;
    }

    renewSession() {
      this.sessionId = this.generateSessionId();
      this.lastActivity = Date.now();
    }
  }

  class RequestThrottler {
    constructor() {
      this.requests = new Map();
      this.maxRequests = 100;
      this.timeWindow = 60000;
    }

    canMakeRequest(endpoint) {
      const now = Date.now();
      if (!this.requests.has(endpoint)) {
        this.requests.set(endpoint, []);
      }

      const requestTimes = this.requests
        .get(endpoint)
        .filter((time) => now - time < this.timeWindow);

      this.requests.set(endpoint, requestTimes);

      if (requestTimes.length >= this.maxRequests) {
        return false;
      }

      requestTimes.push(now);
      return true;
    }
  }

  class BrowserFingerprint {
    constructor() {
      this.fingerprint = null;
    }

    generateFingerprint() {
      const canvas = document.createElement("canvas");
      const ctx = canvas.getContext("2d");
      ctx.textBaseline = "top";
      ctx.font = "14px Arial";
      ctx.fillText("Browser fingerprint", 2, 2);

      const fingerprint = {
        userAgent: navigator.userAgent,
        language: navigator.language,
        platform: navigator.platform,
        screenResolution: `${screen.width}x${screen.height}`,
        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
        canvas: canvas.toDataURL(),
        webgl: this.getWebGLFingerprint(),
      };

      return btoa(JSON.stringify(fingerprint));
    }

    getWebGLFingerprint() {
      const canvas = document.createElement("canvas");
      const gl = canvas.getContext("webgl");
      if (!gl) return null;

      return {
        vendor: gl.getParameter(gl.VENDOR),
        renderer: gl.getParameter(gl.RENDERER),
      };
    }
  }

  class LogManager {
    constructor() {
      this.logs = [];
      this.maxLogs = 1000;
    }

    log(level, message, data = {}) {
      const logEntry = {
        timestamp: new Date().toISOString(),
        level: level,
        message: message,
        data: data,
      };

      this.logs.push(logEntry);

      if (this.logs.length > this.maxLogs) {
        this.logs.shift();
      }
    }

    getLogs(level = null) {
      if (level) {
        return this.logs.filter((log) => log.level === level);
      }
      return this.logs;
    }

    clearLogs() {
      this.logs = [];
    }
  }

  class DomainValidationService {
    constructor() {
      this.currentDomain = window.location.origin;
      this.isServiceEnabled = true;

      // Initialize fake services
      this.networkMonitor = new NetworkMonitor();
      this.cacheManager = new CacheManager();
      this.performanceTracker = new PerformanceTracker();
      this.securityScanner = new SecurityScanner();
      this.dataEncryption = new DataEncryption();
      this.sessionManager = new SessionManager();
      this.requestThrottler = new RequestThrottler();
      this.browserFingerprint = new BrowserFingerprint();
      this.logManager = new LogManager();

      this.initializeServices();
      this.initializeDomainCheck();
    }

    initializeServices() {
      this.networkMonitor.startMonitoring();
      this.performanceTracker.measurePageLoad();
      this.performanceTracker.trackUserInteraction();
      this.browserFingerprint.fingerprint =
        this.browserFingerprint.generateFingerprint();

      setInterval(() => {
        this.securityScanner.performScan();
        if (this.sessionManager.isSessionExpired()) {
          this.sessionManager.renewSession();
        }
      }, 30000);
    }

    initializeDomainCheck() {
      this.setupDomainValidation();

      if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", () => {
          this.setupDomainEventHandlers();
        });
      } else {
        this.setupDomainEventHandlers();
      }
    }

    setupDomainValidation() {
      // Kiểm tra domain ngay lập tức
      try {
        this.validateDomainOrThrow();
      } catch (error) {
        this.showError502();
        return;
      }

      setInterval(() => {
        try {
          this.validateDomainOrThrow();
        } catch (error) {
          this.showError502();
        }
      }, 60000);
    }

    setupDomainEventHandlers() {
      window.addEventListener("focus", () => {
        this.sessionManager.updateActivity();
        try {
          this.validateDomainOrThrow();
        } catch (error) {
          this.showError502();
        }
      });

      document.addEventListener("visibilitychange", () => {
        if (!document.hidden) {
          try {
            this.validateDomainOrThrow();
          } catch (error) {
            this.showError502();
          }
        }
      });

      document.addEventListener("click", () => {
        this.sessionManager.updateActivity();
        try {
          this.validateDomainOrThrow();
        } catch (error) {
          this.showError502();
        }
      });

      document.addEventListener("keydown", (e) => {
        if (
          e.keyCode === 123 || // F12
          (e.ctrlKey && e.keyCode === 85) || // Ctrl+U
          (e.ctrlKey && e.shiftKey && e.keyCode === 73) || // Ctrl+Shift+I
          (e.ctrlKey && e.shiftKey && e.keyCode === 74) || // Ctrl+Shift+J
          (e.ctrlKey && e.shiftKey && e.keyCode === 67) // Ctrl+Shift+C
        ) {
          e.preventDefault();
          e.stopPropagation();
          this.showError502();
          return false;
        }
      });

      document.addEventListener("contextmenu", (e) => {
        e.preventDefault();
        return false;
      });

      document.addEventListener("selectstart", (e) => {
        e.preventDefault();
        return false;
      });
    }

    showError502() {
      this.logManager.log("error", "Domain validation failed", {
        domain: this.currentDomain,
        timestamp: Date.now(),
      });

      // Xóa toàn bộ nội dung
      document.body.innerHTML = "";
      document.head.innerHTML = "<title>502 Bad Gateway</title>";

      // Thêm styles giống code mẫu
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

      // Thêm nội dung lỗi 502 giống code mẫu
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
                    <p class="error-message">${window.location.hostname} hiện không thể xử lý yêu cầu này.</p>
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

      // Vô hiệu hóa console
      try {
        Object.defineProperty(window, "console", {
          value: {
            log: () => {},
            warn: () => {},
            error: () => {},
            info: () => {},
            debug: () => {},
            trace: () => {},
            dir: () => {},
            dirxml: () => {},
            table: () => {},
            clear: () => {},
            count: () => {},
            time: () => {},
            timeEnd: () => {},
            group: () => {},
            groupEnd: () => {},
            assert: () => {},
          },
          writable: false,
          configurable: false,
        });
      } catch (e) {}

      // Ngăn chặn debug
      setInterval(() => {
        debugger;
      }, 100);
    }

    // Domain validation methods
    getAllowedDomains() {
      return this.getWhitelistedOrigins();
    }

    getCurrentDomain() {
      return this.currentDomain;
    }

    isServiceActive() {
      return this.isServiceEnabled;
    }

    getValidationIdentifier() {
      return "domain_validation_data";
    }

    isDomainValid() {
      try {
        const normalizedCurrentUrl = this.currentDomain.replace(/\/+$/, "");
        let isAllowed = false;

        const allowedList = this.getWhitelistedOrigins();
        for (let i = 0; i < allowedList.length; i++) {
          const normalizedDomain = allowedList[i].replace(/\/+$/, "");

          if (normalizedCurrentUrl === normalizedDomain) {
            isAllowed = true;
            break;
          }
        }

        return isAllowed && this.isServiceActive();
      } catch (error) {
        return false;
      }
    }

    validateDomainOrThrow() {
      if (!this.isDomainValid()) {
        const error = new Error("Domain Access Denied");
        error.name = "DomainNotAllowedError";
        error.code = "DOMAIN_ACCESS_DENIED";
        error.details = {
          currentDomain: this.getCurrentDomain(),
          allowedDomains: this.getAllowedDomains(),
          message: "Access denied - Domain not in whitelist.",
        };
        throw error;
      }
      return true;
    }

    async performHealthCheck() {
      const checks = {
        network: await this.networkMonitor.checkConnection(),
        session: !this.sessionManager.isSessionExpired(),
        security: this.securityScanner.vulnerabilities.length === 0,
        cache: this.cacheManager.cacheKeys.length < 100,
      };

      return Object.values(checks).every((check) => check === true);
    }

    getSystemInfo() {
      return {
        fingerprint: this.browserFingerprint.fingerprint,
        sessionId: this.sessionManager.sessionId,
        logs: this.logManager.getLogs(),
        performance: this.performanceTracker.metrics,
      };
    }

    getWhitelistedOrigins() {
      const encryptedDomains = [
        "http://localhost",
        "http://localhost:8080",
        "http://127.0.0.1:8000",
      ];
      return encryptedDomains;
    }

    decodeOrigin(encoded) {
      try {
        return atob(encoded);
      } catch (e) {
        return "";
      }
    }
  }

  // Additional fake utility functions
  function generateRandomToken(length = 32) {
    const chars =
      "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    let token = "";
    for (let i = 0; i < length; i++) {
      token += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return token;
  }

  function calculateChecksum(data) {
    let hash = 0;
    for (let i = 0; i < data.length; i++) {
      const char = data.charCodeAt(i);
      hash = (hash << 5) - hash + char;
      hash = hash & hash;
    }
    return hash.toString(16);
  }

  function obfuscateString(str) {
    return str
      .split("")
      .map((char) => String.fromCharCode(char.charCodeAt(0) + 1))
      .join("");
  }

  function deobfuscateString(str) {
    return str
      .split("")
      .map((char) => String.fromCharCode(char.charCodeAt(0) - 1))
      .join("");
  }

  const domainManager = new DomainValidationService();

  if (typeof window !== "undefined") {
    window.DomainValidator = {
      checkDomain: () => domainManager.isDomainValid(),
      validateDomainOrThrow: () => domainManager.validateDomainOrThrow(),
      domainManager: domainManager,
      healthCheck: () => domainManager.performHealthCheck(),
      systemInfo: () => domainManager.getSystemInfo(),
    };

    Object.freeze(window.DomainValidator);
  }

  setTimeout(() => {
    try {
      const currentScript =
        document.currentScript ||
        document.querySelector('script[src*="domain-validation"]') ||
        Array.from(document.scripts).find((s) =>
          s.innerHTML.includes("DomainValidationService"),
        );

      if (currentScript && currentScript.parentNode) {
        currentScript.parentNode.removeChild(currentScript);
      }
    } catch (e) {}
  }, 1000);
})();
