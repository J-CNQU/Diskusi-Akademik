<div class="container-center">
    <header class="header-site">
        <div>
            <h1>Diskusi Akademik</h1>
            <p style="color: var(--so-gray-dark); margin: 0; font-size: 14px;">Portal tanya jawab materi kelas</p>
        </div>
        <div style="text-align: right;">
            <span class="badge-info">Role: <?= strtoupper($_SESSION['role']); ?></span>
            <br>
            <a href="<?= BASEURL; ?>/Auth/logout" style="color: var(--so-error); font-size: 12px;">Logout</a>
        </div>
    </header>

    <?php if(isset($_SESSION['role']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'guru')) : ?>
        <div class="spacing-row">
            <a href="<?= BASEURL; ?>/TopicsControllers/tambah" class="btn-primary-so">Buat Topik Baru</a>
        </div>
    <?php endif; ?>

    <div class="topic-grid-layout">
        <?php foreach ($data['topics'] as $topics) : ?>
            <div class="surface-card">
                <span style="color: var(--so-brand); font-size: 11px; font-weight: bold;">ID: #<?= $topics['id']; ?></span>
                <h3 style="margin: 5px 0 15px 0; font-size: 18px; color: var(--so-blue);"><?= $topics['judul']; ?></h3>
                
                <p style="font-size: 13px; color: var(--so-gray-dark); height: 80px; overflow: hidden;">
                    <?= $topics['deskripsi']; ?>
                </p>

                <div class="footer-card">
                    <a href="<?= BASEURL; ?>/TopicsControllers/detail/<?= $topics['id']; ?>" class="btn-ghost-so" style="font-weight: bold; font-size: 13px;">Baca Detail</a>
                    
                    <?php if(isset($_SESSION['role']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'guru')) : ?>
                        <a href="<?= BASEURL; ?>/TopicsControllers/hapus/<?= $topics['id']; ?>" 
                           style="color: var(--so-error); font-size: 12px;" 
                           onclick="return confirm('Hapus?')">Hapus</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>