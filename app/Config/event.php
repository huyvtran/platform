<?php

App::uses('CakeEventManager', 'Event');
App::uses('CakeEventListener', 'Event');

CakeEventManager::instance()->attach('beforeThemeCallback', 'Controller.beforeTheme');
CakeEventManager::instance()->attach('afterThemeCallback', 'Controller.afterTheme');
CakeEventManager::instance()->attach('beforeGameCallback', 'Controller.beforeGame');
CakeEventManager::instance()->attach('afterGameCallback', 'Controller.afterGame');

# callback for per action per theme , create flexible theme.
function beforeThemeCallback($event)
{
	$Controller = $event->subject();
	if ($Controller->request->prefix != true) {
		$theme = $Controller->Common->getTheme();
		$file = APP . 'Event' . DS . 'Themed' . DS . $theme . DS . $Controller->name . '.php';
		if (file_exists($file)) {
			require_once $file;
			$class = $Controller->name . 'Theme';
			if (method_exists($class, 'before_' . $Controller->action)) {
				$Object = new $class;
				$Object->{'before_' . $Controller->action}($Controller);
			}
		}
	}
}

function afterThemeCallback($event)
{
	$Controller = $event->subject();
	$file = APP . 'Event' . DS . 'Themed' . DS . $Controller->theme . DS . $Controller->name . '.php';

	if (file_exists($file)) {
		require_once $file;
		$class = $Controller->name . 'Theme';
		if (method_exists($class, 'after_' . $Controller->action)) {
			$Object = new $class;
			$Object->{'after_' . $Controller->action}($Controller);
		}
	}
}

# callback for per action per SDK , create flexible SDK.
function beforeGameCallback($event)
{
	$Controller = $event->subject();
	if ($Controller->request->prefix != true) {
		$game = $Controller->Common->currentGame();
		$file = APP . 'Event' . DS . 'Games' . DS . $game['slug'] . DS . $Controller->name . '.php';
		if (file_exists($file)) {
			require_once $file;
			$class = $Controller->name . 'Game';
			if (method_exists($class, 'before_' . $Controller->action)) {
				$Object = new $class;
				$Object->{'before_' . $Controller->action}($Controller);
			}
		}
	}
}

function afterGameCallback($event)
{
	$Controller = $event->subject();
	$game = $Controller->Common->currentGame();
	$file = APP . 'Event' . DS . 'Games' . DS . $game['slug'] . DS . $Controller->name . '.php';
	if (file_exists($file)) {
		require_once $file;
		$class = $Controller->name . 'Game';
		if (method_exists($class, 'after_' . $Controller->action)) {
			$Object = new $class;
			$Object->{'after_' . $Controller->action}($Controller);
		}
	}
}