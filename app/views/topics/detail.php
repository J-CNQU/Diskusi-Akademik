<div class="wrapper" style="padding: 20px; max-width: 800px; margin: auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <a href="<?= BASEURL; ?>/index.php?url=TopicsControllers"
            style="text-decoration: none; color: #666; font-weight: bold; display: flex; align-items: center; gap: 5px;">
            <span style="font-size: 20px;">&larr;</span> Kembali
        </a>
        <?php if ($data['topic']['user_id'] == $_SESSION['user_id'] || strtolower($_SESSION['role']) == 'admin'): ?>
            <a href="<?= BASEURL; ?>/index.php?url=TopicsControllers/hapus/<?= $data['topic']['id']; ?>"
                onclick="return confirm('Hapus topik?')"
                style="color: #dc3545; text-decoration: none; font-weight: bold; font-size: 14px;">🗑️ Hapus Topik</a>
        <?php endif; ?>
    </div>

    <article class="topic-card"
        style="background: #fff; border: 1px solid #eee; border-radius: 16px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px;">
            <div
                style="background: #f0f2f5; padding: 5px 15px; border-radius: 20px; font-size: 11px; font-weight: 800; color: #555;">
                <?= ($data['topic']['target_kelas'] == 'Umum') ? '🌍 UMUM' : '📍 ' . $data['topic']['target_kelas']; ?>
            </div>
            <?php if (isset($data['topic']['role']) && $data['topic']['role'] == 'guru'): ?>
                <div
                    style="background: #e3f2fd; color: #1565c0; padding: 5px 12px; border-radius: 20px; font-size: 10px; font-weight: 800; border: 1px solid #bbdefb;">
                    ✅ GURU TERVERIFIKASI
                </div>
            <?php endif; ?>
        </div>

        <h1 style="margin: 0 0 15px; font-size: 26px; color: #1a1a1a; letter-spacing: -0.5px;">
            <?= $data['topic']['judul']; ?>
        </h1>
        <p style="line-height: 1.7; color: #444; font-size: 16px;"><?= nl2br($data['topic']['deskripsi']); ?></p>

        <?php if (!empty($data['topic']['lampiran'])): ?>
            <div style="margin-top: 25px; border-radius: 12px; overflow: hidden; border: 1px solid #eee;">
                <img src="<?= BASEURL; ?>/uploads/<?= $data['topic']['lampiran']; ?>"
                    style="width: 100%; display: block; cursor: zoom-in;" onclick="window.open(this.src)">
            </div>
        <?php endif; ?>
    </article>

    <section class="comment-area" style="margin-top: 40px;">
        <h3 style="margin-bottom: 25px; color: #222; display: flex; align-items: center; gap: 10px;">
            💬 Diskusi <span
                style="background: #eee; padding: 2px 10px; border-radius: 10px; font-size: 14px;"><?= count($data['replies']); ?></span>
        </h3>

        <?php foreach ($data['replies'] as $reply): ?>
            <div class="comment-bubble <?= ($reply['user_id'] == $_SESSION['user_id']) ? 'my-comment' : ''; ?>"
                id="reply-<?= $reply['id']; ?>"
                style="display: flex; gap: 15px; background: #fff; border: 1px solid #eee; padding: 20px; border-radius: 16px; margin-bottom: 20px; position: relative; transition: 0.3s;">

                <div
                    style="width: 40px; height: 40px; background: <?= (isset($reply['role']) && $reply['role'] == 'guru') ? '#007bff' : '#6c757d'; ?>; color: #fff; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    <?= strtoupper(substr($reply['nama'], 0, 1)); ?>
                </div>

                <div style="flex: 1;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-weight: 800; color: #333; font-size: 14px;"><?= $reply['nama']; ?></span>
                            <?php if (isset($reply['role']) && $reply['role'] == 'guru'): ?>
                                <span title="Guru Terverifikasi" style="color: #007bff; font-size: 12px;">✔️</span>
                            <?php endif; ?>
                            <span
                                style="font-size: 11px; color: #bbb;"><?= date('H:i', strtotime($reply['created_at'])); ?></span>
                        </div>
                        <div style="position: relative;">
                            <button class="btn-dots" onclick="toggleMenu('menu-<?= $reply['id']; ?>')"
                                style="background: none; border: none; cursor: pointer; color: #bbb; font-size: 20px; padding: 5px;">⋮</button>

                            <div id="menu-<?= $reply['id']; ?>" class="emoji-popup"
                                style="display: none; position: absolute; right: 0; top: 35px; background: #fff; border: 1px solid #eee; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-radius: 12px; padding: 10px; z-index: 100; min-width: 150px;">

                                <p
                                    style="font-size: 10px; font-weight: bold; color: #aaa; margin: 0 0 8px 5px; text-transform: uppercase;">
                                    Berikan Reaksi</p>
                                <form action="<?= BASEURL; ?>/index.php?url=TopicsControllers/react" method="post"
                                    style="display: flex; gap: 8px; justify-content: center; margin-bottom: 10px;">
                                    <input type="hidden" name="topic_id" value="<?= $data['topic']['id']; ?>">
                                    <input type="hidden" name="reply_id" value="<?= $reply['id']; ?>">
                                    <button type="submit" name="emoji" value="👍" class="btn-react">👍</button>
                                    <button type="submit" name="emoji" value="❤️" class="btn-react">❤️</button>
                                    <button type="submit" name="emoji" value="😂" class="btn-react">😂</button>
                                    <button type="submit" name="emoji" value="🔥" class="btn-react">🔥</button>
                                </form>

                                <?php if ($reply['user_id'] == $_SESSION['user_id'] || $_SESSION['role'] == 'admin'): ?>
                                    <div style="border-top: 1px solid #f5f5f5; padding-top: 8px;">
                                        <a href="<?= BASEURL; ?>/index.php?url=TopicsControllers/hapusReply/<?= $reply['id']; ?>/<?= $data['topic']['id']; ?>"
                                            onclick="return confirm('Hapus komentar?')"
                                            style="color: #dc3545; font-size: 12px; text-decoration: none; display: block; text-align: center; font-weight: bold;">🗑️
                                            Hapus</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <p style="margin: 8px 0; font-size: 15px; color: #444; line-height: 1.5;">
                        <?= nl2br($reply['isi_komentar']); ?>
                    </p>

                    <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-top: 10px;">
                        <?php if (!empty($reply['reactions'])):
                            foreach ($reply['reactions'] as $react): ?>
                                <div
                                    style="background: #f8f9fa; border: 1px solid #eef0f2; border-radius: 20px; padding: 3px 10px; font-size: 12px; display: flex; align-items: center; gap: 5px; transition: 0.2s;">
                                    <span><?= $react['emoji']; ?></span>
                                    <span style="font-weight: 800; color: #007bff;"><?= $react['jumlah']; ?></span>
                                </div>
                            <?php endforeach; endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <section
        style="margin-top: 30px; background: #fff; border: 1px solid #eee; border-radius: 16px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
        <form action="<?= BASEURL; ?>/index.php?url=TopicsControllers/reply" method="post">
            <input type="hidden" name="topic_id" value="<?= $data['topic']['id']; ?>">
            <input type="hidden" name="user_id" value="<?= $_SESSION['user_id']; ?>">
        </form>
    </section>
</div>

<style>
    .my-comment {
        border-left: 5px solid #007bff !important;
        background-color: #fcfdff !important;
    }

    .btn-react {
        background: #f8f9fa;
        border: 1px solid #eee;
        border-radius: 8px;
        cursor: pointer;
        font-size: 18px;
        padding: 5px;
        transition: 0.2s;
        flex: 1;
    }

    .btn-react:hover {
        transform: translateY(-3px);
        background: #fff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .comment-bubble:hover {
        border-color: #007bff;
    }
</style>

<script>
    function toggleMenu(id) {
        document.querySelectorAll('.emoji-popup').forEach(el => { if (el.id !== id) el.style.display = 'none'; });
        const menu = document.getElementById(id);
        menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
    }
    window.onclick = function (e) {
        if (!e.target.matches('.btn-dots')) {
            document.querySelectorAll('.emoji-popup').forEach(el => el.style.display = 'none');
        }
    }
</script>