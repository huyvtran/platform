<?php

App::uses('Shell', 'Console');
App::uses('AppShell', 'Console/Command');
			
class AppShell extends Shell {

	public function initialize()
	{
		ini_set('memory_limit', '512M');
		parent::initialize();
	}

	
	public function perform()
	{
		$this->initialize();
		$this->loadTasks();
		return $this->runCommand($this->args[0], $this->args);

	}

	/** 
	 * Run a cron once at time, used to avoid cron overlaps on long time process
	 */
	public function lock($timeLimit = 3600)
	{

		$file = "/tmp/" . $this->name . "_" . $this->command;
		$this->lockedFile = $file;
		$time = 0;
		if (file_exists($file)) {
			$time = file_get_contents($file);
		}


		if (!empty($time) && (time() - $time) < $timeLimit) {
			return false;
		} else {
			file_put_contents($file, time());
			return true;
		}
	}

	public function unlock()
	{
		unlink($this->lockedFile);
	}
}
