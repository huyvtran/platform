<?php

App::uses('Controller', 'Controller');
require_once ROOT . DS . 'Vendor' . DS . 'mobiledetect' . DS . 'mobiledetectlib' . DS . 'Mobile_Detect.php';

if (Configure::read('debug') != 0) {
	App::uses('FireCake', 'DebugKit.Lib');
}

function n($num, $decimal = 0)
{
	return number_format($num, $decimal, '.', ',');
}

class AppController extends Controller {

	public $helpers = array(
		'Session',
		'Html',
		'Form',
		'Js',
		'Time',
		'Paginator',
		'Cache',
		'Nav',
		'Highchart'
	);
	public $components = array(
		'Auth',
		'Cookie',
		'Session',
		'Common',
		'RequestHandler',
		'Acl',
		'Paginator'
		// ,'DebugKit.Toolbar'
	);

	public $menu = array(
		'Revenue' => array(
			'categories' => array(
				'Revenue (Daily)' => '/Revenues/index',
                'Revenue (Countries)' => '/Revenues/country',
		 	),
			'activeMenu' => array('revenues')
		),
		'DAU' => array(
			'categories' => array(
				'DAU (Daily)' => '/daus/index',
				'MAU (Monthly)' => '/daus/monthly',
				'QAU (Quarter)' => '/daus/quarter',
				'DAU By Countries' => '/daus/country',
			),
			'activeMenu' => array('daus')
		),
		'NRU' => array(
			'categories' => array(
				'NRU (Daily)' => '/nius/index',
                'NRU (Countries)' => '/nius/country',
			),
			'activeMenu' => array('nius')
		),
		'Retention' => array(
			'categories' => array(
				'Retention' => '/Retentions/index',
			),
			'activeMenu' => array('Retentions')
		),
        'Arpu' => array(
            'categories' => array(
                'Arpu (Daily)'  => '/Arpu/index',
                'Arppu (Daily)' => '/Arppu/index',
            ),
            'activeMenu' => array('Arpu')
        ),
        'Debug' => array(
            'categories' => array(
                'Logs - Local'			=> '/admin/Administrators/readLog',
                'Logs - Clear'			=> '/admin/Administrators/clearLogs',
            ),
            'activeMenu' => array('debug')
        ),
	);


