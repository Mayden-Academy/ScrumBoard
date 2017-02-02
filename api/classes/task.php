<?php

class task
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM tasks";
        $query = $this->db->prepare($query);
        $query->execute();
        return $query->fetchAll();
    }

    public function getTask($id) {
        $query = "SELECT story_id, `desc`, points, pointsRemaining, lane_id, `order` FROM tasks WHERE id = ?";
        $query = $this->db->prepare($query);
        $query->execute([$id]);
        $task = $query->fetch();

        $task['users'] = $this->getTaskUsers($id);

        return $task;
    }

    public function getTaskUsers($taskId) {
        $query = "SELECT `name`, email, avatar FROM users LEFT JOIN task_users ON task_users.user_id = users.id WHERE task_users.task_id = ?";
        $query = $this->db->prepare($query);
        $query->execute([$taskId]);
        return $query->fetchAll();
    }
}