<?php

class ClassController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . '/Auth');
            exit;
        }
    }

    public function index() {
        $data['judul'] = 'Daftar Kelas';    
        $data['kelas'] = $this->model('Model')->getClassesByRole($_SESSION['user_id'], $_SESSION['role']);
        
        $this->view('templates/header', $data);
        $this->view('class/index', $data);
        $this->view('templates/footer');
    }

    public function buat() {
        if ($_SESSION['role'] !== 'guru') {
            header('Location: ' . BASEURL . '/ClassController');
            exit;
        }
        
        if ($this->model('Model')->tambahKelas($_POST) > 0) {
            header('Location: ' . BASEURL . '/ClassController');
            exit;
        }
    }
}