	public function beforeFilter()
	{
		$this->loadModel('Game');
		if (
			# Nếu request từ app có token, mà ko có app_key thì báo lỗi , để tránh trường hợp app quên send header đúng chuẩn
			!$this->request->header('app') && $this->request->header('token')
		) {
			CakeLog::info(print_r($_SERVER, true));
			throw new BadRequestException("Hello, header don't have appkey");
		}
		$this->__setCookie();
		$this->__configAuth();

		if (empty($this->request->params['requested'])){
			$this->__cookieAuth();
		}
		if (!empty($this->request->data)) {
			$this->request->data = $this->__trimData($this->request->data);
		}
		if ($this->Auth->loggedIn()) {
            if($this->Auth->user('username') == 'quanvh'){
                Configure::write('debug', 2);
            }

			if (	in_array($this->Auth->user('role'), array('Admin'))
				||  (	in_array($this->Auth->user('role'), array('Developer')) 
					&&	!in_array(strtolower($this->request->params['controller']), array('revenues'))
					)
				||	(	in_array($this->Auth->user('role'), array('Developer')) 
					&&	(time() - $this->Auth->user('created')) > 60 * 60 * 24 * 50
					)
			) {
				$this->loadModel('Game');
				$conditions = array('Game.status' => 1);

				if (isset($this->request->query['group']) && $this->request->query['group'] != '') {
					$conditions = array_merge($conditions, array('group' => $this->request->query['group']));
				}

				$game = $this->Game->find('list', array(
					'fields' => array('id', 'id'),
					'conditions' => $conditions,
				));
				$this->Session->write('Auth.User.permission_game_stats', $game);
			} else {
				$this->loadModel('Permission');
				$group = '';

				if (isset($this->request->query['group']) && $this->request->query['group'] != '') {
					$group = $this->request->query['group'];
				}

				if (!in_array($this->Auth->user('role'), array('Stats'))) {
					$permissions = $this->Permission->getRightIds('Game', $this->Auth->user('id'), 'Stats', '', $group);
				} else {
					$permissions = $this->Permission->find('list', array(
						'conditions' => array(
							'user_id' => $this->Auth->user('id'),
							'model'   => 'Game',
							'type'    => 'Stats',
							'access' => 1
						),
						'recursive' => -1,
						'fields' => array('foreign_key', 'foreign_key'),
					));
				}
				$this->Session->write('Auth.User.permission_game_stats', $permissions);
			}

            ////Full title Game + short_words title
            if(isset($this->passedArgs['game_title'])&&  $this->Game->hasField('title_os',true))
            {
                unset($this->Game->virtualFields['title_os']);
                $this->Game->virtualFields['title_os'] = 'CONCAT(Game.title, " - ", Game.os)';
            }
		}

		# Check permission user to show menu that user has permission to access
		foreach ($this->menu as $name1 => $categories) {
			$categories = $categories['categories'];
			foreach ($categories as $name2 => $category) {
				if (is_string($category)) {
					$parses = Router::parse($category);
					if (!$this->Acl->check($this->Auth->user(), $parses['controller'] . '/'. $parses['action'])) {
						unset($this->menu[$name1]['categories'][$name2]);
					}
				} else { # is array
					foreach ($category as $name3 => $childCategory) {
						$parses = Router::parse($childCategory);
						if (!$this->Acl->check($this->Auth->user(), $parses['controller'] . '/'. $parses['action'])) {
							unset($this->menu[$name1]['categories'][$name2][$name3]);
						}
					}
				}

			}
		}

		$this->set('menu', $this->menu);
	}

	/**
	 *  Set Cookies default configs
	 */
	protected function __setCookie()
	{
		$this->Cookie->type('cipher');
		$this->Cookie->name = 'Stats';
		$this->Cookie->time = '90 days';
		$this->Cookie->path = '/';

		# Dev or production server
		if (empty($_SERVER['APPLICATION_ENV'])) {
			$domain = get_domain(env('HTTP_HOST'));
			$domain = "cms.muoriginfree.com";
			#$domain = '45.117.77.125';
		} else {
			$domain = env('HTTP_HOST');
		}
		$this->Cookie->domain = $domain;
		
		$this->Cookie->key = 'quanvh_qSdd%ddId2121232xdddddxqADYhG93b0qyJfIxfs1232guVoUubWwvaniR2G0FgaC9mis*&saX6Owsd121!';
	}

	/**
	 * Set Authen default configs
	 */
	protected function __configAuth()
	{
		$this->Auth->userModel = 'User';
		AuthComponent::$sessionKey = 'Auth.User';
		$this->Auth->authorize = array('Actions');
		$this->Auth->authError = 'You need to login your account to access this page.';
		$this->Auth->loginError = 'Sai mật mã hoặc tên tài khoản, xin hãy thử lại';
		$this->Auth->authenticate = array('Form' => array(
			'fields' => array('username' => 'email'),
			'userModel' => 'User',
			'scope' => array(
				'User.active' => true
			)
		));


		$this->Auth->loginAction = array(
			'admin' => false,
			'controller' => 'users',
			'action' => 'login'
		);
		$this->Auth->loginRedirect = '/';
		$this->Auth->logoutRedirect = $this->referer('/');
	}

	/**
	 * Login bằng cookies
	 */
	protected function __cookieAuth()
	{
		if (empty($this->request->data['User'])) {
			if (!$this->Auth->loggedIn()) {
                $cookie = $this->Cookie->read('User');
				if (!empty($cookie['username'])) {
					$this->loadModel('User');
					$user = $this->User->findByUsernameAndEmail($cookie['username'], $cookie['email']);
					if ($user) {
						if ($this->Auth->login($user['User'])) {
							$this->Session->delete('Message.auth');
						}
					}
				}
			}
		}
	}

