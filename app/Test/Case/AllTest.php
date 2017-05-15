<?php

App::uses('CakeSchema', 'Model');

class AllAppTest extends CakeTestSuite {

/**
 * suite method, defines tests for this suite.
 *
 * @return void
 */
	public static function suite() {
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
        
		$suite = new self('All Tests');
		$suite->addTestDirectoryRecursive(TESTS . 'Case');
		return $suite;
	}

    protected function setUp()
    {
    }

    /**
     * Template Method that is called after the tests
     * of this test suite have finished running.
     *
     * @since  Method available since Release 3.1.0
     */
    protected function tearDown()
    {
    	ini_set("memory_limit", '1024M');
    }
}