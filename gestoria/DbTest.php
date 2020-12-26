<?php

use PHPUnit\Framework\TestCase;

include_once "Db.php";

class DbTest extends TestCase {
	public function testUniqueness() {
		$firstInstance  = DB::getInstance();
		$secondInstance = DB::getInstance();

		$this->assertInstanceOf( DB::class, $firstInstance );
		$this->assertSame( $firstInstance, $secondInstance );
	}
}