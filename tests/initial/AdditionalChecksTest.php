<?php

namespace initial;

use initial\samples\ObjectForTest3;
use PHPUnit\Framework\TestCase;
use function print_r;

class AdditionalChecksTest extends TestCase {

	/**
	 * @covers \spaf\metamagic\attributes\magic\Get
	 * @covers \spaf\metamagic\attributes\magic\Set
	 * @covers \spaf\metamagic\attributes\magic\IsAssigned
	 * @uses \spaf\metamagic\MetaMagic
	 * @uses \spaf\metamagic\basic\BasicMetaMagicAttribute
	 * @uses \spaf\metamagic\components\Spell
	 * @uses \spaf\metamagic\traits\MagicMethodsTrait
	 * @return void
	 */
	function testMetaMagicCall() {
		$obj1 = new ObjectForTest3;
		$obj2 = new ObjectForTest3;
		$obj3 = new ObjectForTest3;
		
		$obj1->gg1 = $obj1gg1 = "test1[obj1]";
		$obj1->gg2 = $obj1gg2 = "test2[obj1]";
		$obj1->gg3 = $obj1gg3 = "test3[obj1]";

		$obj2->gg1 = $obj2gg1 = "test1[obj2]";
		$obj2->gg2 = $obj2gg2 = "test2[obj2]";
		$obj2->gg3 = $obj2gg3 = "test3[obj2]";

		$obj3->gg1 = $obj3gg1 = "test1[obj3]";
		$obj3->gg2 = $obj3gg2 = "test2[obj3]";
		$obj3->gg3 = $obj3gg3 = "test3[obj3]";


		$this->assertEquals($obj1gg1, $obj1->gg1);
		$this->assertEquals($obj1gg2, $obj1->gg2);
		$this->assertEquals($obj1gg3, $obj1->gg3);

		$this->assertEquals($obj2gg1, $obj2->gg1);
		$this->assertEquals($obj2gg2, $obj2->gg2);
		$this->assertEquals($obj2gg3, $obj2->gg3);

		$this->assertEquals($obj3gg1, $obj3->gg1);
		$this->assertEquals($obj3gg2, $obj3->gg2);
		$this->assertEquals($obj3gg3, $obj3->gg3);
	}

}
