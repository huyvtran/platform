<?php

App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::import('Lib', 'RedisQueue');

class EmailMarketingsController extends AppController {

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout = 'default_bootstrap';
	}

    public $components = array(
        'Search.Prg'
    );

	public function admin_index()
	{
        $this->Prg->commonProcess();
        $this->request->data['EmailMarketing'] = $this->passedArgs;

        $parsedConditions = array();
        if(!empty($this->passedArgs)) {
            $parsedConditions = $this->EmailMarketing->parseCriteria($this->passedArgs);
        }

        if( !empty($this->passedArgs) && empty($parsedConditions)
        ){
            if (	(count($this->passedArgs) == 1 && empty($this->passedArgs['page']))
                ||	count($this->passedArgs) > 1
            ) {
                $this->Session->setFlash("Can not find anyone match this conditions", "error");
            }
        }

        $this->paginate = array(
            'EmailMarketing' => array(
                'conditions' => $parsedConditions,
                'contain' => array(
                    'Game', 'User'
                ),
                'order' => array('EmailMarketing.id' => 'DESC'),
                'recursive' => -1,
                'limit' => 20
            )
        );

        $emailMarketings = $this->paginate();

        $distinctGames = $this->EmailMarketing->Game->find('list',
			array(
				'fields' => array('id', 'title'),
				'group' => array('title'),
				'conditions' => array('alias IS NOT NULL')
		));

		$this->set(compact('emailMarketings' ,'distinctGames'));
	}

    public function admin_add($id = null)
    {
        if ($this->request->is('post') || $this->request->is('put')) {
            try {
                if (empty($id)) {
                    $this->EmailMarketing->create();
                } else {
                    $this->request->data['EmailMarketing']['id'] = $id;
                }
                if (!empty($this->request->data['EmailMarketing']['data']['from_time'])) {
                    $this->request->data['EmailMarketing']['data']['from_time'] = date('Y-m-d H:i:s', $this->request->data['EmailMarketing']['data']['from_time']);

                }
                if (!empty($this->request->data['EmailMarketing']['data']['to_time'])) {
                    $this->request->data['EmailMarketing']['data']['to_time'] = date('Y-m-d 23:59:59', $this->request->data['EmailMarketing']['data']['to_time']);
                }

                if (!empty($this->request->data['EmailMarketing']['giftcodefile']['tmp_name'])) {
                    if ($this->request->data['EmailMarketing']['giftcodefile']['error'] != 0) {
                        throw new Exception("Error happen while upload file, please try again or report tech");
                    }
                    $this->request->data['EmailMarketing']['data']['giftcodes'] = file_get_contents($this->request->data['EmailMarketing']['giftcodefile']['tmp_name']);
                }

                $this->request->data['EmailMarketing']['user_id'] = $this->Auth->user('id');
                if ($new_email = $this->EmailMarketing->save($this->request->data)) {
                    $this->Session->setFlash('The email marketing has been saved.', 'success');
                } else {
                    throw new Exception('The email marketing could not be saved. Please, try again.');
                }
            } catch (Exception $e) {
                $this->Session->setFlash($e->getMessage(), 'error');
            }
        }

        $distinctGames = $this->EmailMarketing->Game->find('all',
            array(
                'fields' => array('Game.id', 'Game.title', 'Website.url'),
                'contain' => array('Website'),
                'group' => array('title'),
                'conditions' => array('alias IS NOT NULL', 'os !=' => NULL, 'os !=' => '')
            ));

        foreach ($distinctGames as $key => $game) {
            if( empty($game['Website']['url']) ) $game['Website']['url'] = 'localhost';
            $tmp[$game['Game']['id']] = $game['Game']['title'] . " - " . $game['Website']['url'];
        }
        $distinctGames = $tmp;

        if (!empty($id)) {
            $this->request->data = $email = $this->EmailMarketing->findById($id);
            if (!empty($this->request->data['EmailMarketing']['data']['to_time'])) {
                $toTime = strtotime($this->request->data['EmailMarketing']['data']['to_time']);
                $this->set(array(
                    'toTime' => $toTime,
                ));
            }
            if (!empty($this->request->data['EmailMarketing']['data']['from_time'])) {
                $fromTime = strtotime($this->request->data['EmailMarketing']['data']['from_time']);
                $this->set(array(
                    'fromTime' => $fromTime,
                ));
            }

        }

        $this->set(compact('distinctGames', 'email'));
    }

    public function admin_sendTest($id)
    {
        if ($this->request->is('post')) {
            try {
                $addresses = explode("\n", $this->data['EmailMarketing']['email']);
                foreach($addresses as $address) {
                    $this->loadModel('User');
                    $this->User->recursive = -1;
                    if ($user = $this->User->findByEmail($address)) {
                        $username = $user['User']['username'];
                    } else {
                        $username = $address;
                    }

                    $this->EmailMarketing->send($id,
                        trim($address),
                        array(
                            '@username' => $username,
                            '@email' => $address,
                            '@giftcode' => 'thisisagiftcode'
                        ),
                        true
                    );
                }
                $this->Session->setFlash('The email has been sent successfully', 'success');
            } catch (Exception $e) {
                $this->Session->setFlash($e->getMessage(), 'error');
            }
        }
        $email = $this->EmailMarketing->findById($id);
        $this->set(compact('email'));
    }

    public function admin_edit($id = null)
    {
        $this->EmailMarketing->query("SET NAMES utf8mb4");
        $directoryTemp = APP . 'View' . DS . 'Emails' . DS . 'html' . DS . 'marketings' . DS;
        if (!$this->EmailMarketing->exists($id)) {
            throw new NotFoundException('Invalid email marketing');
        }

        $this->EmailMarketing->contain('Game', 'User');
        $email = $this->EmailMarketing->findById($id);

        if ($this->request->is('post') || $this->request->is('put')) {

            $this->request->data['EmailMarketing']['id'] = $id;
            $this->request->data['EmailMarketing']['body'] = html_entity_decode($this->request->data['<http://schema.org/text>'], version_compare(phpversion(), '5.4', '<') ? ENT_COMPAT : (ENT_COMPAT | ENT_HTML401), 'UTF-8');
            $this->request->data['EmailMarketing']['title'] = html_entity_decode($this->request->data['<http://schema.org/headline>'], version_compare(phpversion(), '5.4', '<') ? ENT_COMPAT : (ENT_COMPAT | ENT_HTML401), 'UTF-8');
            $this->request->data['EmailMarketing']['layout'] = $this->request->query('template');
            $this->request->data['EmailMarketing']['game_id'] = $email['EmailMarketing']['game_id'];
            $this->request->data['EmailMarketing']['type'] = $email['EmailMarketing']['type'];

            $list_keyword = array('@unsubscribe', '@unsubscribeLink', '@giftcode', '@email', '@friendlyName');
            if(preg_match_all('/\B\@{1}[a-zA-Z0-9]+\b/', $this->request->data['EmailMarketing']['body'],$matches))
            {
                foreach ($matches[0] as $key => $value) {
                    if(!in_array(trim($value), $list_keyword))
                    {
                        $this->Session->setFlash('The email marketing could not be saved.', 'error');
                        $result = array('code' => 4, 'message' => 'Sử dụng cú pháp @giftcode, @email chưa đúng');
                        $this->set('result', $result);
                        $this->set('_serialize', 'result');
                        return false;
                    }
                }
            }

            // if(!preg_match("/\s+\@unsubscribe\[(.+)\]/", $this->request->data['EmailMarketing']['body']))
            if(!preg_match("/\@unsubscribe\[([^\]]*)\]/i", $this->request->data['EmailMarketing']['body'],$matches))
            {
                $this->Session->setFlash('The email marketing could not be saved.', 'error');
                $result = array('code' => 2, 'message' => 'Not found @unsubscribe in body');
                $this->set('result', $result);
                $this->set('_serialize', 'result');
            } else {
                if (preg_match("/\<a(.+)\@unsubscribe(.+)\>/", $this->request->data['EmailMarketing']['body'])) {
                    $result = array('code' => 3, 'message' => 'Need to use the correct syntax @unsubscribe');
                    $this->set('result', $result);
                    $this->set('_serialize', 'result');
                } else {
                    CakeLog::info('check data email mkt: ' . print_r($this->request->data['EmailMarketing'],true));
                    if ($this->EmailMarketing->save($this->request->data)) {
                        $this->Session->setFlash('The email marketing has been saved.', 'success');
                        if ($this->request->is('ajax')) {
                            $result = array('code' => 1, 'message' => 'saved email');
                            $this->set('result', $result);
                            $this->set('_serialize', 'result');
                        } else {
                            return $this->redirect(array('action' => 'index'));
                        }
                    } else {
                        if ($this->request->is('ajax')) {
                            $result = array('code' => 1, 'message' => 'The email marketing could not be saved.');
                            $this->set('result', $result);
                            $this->set('_serialize', 'result');
                        } else {
                            $this->Session->setFlash('The email marketing could not be saved.', 'error');
                        }
                    }
                }
            }
        } else {
            $options = array('conditions' => array('EmailMarketing.' . $this->EmailMarketing->primaryKey => $id));
            $this->request->data = $this->EmailMarketing->find('first', $options);
        }

        if (	empty($email['EmailMarketing']['body'])
            && 	$this->request->query('template')
        ) {
            $this->layout = 'Emails/html/marketings/' . $this->request->query('template');
            $this->view = $directoryTemp . $this->request->query('template') . '.ctp';
        } elseif (!empty($email['EmailMarketing']['body'])) {
            $body = $email['EmailMarketing']['body'];
            $this->layout = 'Emails/html/marketings/' . $email['EmailMarketing']['layout'];
        }

        $layout = basename($this->layout);
        $title = $email['EmailMarketing']['title'];
        $games = $this->EmailMarketing->Game->find('list', array('fields' => array('id', 'title_os')));

        $this->set(compact('users', 'games', 'directoryTemp', 'title', 'body', 'layout', 'email'));
    }

    public function admin_delete($id = null)
    {
        $this->EmailMarketing->id = $id;
        if (!$this->EmailMarketing->exists()) {
            throw new NotFoundException('Invalid email marketing');
        }
        $emailMarketing = $this->EmailMarketing->find('first', array(
            'conditions' => array('id' => $id), 'recursive' => -1
        ));

        if (	$emailMarketing['EmailMarketing']['status'] != EmailMarketing::SEND_WAIT
            && 	!in_array($this->Session->read('Auth.User.role'), array('Admin', 'Developer'))
        ) {
            $this->Session->setFlash('The email marketing could not be deleted when it was published.', 'error');
            return $this->redirect($this->referer());
        }

        $this->request->onlyAllow('post', 'delete');
        if ($this->EmailMarketing->delete()) {
            $Redis = new RedisQueue('default', 'email-marketing-id-' . $id);
            $Redis->delete();
            $this->Session->setFlash('The email marketing has been deleted.', 'success');
        } else {
            $this->Session->setFlash('The email marketing could not be deleted. Please, try again.', 'error');
        }
        return $this->redirect($this->referer());
    }

    public function admin_publish($id = null)
    {
        if (!$id || !$email = $this->EmailMarketing->findById($id)) {
            throw new NotFoundException('Không tìm thấy email này');
        }

        # clear queue in resend case
        $Redis = new RedisQueue('default', 'email-marketing-id-' . $id);
        $Redis->delete();

        $this->EmailMarketing->id = $id;
        $this->EmailMarketing->validator()->remove('type')->remove('game_id')->remove('title');
        if ($this->EmailMarketing->save(array(
            'EmailMarketing' => array(
                'status' => EmailMarketing::SEND_PUSHLISHED,
                'published_date' => date('Y-m-d H:i:s')
            )
        ))
        ) {
            $this->Session->setFlash('Đã gửi email này <strong>'.$email['EmailMarketing']['title'].'</strong>',
                'success');
        } else {
            $this->Session->setFlash('Có lỗi xảy ra');
        }

        $this->redirect($this->referer(array('action' => 'index'), true));
    }

    public function admin_unpublish($id = null)
    {
        if (!$id || !$email = $this->EmailMarketing->findById($id)) {
            throw new NotFoundException('Không tìm thấy email này');
        }

        $this->EmailMarketing->id = $id;
        if ($this->EmailMarketing->saveField('status', EmailMarketing::SEND_WAIT)) {
            $this->Session->setFlash('Đã hủy gửi email này <strong>' . $email['EmailMarketing']['title'] . '</strong>',
                'success');
        } else {
            $this->Session->setFlash('Có lỗi xảy ra');
        }

        $this->redirect($this->referer(array('action' => 'index'), true));
    }
}
