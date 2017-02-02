<?php

class sprint
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM sprints";
        $query = $this->db->prepare($query);
        $query->execute();
        return $query->fetchAll();
    }

    public function getSprint($id, story $story) {
        $query = "SELECT ref, board_id, start_date, end_date, points_available, hrs_available FROM sprints WHERE id = ?";
        $query = $this->db->prepare($query);
        $query->execute([$id]);
        $sprint = $query->fetch();

        $sprint['stories'] = $story->getStoriesInSprint($id);

        return $sprint;
    }
}