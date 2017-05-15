<?php

App::uses('Component', 'Controller');

class SiteComponent extends Component {

	public $components = array(
		'Session', 'Auth', 'Common', 'Cookie'
	);

	public $requiredAction = array();

}