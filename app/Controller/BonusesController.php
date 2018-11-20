<?php
App::uses('AppController', 'Controller');

/**
 * Bonus Controller
 *
 * @property Bonus              $Bonus
 * @property PaginatorComponent $Paginator
 */
class BonusesController extends AppController
{

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = ['Paginator', 'Search.Prg'];

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout = 'default_bootstrap';

		if (!empty($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') {
			$this->Bonus->enablePublishable('find', false);
		}
	}

	public function admin_index()
	{
		$this->Prg->commonProcess();

		$this->request->data['Bonus'] = $this->passedArgs;

		if ($this->Bonus->Behaviors->loaded('Searchable')) {
			$parsedConditions = $this->Bonus->parseCriteria($this->passedArgs);
		} else {
			$parsedConditions = array();
		}
		if (	!empty($this->passedArgs)
			&&	empty($parsedConditions)
		) {
			if (	(count($this->passedArgs) == 1 && empty($this->passedArgs['page']))
			) {
				$this->Session->setFlash("Can not find anyone match this conditions", "error");
			}
		}

        $parsedConditions = array_merge(array(
            'Bonus.game_id' => $this->Session->read('Auth.User.permission_game_default')
        ), $parsedConditions);

		$games = $this->Bonus->Game->find('list', array(
			'fields' => array('id', 'title_os'),
			'conditions' => array(
				'Game.id' => $this->Session->read('Auth.User.permission_game_default'),
			)
		));

		$this->paginate = array(
			'Bonus' => array(
				'fields' => array('Bonus.*', 'User.username', 'User.id', 'Game.title', 'Game.os'),
				'conditions'    => $parsedConditions,
				'order'         => array('Bonus.id' => 'DESC'),
				'contain'       => array('Game', 'User'),
				'recursive'     => -1
			)
		);

		$compense = $this->paginate();

		$this->set(compact('games', 'compense'));

		# set contain in view
		$this->loadModel('Payment');
	}

	public function admin_add($id = null)
	{
		$this->loadModel('Game');
		$this->loadModel('Payment');

		if (!empty($id)) {
			$bonus = $this->Bonus->findById($id);
			if (empty($bonus)) {
				throw new NotFound('Không tìm thấy Bonus Payment này');
			}
		}

		$games = $this->Bonus->Game->find('list', array(
			'fields' => array('id', 'title_os'),
			'conditions' => array(
				'Game.id' => $this->Session->read('Auth.User.permission_game_default'),
			)
		));

		if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Bonus']['order_id']   = microtime(true) * 10000;
            $this->request->data['Bonus']['price']      = 0;
            $this->request->data['Bonus']['type']       = 1;
            $this->request->data['Bonus']['chanel']     = Payment::CHANEL_BONUS;

			try {
                $this->Bonus->create();
				if ($this->Bonus->save($this->request->data)) {
					$this->Session->setFlash('Bonus Payment has been saved');
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash('Bonus Payment could not be saved. Please, try again.');
				}
			}catch (Exception $e){
				CakeLog::error('save Bonus Payment add error: '.$e->getMessage());
				$this->Session->setFlash('Bonus Payment could not be saved. Please, try again.');
			}
		}

		if( $id ){
			$this->request->data = $bonus;
		}

		$this->set(compact('games'));
		$this->view = 'admin_add';
	}

	public function admin_edit($id)
	{
		$this->Bonus->recursive = -1;
		if (!$id || !$bonus = $this->Bonus->findById($id)) {
			throw new NotFoundException('Không tìm thấy giao dịch này');
		}
		if( !empty($bonus['Bonus']['status'])){
			throw new NotFoundException('Giao dịch đã được bù');
		}

		$this->admin_add($id);
	}

    public function admin_bonus($id)
    {
        $this->loadModel('Bonus');
        if (!$id || !$bonus = $this->Bonus->findById($id)) {
            throw new NotFoundException('Không tìm thấy giao dịch này');
        }

        if( !empty($bonus['Bonus']['status']) ) {
            throw new NotFoundException('Đã thực hiện bonus giao dịch này');
        }

        $dataSource = $this->Bonus->getDataSource();
        $dataSource->begin();

        $this->Bonus->id = $id;
        if ($this->Bonus->publish($id)) {
            $this->Bonus->User->recursive = -1;
            $user = $this->Bonus->User->findById($bonus['Bonus']['user_id']);
            $updatePay = $user['User']['payment'] + $bonus['Bonus']['bonus'];
            $this->Bonus->User->id = $bonus['Bonus']['user_id'];
            if( $this->Bonus->User->saveField('payment', $updatePay, array('callbacks' => false)) ){
                $this->Session->setFlash('Giao dịch thành công', 'success');
                $dataSource->commit();
            }else{
                $this->Session->setFlash('Lỗi xảy ra', 'error');
                $dataSource->rollback();
            }
        } else {
            $this->Session->setFlash('Lỗi xảy ra', 'error');
            $dataSource->rollback();
        }
        $this->redirect($this->referer(array('action' => 'index'), true));
    }

    public function api_index(){
        $result = [
            'error_code' => 1,
            'message' => 'error',
        ];

        if ( $this->request->query('app') ) {
            $appKey = $this->request->query('app');
        }

        if ( $this->request->query('token') ){
            $accessToken = $this->request->query('token');
        }

        if (!isset($appKey, $accessToken)) {
            $result = array(
                'error_code'    => 2,
                'message'    => 'empty token or appkey'
            );
            goto end;
        }

        $game = $this->Common->currentGame();
        if( empty($game) || !$this->Auth->loggedIn() ){
            $result = array(
                'error_code'    => 3,
                'message'    => 'Invalid token or appkey'
            );
            goto end;
        }
        $user = $this->Auth->user();

        if ($this->request->is('post')) {
            if( empty($this->request->data['coin']) ){
                $result = [
                    'error_code' => 3,
                    'message' => 'Thiếu thông tin nhận coin',
                ];
                goto end;
            }

            $this->loadModel('Bonus');
            $dataSource = $this->Bonus->getDataSource();
            $dataSource->begin();

            try {
                # tạo giao dịch bonus
                $data = array(
                    'Bonus' => array(
                        'order_id'  => microtime(true) * 10000,
                        'user_id'   => $user['id'],
                        'game_id'   => $game['id'],
                        'price'     => 0,
                        'bonus'     => $this->request->data['coin'],
                        'status'    => 1, // 0 để duyệt tay
                        'chanel'    => 10,
                        'note'      => 'World Cup'
                    )
                );
                $this->Bonus->save($data);

                $this->Bonus->User->recursive = -1;
                $updatePay = $user['payment'] + $this->request->data['coin'];
                $this->Bonus->User->id = $user['id'];
                if( $this->Bonus->User->saveField('payment', $updatePay, array('callbacks' => false)) ){
                    $result = [
                        'error_code' => 0,
                        'message' => 'Giao dịch thành công',
                    ];
                    $dataSource->commit();
                }else{
                    $result = [
                        'error_code' => 3,
                        'message' => 'lỗi lưu dữ liệu',
                    ];
                    $dataSource->rollback();
                }
            }catch (Exception $e){
                $result = [
                    'error_code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ];
                $dataSource->rollback();
            }
        }

        end:
        $this->set('result', $result);
        $this->set('_serialize', 'result');
    }
}
