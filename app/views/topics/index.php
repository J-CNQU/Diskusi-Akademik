<link rel="stylesheet" href="<?= BASEURL; ?>/css/main.css">
<link rel="stylesheet" href="<?= BASEURL; ?>/css/style.css">

<style>
    .content-header {
        border-bottom: 2px solid #eee;
        /* Garis pembatas di atas */
        padding-bottom: 20px;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .section-title {
        font-size: 24px;
        font-weight: 700;
        color: #333;
        margin: 0;
    }

    .topics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100%, 1fr));
        gap: 20px;
    }

    .topic-card-fancy {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 25px;
        transition: transform 0.2s, box-shadow 0.2s;
        position: relative;
        overflow: hidden;
    }

    .topic-card-fancy:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
    }

    .card-badge {
        font-size: 11px;
        font-weight: 800;
        padding: 5px 12px;
        border-radius: 50px;
        text-transform: uppercase;
        margin-bottom: 15px;
        display: inline-block;
    }

    .badge-umum {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .badge-kelas {
        background: #e3f2fd;
        color: #1565c0;
    }

    .topic-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px solid #f0f0f0;
        font-size: 13px;
        color: #777;
    }

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
        padding-top: 60px;
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
</style>

<div id="sideNav" class="sidebar-panel">
    <a href="javascript:void(0)" onclick="closeNav()"
        style="position: absolute; top: 15px; right: 25px; font-size: 36px; text-decoration: none; color: #333;">&times;</a>
    <div style="padding: 20px 25px; border-bottom: 1px solid #eee;">
        <div
            style="width: 50px; height: 50px; background: var(--so-blue); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 20px; margin-bottom: 10px;">
            <?= strtoupper(substr($_SESSION['nama'], 0, 1)); ?>
        </div>
        <strong style="font-size: 16px;"><?= $_SESSION['nama']; ?></strong><br>
        <small style="color: #888; text-transform: uppercase;"><?= $_SESSION['role']; ?>
            <?= ($_SESSION['role'] == 'siswa') ? '• ' . $_SESSION['kelas'] : ''; ?></small>
    </div>
    <div style="padding: 10px 0;">
        <a href="<?= BASEURL; ?>/index.php?url=TopicsControllers"
            style="padding: 15px 25px; display: block; text-decoration: none; color: #333;">🏠 Beranda</a>

        <?php if ($_SESSION['role'] !== 'siswa'): ?>
            <a href="<?= BASEURL; ?>/index.php?url=TopicsControllers/tambah"
                style="padding: 15px 25px; display: block; text-decoration: none; color: #333;">➕ Buat Topik Baru</a>
        <?php endif; ?>

        <a href="<?= BASEURL; ?>/index.php?url=Auth/logout"
            style="padding: 15px 25px; display: block; text-decoration: none; color: #dc3545;">🚪 Keluar</a>
    </div>
</div>
<div id="sideOverlay" class="overlay-blur" onclick="closeNav()"></div>

<div class="wrapper">
    <header class="content-header">
        <div style="display: flex; align-items: center; gap: 15px;">
            <button onclick="openNav()"
                style="background: #f4f4f4; border: none; width: 45px; height: 45px; border-radius: 10px; cursor: pointer; font-size: 20px;">☰</button>
            <h1 class="section-title">Forum Diskusi</h1>
        </div>

        <?php if ($_SESSION['role'] !== 'siswa'): ?>
            <a href="<?= BASEURL; ?>/index.php?url=TopicsControllers/tambah" class="btn-primary-so"
                style="text-decoration: none; padding: 10px 20px;">+ Tambah Topik</a>
        <?php endif; ?>
    </header>

    <div class="topics-grid">
        <?php foreach ($data['topics'] as $topic): ?>
            <?php
            $role = strtolower($_SESSION['role']);
            $target = $topic['target_kelas'];
            $user_kelas = $_SESSION['kelas'];

            // Filter Hak Lihat
            if ($role !== 'siswa' || $target === 'Umum' || $target === $user_kelas):
                ?>
                <article class="topic-card-fancy">
                    <div class="card-badge <?= ($target == 'Umum') ? 'badge-umum' : 'badge-kelas'; ?>">
                        <?= ($target == 'Umum') ? '🌍 Umum' : '📍 Kelas ' . $target; ?>
                    </div>

                    <h2 style="margin: 0 0 10px 0; font-size: 20px; color: #222;"><?= $topic['judul']; ?></h2>
                    <p style="color: #666; line-height: 1.6; font-size: 15px;">
                        <?= (strlen($topic['deskripsi']) > 150) ? substr($topic['deskripsi'], 0, 150) . '...' : $topic['deskripsi']; ?>
                    </p>

                    <div class="topic-info">
                        <span>📅 <?= date('d M Y', strtotime($topic['created_at'])); ?></span>
                        <a href="<?= BASEURL; ?>/index.php?url=TopicsControllers/detail/<?= $topic['id']; ?>"
                            style="color: var(--so-blue); font-weight: bold; text-decoration: none;">Diskusi Lengkap &rarr;</a>
                    </div>
                </article>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>

<script>
    function openNav() { document.getElementById("sideNav").style.width = "300px"; document.getElementById("sideOverlay").style.display = "block"; }
    function closeNav() { document.getElementById("sideNav").style.width = "0"; document.getElementById("sideOverlay").style.display = "none"; }
</script>