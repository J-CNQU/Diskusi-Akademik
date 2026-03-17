<?php

class TopicsControllers extends Controller
{
    public function __construct()
    {

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }


        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . '/index.php?url=Auth');
            exit;
        }
    }


    public function index()
    {
        $data['judul'] = 'Daftar Topik Diskusi';
        $data['topics'] = $this->model('Model')->getAllTopics();

        $this->view('templates/header', $data);
        $this->view('topics/index', $data);
        $this->view('templates/footer');
    }


    public function detail($id)
    {
        $data['judul'] = 'Detail Topik & Diskusi';
        $data['topic'] = $this->model('Model')->getTopicById($id);
        $data['replies'] = $this->model('Model')->getRepliesByTopicId($id);

        $this->view('templates/header', $data);
        $this->view('topics/detail', $data);
        $this->view('templates/footer');
    }


    public function tambah()
    {

        $role = isset($_SESSION['role']) ? strtolower($_SESSION['role']) : '';

        if ($role !== 'admin' && $role !== 'guru') {

            header('Location: ' . BASEURL . '/index.php?url=TopicsControllers');
            exit;
        }

        $data['judul'] = 'Buat Topik';
        $data['classes'] = $this->model('Model')->getAllClasses();

        $this->view('templates/header', $data);
        $this->view('topics/tambah', $data);
        $this->view('templates/footer');
    }


    public function simpan()
    {
        try {
            
            $namaLampiran = null;
            if (isset($_FILES['lampiran']) && $_FILES['lampiran']['error'] === 0) {
                $namaLampiran = $_FILES['lampiran']['name'];
                
            }

            
            $data = [
                'judul' => $_POST['judul'],
                'deskripsi' => $_POST['deskripsi'],
                'target_kelas' => $_POST['target_kelas'], 
                'lampiran' => $namaLampiran,
                'user_id' => $_SESSION['user_id']
            ];

            if ($this->model('Model')->tambahDataTopic($data) > 0) {
                echo "<script>alert('✅ Topik berhasil diterbitkan!'); window.location.href='" . BASEURL . "/index.php?url=TopicsControllers';</script>";
                exit;
            } else {
                echo "<script>alert('❌ Gagal menyimpan topik.'); window.location.href='" . BASEURL . "/index.php?url=TopicsControllers/tambah';</script>";
                exit;
            }

        } catch (Exception $e) {
            $error = addslashes($e->getMessage());
            echo "<script>alert('⚠️ Terjadi Kesalahan: {$error}'); window.location.href='" . BASEURL . "/index.php?url=TopicsControllers/tambah';</script>";
            exit;
        }
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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $reply_id = $_POST['reply_id'];
            $topic_id = $_POST['topic_id'];
            $emoji = $_POST['emoji'];
            $user_id = $_SESSION['user_id'];

            $this->model('Model')->giveReaction($reply_id, $user_id, $emoji);


            header('Location: ' . BASEURL . '/index.php?url=TopicsControllers/detail/' . $topic_id . '#reply-' . $reply_id);
            exit;
        }
    }


    public function editReply()
    {
        if ($this->model('Model')->editKomentar($_POST) > 0) {
            header('Location: ' . BASEURL . '/index.php?url=TopicsControllers/detail/' . $_POST['topic_id']);
            exit;
        } else {

            header('Location: ' . BASEURL . '/index.php?url=TopicsControllers/detail/' . $_POST['topic_id']);
            exit;
        }
    }


    public function hapusReply($id, $topic_id)
    {
        if ($this->model('Model')->hapusKomentar($id, $_SESSION['user_id']) > 0) {
            header('Location: ' . BASEURL . '/index.php?url=TopicsControllers/detail/' . $topic_id);
            exit;
        }
    }


    public function hapus($id)
    {
        if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'guru')) {
            $this->model('Model')->hapusDataTopic($id);
        }

        header('Location: ' . BASEURL . '/index.php?url=TopicsControllers');
        exit;
    }
}