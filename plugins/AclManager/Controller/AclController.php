<?php

 App::uses('AclExtras', 'AclExtras.Lib');

class AclController extends AclManagerAppController {

	public $paginate = array();
	protected $_authorizer = null;
	protected $acos = array();

	public function beforeFilter()
	{
		parent::beforeFilter();

		$this->AclExtras = new AclExtras();
		$this->AclExtras->startup($this);

		/**
		 * Loading required Model
		 */
		$aros = Configure::read('AclManager.models');
		foreach ($aros as $aro) {
			$this->loadModel($aro);
		}
		
		/**
		 * Pagination
		 */
		$aros = Configure::read('AclManager.aros');
		foreach ($aros as $aro) {
			$limit = Configure::read("AclManager.{$aro}.limit");
			$limit = empty($limit) ? 4 : $limit;
			$this->paginate[$this->{$aro}->alias] = array(
				'recursive' => -1,
				'limit' => $limit
			);
		}
		$this->layout = 'default_bootstrap';
	}


	public function index() {
	}

	public function permissions() {
		// Saving permissions
		if ($this->request->is('post') || $this->request->is('put')) {
			$perms =  isset($this->request->data['Perms']) ? $this->request->data['Perms'] : array();
			foreach ($perms as $aco => $aros) {
				$action = str_replace(":", "/", $aco);
				foreach ($aros as $node => $perm) {
					list($model, $id) = explode(':', $node);
					$node = array('model' => $model, 'foreign_key' => $id);
					if ($perm == 'allow') {
						$this->Acl->allow($node, $action);
					}
					elseif ($perm == 'inherit') {
						$this->Acl->inherit($node, $action);
					}
					elseif ($perm == 'deny') {
						$this->Acl->deny($node, $action);
					}
				}
			}
			$result = array('status' => 1, 'message' => 'Updated !');
			$this->set('result', $result);
			$this->set('_serialize', 'result');
			$this->_stop();
		}
		
		$model = isset($this->request->params['named']['aro']) ? $this->request->params['named']['aro'] : null;
		if (!$model || !in_array($model, Configure::read('AclManager.aros'))) {
			$model = Configure::read('AclManager.aros');
			$model = $model[0];
		}

		$Aro = $this->{$model};
		$aros = $this->paginate($Aro->alias);
		$permKeys = $this->_getKeys();
		
		/**
		 * Build permissions info
		 */
		$this->acos = $acos = $this->Acl->Aco->find('all', array('order' => 'Aco.lft ASC', 'recursive' => 1));
		$perms = array();
		$parents = array();
		foreach ($acos as $key => $data) {
			$aco =& $acos[$key];
			$aco = array('Aco' => $data['Aco'], 'Aro' => $data['Aro'], 'Action' => array());
			$id = $aco['Aco']['id'];
			
			// Generate path
			if ($aco['Aco']['parent_id'] && isset($parents[$aco['Aco']['parent_id']])) {
				$parents[$id] = $parents[$aco['Aco']['parent_id']] . '/' . $aco['Aco']['alias'];
			} else {
				$parents[$id] = $aco['Aco']['alias'];
			}
			$aco['Action'] = $parents[$id];

			// Fetching permissions per ARO
			$acoNode = $aco['Action'];
			foreach($aros as $aro) {
				$aroId = $aro[$Aro->alias][$Aro->primaryKey];
				$evaluate = $this->_evaluate_permissions($permKeys, array('id' => $aroId, 'alias' => $Aro->alias), $aco, $key);
				$perms[str_replace('/', ':', $acoNode)][$Aro->alias . ":" . $aroId . '-inherit'] = $evaluate['inherited'];
				$perms[str_replace('/', ':', $acoNode)][$Aro->alias . ":" . $aroId] = $evaluate['allowed'];
			}
		}

		$this->request->data = array('Perms' => $perms);
		$this->set('aroAlias', $Aro->alias);
		$this->set('aroDisplayField', $Aro->displayField);
		$this->set(compact('acos', 'aros'));
	}
	
