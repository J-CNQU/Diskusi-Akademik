<?php

class AuthModels
{
    private $table = 'Users';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getUserByEmail($email)
    {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE email = :email");
        $this->db->bind('email', $email);
        return $this->db->single();
    }

    public function register($data)
    {
        $query = "INSERT INTO Users (nama, email, password, role) VALUES (:nama, :email, :password, 'siswa')";

        $this->db->query($query);
        $this->db->bind('nama', $data['nama']);
        $this->db->bind('email', $data['email']);

        $password_hashed = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->db->bind('password', $password_hashed);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function login($email, $password)
    {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind('email', $email);
        $user = $this->db->single();

        if ($user && $password === $user['password']) {
            return $user;
        }
        return false;
    }
}