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
		// var_dump($output);
		$this->assertEquals( 1, preg_match('/'.preg_quote('<meta property="og:title" content="aaa" />','/').'/s', $output) );
		$this->assertEquals( 1, preg_match('/'.preg_quote('<meta property="og:description" content="bbb" />','/').'/s', $output) );
		$this->assertEquals( 1, preg_match('/'.preg_quote('<meta property="og:image" content="./index_files/resources/download.jpg" />','/').'/s', $output) );
		$this->assertEquals( 1, preg_match('/'.preg_quote('<meta property="og:type" content="ccc" />','/').'/s', $output) );
		$this->assertEquals( 1, preg_match('/'.preg_quote('<meta property="og:site_name" content="eee" />','/').'/s', $output) );
		
		$output = $this->passthru( [
			'php',
			__DIR__.'/testData/standard/.px_execute.php' ,
			'/?PX=clearcache' ,
		] );
	}//testStandardJade()

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