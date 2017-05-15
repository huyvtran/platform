<?php
App::uses('CakeSchema', 'Model');
App::uses('CakeTestCase', 'TestSuite');

abstract class AppCakeTestCase extends CakeTestCase{
	
	public function run(PHPUnit_Framework_TestResult $result = null) {
        $_SERVER['testing'] = true;
        # Create all tables
        $CakeSchema = new CakeSchema(array('file' => 'app.php'));
        $Schema = $CakeSchema->load(array('name' => 'App', 'connection' => 'test'));
        $db = ConnectionManager::getDataSource($CakeSchema->connection);
        foreach ($Schema->tables as $tableName => $table) {
            try {
                $db->execute($db->createSchema($Schema, $tableName));
            } catch (PDOException $e) {
            }
        }
        parent::run($result);
	}	
}