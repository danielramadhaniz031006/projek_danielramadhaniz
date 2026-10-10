// SIMPUS-Mini Jobsheet 6 — navigasi, pencarian, konfirmasi, validasi
function initNavToggle() {
  const button = document.getElementById("nav-toggle-btn");
  const nav = document.getElementById("mainNav");
  if (!button || !nav) return;
  button.addEventListener("click", () => {
    const opened = nav.classList.toggle("show");
    button.setAttribute("aria-expanded", String(opened));
  });
}
function initHapusConfirm() {
  document.addEventListener("click", (event) => {
    const button = event.target.closest(".btn-hapus");
    if (!button) return;
    const row = button.closest("tr");
    const label = row?.querySelector("td")?.textContent.trim() || "data ini";
    if (row && confirm(`Yakin ingin menghapus "${label}" dari tampilan?`)) {
      row.remove();
      const search = document.getElementById("search-input");
      if (search) search.dispatchEvent(new Event("input"));
    }
  });
}
function initTableFilter() {
  const input = document.getElementById("search-input");
  const table = document.getElementById("dataTable");
  if (!input || !table) return;
  const update = () => {
    const keyword = input.value.toLocaleLowerCase("id").trim();
    const rows = [...table.querySelectorAll("tbody tr")];
    let visible = 0;
    rows.forEach(row => {
      const match = row.textContent.toLocaleLowerCase("id").includes(keyword);
      row.hidden = !match;
      if (match) visible++;
    });
    const counter = document.getElementById("table-counter");
    if (counter) counter.textContent = `Menampilkan ${visible} dari ${rows.length} data`;
  };
  input.addEventListener("input", update);
  document.addEventListener("dataLoaded", update);
  update();
}
function showFieldError(input, message) {
  input.classList.add("is-invalid");
  let feedback = input.parentElement.querySelector(".invalid-feedback");
  if (!feedback) {
    feedback = document.createElement("div");
    feedback.className = "invalid-feedback";
    input.insertAdjacentElement("afterend", feedback);
  }
  feedback.textContent = message;
}
function clearFieldError(input) {
  input.classList.remove("is-invalid");
  const feedback = input.parentElement.querySelector(".invalid-feedback");
  if (feedback) feedback.remove();
}
function initValidation() {
  const form = document.getElementById("form-tambah");
  if (!form) return;
  form.addEventListener("submit", event => {
    event.preventDefault();
    let valid = true;
    const requiredFields = form.querySelectorAll("[required]");
    requiredFields.forEach(input => {
      if (!input.value.trim()) {
        showFieldError(input, "Bagian ini wajib diisi.");
        valid = false;
      } else clearFieldError(input);
    });
    const year = form.querySelector("[name='tahun']");
    if (year && year.value) {
      const n = Number(year.value);
      if (!Number.isInteger(n) || n < 1900 || n > new Date().getFullYear()) {
        showFieldError(year, `Tahun harus antara 1900-${new Date().getFullYear()}.`);
        valid = false;
      } else clearFieldError(year);
    }
    const stock = form.querySelector("[name='stok']");
    if (stock && stock.value !== "" && Number(stock.value) < 0) {
      showFieldError(stock, "Stok tidak boleh negatif.");
      valid = false;
    } else if (stock) clearFieldError(stock);
    const isbn = form.querySelector("[name='isbn']");
    if (isbn && isbn.value && !/^[0-9-]+$/.test(isbn.value)) {
      showFieldError(isbn, "ISBN hanya boleh berisi angka dan tanda hubung (-).");
      valid = false;
    } else if (isbn) clearFieldError(isbn);
    if (valid) alert("Validasi berhasil. Form belum menyimpan data ke server/database.");
  });
}
document.addEventListener("DOMContentLoaded", () => {
  initNavToggle();
  initHapusConfirm();
  initTableFilter();
  initValidation();
});
