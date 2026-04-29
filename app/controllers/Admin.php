<?php
class Admin extends Controller {
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        // Proteksi: Jika bukan admin, tendang keluar
        if ($_SESSION['role'] !== 'admin') {
            header('Location: ' . BASEURL . '/index.php?url=TopicsControllers');
            exit;
        }
    }

    public function users() {
        $data['judul'] = 'Manajemen User';
        $data['users'] = $this->model('Model')->getAllUsers();
        $this->view('templates/header', $data);
        $this->view('admin/users', $data);
        $this->view('templates/footer');
    }

    public function hapusUser($id) {
        if($this->model('Model')->deleteUser($id) > 0) {
            header('Location: ' . BASEURL . '/index.php?url=Admin/users');
        }
    }
}