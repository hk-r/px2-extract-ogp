<?php
/**
 * Test for tomk79\px2-jade
 *
 * $ cd (project dir)
 * $ composer test
 */
class mainTest extends PHPUnit_Framework_TestCase{
	/**
	 * 
	 */
	// private $fs;
	/**
	 * setup
	 */
	public function setup(){
		// $this->fs = new \tomk79\filesystem();
	}

	/**
	 * 
	 */
	public function testStandardOGP(){
		// 

		$output = $this->passthru( [
			'php',
			__DIR__.'/testData/standard/.px_execute.php' ,
			'/sample_pages/training/index.html' ,// picklesのrootからのパス
		] );
		
		$this->assertEquals( 1, preg_match('/'.preg_quote('<meta property="og:custome1" content="カスタム項目1" />','/').'/s', $output) );
		$this->assertEquals( 1, preg_match('/'.preg_quote('<meta property="og:title" content="タイトルを設定します" />','/').'/s', $output) );
		$this->assertEquals( 1, preg_match('/'.preg_quote('<meta property="og:description" content="descriptionを設定します" />','/').'/s', $output) );
		$this->assertEquals( 1, preg_match('/'.preg_quote('<meta property="og:image" content="http://127.0.0.1/sample_pages/training/index_files/resources/test_image.jpg" />','/').'/s', $output) );
		$this->assertEquals( 1, preg_match('/'.preg_quote('<meta property="og:type" content="website" />','/').'/s', $output) );
		$this->assertEquals( 1, preg_match('/'.preg_quote('<meta property="og:url" content="http://127.0.0.1/sample_pages/training/" />','/').'/s', $output) );
		$this->assertEquals( 1, preg_match('/'.preg_quote('<meta property="og:site_name" content="Get start &quot;Pickles 2&quot; !" />','/').'/s', $output) );
		
		$output = $this->passthru( [
			'php',
			__DIR__.'/testData/standard/.px_execute.php' ,
			'/?PX=clearcache' ,
		] );
	}//testStandardOGP()

	/**
	 * 
	 * @param array $ary_command
	 * @return string 
	 */
	private function passthru( $ary_command ){
		$cmd = array();
		foreach( $ary_command as $row ){
			
			$param = '"'.addslashes($row).'"';
			array_push( $cmd, $param );
		}
		$cmd = implode( ' ', $cmd );

		ob_start();
		passthru( $cmd );
		$bin = ob_get_clean();

		return $bin;
	}// passthru()
}