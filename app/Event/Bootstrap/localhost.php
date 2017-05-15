<?php

if (env('REQUEST_URI') == '/' && empty($_GET['test'])) {
    header('Location: /landing');
    exit();
}

if (strpos(env('REQUEST_URI'), 'landing2') !== false) {
    header('Location: /cardrace');
    exit();
}

if ($_SERVER['REQUEST_URI'] == '/download') {
    Router::connect('/download', array('controller' => 'games', 'action' => 'view', 'one-piece'));
    require_once CAKE . 'Config' . DS . 'routes.php';
}

if ($_SERVER['REQUEST_URI'] == '/SnowmanHunting') {
    Router::connect('/SnowmanHunting', array('controller' => 'pages', 'action' => 'landing2'));
    require_once CAKE . 'Config' . DS . 'routes.php';
}

if ($_SERVER['REQUEST_URI'] == '/plf/monkeyrace') {
    Router::connect('/monkeyrace', array('controller' => 'pages', 'action' => 'display', 'monkeyrace'));
    require_once CAKE . 'Config' . DS . 'routes.php';
}