	/**
	 * Recursive function to find permissions avoiding slow $this->Acl->check().
	 */
	private function _evaluate_permissions($permKeys, $aro, $aco, $aco_index) { 
		$permissions = Set::extract("/Aro[model={$aro['alias']}][foreign_key={$aro['id']}]/Permission/.", $aco);
		$permissions = array_shift($permissions);		
		
		$allowed = false;
		$inherited = false;
		$inheritedPerms = array();
		$allowedPerms = array();
		
		/**
		 * Manually checking permission
		 * Part of this logic comes from DbAcl::check()
		 */
		foreach ($permKeys as $key) {
			if (!empty($permissions)) {
				if ($permissions[$key] == -1) {
					$allowed = false;
					break;
				} elseif ($permissions[$key] == 1) {
					$allowedPerms[$key] = 1;
				} elseif ($permissions[$key] == 0) {
					$inheritedPerms[$key] = 0;
				}
			} else {
				$inheritedPerms[$key] = 0;
			}
		}
		
		if (count($allowedPerms) === count($permKeys)) {
			$allowed = true;
		} elseif (count($inheritedPerms) === count($permKeys)) {
			if ($aco['Aco']['parent_id'] == null) {
				$this->lookup +=1;
				$acoNode = (isset($aco['Action'])) ? $aco['Action'] : null;
				$aroNode = array('model' => $aro['alias'], 'foreign_key' => $aro['id']);
				$allowed = $this->Acl->check($aroNode, $acoNode);
				$this->acos[$aco_index]['evaluated'][$aro['id']] = array(
					'allowed' => $allowed,
					'inherited' => true
				);
			}
			else {
				/**
				 * Do not use Set::extract here. First of all it is terribly slow, 
				 * besides this we need the aco array index ($key) to cache are result.
				 */
				foreach ($this->acos as $key => $a) {
					if ($a['Aco']['id'] == $aco['Aco']['parent_id']) {
						$parent_aco = $a;
						break;
					}
				}
				// Return cached result if present
				if (isset($parent_aco['evaluated'][$aro['id']])) {
					return $parent_aco['evaluated'][$aro['id']];
				}
				
				// Perform lookup of parent aco
				$evaluate = $this->_evaluate_permissions($permKeys, $aro, $parent_aco, $key);
				
				// Store result in acos array so we need less recursion for the next lookup
				$this->acos[$key]['evaluated'][$aro['id']] = $evaluate;
				$this->acos[$key]['evaluated'][$aro['id']]['inherited'] = true;
				
				$allowed = $evaluate['allowed'];
			}
			$inherited = true;
		}
		
		return array(
			'allowed' => $allowed,
			'inherited' => $inherited,
		);
	}

	/**
	 * Update ACOs
	 * Sets the missing actions in the database
	 */
	public function update_acos()
	{
		$this->AclExtras->aco_update();
		$this->Session->setFlash(sprintf(__("%d ACOs have been created/updated"), $count));
		$this->redirect($this->request->referer());
	}

	/**
	 * Update AROs
	 * Sets the missing AROs in the database
	 */
	public function update_aros() {
	
		// Debug off to enable redirect
		Configure::write('debug', 0);
		
		$count = 0;
		$type = 'Aro';
			
		// Over each ARO Model
		$objects = Configure::read("AclManager.aros");
		foreach ($objects as $object) {
			
			$Model = $this->{$object};

			$items = $Model->find('all');
			foreach ($items as $item) {
	
				$item = $item[$Model->alias];
				$Model->create();
				$Model->id = $item['id'];

				try {
					$node = $Model->node();
				} catch (Exception $e) {
					$node = false;
				}
				
				// Node exists
				if ($node) {
					$parent = $Model->parentNode();
					if (!empty($parent)) {
						$parent = $Model->node($parent, $type);
					}
					$parent = isset($parent[0][$type]['id']) ? $parent[0][$type]['id'] : null;
					
					// Parent is incorrect
					if ($parent != $node[0][$type]['parent_id']) {
						// Remove Aro here, otherwise we've got duplicate Aros
						// TODO: perhaps it would be nice to update the Aro with the correct parent
						$this->Acl->Aro->delete($node[0][$type]['id']);
						$node = null;
					}
				}
				
				// Missing Node or incorrect
				if (empty($node)) {
					
					// Extracted from AclBehavior::afterSave (and adapted)
					$parent = $Model->parentNode();
					if (!empty($parent)) {
						$parent = $Model->node($parent, $type);
					}
					$data = array(
						'parent_id' => isset($parent[0][$type]['id']) ? $parent[0][$type]['id'] : null,
						'model' => $Model->name,
						'foreign_key' => $Model->id
					);
					
					// Creating ARO
					$this->Acl->{$type}->create($data);
					$this->Acl->{$type}->save();
					$count++;
				}
			}
		}
		
		$this->Session->setFlash(sprintf(__("%d AROs have been created"), $count));
		$this->redirect($this->request->referer());
	}

	/**
	 * Gets the action from Authorizer
	 */
	protected function _action($request = array(), $path = '/:plugin/:controller/:action') {
		$plugin = empty($request['plugin']) ? null : Inflector::camelize($request['plugin']) . '/';
		$params = array_merge(array('controller' => null, 'action' => null, 'plugin' => null), $request);
		$request = new CakeRequest(null, false);
		$request->addParams($params);	
		$authorizer = $this->_getAuthorizer();
		return $authorizer->action($request, $path);
	}

