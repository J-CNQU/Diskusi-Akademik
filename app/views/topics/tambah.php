<div class="wrapper container-center" style="padding: 40px 20px;">
    <div style="border-top: 1px solid #eee; margin-bottom: 30px;"></div>

    <div class="surface-card" style="max-width: 800px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 12px; border: 1px solid #e0e0e0;">
        <div class="spacing-row" style="margin-bottom: 25px;">
            <h2 style="font-size: 24px; font-weight: 700; color: #333;">Input Data Topik</h2>
            <p style="color: #888; font-size: 14px;">Guru & Admin: Silakan isi materi diskusi di bawah ini.</p>
        </div>

        <form action="<?= BASEURL; ?>/index.php?url=TopicsControllers/simpan" method="post" enctype="multipart/form-data">
            
            <div class="spacing-row" style="margin-bottom: 20px;">
                <label class="input-label" style="display: block; font-weight: bold; margin-bottom: 8px;">Distribusi Kelas (Bidang)</label>
                <select name="target_kelas" class="form-control" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd; background: #fdfdfd;">
                    <option value="Umum">🌍 Umum (Semua Kelas)</option>
                    <?php
                    $tingkat = ['10', '11', '12'];
                    $jurusan = ['BID', 'AKL', 'TKJ'];
                    $nomor = ['1', '2'];
                    foreach ($tingkat as $t) {
                        foreach ($jurusan as $j) {
                            foreach ($nomor as $n) {
                                $val = "$t $j $n";
                                echo "<option value='$val'>Kelas $val</option>";
                            }
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="spacing-row" style="margin-bottom: 20px;">
                <label class="input-label" style="display: block; font-weight: bold; margin-bottom: 8px;">Judul Materi</label>
                <input type="text" name="judul" class="form-control" placeholder="Tuliskan judul materi..." required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd;">
            </div>

            <div class="spacing-row" style="margin-bottom: 20px;">
                <label class="input-label" style="display: block; font-weight: bold; margin-bottom: 8px;">Deskripsi Lengkap</label>
                <textarea name="deskripsi" class="form-control" rows="10" placeholder="Masukkan isi materi diskusi..." required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd; resize: vertical;"></textarea>
            </div>

            <div class="spacing-row" style="margin-bottom: 20px;">
                <label class="input-label" style="display: block; font-weight: bold; margin-bottom: 8px;">Lampiran File (Opsional)</label>
                <input type="file" name="lampiran" class="form-control" style="width: 100%; padding: 8px;">
            </div>

            <div style="display: flex; gap: 15px; align-items: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                <button type="submit" class="btn-primary-so" style="padding: 12px 25px; cursor: pointer;">Publish Sekarang</button>
                <a href="<?= BASEURL; ?>/index.php?url=TopicsControllers" class="btn-ghost-so" style="text-decoration: none; color: #666;">Batalkan</a>
            </div>
        </form>
    </div>
</div>