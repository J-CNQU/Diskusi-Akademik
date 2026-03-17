<?php

class Model
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function giveReaction($reply_id, $user_id, $emoji)
    {
        // 1. Cek apakah user sudah memberikan emoji yang SAMA pada reply ini
        $this->db->query('SELECT * FROM reactions WHERE reply_id = :rid AND user_id = :uid');
        $this->db->bind('rid', $reply_id);
        $this->db->bind('uid', $user_id);
        $existing = $this->db->single();

        if ($existing) {
            if ($existing['emoji'] == $emoji) {
                // Jika emojinya sama, maka HAPUS (Toggle Off)
                $this->db->query('DELETE FROM reactions WHERE reply_id = :rid AND user_id = :uid');
                $this->db->bind('rid', $reply_id);
                $this->db->bind('uid', $user_id);
                return $this->db->execute();
            } else {
                // Jika emojinya beda, maka UPDATE ke emoji baru
                $this->db->query('UPDATE reactions SET emoji = :emo WHERE reply_id = :rid AND user_id = :uid');
                $this->db->bind('rid', $reply_id);
                $this->db->bind('uid', $user_id);
                $this->db->bind('emo', $emoji);
                return $this->db->execute();
            }
        } else {
            // Jika belum ada reaksi sama sekali, INSERT baru
            $this->db->query('INSERT INTO reactions (reply_id, user_id, emoji) VALUES (:rid, :uid, :emo)');
            $this->db->bind('rid', $reply_id);
            $this->db->bind('uid', $user_id);
            $this->db->bind('emo', $emoji);
            return $this->db->execute();
        }
    }

    public function getReactionsByReplyId($reply_id)
    {
        $this->db->query('SELECT emoji, COUNT(*) as total FROM reactions WHERE reply_id = :rid GROUP BY emoji');
        $this->db->bind('rid', $reply_id);
        return $this->db->resultSet();
    }

    public function getRepliesByTopicId($topic_id)
    {
        $this->db->query('SELECT replies.*, users.nama FROM replies 
                          JOIN users ON replies.user_id = users.id 
                          WHERE replies.topic_id = :topic_id ORDER BY replies.created_at ASC');
        $this->db->bind('topic_id', $topic_id);
        $replies = $this->db->resultSet();

        foreach ($replies as &$reply) {
            $reply['reactions'] = $this->getReactionsByReplyId($reply['id']);
        }
        return $replies;
    }

    public function tambahKomentar($data)
    {
        $query = "INSERT INTO replies (topic_id, user_id, isi_komentar) VALUES (:topic_id, :user_id, :isi_komentar)";
        $this->db->query($query);
        $this->db->bind('topic_id', $data['topic_id']);
        $this->db->bind('user_id', $_SESSION['user_id']);
        $this->db->bind('isi_komentar', $data['isi_komentar']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function editKomentar($data)
    {
        $this->db->query('UPDATE replies SET isi_komentar = :isi WHERE id = :id AND user_id = :user_id');
        $this->db->bind('isi', $data['isi_komentar']);
        $this->db->bind('id', $data['id']);
        $this->db->bind('user_id', $_SESSION['user_id']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusKomentar($id, $user_id)
    {
        $this->db->query('DELETE FROM replies WHERE id = :id AND user_id = :user_id');
        $this->db->bind('id', $id);
        $this->db->bind('user_id', $user_id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getUserByEmail($email)
    {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind('email', $email);
        return $this->db->single();
    }

    // Tambah User (Sign Up)
    public function tambahDataUser($data)
    {
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $query = "INSERT INTO users (nama, email, password, role, kelas) 
              VALUES (:nama, :email, :password, :role, :kelas)";

        $this->db->query($query);
        $this->db->bind('nama', $data['nama']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('password', $password);
        $this->db->bind('role', 'siswa'); // Otomatis siswa
        $this->db->bind('kelas', $data['kelas']); // Ambil dari dropdown signup

        $this->db->execute();
        return $this->db->rowCount();
    }

    // Tambah Topik (Post Materi)
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

    public function getAllTopics()
    {
        $this->db->query('SELECT * FROM topics');
        return $this->db->resultSet();
    }

    public function getTopicById($id)
    {
        $this->db->query('SELECT * FROM topics WHERE id=:id');
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function hapusDataTopic($id)
    {
        $query = "DELETE FROM topics WHERE id = :id";
        $this->db->query($query);
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getAllClasses()
    {
        $this->db->query('SELECT * FROM classes');
        return $this->db->resultSet();
    }
}