	/**
	 * Build ACO node
	 *
	 * @return node
	 */
	protected function _buildAcoNode($alias, $parent_id = null) {
		if (is_array($parent_id)) {
			$parent_id = $parent_id[0]['Aco']['id'];
		}
		$this->Acl->Aco->create(array('alias' => $alias, 'parent_id' => $parent_id));
		$this->Acl->Aco->save();
		return array(array('Aco' => array('id' => $this->Acl->Aco->id)));
	}

	/**
	 * Returns all the Actions found in the Controllers
	 * 
	 * Ignores:
	 * - protected and private methods (starting with _)
	 * - Controller methods
	 * - methods matching Configure::read('AclManager.ignoreActions')
	 * 
	 * @return array('Controller' => array('action1', 'action2', ... ))
	 */
	protected function _getActions() {
		$ignore = Configure::read('AclManager.ignoreActions');
		$methods = get_class_methods('Controller');
		foreach($methods as $method) {
			$ignore[] = $method;
		}
		
		$controllers = $this->_getControllers();
		$actions = array();
		foreach ($controllers as $controller) {
		    
		    list($plugin, $name) = pluginSplit($controller);
			
		    $methods = get_class_methods($name . "Controller");
			$methods = array_diff($methods, $ignore);
			foreach ($methods as $key => $method) {
				if (strpos($method, "_") === 0 || in_array($controller . '/' . $method, $ignore)) {
					unset($methods[$key]);
				}
			}
			$actions[$controller] = $methods;
		}
		
		return $actions;
	}


	/**
	 * Gets the Authorizer object from Auth
	 */
	protected function _getAuthorizer() {
		if (!is_null($this->_authorizer)) {
			return $this->_authorizer;
		}
		$authorzeObjects = $this->Auth->_authorizeObjects;
		foreach ($authorzeObjects as $object) {
			if (!$object instanceOf ActionsAuthorize) {
				continue;
			}
			$this->_authorizer = $object; 
			break;
		}
		if (empty($this->_authorizer)) {
			$this->Session->setFlash(__("ActionAuthorizer could not be found"));
			$this->redirect($this->referer());
		}
		return $this->_authorizer;
	}

	/**
	 * Returns all the controllers from Cake and Plugins
	 * Will only browse loaded plugins
	 *
	 * @return array('Controller1', 'Plugin.Controller2')
	 */
	protected function _getControllers() {
		
		// Getting Cake controllers
		$objects = array('Cake' => array());
		$objects['Cake'] = App::objects('Controller');
		$unsetIndex = array_search("AppController", $objects['Cake']);
		if ($unsetIndex !== false) {
			unset($objects['Cake'][$unsetIndex]);
		}
		
		// App::objects does not return PagesController
		if (!in_array('PagesController', $objects['Cake'])) {
		    array_unshift($objects['Cake'], 'PagesController');
		}
		
		// Getting Plugins controllers
		$plugins = CakePlugin::loaded();
		foreach ($plugins as $plugin) {
			$objects[$plugin] = App::objects($plugin . '.Controller');
			$unsetIndex = array_search($plugin . "AppController", $objects[$plugin]);
			if ($unsetIndex !== false) {
				unset($objects[$plugin][$unsetIndex]);
			}
		}

		// Around each controller
		$return = array();
		foreach ($objects as $plugin => $controllers) {
			$controllers = str_replace("Controller", "", $controllers);
			foreach ($controllers as $controller) {
				if ($plugin !== "Cake") {
					$controller = $plugin . "." . $controller;
				}
				if (App::import('Controller', $controller)) {
					$return[] = $controller;
				}
			}
		}

		return $return;
	}

	/**
	 * Returns permissions keys in Permission schema
	 * @see DbAcl::_getKeys()
	 */
	protected function _getKeys() {
		$keys = $this->Acl->Aro->Permission->schema();
		$newKeys = array();
		$keys = array_keys($keys);
		foreach ($keys as $key) {
			if (!in_array($key, array('id', 'aro_id', 'aco_id'))) {
				$newKeys[] = $key;
			}
		}
		return $newKeys;
	}
	
	/**
	 * Returns an array without the corresponding action
	 */
	protected function _removeActionFromAcos($acos, $action) {
		foreach ($acos as $key => $aco) {
			if ($aco['Aco']['action'] == $action) {
				unset($acos[$key]);
				break;
			}
		}
		return $acos;
	}
}
