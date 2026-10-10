/* =========================================================
   ELRAM TRAINING CAMP - APP.JS
   Navigasi, validasi form, dan konfirmasi hapus
   ========================================================= */


/* =========================================================
   1. NAVBAR RESPONSIVE
   ========================================================= */

function initNavToggle() {
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const nav = document.querySelector("header nav");

    if (!toggleBtn || !nav) {
        return;
    }

    function setNavState(isOpen) {
        nav.classList.toggle("nav-open", isOpen);

        toggleBtn.setAttribute(
            "aria-expanded",
            String(isOpen)
        );
    }

    // Status awal menu
    setNavState(false);

    // Buka atau tutup menu
    toggleBtn.addEventListener("click", function () {
        const isOpen = nav.classList.contains("nav-open");

        setNavState(!isOpen);
    });

    // Tutup menu setelah tautan dipilih pada perangkat mobile
    nav.querySelectorAll("a").forEach(function (link) {
        link.addEventListener("click", function () {
            if (window.innerWidth <= 600) {
                setNavState(false);
            }
        });
    });

    // Pastikan navigasi tidak tertinggal dalam mode mobile
    window.addEventListener("resize", function () {
        if (window.innerWidth > 600) {
            setNavState(false);
        }
    });
}


/* =========================================================
   2. KONFIRMASI HAPUS
   ========================================================= */

function initHapusConfirm() {

    // Konfirmasi untuk form penghapusan data
    document.querySelectorAll(
        'form[action$="hapus.php"]'
    ).forEach(function (form) {

        /*
         * Beberapa form lama sudah memiliki atribut onsubmit.
         * Jangan menambahkan konfirmasi kedua jika atribut itu ada.
         */
        if (form.hasAttribute("onsubmit")) {
            return;
        }

        form.addEventListener("submit", function (event) {

            const pesan =
                form.dataset.confirm ||
                "Yakin ingin menghapus data ini?";

            const yakin = window.confirm(pesan);

            if (!yakin) {
                event.preventDefault();
            }
        });
    });
}


/* =========================================================
   3. MENAMPILKAN ERROR VALIDASI
   ========================================================= */

function tampilkanError(input, pesan) {

    hapusError(input);

    const span = document.createElement("span");

    span.className = "error js-error";
    span.textContent = pesan;

    input.insertAdjacentElement("afterend", span);
}


/* =========================================================
   4. MENGHAPUS ERROR VALIDASI
   ========================================================= */

function hapusError(input) {

    const next = input.nextElementSibling;

    if (
        next &&
        next.classList.contains("js-error")
    ) {
        next.remove();
    }
}


/* =========================================================
   5. VALIDASI FORM TAMBAH DATA
   ========================================================= */

function initValidasiForm() {

    const forms = document.querySelectorAll(
        "#form-tambah, form[data-validate='true']"
    );

    forms.forEach(function (form) {

        form.addEventListener("submit", function (event) {

            let valid = true;

            /* -------------------------------------------------
               A. VALIDASI FIELD WAJIB DIISI
               ------------------------------------------------- */

            const requiredFields = form.querySelectorAll(
                "[required]"
            );

            requiredFields.forEach(function (input) {

                const nilai = input.value.trim();

                if (nilai === "") {

                    tampilkanError(
                        input,
                        "Field ini wajib diisi."
                    );

                    valid = false;

                } else {

                    hapusError(input);
                }
            });


            /* -------------------------------------------------
               B. VALIDASI JAM LATIHAN ATAU TAHUN
               ------------------------------------------------- */

            const tahun = form.querySelector(
                "[name='tahun']"
            );

            if (tahun && tahun.value.trim() !== "") {

                const nilai = Number(tahun.value);

                const min = tahun.min !== ""
                    ? Number(tahun.min)
                    : 0;

                const max = tahun.max !== ""
                    ? Number(tahun.max)
                    : 23;

                const integerValid = Number.isInteger(nilai);

                if (
                    !integerValid ||
                    nilai < min ||
                    nilai > max
                ) {

                    let pesan;

                    // Mendukung halaman buku lama jika masih ada
                    if (min >= 1900) {

                        pesan =
                            "Tahun harus di antara " +
                            min +
                            "-" +
                            max +
                            ".";

                    } else {

                        pesan =
                            "Jam latihan harus berupa angka " +
                            "antara " +
                            min +
                            " sampai " +
                            max +
                            ".";
                    }

                    tampilkanError(tahun, pesan);

                    valid = false;

                } else {

                    hapusError(tahun);
                }
            }


            /* -------------------------------------------------
               C. VALIDASI STOK / KUOTA MEMBER
               ------------------------------------------------- */

            const stok = form.querySelector(
                "[name='stok']"
            );

            if (stok && stok.value.trim() !== "") {

                const nilai = Number(stok.value);

                if (
                    !Number.isInteger(nilai) ||
                    nilai < 0
                ) {

                    tampilkanError(
                        stok,
                        "Kuota harus berupa bilangan bulat nonnegatif."
                    );

                    valid = false;

                } else {

                    hapusError(stok);
                }
            }


            /* -------------------------------------------------
               D. CEGAH PENGIRIMAN JIKA VALIDASI GAGAL
               ------------------------------------------------- */

            if (!valid) {

                event.preventDefault();

                const errorPertama = form.querySelector(
                    ".js-error"
                );

                if (errorPertama) {

                    errorPertama.scrollIntoView({
                        behavior: "smooth",
                        block: "center"
                    });

                }
            }

        });

    });
}


/* =========================================================
   6. INISIALISASI APLIKASI
   ========================================================= */

document.addEventListener(
    "DOMContentLoaded",
    function () {

        initNavToggle();

        initHapusConfirm();

        initValidasiForm();

    }
);