<?php
App::uses('File', 'Utility');
App::uses('CakeSession', 'Model/Datasource');
App::uses('AppCakeTestCase', 'Testsuite');

class ImagesTestCase extends AppCakeTestCase {

	public function setUp()
	{
		parent::setUp();
		$this->Image = ClassRegistry::init('Image');
		$this->Image->cacheQueries = false;
	}

	public function tearDown()
	{
		unset($this->Image);

		parent::tearDown();
	}
	
	public function testEmpty()
	{

	}
	
	// /**
	//  *  Test xem JpegTran co the toi uu kich thuoc anh khong
	//  */
	// public function testJpegTran(){
	// 	$file1 = TMP . 'test.jpg';
	// 	$file2 = TMP . '1.jpg';
	// 	$File = new File($file1);
	// 	$File->copy($file2);
		
	// 	$a = filesize($file1);
	// 	if (DS === '/') {
	// 		$command = " -copy none -optimize -progressive -perfect $file1 > $file2";
	// 		$result = exec("jpegtran $command");
	// 	} else {
	// 		$command = " -copy none -optimize -progressive -perfect $file1 $file2";
	// 		$jpegtran = APP . 'Vendor' . DS . "jpegtran.exe";
	// 		$result = exec("$jpegtran $command");
	// 	}
	// 	$b = filesize($file2);
	// 	$this->assertTrue($a > $b);
	// 	unlink($file2);
	// }

	// public function testUploadImage()
	// {

	// 	if ($this->Image->Behaviors->enabled('SoftDelete')) {
	// 		$this->Image->Behaviors->disable('SoftDelete');
	// 	}
	// 	ini_set('memory_limit', '60M');
	// 	$File = new File(TMP . 'test.jpg');
	// 	$file = TMP . '1.jpg';
	// 	# Test image upload binh thuong
	// 	$File->copy($file);
	// 	$result = $this->Image->upload(array(
	// 		'name' => '1.jpg',
	// 		'uniqueName' => false,
	// 		'file' => $file
	// 	));
	// 	$this->assertTrue($result);
	// 	$image = $this->Image->findById($this->Image->id);
	// 	# Check file exist neu upload thanh cong

	// 	foreach($this->Image->settings['thumb'] as $thumb){
	// 		$this->assertTrue(file_exists($image['Image']['s' . $thumb['width']]['dir']));
	// 	}
	// 	# Test image delete
	// 	$this->Image->delete($this->Image->id);
	// 	# Xem image co bi delete han chua
	// 	$this->assertEmpty($this->Image->findById($this->Image->id));
	// 	foreach($this->Image->settings['thumb'] as $thumb){
	// 		$this->assertFalse(file_exists($image['Image']['s' . $thumb['width']]['dir']));
	// 	}

	// 	# Upload image nhung khong resize
	// 	$File->copy($file);
	// 	$result = $this->Image->upload(array(
	// 		'name' => '1.jpg',
	// 		'resized' => false,
	// 		'file' => $file
	// 	));
	// 	$this->assertTrue($result);
	// 	$image = $this->Image->findById($this->Image->id);
	// 	foreach($this->Image->settings['thumb'] as $thumb){
	// 		$this->assertFalse(file_exists($image['Image']['s' . $thumb['width']]['dir']));
	// 	}
	// 	$this->Image->delete($this->Image->id);

	// 	# Upload binh thuong
	// 	$File->copy($file);
	// 	$result = $this->Image->upload(array(
	// 		'file' => $file,
	// 		'name' => '1.jpg'
	// 	));
	// 	$this->assertTrue($result);
	// 	$image = $this->Image->findById($this->Image->id);
	// 	$this->assertEqual($image['Image']['id'], $this->Image->id);
	// 	$this->assertFileExists($image['Image']['o']['dir']);
	// 	$this->Image->delete($this->Image->id);
		
	// 	# Upload tu 1 URL nao do
	// 	$url = 'https://lh3.googleusercontent.com/-osFO2VF4T-Y/T3_BJ4n0-gI/AAAAAAAAJHs/bRwdqhChY2o/s720/01.jpg';
	// 	$this->Image->upload(array('url' => $url));
	// 	$image = $this->Image->findById($this->Image->id);
	// 	$this->assertEqual($image['Image']['id'], $this->Image->id);
	// 	$this->assertFileExists($image['Image']['o']['dir']);
	// 	$before = $this->Image->id;
		
	// 	# Upload tu 1 URL nhung neu url do da duoc download thi ko donwload nua
	// 	$url = 'https://lh3.googleusercontent.com/-osFO2VF4T-Y/T3_BJ4n0-gI/AAAAAAAAJHs/bRwdqhChY2o/s720/01.jpg';
	// 	$this->Image->upload(array('url' => $url));
	// 	$image = $this->Image->findById($this->Image->id);
	// 	$this->assertEqual($before, $image['Image']['id']);
	// 	$this->Image->delete($this->Image->id);
		
	// 	# Test upload nhung configure da duoc overwrite 
	// 	$this->Image->modelSettings = array(
	// 		'User' => array(
	// 			'Avatar' => array(
	// 				'limit' => 1,
	// 				'thumb' => array(
	// 					'small' => array(
	// 						'width'   => 80,
	// 						'width'   => 80,
	// 						'height' => 50,
	// 						'type'    => 'outbound' # Tong cong 3 dang la : inset, outbound, base
	// 					),
	// 					'medium' => array(
	// 						'width'   => 160,
	// 						'height'   => 160,
	// 						'quality' => 60,
	// 						'type'    => 'outbound',
	// 					)
	// 				)
	// 			)
	// 		)
	// 	);
		
	// 	$File->copy($file);
	// 	$result1 = $this->Image->upload(array(
	// 		'file' => $file,
	// 		'name' => '1.jpg',
	// 		'model' => 'User',
	// 		'foreign_key' => '11',
	// 		'type' => 'Avatar'
	// 	));
	// 	$this->assertTrue($result1);
		
	// 	$File->copy($file);
	// 	$result2 = $this->Image->upload(array(
	// 		'file' => $file,
	// 		'name' => '1.jpg',
	// 		'model' => 'User',
	// 		'foreign_key' => '11',
	// 		'type' => 'Avatar'
	// 	));

	// 	$this->assertTrue($result2);
	// 	$count = $this->Image->find('all', array('conditions' => array('model' => 'User', 'foreign_key' => 11)));

	// 	$this->assertEqual(count($count), 1);
	// 	$this->Image->delete($count[0]['Image']['id']);
	// }

}
