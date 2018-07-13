<?php
App::uses('AppShell', 'Console/Command');
App::uses('ClassRegistry', 'Utility');
App::uses('CakeTime', 'Utility');

class AggregateShell extends AppShell {

	public $tasks = array('AggregateBase', 'AggregateCountry');

    public function initialize()
    {
        set_time_limit(60 * 60 * 24);
        ini_set('memory_limit', '384M');
        parent::initialize();
    }

	public function DAU(){
        $this->out(date('Y-m-d H:i:s') . " - Start run aggregate DAU");

        if( date('H') < 2 && empty($this->args[0]) ){
            $this->out(date('Y-m-d H:i:s') . " - disable run aggregate DAU");
            return ;
        }

		$date = date('d-m-Y');
		if ( !empty($this->args[0])) {
			$date = $this->args[0];
		}
        $this->out('DAU run date: ' . $date);

		$this->AggregateBase->_aggreateByDay(
		    "LogLogin", "LogLoginsByDay", "COUNT_DISTINCT", "user_id",
            array("game_id"), array('day' => $date)
        );
	}

	public function cDAU(){
        $this->out(date('Y-m-d H:i:s') . " - Start run aggregate DAU by country");

        if( date('H') < 2 && empty($this->args[0]) ){
            $this->out(date('Y-m-d H:i:s') . " - disable run aggregate DAU by country");
            return ;
        }

        $date = date('d-m-Y');
        if (isset($this->args[0])) {
            $date = $this->args[0];
        }
        $this->out('cDAU run date: ' . $date);

        $this->AggregateCountry->Dau($date);
    }

    public function MAU(){
        $this->out(date('Y-m-d H:i:s') . " - Start run aggregate MAU");

        if( date('H') < 2 && empty($this->args[0])){
            $this->out(date('Y-m-d H:i:s') . " - disable run aggregate MAU");
            return ;
        }

        $month = date('d-m-Y');
        if (isset($this->args[0])) {
            $month = $this->args[0];
        }
        $this->out('cDAU run month: ' . $month);

        $model = array(
            'name' => 'LogLogin',
            'timeField' => 'created'
        );
        $model2 = 'LogLoginsByMonth';

        $this->AggregateBase->_aggreateByMonth(
            $model, $model2, 'COUNT_DISTINCT', 'user_id',
            array('game_id'), array('time' => $month)
        );
    }

    public function Niu()
    {
        $this->out(date('Y-m-d H:i:s') . " - Start run aggregate NIU");

        if( date('H') < 2 && empty($this->args[0]) ){
            $this->out(date('Y-m-d H:i:s') . " - disable run aggregate NIU");
            return ;
        }

        $date = date('d-m-Y');
        if (isset($this->args[0])) {
            $date = $this->args[0];
        }
        $this->out('Niu run date: ' . $date);

        $this->AggregateBase->_aggreateByDay("Account", "LogAccountsByDay", "COUNT", "user_id", array("game_id"), array('day' => $date));
    }

    public function cNiu(){
        $this->out(date('Y-m-d H:i:s') . " - Start run aggregate NIU by country");

        if( date('H') < 2 && empty($this->args[0]) ){
            $this->out(date('Y-m-d H:i:s') . " - disable run aggregate NIU by country");
            return ;
        }

        $date = date('d-m-Y');
        if (isset($this->args[0])) {
            $date = $this->args[0];
        }
        $this->out('cNiu run date: ' . $date);

        $this->AggregateCountry->Niu($date);
    }

    public function Retention(){
        $this->out(date('Y-m-d H:i:s') . " - Start run aggregate Retention");

        if( date('H') < 2 && empty($this->args[0]) ){
            $this->out(date('Y-m-d H:i:s') . " - disable run aggregate Retention");
            return ;
        }

        $date = date('d-m-Y');
        if (isset($this->args[0])) {
            $date = $this->args[0];
        }

        $this->AggregateBase->_retention($date);
    }

    public function Arpu(){
        $this->out(date('Y-m-d H:i:s') . " - Start run aggregate Arpu");

        if( date('H') < 2 && empty($this->args[0]) ){
            $this->out(date('Y-m-d H:i:s') . " - disable run aggregate Arpu");
            return ;
        }

        $date = date('d-m-Y');
        if (isset($this->args[0])) {
            $date = $this->args[0];
        }
        $this->AggregateBase->_arpu($date);
    }

    public function Arppu(){
        $this->out(date('Y-m-d H:i:s') . " - Start run aggregate Arppu");

        if( date('H') < 2 && empty($this->args[0]) ){
            $this->out(date('Y-m-d H:i:s') . " - disable run aggregate Arppu");
            return ;
        }

        $date = date('d-m-Y');
        if (isset($this->args[0])) {
            $date = $this->args[0];
        }
        $this->AggregateBase->_arppu($date);
    }

    public function cRevenues(){
        $this->out(date('Y-m-d H:i:s') . " - Start run aggregate Revenues by country");

        if( date('H') < 2 && empty($this->args[0]) ){
            $this->out(date('Y-m-d H:i:s') . " - disable run aggregate Revenues by country");
            return ;
        }

        $date = date('d-m-Y');
        if (isset($this->args[0])) {
            $date = $this->args[0];
        }

        $this->AggregateCountry->Revenue($date);
    }

    # run start
    public function setCRevenue(){
        $this->out(date('Y-m-d H:i:s') . " run all NIU by country");
        for ($i = 30; $i >= 0; $i--){
            $date = date('Y-m-d', strtotime("-$i days"));
            $this->out('date: ' . $date . " - Start run aggregate NIU by country");
            $this->AggregateCountry->Revenue($date);
        }
    }

    # run install
    public function install(){
        $this->out(date('Y-m-d H:i:s') . " - Start run aggregate Install");

        if( date('H') < 2 && empty($this->args[0]) ){
            $this->out(date('Y-m-d H:i:s') . " - disable run aggregate Install");
            return ;
        }

        $date = date('d-m-Y');
        if (isset($this->args[0])) {
            $date = $this->args[0];
        }
        $this->out('install run date: ' . $date);

        $this->AggregateBase->_aggreateByDay(
            "LogInstall", "LogInstallByDay", "COUNT_DISTINCT", "device_id",
            array("game_id"), array('day' => $date)
        );
    }

    public function cInstall(){
        $this->out(date('Y-m-d H:i:s') . " - Start run aggregate install by country");

        if( date('H') < 2 && empty($this->args[0]) ){
            $this->out(date('Y-m-d H:i:s') . " - disable run aggregate install by country");
            return ;
        }

        $date = date('d-m-Y');
        if (isset($this->args[0])) {
            $date = $this->args[0];
        }

        $this->AggregateCountry->Install($date);
    }
}
