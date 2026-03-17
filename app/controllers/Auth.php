<?php

class Auth extends Controller
{
    public function index()
    {
        $data['judul'] = 'Halaman Login';
        $this->view('templates/header', $data);
        $this->view('auth/login', $data);
        $this->view('templates/footer');
    }

    public function signup()
    {
        $data['judul'] = 'Daftar Akun';
        $this->view('templates/header', $data);
        $this->view('auth/signup', $data);
        $this->view('templates/footer');
    }

    public function prosesLogin()
    {
        try {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->model('Model')->getUserByEmail($email);

            if ($user) {

                if (password_verify($password, $user['password'])) {
                    if (session_status() == PHP_SESSION_NONE) session_start();

                    $_SESSION['login'] = true;
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['nama'] = $user['nama'];
                    $_SESSION['role'] = strtolower($user['role']);
                    $_SESSION['kelas'] = $user['kelas']; 

                    header('Location: ' . BASEURL . '/index.php?url=TopicsControllers');
                    exit;
                } else {
    
                    echo "<script>alert('❌ Gagal Login: Password yang Anda masukkan salah!'); window.location.href='" . BASEURL . "/index.php?url=Auth';</script>";
                    exit;
                }
            } else {

                echo "<script>alert('❌ Gagal Login: Email tidak terdaftar! Silakan daftar akun terlebih dahulu.'); window.location.href='" . BASEURL . "/index.php?url=Auth';</script>";
                exit;
            }
        } catch (PDOException $e) {
            $pesanError = addslashes($e->getMessage());
            echo "<script>alert('⚠️ Terjadi Kesalahan Database: {$pesanError}'); window.location.href='" . BASEURL . "/index.php?url=Auth';</script>";
            exit;
        }
    }

    public function prosesRegister()
    {
        try {
            $email = $_POST['email'];

            $cekUser = $this->model('Model')->getUserByEmail($email);

            if ($cekUser) {

                echo "<script>alert('❌ Pendaftaran Gagal: Email {$email} sudah digunakan! Silakan gunakan email lain atau coba Login.'); window.location.href='" . BASEURL . "/index.php?url=Auth/signup';</script>";
                exit;
            }

            if ($this->model('Model')->tambahDataUser($_POST) > 0) {
                echo "<script>alert('✅ Berhasil Mendaftar! Akun Anda sudah aktif, silakan Login.'); window.location.href='" . BASEURL . "/index.php?url=Auth';</script>";
                exit;
            } else {
                echo "<script>alert('❌ Pendaftaran Gagal: Data tidak tersimpan ke sistem.'); window.location.href='" . BASEURL . "/index.php?url=Auth/signup';</script>";
                exit;
            }
            
        } catch (PDOException $e) {
            $pesanError = addslashes($e->getMessage());
            
            if (strpos($pesanError, 'Duplicate entry') !== false) {
                echo "<script>alert('❌ Pendaftaran Gagal: Email ini sudah terdaftar di sistem!'); window.location.href='" . BASEURL . "/index.php?url=Auth/signup';</script>";
            } else {
                echo "<script>alert('⚠️ Sistem Error (Database): {$pesanError}'); window.location.href='" . BASEURL . "/index.php?url=Auth/signup';</script>";
            }
            exit;
        } catch (Exception $e) {
            $pesanError = addslashes($e->getMessage());
            echo "<script>alert('⚠️ Sistem Error: {$pesanError}'); window.location.href='" . BASEURL . "/index.php?url=Auth/signup';</script>";
            exit;
        }
    }
    public function logout()
    {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        $_SESSION = [];
        session_unset();
        session_destroy();
        header('Location: ' . BASEURL . '/index.php?url=Auth');
        exit;
    }
}