<div class="wrapper" style="padding: 20px; max-width: 1100px; margin: auto; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); border: 1px solid #eee;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <button onclick="openNav()" style="background: #f4f4f4; border: none; width: 40px; height: 40px; border-radius: 8px; cursor: pointer; font-size: 18px;">☰</button>
            <div>
                <h1 style="margin: 0; font-size: 22px; color: #333;">👥 Manajemen Pengguna</h1>
                <p style="color: #888; margin: 2px 0 0 0; font-size: 13px;">Kelola akun siswa, guru, dan admin sistem.</p>
            </div>
        </div>

        <a href="<?= BASEURL; ?>/index.php?url=TopicsControllers" 
           style="text-decoration: none; padding: 10px 20px; background: #fff; color: #333; border-radius: 8px; font-weight: 600; font-size: 14px; border: 1px solid #ddd; transition: 0.3s; display: flex; align-items: center; gap: 8px;">
           <span>&larr;</span> Kembali ke Beranda
        </a>
    </div>

    <div style="background: white; border-radius: 15px; border: 1px solid #e0e0e0; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #fafafa; border-bottom: 2px solid #eee;">
                    <th style="padding: 18px 25px; color: #666; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">Informasi Pengguna</th>
                    <th style="padding: 18px 25px; color: #666; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">Kelas</th>
                    <th style="padding: 18px 25px; color: #666; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">Role</th>
                    <th style="padding: 18px 25px; color: #666; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['users'] as $u) : ?>
                <tr style="border-bottom: 1px solid #f8f8f8; transition: 0.2s;" onmouseover="this.style.background='#fcfcfc'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 15px 25px;">
                        <div style="font-weight: 600; color: #333;"><?= $u['nama']; ?></div>
                        <div style="font-size: 12px; color: #999;"><?= $u['email']; ?></div>
                    </td>
                    <td style="padding: 15px 25px;">
                        <?php if($u['role'] === 'admin'): ?>
                            <span style="color: #ccc; font-style: italic;">Sistem</span>
                        <?php else: ?>
                            <span style="font-weight: 500; color: #444;"><?= $u['kelas']; ?></span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 15px 25px;">
                        <?php 
                            $bg = '#eee'; $color = '#666';
                            if($u['role'] === 'admin') { $bg = '#ffebee'; $color = '#c62828'; }
                            elseif($u['role'] === 'guru') { $bg = '#e3f2fd'; $color = '#1565c0'; }
                            else { $bg = '#e8f5e9'; $color = '#2e7d32'; }
                        ?>
                        <span style="padding: 5px 12px; border-radius: 50px; font-size: 11px; font-weight: 800; background: <?= $bg; ?>; color: <?= $color; ?>; text-transform: uppercase;">
                            <?= $u['role']; ?>
                        </span>
                    </td>
                    <td style="padding: 15px 25px; text-align: center;">
                        <?php if($u['id'] != $_SESSION['user_id']) : ?>
                            <a href="<?= BASEURL; ?>/index.php?url=Admin/hapusUser/<?= $u['id']; ?>" 
                               onclick="return confirm('Yakin ingin menghapus user ini? Data tidak bisa dikembalikan.')" 
                               style="background: #fff; border: 1px solid #ffcdd2; color: #d32f2f; padding: 7px 14px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; transition: 0.2s;">
                               🗑️ Hapus
                            </a>
                        <?php else : ?>
                            <span style="font-size: 12px; color: #bbb; font-style: italic;">(Akun Anda)</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Tambahan agar tombol kembali berubah warna saat di-hover */
    a[href*="TopicsControllers"]:hover {
        background: #f0f0f0 !important;
        border-color: #ccc !important;
    }
</style>