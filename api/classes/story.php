<?php

class story
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAll($filters) {
        $query = "SELECT * FROM stories";
        $query = $this->addFilters($filters, $query);
        $query = $this->db->prepare($query);
        $query->execute();
        return $query->fetchAll();
    }

    public function getStoriesInSprint($sprintId) {
        $query = "SELECT * FROM stories WHERE sprint_id = ?";
        $query = $this->db->prepare($query);
        $query->execute([$sprintId]);
        return $query->fetchAll();
    }

    public function getStory($id) {
        $query = "SELECT title, `desc`, points, descLong, sprint_id, priority FROM stories WHERE stories.id = ? ORDER BY `priority` ASC";
        $query = $this->db->prepare($query);
        $query->execute([$id]);
        $story = $query->fetch();

        $query = "SELECT `desc`, points, pointsRemaining, `order` FROM tasks WHERE story_id = ? ORDER BY `order` ASC";
        $query = $this->db->prepare($query);
        $query->execute([$id]);
        $story['tasks'] = $query->fetchAll();

        return $story;
    }

    private function addFilters($filters, $query) {
        if (in_array(true, $filters)) {
            $query .= ' WHERE ';
        }
        if ($filters['backlog']) {
            $query .= 'sprint_id IS NULL';
        }
        return $query;
    }
}