	protected function __trimData($data)
	{
		if (is_array($data)) {
			foreach($data as $key => $val) {
				$data[$key] = $this->__trimData($val);
			}
		} else {
			$data = trim($data);
		}
		return $data;
	}

    public function beforeRender()
    {
        if (!isset($this->viewVars['title_for_layout'])){
            $this->set('title_for_layout', implode('-', array_merge(array(ucfirst($this->request->params['controller'])), array_reverse(explode('_', $this->request->params['action'])))));
        }
    }

    public function afterRender()
    {
        $this->Session->delete('currentUrl');
    }

    public function getDate($time, $range)
    {
        if (isset($time)) {
            $start = strtotime("- $range day", $time);
            $end   = date('Y-m-d 23:59:59', strtotime("- 1 day", $time));
            $end   = strtotime($end);
            return array($start, $end);
        }
    }

    public function getMonths($time, $range)
    {
        if (isset($time)) {
            $start = strtotime("- $range month", $time);
            $end   = date('Y-m-d 23:59:59', strtotime("- 1 day", $time));
            $end   = strtotime($end);
            return array($start, $end);
        }
    }

    protected function __processDates()
    {
        $fromTime = isset($this->request->params['named']['fromTime'])
            ? $this->request->params['named']['fromTime'] : '';
        $toTime = isset($this->request->params['named']['toTime'])
            ? $this->request->params['named']['toTime'] : '';

        if (!empty($fromTime)) {
            $date = new DateTime("@" . $fromTime);
            $date->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
            $fromTime = $date->getTimestamp();
        }
        if (!empty($toTime)) {
            $date = new DateTime("@" . $toTime);
            $date->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
            $toTime = $date->getTimestamp();
        }
        # at end of time can't larger than today
        if (!empty($toTime)
            && 	($toTime > strtotime('today'))
        ) {
            $toTime = strtotime('today');
        }

        $rangeDefault = 5;
        if (!empty($this->rangeDefault)) {
            $rangeDefault = $this->rangeDefault;
        }
        if (empty($fromTime) && empty($toTime)) {
            $fromTime = strtotime("-$rangeDefault days", strtotime('today'));
            $toTime = strtotime('today');
        } else {
            if (empty($fromTime) && !empty($toTime)) {
                $fromTime = strtotime("-$rangeDefault days", $toTime);
            } else if (!empty($fromTime) && empty($toTime)) {
                $toTime = strtotime('today');
            }
        }
        # always fix $toTime to "d-m-Y 23:59:59"
        $toTime = strtotime(date('d-m-Y 23:59:59', $toTime));
        return array($fromTime, $toTime);
    }

    protected function __processMonths()
    {
        $fromTime = isset($this->request->params['named']['fromTime'])
            ? $this->request->params['named']['fromTime'] : '';
        $toTime = isset($this->request->params['named']['toTime'])
            ? $this->request->params['named']['toTime'] : '';

        # at end of time can't larger than today
        if (	!empty($toTime)
            && 	($toTime > strtotime('today'))
        ) {
            $toTime = strtotime('today');
        }

        if (!empty($fromTime)) {
            $fromTime = strtotime('01-' . $fromTime);
        }

        if (!empty($toTime)) {
            $toTime = strtotime(date('t-m-Y', strtotime('1-'. $toTime)));
        }

        if (empty($fromTime) && empty($toTime)) {
            $fromTime = strtotime(date('01-m-Y', strtotime('- 5 months', strtotime('today'))));
            $toTime = strtotime(date('t-m-Y', strtotime('today')));
        } else {
            if (empty($fromTime) && !empty($toTime)) {
                $fromTime = strtotime('- 5 months', $toTime);
            } else if (!empty($fromTime) && empty($toTime)) {
                $toTime = strtotime('today');
            }
        }

        # always fix $toTime to "d-m-Y 23:59:59"
        $toTime = strtotime(date('d-m-Y 23:59:59', $toTime));

        return array($fromTime, $toTime);
    }

