// ===== Toggle Menu =====
function initNavToggle() {
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const nav = document.querySelector("header nav");
    if (!toggleBtn || !nav) return;

    nav.classList.add("transition");

    toggleBtn.addEventListener("click", function () {
        nav.classList.toggle("nav-open");
    });
}


// ===== Konfirmasi Hapus =====
function initHapusConfirm() {
    const tombolHapus = document.querySelectorAll(".btn-hapus");

    tombolHapus.forEach(function (button) {
        button.addEventListener("click", function () {
            const yakin = confirm("Apakah Anda yakin ingin menghapus buku ini?");
            const row = button.closest("tr");

            if (yakin && row) {
                const table = row.closest("table");
                row.remove();

                // Memperbarui counter setelah data dihapus
                updateTableCounter(table);
            }
        });
    });
}


// ===== Counter Jumlah Buku =====
function updateTableCounter(table) {
    const counter = document.getElementById("table-counter");
    if (!counter || !table) return;

    const rows = table.querySelectorAll("tbody tr");
    const total = rows.length;

    let tampil = 0;

    rows.forEach(function (row) {
        if (row.style.display !== "none") {
            tampil++;
        }
    });

    counter.textContent =
        "Menampilkan " + tampil + " dari " + total + " buku";
}


// ===== Filter Tabel Berdasarkan Judul =====
function initTableFilter() {
    const input = document.getElementById("search-input");
    const table = document.querySelector(".table-responsive table");
    if (!input || !table) return;

    input.addEventListener("keyup", function () {
        const keyword = input.value.toLowerCase();
        const rows = table.querySelectorAll("tbody tr");

        rows.forEach(function (row) {
            // Mengambil kolom pertama, yaitu Judul
            const kolomJudul = row.querySelector("td");

            const teks = kolomJudul
                ? kolomJudul.textContent.toLowerCase()
                : "";

            row.style.display =
                teks.includes(keyword) ? "" : "none";
        });

        // Memperbarui counter setelah filter
        updateTableCounter(table);
    });

    // Menampilkan counter saat halaman pertama kali dibuka
    updateTableCounter(table);
}


// ===== Validasi Form =====
function initValidasiForm() {
    const form = document.querySelector("form");
    if (!form) return;

    const fieldWajib = ["judul", "pengarang", "tahun", "stok"];

    form.addEventListener("submit", function (event) {
        fieldWajib.forEach(function (namaField) {
            const field = document.getElementById(namaField);

            if (field && field.value.trim() === "") {
                field.setCustomValidity("Field ini wajib diisi.");
            } else if (field) {
                field.setCustomValidity("");
            }
        });
    });
}


// ===== Jalankan Semua Fungsi =====
document.addEventListener("DOMContentLoaded", function () {
    initNavToggle();
    initHapusConfirm();
    initTableFilter();
    initValidasiForm();
});