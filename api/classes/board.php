<?php

class board
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM boards";
        $query = $this->db->prepare($query);
        $query->execute();
        return $query->fetchAll();
    }

    public function getBoard($id) {
        $query = "SELECT boards.id, boards.name FROM boards WHERE boards.id = ?";
        $query = $this->db->prepare($query);
        $query->execute([$id]);
        $board = $query->fetch();

        $query = "SELECT lanes.name, lanes.order FROM lanes WHERE board_id = ?";
        $query = $this->db->prepare($query);
        $query->execute([$id]);
        $board['lanes'] = $query->fetchAll();

        return $board;
    }
}