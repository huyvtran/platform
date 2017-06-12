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

	public function admin_index()
	{
		$this->EmailMarketing->recursive = 0;
		$this->Paginator->settings['EmailMarketing']['order'] = array('EmailMarketing.id' => 'desc');
		
		if (!empty($this->request->data['EmailMarketing']['game_id'])){
			$this->Paginator->settings['EmailMarketing']['conditions'] = array('Game.id'=>$this->request->data['EmailMarketing']['game_id']);
		}
		
		$emailMarketings = $this->Paginator->paginate();

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
                $data_save = array();
                if (!empty($this->request->data['EmailMarketing']['data']['segment']) && !empty($this->request->data['EmailMarketing']['data']['email_marketing_id'])) {
                    $data = $this->EmailMarketing->find('first', array('conditions' => array('id' => $this->request->data['EmailMarketing']['data']['email_marketing_id'])));
                    if (!empty($data)) {
                        $data_save = array(
                            'EmailMarketing' => array(
                                'body' => $data['EmailMarketing']['body'],
                                'layout' => $data['EmailMarketing']['layout'],
                            ),
                        );
                    }
                }
                $this->request->data['EmailMarketing']['user_id'] = $this->Auth->user('id');
                if ($new_email = $this->EmailMarketing->save($this->request->data)) {
                    $this->Session->setFlash('The email marketing has been saved.', 'success');
                } else {
                    throw new Exception('The email marketing could not be saved. Please, try again.');
                }
                if (!empty($data_save)) {
                    $id = $new_email['EmailMarketing']['id'];
                    $this->EmailMarketing->id = $id;
                    $this->EmailMarketing->save($data_save);
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

        $games = $this->EmailMarketing->Game->find('list', array(
            'fields' => array('Game.id', 'Game.title_os'),
            'conditions' => array('alias IS NOT NULL', 'os !=' => NULL, 'os !=' => '')
        ));

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

        $this->set(compact('users', 'distinctGames', 'template', 'email', 'giftcodes', 'addresses', 'games'));
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
}
