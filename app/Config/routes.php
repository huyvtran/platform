<?php

Router::parseExtensions('rss', 'xml', 'json', 'ajax');

# Load specific routing file per domain
if (!empty($_SERVER['HTTP_HOST'])) {
    $addRoute = APP . 'Config' . DS . 'Routes' . DS . $_SERVER['HTTP_HOST'] . '.php';
    if (file_exists($addRoute)) {
        require_once  $addRoute;
    }
}


Router::connect('/', array('controller' => 'pages', 'action' => 'home'));

# this routing support SDK only ?
Router::connect('/news/:category/:slug',
    array('controller' => 'articles', 'action' => 'view'),
    array(
        'pass' => array('category', 'slug'),
        'slug' => '[^\:]+'
    )
);
# this routing support SDK only ?
Router::connect('/news/*', array('controller' => 'categories', 'action' => 'index'));

Router::connect('/admin', array('controller' => 'administrators', 'action' => 'index', 'admin' => true));

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
require CAKE . 'Config' . DS . 'routes.php';