    protected function __processQuarter()
    {
        $currentYear = isset($this->request->params['named']['Y'])
            ? $this->request->params['named']['Y'] : '';
        $currentY = date('Y', strtotime('today'));
        if (empty($currentYear) || ($currentYear == $currentY)) {
            $fromTime = strtotime('first day of January this year');
            $toTime = strtotime('today');
        }else{
            $fromTime = strtotime('first day of January '.$currentYear);
            $toTime = strtotime('last day of december '.$currentYear);
        }
        return array($fromTime, $toTime);
    }

    public function indexDefault()
    {
        $model = $this->modelClass;
        $this->Prg->commonProcess();
        list($fromTime, $toTime) = $this->__processDates();
        $rangeDates = $this->{$model}->getDates($fromTime, $toTime);
        list($start, $end) = $this->getDate($fromTime, count($rangeDates));
        $parsedConditions = $this->{$model}->parseCriteria($this->passedArgs);
        $old_conditions = $parsedConditions;
        $games = $this->{$model}->Game->find('list', array(
            'conditions' => array('Game.id' => $this->Auth->user('permission_game_stats'), 'Game.status' => 1)
        ));

        //load event
        $event = $milestones = array();

        $gamesCond = array($model . '.game_id' => $this->Auth->user('permission_game_stats'));
        $timeCond = array();
        if (empty($this->request->params['fromTime'])) {
            $timeCond = (array) CakeTime::daysAsSql($fromTime, $toTime, $model . '.day');
        }
        if (isset($old_conditions["$model.day >= "]) || isset($old_conditions["$model.day <= "])) {
            unset($old_conditions["$model.day >= "]);
            unset($old_conditions["$model.day <= "]);
        }
        $tmp = (array) CakeTime::daysAsSql($start, $end, $model . '.day');
        $parsedConditions = array_merge($gamesCond, (array) $parsedConditions, $timeCond);
        $parsedConditions_old = array_merge($gamesCond, (array) $old_conditions, $tmp);
        $dau = $this->{$model}->find('all', array(
            'conditions' => $parsedConditions,
            'recursive' => -1,
            'order' => array('game_id' => 'DESC')
        ));
        $old_data =  $this->{$model}->find('all', array(
            'fields' => array('game_id', 'Sum(value) as sum'),
            'conditions' => $parsedConditions_old,
            'recursive' => -1,
            'order' => array('game_id' => 'DESC'),
            'group' => array('game_id'),
        ));
        $total = array();
        foreach ($old_data as $value) {
            $total[] = array (
                'game_id' => $value["$model"]['game_id'],
                'sum' => $value[0]['sum'],
            );
        }
        $data = $this->{$model}->dataToChartLine($dau, $games, $fromTime, $toTime);
        $data = Hash::sort($data, '{n}.name', 'asc');
        $data2 = $this->{$model}->addLineTotal($data);

        if (empty($data)) {
            $this->Session->setFlash('No avaiable data in this time range.', 'warning');
        }

        if ($this->name == 'Nius') {
            $sums = $this->{$model}->getTotals($games);
        }

        $MobileDetect = new Mobile_Detect();
        if ( ( $MobileDetect->isMobile() || $MobileDetect->isTablet() )
        ){
            $this->view = 'index_mobile';
        }


        $this->set(compact('games', 'fromTime', 'toTime', 'data', 'rangeDates', 'sums', 'data2', 'total', 'event', 'event_data', 'milestones_data'));
    }

