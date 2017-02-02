<?php

class user
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM users";
        $query = $this->db->prepare($query);
        $query->execute();
        return $query->fetchAll();
    }

    public function getUser($id) {
        $query = "SELECT name, email, avatar FROM users WHERE id = ?";
        $query = $this->db->prepare($query);
        $query->execute([$id]);
        return $query->fetch();
    }
}