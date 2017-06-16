<?php

App::uses('AppModel', 'Model');
App::uses('HttpSocket', 'Network/Http');

class Email extends AppModel
{
    public $useTable = false;
}