    public function indexCountry()
    {
        $model = $this->modelClass;

        $this->Prg->commonProcess();
        list($fromTime, $toTime) = $this->__processDates();
        $rangeDates = $this->{$model}->getDates($fromTime, $toTime);
        list($start, $end) = $this->getDate($fromTime, count($rangeDates));
        $parsedConditions = $this->{$model}->parseCriteria($this->passedArgs);
        $this->loadModel('Permission');
        $ids = $this->Auth->user('permission_game_stats');
        $games = $this->{$model}->Game->find('list', array(
            'fields' => array('id', 'title_os'),
            'conditions' => array('Game.id' => $this->Auth->user('permission_game_stats'), 'Game.status' => 1)
        ));
        if( $model == 'LogPaymentsCountryByDay') $games[999999999] = 'All Games';

        if (!empty($this->request->named['game_id'])) {
            $gamesCond = array($model . '.game_id' => $ids);
            $timeCond = array();
            $timeCond1 = array();
            if (empty($this->request->params['fromTime'])) {
                $timeCond = (array) CakeTime::daysAsSql($fromTime, $toTime, $model . '.day');
            }
            if (empty($this->request->params['fromTime'])) {
                $timeCond1 = (array) CakeTime::daysAsSql($start, $end, $model . '.day');
            }
            $parsedConditions = array_merge($gamesCond, (array) $parsedConditions, $timeCond);
            $old_conditions = $parsedConditions;
            if (isset($old_conditions["$model.day >= "]) || isset($old_conditions["$model.day <= "]) || $old_conditions["0"]) {
                unset($old_conditions["$model.day >= "]);
                unset($old_conditions["$model.day <= "]);
                unset($old_conditions["0"]);
            }
            $old_conditions = array_merge($gamesCond, (array) $old_conditions, $timeCond1);
            $data = $this->{$model}->find('all', array(
                'conditions' => $parsedConditions,
                'recursive' => -1,
                'order' => array(
                    'country' => 'DESC'
                )
            ));
            $old_data = $this->{$model}->find('all', array(
                'fields' => array('SUM(value) as sum', 'country'),
                'conditions' => $old_conditions,
                'recursive' => -1,
                'order' => array(
                    'country' => 'DESC'
                ),
                'group' => array('country'),
            ));
            $old_dat_rev = $old_data;
            $total_old_rev = array();
            foreach ($old_dat_rev as $value) {
                $total_old_rev[] = array(
                    'sum' => $value[0]['sum'],
                    'country' => $value["$model"]['country'],
                );
            }
            $total_old = array();
            foreach ($old_data as $value) {
                $total_old[] = array(
                    'sum' => $value[0]['sum'],
                    'country' => $value["$model"]['country'],
                );
            }
            $data = $this->{$model}->dataCountryToChartLine($data, $games, $fromTime, $toTime);

            if (empty($data)) {
                $this->Session->setFlash('No avaiable data in this time range.', 'warning');
            }
        } else {
            $this->Session->setFlash('You need to choose a game.', 'error');
        }

        if (!empty($data)) {
            $dataHighchart = $data;

            foreach ($dataHighchart as $key => $value) {
                if ($key > 15) {
                    $dataHighchart[15]['name'] = 'Others';
                    $dataHighchart[15]['game_id'] = $value['game_id'];
                    foreach ($value['data'] as $k => $v) {
                        if (empty($dataHighchart[15]['data'][$k])) {
                            $dataHighchart[15]['data'][$k] = 0;
                        }

                        $dataHighchart[15]['data'][$k] += $v;
                    }
                    if ($key > 15) {
                        unset($dataHighchart[$key]);
                    }
                }
            }
        }
        $this->set(compact('games', 'fromTime', 'toTime', 'data', 'rangeDates', 'sums', 'dataHighchart', 'total_old', 'total_old_rev'));
    }

