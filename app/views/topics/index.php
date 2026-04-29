<link rel="stylesheet" href="<?= BASEURL; ?>/css/main.css">
<link rel="stylesheet" href="<?= BASEURL; ?>/css/style.css">

<style>
    .content-header {
        border-bottom: 2px solid #eee;
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
        transition: transform 0.2s;
        position: relative;
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

    /* Sidebar Styles */
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

    /* Modal Styles */
    .modal-so {
        display: none;
        position: fixed;
        z-index: 3000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
    }

    .modal-content {
        background: #fff;
        width: 90%;
        max-width: 500px;
        margin: 50px auto;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }
</style>

<div id="sideNav" class="sidebar-panel">
    <div style="padding: 10px 0;">
        <a href="<?= BASEURL; ?>/index.php?url=TopicsControllers">🏠 Beranda</a>

        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="<?= BASEURL; ?>/index.php?url=Admin/users" style="color: #28a745;">👥 Kelola Pengguna</a>
        <?php endif; ?>

        <a href="<?= BASEURL; ?>/index.php?url=Auth/logout">🚪 Keluar</a>
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
        <?php if (strtolower($_SESSION['role']) !== 'siswa'): ?>
            <button onclick="openModal()" class="btn-primary-so"
                style="padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer;">
                + Tambah Topik
            </button>
        <?php endif; ?>
    </header>

    <div class="topics-grid">
        <?php foreach ($data['topics'] as $topic): ?>
            <?php
            $role = strtolower($_SESSION['role']);
            $user_kelas = $_SESSION['kelas']; // Kelas siswa saat login
            $target = $topic['target_kelas'];

            // LOGIKA FILTER:
            // 1. Jika Admin atau Guru -> Bisa lihat SEMUA.
            // 2. Jika Siswa -> Hanya bisa lihat yang 'Umum' ATAU yang kelasnya COCOK
            if ($role !== 'siswa' || $target === 'Umum' || $target === $user_kelas):
                ?>
                <article class="topic-card-fancy">
                    <div class="card-badge <?= ($target == 'Umum') ? 'badge-umum' : 'badge-kelas'; ?>">
                        <?= ($target == 'Umum') ? '🌍 Umum' : '📍 ' . $target; ?>
                    </div>
                    <h2><?= $topic['judul']; ?></h2>
                    <div class="topic-info">
                        <span>📅 <?= date('d M Y', strtotime($topic['created_at'])); ?></span>
                        <a href="<?= BASEURL; ?>/index.php?url=TopicsControllers/detail/<?= $topic['id']; ?>">Lihat &rarr;</a>
                    </div>
                </article>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>

<div id="modalTopic" class="modal-so">
    <div class="modal-content">
        <div
            style="padding: 20px; background: #007bff; color: white; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0;">Buat Topik Baru</h3>
            <span onclick="closeModal()" style="cursor: pointer; font-size: 28px;">&times;</span>
        </div>
        <form action="<?= BASEURL; ?>/index.php?url=TopicsControllers/simpan" method="POST"
            enctype="multipart/form-data" style="padding: 25px;">
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Judul Topik</label>
                <input type="text" name="judul" required
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Deskripsi</label>
                <textarea name="deskripsi" rows="4" required
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; resize: none;"></textarea>
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Target Kelas</label>
                <select name="target_kelas"
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                    <option value="Umum">🌍 Umum (Semua Kelas)</option>

                    <?php
                    $current_jurusan = "";
                    foreach ($data['list_kelas'] as $kls):

                        $parts = explode(' ', $kls['nama_kelas']);
                        $jurusan = isset($parts[1]) ? $parts[1] : '';


                        if ($current_jurusan !== $jurusan) {
                            echo "<option disabled style='background:#f4f4f4; color:#333; font-weight:bold;'>--- JURUSAN $jurusan ---</option>";
                            $current_jurusan = $jurusan;
                        }
                        ?>
                        <option value="<?= $kls['nama_kelas']; ?>">
                            📍 <?= $kls['nama_kelas']; ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>
            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Lampiran Foto (Opsional)</label>
                <input type="file" name="lampiran" accept="image/*">
            </div>
            <button type="submit"
                style="width: 100%; background: #007bff; color: white; border: none; padding: 14px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 16px;">
                Terbitkan Topik
            </button>
        </form>
    </div>
</div>

<script>
    function openNav() { document.getElementById("sideNav").style.width = "300px"; document.getElementById("sideOverlay").style.display = "block"; }
    function closeNav() { document.getElementById("sideNav").style.width = "0"; document.getElementById("sideOverlay").style.display = "none"; }


    function openModal() { document.getElementById("modalTopic").style.display = "block"; }
    function closeModal() { document.getElementById("modalTopic").style.display = "none"; }


    window.onclick = function (event) {
        let modal = document.getElementById("modalTopic");
        if (event.target == modal) { closeModal(); }
        if (event.target == document.getElementById("sideOverlay")) { closeNav(); }
    }
</script>