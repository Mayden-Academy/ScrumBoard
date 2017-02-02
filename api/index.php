<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

spl_autoload_register(function ($classname) {
    require ("classes/" . $classname . ".php");
});

$container = new \Slim\Container;
$container['db'] = function() {
    $pdo = new PDO("mysql:host=192.168.20.56;dbname=ScrumBoard", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};
$container['settings']['displayErrorDetails'] = true;
$app = new \Slim\App($container);

$app->get('/', function (Request $request, Response $response) {
    return $response->withJSON([]);
});

$app->get('/board', function (Request $request, Response $response) {
    $board = new board($this->db);
    $boards = $board->getAll();
    return $response->withJSON($boards);
});

$app->get('/board/{id}', function (Request $request, Response $response, $args) {
    $board = new board($this->db);
    $boards = $board->getBoard($args['id']);
    return $response->withJSON($boards);
});

$app->get('/story', function (Request $request, Response $response) {
    $params = $request->getQueryParams();
    $filters = [
        'backlog' => false
    ];
    foreach($filters as $filter => $v) {
        if (array_key_exists($filter, $params) && $params[$filter]) {
            $filters[$filter] = true;
        }
    }
    $story = new story($this->db);
    $stories = $story->getAll($filters);
    return $response->withJSON($stories);
});

$app->get('/story/{id}', function (Request $request, Response $response, $args) {
    $story = new story($this->db);
    $stories = $story->getStory($args['id']);
    return $response->withJSON($stories);
});

$app->get('/task/{id}', function (Request $request, Response $response, $args) {
    $task = new task($this->db);
    $tasks = $task->getTask($args['id']);
    return $response->withJSON($tasks);
});

$app->get('/sprint', function (Request $request, Response $response, $args) {
    $sprint = new sprint($this->db);
    $sprints = $sprint->getAll();
    return $response->withJSON($sprints);
});

$app->get('/sprint/{id}', function (Request $request, Response $response, $args) {
    $sprint = new sprint($this->db);
    $story = new story($this->db);
    $sprintData = $sprint->getSprint($args['id'], $story);
    return $response->withJSON($sprintData);
});

$app->get('/user', function (Request $request, Response $response, $args) {
    $user = new user($this->db);
    $users = $user->getAll();
    return $response->withJSON($users);
});

$app->get('/user/{id}', function (Request $request, Response $response, $args) {
    $user = new user($this->db);
    $userData = $user->getUser($args['id']);
    return $response->withJSON($userData);
});

$app->run();