    public function monthlyDefault() {
        $model = $this->modelClass;
        $this->Prg->commonProcess();
        list($fromTime, $toTime) = $this->__processMonths();
        $rangeDates = $this->{$model}->getDates($fromTime, $toTime, 'M Y', new DateInterval('P1M'));
        list($start, $end) = $this->getMonths($fromTime, count($rangeDates));
        $parsedConditions = $this->{$model}->parseCriteria($this->passedArgs);
        $old_conditions = $parsedConditions;
        $timeCond = array();
        if (empty($this->request->params['fromTime'])) {
            $timeCond = (array) CakeTime::daysAsSql($fromTime, $toTime, $model . '.time');
        }
        if (isset($old_conditions['time >= ']) || isset($old_conditions['time <= '])) {
            unset($old_conditions['time >= ']);
            unset($old_conditions['time <= ']);
        }
        $ids_game = $this->Auth->user('permission_game_stats');
        $games = $this->{$model}->Game->find('list', array(
            'conditions' => array('id' => $ids_game, 'status' => 1)
        ));
        $gamesCond = array($model . '.game_id' => $ids_game);
        $parsedConditions = array_merge((array) $parsedConditions, $gamesCond, $timeCond);
        $tmp = (array) CakeTime::daysAsSql($start, $end, $model . '.time');
        $parsedConditions_old = array_merge($gamesCond, (array) $old_conditions, $tmp);
        $aggregate = $this->{$model}->find('all', array(
            'conditions' => $parsedConditions,
            'recursive' => -1
        ));
        $old_data =  $this->{$model}->find('all', array(
            'fields' => array('game_id', 'Sum(value) as sum'),
            'conditions' => $parsedConditions_old,
            'recursive' => -1,
            'order' => array('game_id' => 'DESC'),
            'group' => array('game_id'),
        ));
        $total_data = array();
        foreach ($old_data as $value) {
            $total_data[] = array (
                'game_id' => $value['LogLoginsByMonth']['game_id'],
                'sum' => $value[0]['sum'],
            );
        }
        $data = $this->{$model}->dataMonthToChart($aggregate, $games, $fromTime, $toTime);
        $data = Hash::sort($data, '{n}.name', 'asc');
        $data2 = $this->{$model}->addLineTotal($data);

        if (empty($data)) {
            $this->Session->setFlash('No avaiable data in this time range.', 'warning');
        }

		if ($this->name == 'Nius') {
			$sums = $this->{$model}->getTotals($games);
		}
        $this->set(compact('games', 'fromTime', 'toTime', 'data', 'rangeDates', 'sums', 'data2', 'total_data'));
    }

    public function quarterYearDefault() {
        $model = $this->useModel;
        $this->Prg->commonProcess();
        list($fromTime, $toTime) = $this->__processQuarter();

        $parsedConditions = $this->{$model}->parseCriteria($this->passedArgs);
        $ids = $this->Auth->user('permission_game_stats');
        $games = $this->{$model}->Game->find('list', array(
            'conditions' => array('Game.id' => $ids, 'Game.status' => 1)
        ));
        $gamesCond = array($model . '.game_id' => $ids);
        $timeCond = array();
        if (empty($this->request->params['fromTime'])) {
            $timeCond = (array) CakeTime::daysAsSql($fromTime, $toTime, $model . '.day');
        }
        $parsedConditions = array_merge((array) $parsedConditions, $gamesCond, $timeCond);
        $dau = $this->{$model}->find('all', array(
            'conditions' => $parsedConditions,
            'recursive' => -1,
            'order' => array('game_id' => 'DESC')
        ));
        $data = $this->{$model}->dataQuarterToChart($dau, $games, $fromTime, $toTime);
        if (empty($data)) {
            $this->Session->setFlash('No avaiable data in this time range.', 'warning');
        }

        if ($this->name == 'Nius') {
            $sums = $this->{$model}->getTotals($games);
        }

        $rangeDates = $this->{$model}->getDates($fromTime, $toTime, 'd-m-Y', new DateInterval('P3M'));
        $this->set(compact('games', 'fromTime', 'toTime', 'data', 'rangeDates', 'sums'));
    }
}
