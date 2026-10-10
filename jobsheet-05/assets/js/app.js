// SIMPUS-Mini Jobsheet 5 — interaksi antarmuka
function initNavToggle() {
    const button = document.getElementById("nav-toggle-btn");
    const nav = document.getElementById("mainNav");
    if (!button || !nav) return;
    button.addEventListener("click", () => {
        const opened = nav.classList.toggle("show");
        button.setAttribute("aria-expanded", String(opened));
    });
}

function initTableFilter() {
    const input = document.getElementById("search-input");
    const table = document.querySelector("#dataTable");
    if (!input || !table) return;
    const rows = Array.from(table.querySelectorAll("tbody tr"));
    const counter = document.getElementById("table-counter");
    const noun = table.dataset.kind === "anggota" ? "anggota" : "buku";
    function filterRows() {
        const keyword = input.value.toLocaleLowerCase("id").trim();
        let visible = 0;
        rows.forEach(row => {
            const firstCell = row.querySelector("td");
            const matches = (firstCell?.textContent || "").toLocaleLowerCase("id").includes(keyword);
            row.hidden = !matches;
            if (matches) visible++;
        });
        if (counter) counter.textContent = `Menampilkan ${visible} dari ${rows.length} ${noun}`;
    }
    input.addEventListener("input", filterRows);
    filterRows();
}

function initDeleteButtons() {
    document.querySelectorAll(".btn-hapus").forEach(button => {
        button.addEventListener("click", () => {
            if (!confirm("Yakin ingin menghapus data ini dari tampilan?")) return;
            const row = button.closest("tr");
            const table = button.closest("table");
            if (row) row.remove();
            if (table) {
                const kind = table.dataset.kind;
                const input = document.getElementById("search-input");
                if (input) input.dispatchEvent(new Event("input"));
            }
        });
    });
}

function initFormValidation() {
    const form = document.querySelector("form[data-validate]");
    if (!form) return;
    form.addEventListener("submit", event => {
        event.preventDefault();
        if (!form.reportValidity()) return;
        const isbn = document.getElementById("isbn");
        if (isbn && isbn.value && !/^[0-9-]+$/.test(isbn.value)) {
            isbn.setCustomValidity("ISBN hanya boleh berisi angka dan tanda hubung (-).");
            isbn.reportValidity();
            isbn.setCustomValidity("");
            return;
        }
        alert("Validasi berhasil. Form ini masih berupa Jobsheet 5 dan belum terhubung ke penyimpanan data.");
    });
}

document.addEventListener("DOMContentLoaded", () => {
    initNavToggle();
    initTableFilter();
    initDeleteButtons();
    initFormValidation();
});
