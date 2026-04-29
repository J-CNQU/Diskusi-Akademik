<?php

class TopicsControllers extends Controller
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . '/index.php?url=Auth');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Diskusi Akademik';

        $data['user_aktif'] = $this->model('Model')->getUserById($_SESSION['user_id']);

        $kelasSiswa = $data['user_aktif']['kelas'];

        $data['topics'] = $this->model('Model')->getAllTopicsByKelas($kelasSiswa);

        $data['list_kelas'] = $this->model('Model')->getDaftarKelas();

        $this->view('templates/header', $data);
        $this->view('topics/index', $data);
        $this->view('templates/footer');
    }

    public function tambah()
    {
        header('Location: ' . BASEURL . '/index.php?url=TopicsControllers');
        exit;
        $this->index();
    }

    public function detail($id)
    {
        $topic = $this->model('Model')->getTopicById($id);
        $user = $this->model('Model')->getUserById($_SESSION['user_id']);

        if ($_SESSION['role'] !== 'admin' && $topic['target_kelas'] !== $user['kelas'] && $topic['target_kelas'] !== 'Umum') {
            header('Location: ' . BASEURL . '/index.php?url=TopicsControllers');
            exit;
        }

        $data['topic'] = $topic;
        $data['user_aktif'] = $user;
        $data['replies'] = $this->model('Model')->getRepliesByTopicId($id);

        foreach ($data['replies'] as &$reply) {
            $reply['reactions'] = $this->model('Model')->getReactionsByReplyId($reply['id']);
        }

        $data['judul'] = 'Detail: ' . $data['topic']['judul'];
        $this->view('templates/header', $data);
        $this->view('topics/detail', $data);
        $this->view('templates/footer');
    }

    public function simpan()
    {
        $namaLampiran = null;
        if (isset($_FILES['lampiran']) && $_FILES['lampiran']['error'] === 0) {
            $namaUnik = time() . '_' . str_replace(' ', '_', $_FILES['lampiran']['name']);
            if (move_uploaded_file($_FILES['lampiran']['tmp_name'], 'uploads/' . $namaUnik)) {
                $namaLampiran = $namaUnik;
            }
        }

        $data = [
            'judul' => $_POST['judul'],
            'deskripsi' => $_POST['deskripsi'],
            'target_kelas' => $_POST['target_kelas'],
            'lampiran' => $namaLampiran,
            'user_id' => $_SESSION['user_id']
        ];

        if ($this->model('Model')->tambahDataTopic($data) > 0) {
            header('Location: ' . BASEURL . '/index.php?url=TopicsControllers');
            exit;
        }
    }

    public function hapus($id)
    {
        $topic = $this->model('Model')->getTopicById($id);

        if (!empty($topic['lampiran']) && file_exists('uploads/' . $topic['lampiran'])) {
            unlink('uploads/' . $topic['lampiran']);
        }

        if ($this->model('Model')->hapusDataTopic($id) > 0) {
            header('Location: ' . BASEURL . '/index.php?url=TopicsControllers');
            exit;
        }
    }

    public function hapusReply($id, $topic_id)
    {
        $this->model('Model')->hapusKomentar($id, $_SESSION['user_id']);
        header('Location: ' . BASEURL . '/index.php?url=TopicsControllers/detail/' . $topic_id);
        exit;
    }

    public function reply()
    {
        if ($this->model('Model')->tambahKomentar($_POST) > 0) {
            header('Location: ' . BASEURL . '/index.php?url=TopicsControllers/detail/' . $_POST['topic_id']);
            exit;
        }
    }

    public function react()
    {
        $topic_id = $_POST['topic_id'];
        $reply_id = $_POST['reply_id'];
        $user_id = $_SESSION['user_id'];
        $emoji = $_POST['emoji'];

        if ($this->model('Model')->giveReaction($reply_id, $user_id, $emoji) >= 0) {
            header('Location: ' . BASEURL . '/index.php?url=TopicsControllers/detail/' . $topic_id . '#reply-' . $reply_id);
            exit;
        }
    }

}