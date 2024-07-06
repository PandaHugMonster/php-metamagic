<?php

/** @noinspection ALL */

namespace initial;

use initial\samples\ObjectForTest1;
use PHPUnit\Framework\TestCase;
use function print_r;
use function spl_object_id;

/**
 */
class MetaMagicDefaultAttributesTest extends TestCase {


	/**
	 * @covers \spaf\metamagic\attributes\magic\Call
	 */
	function testMetaMagicCall() {
		$class = ObjectForTest1::class;
		$obj = new $class;

		$method = "nonExistingDynamicMethod";
		$expected = "dynamicCallMethod {$method}";
		$this->assertEquals($expected, $obj->$method());


		$method = "nonExistingStaticMethod";
		$expected = "staticCallMethod {$method}";
		$this->assertEquals($expected, $class::$method());

		$method = "nonExistingStaticMethod";
		$expected = "staticCallMethod {$method}";
		$this->assertEquals($expected, $obj::$method());
	}

	/**
	 * @covers \spaf\metamagic\attributes\magic\CopyShallow
	 */
	function testMetaMagicCopyShallow() {
		$class = ObjectForTest1::class;
		$obj = new $class;
		$obj_id = spl_object_id($obj);
		$obj_cloned = clone $obj;
		$obj_id_cloned = spl_object_id($obj_cloned);

		$this->assertNotEquals($obj_id, $obj_id_cloned);
		$this->assertNotEquals($obj->val1, $obj_cloned->val1);
		$this->assertNull($obj->val1);
		$this->assertEquals("I'm a copy", $obj_cloned->val1);
	}

	/**
	 * @covers \spaf\metamagic\attributes\magic\DebugInfo
	 */
	function testMetaMagicDebugInfo() {
		$class = ObjectForTest1::class;
		$obj = new $class;
		$expected = "initial\samples\ObjectForTest1 Object\n(\n    [info1] => val1\n    [info2] => val2\n)\n";
		$actual = print_r($obj, true);
		$this->assertEquals($expected, $actual);
	}

}
