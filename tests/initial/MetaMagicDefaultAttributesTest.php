<?php

/** @noinspection ALL */

namespace initial;

use initial\samples\ObjectForTest1;
use initial\samples\ObjectForTest2;
use initial\samples\ObjectForTest4;
use initial\samples\ObjectForTest5;
use PHPUnit\Framework\TestCase;
use spaf\metamagic\exceptions\MagicBindingException;
use spaf\metamagic\exceptions\TypeNotAllowedException;
use function print_r;
use function spl_object_id;

/**
 * @covers \spaf\metamagic\traits\MagicMethodsTrait
 * @uses \spaf\metamagic\MetaMagic
 * @uses \spaf\metamagic\components\Spell
 * @uses \spaf\metamagic\basic\BasicMetaMagicAttribute
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
	 * @covers \spaf\metamagic\attributes\magic\Call
	 * @covers \spaf\metamagic\exceptions\MethodNotFound
	 * @runInSeparateProcess
	 */
	function testMetaMagicStaticCallException() {
		$this->expectException(MagicBindingException::class);

		$class = ObjectForTest2::class;
		$method = "nonExistingStaticMethod";

		$class::$method();
	}

	/**
	 * @covers \spaf\metamagic\attributes\magic\Call
	 * @covers \spaf\metamagic\exceptions\MethodNotFound
	 * @runInSeparateProcess
	 */
	function testMetaMagicDynamicCallException() {
		$this->expectException(MagicBindingException::class);

		$class = ObjectForTest2::class;
		$obj = new $class;
		$method = "nonExistingDynamicMethod";

		$obj->$method();
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

	/**
	 * @covers \spaf\metamagic\attributes\magic\Del
	 */
	function testMetaMagicDel() {
		$this->expectOutputString('unset of val3 is called');

		$class = ObjectForTest1::class;
		$obj = new $class;
		$obj->val1 = "test";
		$this->assertEquals("test", $obj->val1);
		unset($obj->val3);

		// NOTE Does not invoke __unset, because a known property
		unset($obj->val1);
	}

	/**
	 * @covers \spaf\metamagic\attributes\magic\Get
	 * @covers \spaf\metamagic\attributes\magic\Set
	 * @covers \spaf\metamagic\attributes\magic\IsAssigned
	 */
	function testMetaMagicGetterSetter() {
		$class = ObjectForTest1::class;
		$obj = new $class;

		$expected = "My Value 1";

		// NOTE Checking setting and isset
		$this->assertFalse(isset($obj->anyPropertyEver), "Checking non-set property");
		$obj->anyPropertyEver = $expected;
		$this->assertTrue(isset($obj->anyPropertyEver), "Checking newly-set property");

		// NOTE Checking getting
		$this->assertEquals($expected, $obj->anyPropertyEver, "Checking the value of the newly-set property");
	}

	/**
	 * @covers \spaf\metamagic\attributes\magic\Invoke
	 */
	function testMetaMagicInvoke() {
		$class = ObjectForTest1::class;
		$obj = new $class;

		$this->assertEquals("Object Was Invoked", "{$obj()}");
	}

	/**
	 * @covers \spaf\metamagic\attributes\magic\ToString
	 * @uses  \spaf\metamagic\attributes\magic\Set
	 */
	function testMetaMagicToString() {
		$class = ObjectForTest1::class;
		$obj = new $class;

		$this->assertEquals("", "{$obj}");

		$obj->my_new_property_1 = "Val 1";
		$obj->my_new_property_2 = "Val 2";
		$obj->my_new_property_3 = "Val 3";

		$this->assertEquals("Val 1,Val 2,Val 3", "{$obj}");
	}

	/**
	 * @covers \spaf\metamagic\attributes\magic\ToString
	 * @uses  \spaf\metamagic\attributes\magic\Set
	 */
	function testMetaMagicToStringNullConversion() {
		$objWithConversion = new ObjectForTest4;
		$objWithoutConversion = new ObjectForTest5;

		$this->assertEquals("", "{$objWithConversion}");

		$this->expectException(TypeNotAllowedException::class);
		$r = "$objWithoutConversion";
	}

}
