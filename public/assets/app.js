(function () {
  // ---------- Toastr defaults ----------
  try {
    if (window.toastr) {
      toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-left",
        timeOut: 3500,
      };
    }
  } catch (e) {}

  // ---------- SweetAlert (Persian) ----------
  window.uiConfirm = async function ({
    title = "تأیید عملیات",
    text = "آیا مطمئن هستید؟",
    confirmText = "بله",
    cancelText = "انصراف",
    icon = "warning",
  } = {}) {
    try {
      if (!window.Swal) return confirm(text);

      const SwalPersian = Swal.mixin({
        customClass: {
          popup: "font-fa",
          title: "font-fa",
          htmlContainer: "font-fa",
          confirmButton: "font-fa",
          cancelButton: "font-fa",
        },
        buttonsStyling: true,
      });

      const res = await SwalPersian.fire({
        title,
        text,
        icon,
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
        reverseButtons: true,
        focusCancel: true,
      });

      return res.isConfirmed;
    } catch (e) {
      console.error("uiConfirm error:", e);
      return confirm(text);
    }
  };

  document.addEventListener("click", async (e) => {
    const el = e.target.closest("[data-confirm]");
    if (!el) return;

    e.preventDefault();

    const ok = await window.uiConfirm({
      title: el.getAttribute("data-confirm-title") || "تأیید عملیات",
      text: el.getAttribute("data-confirm-text") || "آیا مطمئن هستید؟",
      confirmText: el.getAttribute("data-confirm-yes") || "بله",
      cancelText: el.getAttribute("data-confirm-no") || "انصراف",
    });

    if (!ok) return;

    const form = el.closest("form");
    if (form && (el.type === "submit" || el.hasAttribute("data-confirm-submit"))) {
      form.submit();
      return;
    }

    const href = el.getAttribute("href");
    if (href) window.location.href = href;
  });

  // ---------- Jalali Datepicker ----------
  window.uiInitJalaliDatepicker = function () {
    try {
      if (!window.jalaliDatepicker) return;
      if (typeof window.jalaliDatepicker.startWatch === "function") {
        window.jalaliDatepicker.startWatch({
          minDate: "attr",
          maxDate: "attr",
          time: false,
        });
      }
    } catch (e) {
      console.error("uiInitJalaliDatepicker error:", e);
    }
  };

  // ---------- CKEditor ----------
  window.uiInitCKEditor = function (root = document) {
    try {
      if (!window.ClassicEditor) return;

      root.querySelectorAll("textarea[data-ckeditor]").forEach((el) => {
        if (el.dataset.ckeditorReady === "1") return;

        window.ClassicEditor.create(el, { language: "fa" })
          .then(() => (el.dataset.ckeditorReady = "1"))
          .catch((err) => console.error("CKEditor error:", err));
      });
    } catch (e) {
      console.error("uiInitCKEditor error:", e);
    }
  };

  function boot() {
    window.uiInitJalaliDatepicker();
    window.uiInitCKEditor();
  }

  document.addEventListener("DOMContentLoaded", boot);
  document.addEventListener("livewire:navigated", boot);
})();