<div class="detail-container">
    <article class="topic-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div class="card-tag <?= ($data['topic']['target_kelas'] == 'Umum') ? 'tag-umum' : 'tag-kelas'; ?>">
                <?= ($data['topic']['target_kelas'] == 'Umum') ? '🌍 UMUM' : '📍 KELAS ' . $data['topic']['target_kelas']; ?>
            </div>
            <small class="text-muted">ID: #<?= $data['topic']['id']; ?></small>
        </div>

        <h1 style="margin-top: 15px;"><?= $data['topic']['judul']; ?></h1>
        
        <div class="topic-content" style="font-size: 16px; line-height: 1.6; color: #333; margin: 20px 0;">
            <?= nl2br($data['topic']['deskripsi']); ?>
        </div>

        <?php if (!empty($data['topic']['lampiran'])) : ?>
            <div class="attachment-section" style="margin: 25px 0; padding: 15px; background: #f8f9fa; border: 1px dashed #dee2e6; border-radius: 10px;">
                <p style="margin-bottom: 12px; font-weight: bold; color: #555;">📎 Lampiran Materi:</p>
                <?php 
                    $file = $data['topic']['lampiran'];
                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    $img_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                ?>

                <?php if (in_array($ext, $img_ext)) : ?>
                    <div style="text-align: center;">
                        <img src="<?= BASEURL; ?>/uploads/<?= $file; ?>" 
                             style="max-width: 100%; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); cursor: zoom-in;" 
                             onclick="window.open(this.src)">
                        <p style="font-size: 12px; color: #888; margin-top: 5px;">Klik gambar untuk memperbesar</p>
                    </div>
                <?php else : ?>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="font-size: 30px;">📂</div>
                        <div>
                            <div style="font-weight: bold; font-size: 14px;"><?= $file; ?></div>
                            <a href="<?= BASEURL; ?>/uploads/<?= $file; ?>" target="_blank" 
                               style="display: inline-block; margin-top: 5px; color: #007bff; font-weight: bold; text-decoration: none;">
                               Download / Lihat Dokumen (<?= strtoupper($ext); ?>) &rarr;
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="topic-footer" style="border-top: 1px solid #eee; padding-top: 15px; color: #777; font-size: 13px;">
            Diterbitkan oleh: <strong>Admin/Guru</strong> • 
            📅 <?= date('d M Y, H:i', strtotime($data['topic']['created_at'])); ?>
        </div>
    </article>

    <section class="comment-section">
        <div class="comment-title">💬 Diskusi (<?= count($data['replies']); ?>)</div>

        <?php foreach($data['replies'] as $reply) : ?>
            <div class="comment-bubble <?= ($reply['user_id'] == $_SESSION['user_id']) ? 'my-comment' : ''; ?>" id="reply-<?= $reply['id']; ?>">
                
                <div class="user-avatar"><?= strtoupper(substr($reply['nama'], 0, 1)); ?></div>
                
                <div class="comment-body">
                    <div class="comment-header">
                        <div class="author-info">
                            <strong><?= $reply['nama']; ?></strong>
                            <span class="comment-date"><?= date('H:i', strtotime($reply['created_at'])); ?></span>
                        </div>

                        <div style="display: flex; align-items: center; gap: 10px;">
                            <?php if($reply['user_id'] == $_SESSION['user_id']) : ?>
                                <div class="action-links">
                                    <a href="javascript:void(0)" onclick="showEditForm(<?= $reply['id']; ?>)" style="color: var(--so-blue);">Edit</a>
                                    <a href="<?= BASEURL; ?>/index.php?url=TopicsControllers/hapusReply/<?= $reply['id']; ?>/<?= $data['topic']['id']; ?>" 
                                       style="color: #d0393e;" onclick="return confirm('Hapus komentar ini?')">Hapus</a>
                                </div>
                            <?php endif; ?>

                            <div class="menu-container">
                                <button class="btn-dots" onclick="toggleMenu(<?= $reply['id']; ?>)">⋮</button>
                                <div class="emoji-popup" id="popup-<?= $reply['id']; ?>">
                                    <form action="<?= BASEURL; ?>/index.php?url=TopicsControllers/react" method="post">
                                        <input type="hidden" name="reply_id" value="<?= $reply['id']; ?>">
                                        <input type="hidden" name="topic_id" value="<?= $data['topic']['id']; ?>">
                                        <button name="emoji" value="👍">👍</button>
                                        <button name="emoji" value="❤️">❤️</button>
                                        <button name="emoji" value="😂">😂</button>
                                        <button name="emoji" value="🔥">🔥</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="comment-text" id="text-<?= $reply['id']; ?>"><?= $reply['isi_komentar']; ?></p>

                    <form action="<?= BASEURL; ?>/index.php?url=TopicsControllers/editReply" method="post" 
                          id="form-edit-<?= $reply['id']; ?>" style="display: none; margin-top: 10px;">
                        <input type="hidden" name="id" value="<?= $reply['id']; ?>">
                        <input type="hidden" name="topic_id" value="<?= $data['topic']['id']; ?>">
                        <textarea name="isi_komentar" class="form-control" rows="3"><?= $reply['isi_komentar']; ?></textarea>
                        <div style="margin-top: 10px;">
                            <button type="submit" class="btn-primary-so" style="padding: 5px 15px; font-size: 12px;">Simpan</button>
                            <button type="button" onclick="hideEditForm(<?= $reply['id']; ?>)" class="btn-ghost-so" style="font-size: 12px;">Batal</button>
                        </div>
                    </form>

                    <div class="reaction-display">
                        <?php foreach($reply['reactions'] as $res) : ?>
                            <span class="badge-reaction"><?= $res['emoji']; ?> <?= $res['total']; ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if(count($data['replies']) == 0) : ?>
            <div style="text-align: center; color: #999; padding: 40px 0;">Belum ada diskusi. Mari mulai bercakap!</div>
        <?php endif; ?>
    </section>

    <section class="reply-form-container">
        <h4>Kirim Pertanyaan / Tanggapan</h4>
        <form action="<?= BASEURL; ?>/index.php?url=TopicsControllers/reply" method="post">
            <input type="hidden" name="topic_id" value="<?= $data['topic']['id']; ?>">
            <div class="spacing-row">
                <textarea name="isi_komentar" class="form-control" rows="5" placeholder="Tuliskan pendapat atau pertanyaanmu..." required></textarea>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn-primary-so">Kirim Balasan</button>
                <a href="<?= BASEURL; ?>/index.php?url=TopicsControllers" class="btn-ghost-so">Kembali ke Daftar</a>
            </div>
        </form>
    </section>
</div>

<script>
function toggleMenu(id) {
    const popup = document.getElementById('popup-' + id);
    document.querySelectorAll('.emoji-popup').forEach(p => { 
        if(p !== popup) p.style.display = 'none'; 
    });
    popup.style.display = (popup.style.display === 'block') ? 'none' : 'block';
}

window.onclick = function(e) {
    if (!e.target.matches('.btn-dots')) {
        document.querySelectorAll('.emoji-popup').forEach(p => p.style.display = 'none');
    }
}

function showEditForm(id) {
    document.getElementById('text-' + id).style.display = 'none';
    document.getElementById('form-edit-' + id).style.display = 'block';
}

function hideEditForm(id) {
    document.getElementById('text-' + id).style.display = 'block';
    document.getElementById('form-edit-' + id).style.display = 'none';
}
</script>