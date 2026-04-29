<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?></title>
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/css/global.css">
    <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/css/main.css">

    <?php if (isset($data['judul']) && preg_match('/(Login|Sign|Daftar)/i', $data['judul'])): ?>
        <link rel="stylesheet" type="text/css" href="<?= BASEURL; ?>/css/auth.css">
    <?php endif; ?>

    <style>
        .sidebar-panel {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 2000;
            top: 0;
            left: 0;
            background: #fff;
            overflow-x: hidden;
            transition: 0.3s;
            padding-top: 20px;
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.1);
        }

        .overlay-blur {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1999;
            backdrop-filter: blur(3px);
        }

        .nav-link {
            padding: 15px 25px;
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #444;
            transition: 0.2s;
            gap: 12px;
        }

        .nav-link:hover {
            background: #f8f9fa;
            color: #007bff;
        }

        .nav-link.active {
            background: #f0f7ff;
            color: #007bff;
            font-weight: bold;
            border-left: 4px solid #007bff;
        }

        .role-badge {
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            display: inline-block;
            margin-top: 5px;
        }
    </style>
</head>

<body>

    <?php if (isset($_SESSION['login'])): ?>
        <?php
        // Ambil data terbaru dari database yang dikirim oleh Controller melalui $data
        $userAktif = isset($data['user_aktif']) ? $data['user_aktif'] : null;

        // Prioritas data: Database ($userAktif) > Session ($_SESSION)
        $userRole = ($userAktif && !empty($userAktif['role'])) ? $userAktif['role'] : ($_SESSION['role'] ?? 'user');
        $userNama = ($userAktif && !empty($userAktif['nama'])) ? $userAktif['nama'] : $_SESSION['nama'];
        $userKelas = ($userAktif && !empty($userAktif['kelas'])) ? $userAktif['kelas'] : ($_SESSION['kelas'] ?? '-');

        // Pengaturan warna badge berdasarkan role
        $bg = '#eee';
        $color = '#666';
        if ($userRole === 'admin') {
            $bg = '#ffebee';
            $color = '#c62828';
        } elseif ($userRole === 'guru') {
            $bg = '#e3f2fd';
            $color = '#1565c0';
        } else {
            $bg = '#e8f5e9';
            $color = '#2e7d32'; // Hijau untuk Siswa
        }
        ?>

        <div id="sideNav" class="sidebar-panel">
            <a href="javascript:void(0)" onclick="closeNav()"
                style="position: absolute; top: 15px; right: 25px; font-size: 30px; text-decoration: none; color: #333;">&times;</a>

            <div style="padding: 25px; border-bottom: 1px solid #eee; margin-bottom: 10px;">
                <div
                    style="width: 50px; height: 50px; background: #007bff; color: #fff; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 20px; margin-bottom: 10px;">
                    <?= strtoupper(substr($userNama, 0, 1)); ?>
                </div>

                <div style="font-weight: 700; color: #222; font-size: 16px;"><?= $userNama; ?></div>

                <span class="role-badge"
                    style="background: <?= $bg; ?>; color: <?= $color; ?>; margin-bottom: 5px; display: inline-block; padding: 2px 10px; border-radius: 20px; font-size: 11px; font-weight: bold; text-transform: uppercase;">
                    <?= $userRole; ?>
                </span>

                <?php if (strtolower($userRole) === 'siswa'): ?>
                    <div style="font-size: 12px; color: #888; font-weight: 600; margin-top: 5px;">
                        📍 <?= $userKelas; ?>
                    </div>
                <?php endif; ?>
            </div>

            <a href="<?= BASEURL; ?>/index.php?url=TopicsControllers"
                class="nav-link <?= ($data['judul'] == 'Diskusi Akademik') ? 'active' : ''; ?>"
                style="padding: 15px 25px; display: block; text-decoration: none; color: #333;">🏠 Beranda</a>

            <?php if ($userRole === 'admin'): ?>
                <a href="<?= BASEURL; ?>/index.php?url=Admin/users"
                    class="nav-link <?= ($data['judul'] == 'Manajemen User') ? 'active' : ''; ?>"
                    style="padding: 15px 25px; display: block; text-decoration: none; color: #333;">👥 Kelola Pengguna</a>
            <?php endif; ?>

            <a href="<?= BASEURL; ?>/index.php?url=Auth/logout"
                style="padding: 15px 25px; display: flex; align-items: center; text-decoration: none; color: #dc3545; margin-top: 20px; gap: 12px; font-weight: 600;">
                🚪 Keluar
            </a>
        </div>

        <div id="sideOverlay" class="overlay-blur" onclick="closeNav()"
            style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 998;">
        </div>

        <script>
            function openNav() {
                document.getElementById("sideNav").style.width = "280px";
                document.getElementById("sideOverlay").style.display = "block";
            }
            function closeNav() {
                document.getElementById("sideNav").style.width = "0";
                document.getElementById("sideOverlay").style.display = "none";
            }
        </script>
    <?php endif; ?>