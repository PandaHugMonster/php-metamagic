<?php

namespace initial;

use initial\samples\ObjectForTest3;
use PHPUnit\Framework\TestCase;
use function print_r;

class AdditionalChecksTest extends TestCase {

	function testMetaMagicCall() {
		$obj1 = new ObjectForTest3;
		$obj2 = new ObjectForTest3;
		$obj3 = new ObjectForTest3;
		
		$obj1->gg1 = "test1";
		$obj2->gg2 = "test2";
		$obj3->gg3 = "test3";


		print_r($obj2->internal_vars);
		// MARK Still issue with caching apparently. Even though caching was removed.
		$this->assertNotEmpty($obj2->internal_vars);
	}

}
