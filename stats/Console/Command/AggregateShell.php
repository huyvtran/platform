<?php
App::uses('AppShell', 'Console/Command');
App::uses('ClassRegistry', 'Utility');
App::uses('CakeTime', 'Utility');

class AggregateShell extends AppShell {

	public $tasks = array('AggregateBase', 'AggregateCountry');

	public function DAU(){
        $this->out(date('Y-m-d H:i:s') . " - Start run aggregate DAU");

        if( date('H') < 2 ){
            $this->out(date('Y-m-d H:i:s') . " - disable run aggregate DAU");
            return ;
        }

		$date = date('d-m-Y');
		if (isset($this->args[0])) {
			$date = $this->args[0];
		}

		$this->AggregateBase->_aggreateByDay(
		    "LogLogin", "LogLoginsByDay", "COUNT_DISTINCT", "user_id",
            array("game_id"), array('day' => $date)
        );
	}

	public function yesterdayDAU(){
        $this->out("Yesterday " . date('Y-m-d') ." - Start run aggregate DAU");
        $yesterday = date('d-m-Y', strtotime('yesterday'));
        $this->AggregateBase->_aggreateByDay(
            "LogLogin", "LogLoginsByDay", "COUNT_DISTINCT", "user_id",
            array("game_id"), array('day' => $yesterday)
        );
    }

	public function cDAU(){
        $this->out(date('Y-m-d H:i:s') . " - Start run aggregate DAU by country");

        if( date('H') < 2 ){
            $this->out(date('Y-m-d H:i:s') . " - disable run aggregate DAU by country");
            return ;
        }

        $date = date('d-m-Y');
        if (isset($this->args[0])) {
            $date = $this->args[0];
        }

        $this->AggregateCountry->Dau($date);
    }

    public function yesterdayCDAU(){
        $this->out("Yesterday " . date('Y-m-d') ." - Start run aggregate DAU by country");
        $yesterday = date('d-m-Y', strtotime('yesterday'));
        $this->AggregateCountry->Dau($yesterday);
    }

    public function MAU(){
        $this->out(date('Y-m-d H:i:s') . " - Start run aggregate MAU");

        if( date('H') < 2 ){
            $this->out(date('Y-m-d H:i:s') . " - disable run aggregate MAU");
            return ;
        }

        $month = date('d-m-Y');
        if (isset($this->args[0])) {
            $month = $this->args[0];
        }

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

        if( date('H') < 2 ){
            $this->out(date('Y-m-d H:i:s') . " - disable run aggregate NIU");
            return ;
        }

        $date = date('d-m-Y');
        if (isset($this->args[0])) {
            $date = $this->args[0];
        }
        $this->AggregateBase->_aggreateByDay("Account", "LogAccountsByDay", "COUNT", "user_id", array("game_id"), array('day' => $date));
    }

    public function yesterdayNiu(){
        $this->out("Yesterday " . date('Y-m-d') ." - Start run aggregate NIU");
        $yesterday = date('d-m-Y', strtotime('yesterday'));
        $this->AggregateBase->_aggreateByDay("Account", "LogAccountsByDay", "COUNT", "user_id", array("game_id"), array('day' => $yesterday));
    }

    public function cNiu(){
        $this->out(date('Y-m-d H:i:s') . " - Start run aggregate NIU by country");

        if( date('H') < 2 ){
            $this->out(date('Y-m-d H:i:s') . " - disable run aggregate NIU by country");
            return ;
        }

        $date = date('d-m-Y');
        if (isset($this->args[0])) {
            $date = $this->args[0];
        }

        $this->AggregateCountry->Niu($date);
    }

    public function yesterdayCNiu(){
        $this->out("Yesterday " . date('Y-m-d') ." - Start run aggregate NIU by country");
        $yesterday = date('d-m-Y', strtotime('yesterday'));
        $this->AggregateCountry->Niu($yesterday);
    }

    # run start
    public function setCNiu(){
        $this->out(date('Y-m-d H:i:s') . " run all NIU by country");
        for ($i = 23; $i >= 0; $i--){
            $date = date('Y-m-d', strtotime("-$i days"));
            $this->out('date: ' . $date . " - Start run aggregate NIU by country");
            $this->AggregateCountry->Niu($date);
        }
    }

    public function Retention(){
        $this->out(date('Y-m-d H:i:s') . " - Start run aggregate Retention");

        if( date('H') < 2 ){
            $this->out(date('Y-m-d H:i:s') . " - disable run aggregate Retention");
            return ;
        }

        $date = date('d-m-Y');
        if (isset($this->args[0])) {
            $date = $this->args[0];
        }
        $this->AggregateBase->_retention($date);
    }

    public function yesterdayRetention(){
        $this->out("Yesterday " . date('Y-m-d') ." - Start run aggregate Retention");
        $yesterday = date('d-m-Y', strtotime('yesterday'));
        $this->AggregateBase->_retention($yesterday);
    }

    public function Arpu(){
        $this->out(date('Y-m-d H:i:s') . " - Start run aggregate Arpu");

        if( date('H') < 2 ){
            $this->out(date('Y-m-d H:i:s') . " - disable run aggregate Arpu");
            return ;
        }

        $date = date('d-m-Y');
        if (isset($this->args[0])) {
            $date = $this->args[0];
        }
        $this->AggregateBase->_arpu($date);
    }

    public function yesterdayArpu(){
        $this->out("Yesterday " . date('Y-m-d') ." - Start run aggregate Arpu");
        $yesterday = date('d-m-Y', strtotime('yesterday'));
        $this->AggregateBase->_arpu($yesterday);
    }

    public function Arppu(){
        $this->out(date('Y-m-d H:i:s') . " - Start run aggregate Arppu");

        if( date('H') < 2 ){
            $this->out(date('Y-m-d H:i:s') . " - disable run aggregate Arppu");
            return ;
        }

        $date = date('d-m-Y');
        if (isset($this->args[0])) {
            $date = $this->args[0];
        }
        $this->AggregateBase->_arppu($date);
    }

    public function yesterdayArppu(){
        $this->out("Yesterday " . date('Y-m-d') ." - Start run aggregate Arppu");
        $yesterday = date('d-m-Y', strtotime('yesterday'));
        $this->AggregateBase->_arppu($yesterday);
    }

    public function cRevenues(){
        $this->out(date('Y-m-d H:i:s') . " - Start run aggregate Revenues by country");

        if( date('H') < 2 ){
            $this->out(date('Y-m-d H:i:s') . " - disable run aggregate Revenues by country");
            return ;
        }

        $date = date('d-m-Y');
        if (isset($this->args[0])) {
            $date = $this->args[0];
        }

        $this->AggregateCountry->Revenue($date);
    }

    public function yesterdayCRevenues(){
        $this->out("Yesterday " . date('Y-m-d') ." - Start run aggregate Revenues by country");
        $yesterday = date('d-m-Y', strtotime('yesterday'));
        $this->AggregateCountry->Revenue($yesterday);
    }
}
