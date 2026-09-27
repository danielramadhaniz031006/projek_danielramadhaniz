<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . " - ELRAM Training Camp" : "ELRAM Training Camp"; ?></title>
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #0c0d12;
            color: #ffffff;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        header {
            background-color: #12141d;
            border-bottom: 2px solid #e50914;
            padding: 15px 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .logo {
            font-size: 1.3rem;
            font-weight: 900;
            color: #ffffff;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Tombol Garis Tiga (Mobile Only) */
        .menu-toggle {
            display: none;
            background: #1a1d28;
            border: 1px solid #33394b;
            color: #fff;
            font-size: 1.4rem;
            padding: 6px 14px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .menu-toggle:hover {
            background: #e50914;
        }

        /* Menu Navigasi Desktop */
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-link {
            color: #b0b5c4;
            text-decoration: none;
            padding: 10px 18px;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            border-radius: 6px;
            transition: all 0.2s ease-in-out;
        }

        .nav-link:hover {
            color: #ffffff;
            background: #1f2330;
        }

        /* Status Aktif Tombol */
        .nav-link.active {
            background: #e50914;
            color: #ffffff;
            box-shadow: 0 0 10px rgba(229, 9, 20, 0.4);
        }

        /* =========================================================
           CSS RESPONSIVE UNTUK TAMPILAN HP / MOBILE
           ========================================================= */
        @media (max-width: 850px) {
            .menu-toggle {
                display: block; /* Tampilkan garis tiga di layar HP */
            }

            .nav-menu {
                display: none; /* Sembunyikan menu secara default di HP */
                width: 100%;
                flex-direction: column;
                align-items: stretch;
                gap: 8px;
                margin-top: 15px;
                padding-top: 15px;
                border-top: 1px solid #232733;
            }

            /* Kelas ini akan dipanggil oleh JavaScript saat tombol dipencet */
            .nav-menu.show {
                display: flex;
            }

            .nav-link {
                text-align: center;
                padding: 12px;
                background: #1a1d28;
                border: 1px solid #282d3c;
            }
        }
    </style>
</head>
<body>

    <header>
        <div class="header-container">
            <!-- LOGO / JUDUL WEBSITE -->
            <a href="index.php" class="logo">ELRAM TRAINING CAMP</a>

            <!-- TOMBOL GARIS TIGA (HAMBURGER MENU) -->
            <button class="menu-toggle" onclick="toggleNav()" aria-label="Toggle Menu">
                &#9776;
            </button>

            <!-- DAFTAR MENU NAVIGASI -->
            <nav class="nav-menu" id="navMenu">
                <?php 
                    $current_page = basename($_SERVER['PHP_SELF']); 
                ?>
                <a href="index.php" class="nav-link <?php echo ($current_page == 'index.php' || $current_page == '') ? 'active' : ''; ?>">
                    BERANDA
                </a>
                <a href="jadwal_list.php" class="nav-link <?php echo ($current_page == 'jadwal_list.php') ? 'active' : ''; ?>">
                    JADWAL KELAS
                </a>
                <a href="jadwal_tambah.php" class="nav-link <?php echo ($current_page == 'jadwal_tambah.php') ? 'active' : ''; ?>">
                    TAMBAH JADWAL KELAS
                </a>
                <a href="api/anggota/list.php" class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], 'anggota/list') !== false) ? 'active' : ''; ?>">
                    DAFTAR MEMBER
                </a>
                <a href="api/anggota/tambah.php" class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], 'anggota/tambah') !== false) ? 'active' : ''; ?>">
                    TAMBAH MEMBER
                </a>
            </nav>
        </div>
    </header>

    <!-- JAVASCRIPT UNTUK FUNGSI TAMPIL/SEMBUNYI MENU -->
    <script>
        function toggleNav() {
            var nav = document.getElementById("navMenu");
            nav.classList.toggle("show");
        }
    </script>