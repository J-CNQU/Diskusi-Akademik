<?php

class Home extends Controller {
    public function index() {
        $data['judul'] = 'Beranda';
        $this->view('templates/header', $data);
        // Anda bisa membuat file view home/index.php nanti
        echo "<h1>Selamat Datang di Diskusi Akademik</h1>";
        $this->view('templates/footer');
    }
}