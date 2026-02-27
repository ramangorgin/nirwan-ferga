const fs = require("fs");
const path = require("path");

function ensureDir(p) {
  fs.mkdirSync(p, { recursive: true });
}

function copyFile(src, dest) {
  ensureDir(path.dirname(dest));
  fs.copyFileSync(src, dest);
  console.log("Copied:", src, "->", dest);
}

const root = process.cwd();
const nm = (p) => path.join(root, "node_modules", p);
const pub = (p) => path.join(root, "public", p);

// Alpine (use the CDN build file shipped in package)
copyFile(nm("alpinejs/dist/cdn.min.js"), pub("vendor/alpine/alpine.min.js"));

// Toastr
copyFile(nm("toastr/build/toastr.min.css"), pub("vendor/toastr/toastr.min.css"));
copyFile(nm("toastr/build/toastr.min.js"), pub("vendor/toastr/toastr.min.js"));

// SweetAlert2
copyFile(nm("sweetalert2/dist/sweetalert2.min.css"), pub("vendor/sweetalert2/sweetalert2.min.css"));
copyFile(nm("sweetalert2/dist/sweetalert2.min.js"), pub("vendor/sweetalert2/sweetalert2.min.js"));

// Animate.css
copyFile(nm("animate.css/animate.min.css"), pub("vendor/animate/animate.min.css"));


// Jalali Date Picker
copyFile(nm("@majidh1/jalalidatepicker/dist/jalalidatepicker.min.css"), pub("vendor/jalalidatepicker/jalalidatepicker.min.css"));
copyFile(nm("@majidh1/jalalidatepicker/dist/jalalidatepicker.min.js"), pub("vendor/jalalidatepicker/jalalidatepicker.min.js"));

// CKEditor 5 classic build
copyFile(nm("@ckeditor/ckeditor5-build-classic/build/ckeditor.js"), pub("vendor/ckeditor/ckeditor.js"));