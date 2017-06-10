<?php
App::uses('AppController', 'Controller');

class AdministratorsController extends AppController{

	public $components = array(
		'Security' => array(
			'csrfExpires' => '+30 minutes'
		)
	);
		
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout = 'default';
	}
	
	public function admin_readLog($name = null)
	{
        ini_set('memory_limit', '512M');
        if ($name) {
            $lines = $this->request->query('lines');
            if (empty($lines))
                $lines = 1000;

            ob_start();
            passthru("tail -n $lines " . TMP . 'logs' . DS . $name, $result);
            $content = ob_get_clean();
            $this->set(compact('content'));
        }
	}

	public function admin_clearLogs()
	{
		exec('cat /dev/null > ' . TMP . 'logs' . DS . 'info.log');
		exec('cat /dev/null > ' . TMP . 'logs' . DS . 'debug.log');
		exec('cat /dev/null > ' . TMP . 'logs' . DS . 'error.log');
		$this->Session->setFlash('Đã xóa trắng file log debug và error','success');
		$this->redirect(array('action' => 'index'));
	}

	public function admin_showsession()
	{
		$this->autoRender = false;
		Configure::write('debug', 2);
		debug($this->Session->read());
	}

    public function admin_index()
    {

    }
}
