<?php

App::uses('AppController', 'Controller');
class AngularController extends AppController {

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(
            'index', 'login', 'register', 'maintain'
        );
        $this->Common->setTheme();
    }

    public function index(){
        $this->layout = 'blank';
        $this->Common->currentGame();
    }

    public function login(){
        $this->layout = 'blank';
        $this->Common->currentGame();
    }

    public function register(){
        $this->layout = 'blank';
        $this->Common->currentGame();
    }

    public function maintain(){
        $this->layout = 'blank';
        $this->Common->currentGame();
        $maintained = $this->Common->isMaintained();
        $message = $maintained['Maintenance']['description'];

        $textInfo = nl2br($message);
        $this->set('textInfo', $textInfo);
    }
}
