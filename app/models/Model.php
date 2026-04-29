<?php

class Model
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllUsers()
    {
        $this->db->query("SELECT * FROM users ORDER BY role ASC, nama ASC");
        return $this->db->resultSet();
    }

    public function deleteUser($id)
    {
        $this->db->query("DELETE FROM users WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->execute();
    }


    public function getUserByEmail($email)
    {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind('email', $email);
        return $this->db->single();
    }

    public function getDaftarKelas()
    {
        $this->db->query("SELECT * FROM daftar_kelas ORDER BY 
                      SUBSTRING_INDEX(SUBSTRING_INDEX(nama_kelas, ' ', 2), ' ', -1) DESC, 
                      nama_kelas ASC");
        return $this->db->resultSet();
    }

    public function tambahDataUser($data)
    {
        $query = "INSERT INTO users (nama, email, password, role, kelas) 
              VALUES (:nama, :email, :password, :role, :kelas)";

        $this->db->query($query);
        $this->db->bind('nama', $data['nama']);
        $this->db->bind('email', $data['email']);

        // Hash password sebelum disimpan
        $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->db->bind('password', $password_hash);

        // Paksa role menjadi siswa dan ambil kelas dari form
        $this->db->bind('role', 'siswa');
        $this->db->bind('kelas', $data['kelas']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    // Tambahkan juga fungsi ini untuk mengambil data user berdasarkan ID secara lengkap
    public function getUserById($id)
    {
        $this->db->query("SELECT * FROM users WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getAllTopicsByKelas($kelasSiswa)
    {
        if ($_SESSION['role'] == 'admin') {
            $this->db->query("SELECT topics.*, users.nama, users.role 
                          FROM topics 
                          JOIN users ON topics.user_id = users.id 
                          ORDER BY topics.created_at DESC");
        } else {
            $this->db->query("SELECT topics.*, users.nama, users.role 
                          FROM topics 
                          JOIN users ON topics.user_id = users.id 
                          WHERE topics.target_kelas = :kelas 
                          OR topics.target_kelas = 'Umum'
                          ORDER BY topics.created_at DESC");
            $this->db->bind('kelas', $kelasSiswa);
        }

        return $this->db->resultSet();
    }

    public function getAllTopics()
    {
        $this->db->query("SELECT topics.*, users.nama FROM topics 
                          JOIN users ON topics.user_id = users.id 
                          ORDER BY topics.id DESC");
        return $this->db->resultSet();
    }

    public function getTopicById($id)
    {
        $this->db->query("SELECT topics.*, users.nama, users.role 
                      FROM topics 
                      JOIN users ON topics.user_id = users.id 
                      WHERE topics.id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function tambahDataTopic($data)
    {
        $query = "INSERT INTO topics (judul, deskripsi, target_kelas, lampiran, user_id) 
                  VALUES (:judul, :deskripsi, :target_kelas, :lampiran, :user_id)";
        $this->db->query($query);
        $this->db->bind('judul', $data['judul']);
        $this->db->bind('deskripsi', $data['deskripsi']);
        $this->db->bind('target_kelas', $data['target_kelas']);
        $this->db->bind('lampiran', $data['lampiran']);
        $this->db->bind('user_id', $data['user_id']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusDataTopic($id)
    {
        $this->db->query("DELETE FROM reactions WHERE reply_id IN (SELECT id FROM replies WHERE topic_id = :id)");
        $this->db->bind('id', $id);
        $this->db->execute();

        $this->db->query("DELETE FROM replies WHERE topic_id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();

        $this->db->query("DELETE FROM topics WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function getRepliesByTopicId($topic_id)
    {
        $this->db->query("SELECT replies.*, users.nama, users.role 
                      FROM replies 
                      JOIN users ON replies.user_id = users.id 
                      WHERE replies.topic_id = :topic_id 
                      ORDER BY replies.created_at ASC");
        $this->db->bind('topic_id', $topic_id);
        return $this->db->resultSet();
    }

    public function tambahKomentar($data)
    {
        $query = "INSERT INTO replies (topic_id, user_id, isi_komentar) VALUES (:topic_id, :user_id, :isi)";
        $this->db->query($query);
        $this->db->bind('topic_id', $data['topic_id']);
        $this->db->bind('user_id', $data['user_id']);
        $this->db->bind('isi', $data['isi_komentar']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusKomentar($id, $user_id)
    {
        $this->db->query("DELETE FROM reactions WHERE reply_id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();

        $this->db->query("DELETE FROM replies WHERE id = :id AND user_id = :user_id");
        $this->db->bind('id', $id);
        $this->db->bind('user_id', $user_id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function giveReaction($reply_id, $user_id, $emoji)
    {
        $this->db->query("SELECT id, emoji FROM reactions WHERE reply_id = :rid AND user_id = :uid");
        $this->db->bind('rid', $reply_id);
        $this->db->bind('uid', $user_id);
        $existing = $this->db->single();

        if ($existing) {
            if ($existing['emoji'] === $emoji) {
                $this->db->query("DELETE FROM reactions WHERE id = :id");
                $this->db->bind('id', $existing['id']);
            } else {
                $this->db->query("UPDATE reactions SET emoji = :emoji WHERE id = :id");
                $this->db->bind('emoji', $emoji);
                $this->db->bind('id', $existing['id']);
            }
        } else {
            $this->db->query("INSERT INTO reactions (reply_id, user_id, emoji) VALUES (:rid, :uid, :emoji)");
            $this->db->bind('rid', $reply_id);
            $this->db->bind('uid', $user_id);
            $this->db->bind('emoji', $emoji);
        }
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function countReactions($reply_id, $emoji)
    {
        $this->db->query("SELECT COUNT(*) as total FROM reactions WHERE reply_id = :rid AND emoji = :emoji");
        $this->db->bind('rid', $reply_id);
        $this->db->bind('emoji', $emoji);
        return $this->db->single()['total'];
    }

    public function getReactionsByReplyId($reply_id)
    {
        $this->db->query("SELECT emoji, COUNT(*) as jumlah FROM reactions WHERE reply_id = :rid GROUP BY emoji");
        $this->db->bind('rid', $reply_id);
        return $this->db->resultSet();
    }

    public function registerUser($data)
    {
        $query = "INSERT INTO users (nama, email, password, role, kelas) 
              VALUES (:nama, :email, :password, :role, :kelas)";

        $this->db->query($query);
        $this->db->bind('nama', $data['nama']);
        $this->db->bind('email', $data['email']);

        // Hash password demi keamanan
        $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->db->bind('password', $password_hash);

        $this->db->bind('role', $data['role']);
        $this->db->bind('kelas', $data['kelas']);

        $this->db->execute();
        return $this->db->rowCount();
    }
}