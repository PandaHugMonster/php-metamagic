<?php
namespace new;

use PHPUnit\Framework\TestCase;
use spaf\metamagic\attributes\magic\Call;
use spaf\metamagic\attributes\magic\CopyShallow;
use spaf\metamagic\attributes\magic\DebugInfo;
use spaf\metamagic\attributes\magic\Del;
use spaf\metamagic\attributes\magic\Get;
use spaf\metamagic\attributes\magic\Invoke;
use spaf\metamagic\attributes\magic\IsAssigned;
use spaf\metamagic\attributes\magic\Set;
use spaf\metamagic\attributes\magic\ToString;
use spaf\metamagic\exceptions\MagicBindingException;
use spaf\metamagic\exceptions\TypeNotAllowedException;
use spaf\metamagic\traits\MagicMethodsTrait;
use function ob_end_clean;
use function ob_get_contents;
use function ob_start;
use function var_dump;

require_once 'vendor/autoload.php';

class MissingTestSubject1 {
	use MagicMethodsTrait;

}

/**
 * @uses \spaf\metamagic\MetaMagic
 * @uses \spaf\metamagic\basic\BasicMetaMagicAttribute
 * @uses \spaf\metamagic\components\Spell
 * @covers \spaf\metamagic\traits\MagicMethodsTrait::_missingAttributeException
 */
class MissingAttributesTest extends TestCase {

	/**
	 * @covers \spaf\metamagic\traits\MagicMethodsTrait::__call
	 */
	function testCall() {
		$this->expectException(MagicBindingException::class);

		$target = new MissingTestSubject1;
		$res = $target->testestest();
	}

	/**
	 * @covers \spaf\metamagic\attributes\magic\Call
	 * @covers \spaf\metamagic\traits\MagicMethodsTrait::__callStatic
	 */
	function testStaticCall() {
		$this->expectException(MagicBindingException::class);

		$res = MissingTestSubject1::testtesttestStatic();
	}

	/**
	 * @covers \spaf\metamagic\attributes\magic\CopyShallow
	 * @covers \spaf\metamagic\traits\MagicMethodsTrait::__clone
	 */
	function testCopyShallow() {
		$this->expectException(MagicBindingException::class);

		$target = new MissingTestSubject1;
		$res = clone $target;
	}

//	MARK    IMP Need to be analyzed and fixed!
//	/**
//	 * @covers \spaf\metamagic\attributes\magic\DebugInfo
//	 * @covers \spaf\metamagic\traits\MagicMethodsTrait::__debugInfo
//	 */
//	function testDebugInfo() {
//		$this->expectException(MagicBindingException::class);
//
//		$target = new MissingTestSubject1;
//		var_dump($target);
//	}

//	MARK    IMP Must be split!
//	/**
//	 * @covers \spaf\metamagic\attributes\magic\Set
//	 * @covers \spaf\metamagic\attributes\magic\Get
//	 * @covers \spaf\metamagic\attributes\magic\IsAssigned
//	 * @covers \spaf\metamagic\attributes\magic\Del
//	 * @covers \spaf\metamagic\traits\MagicMethodsTrait::__get
//	 * @covers \spaf\metamagic\traits\MagicMethodsTrait::__isset
//	 * @covers \spaf\metamagic\traits\MagicMethodsTrait::__set
//	 * @covers \spaf\metamagic\traits\MagicMethodsTrait::__unset
//	 *
//	 */
//	function testSetGetDel() {
//		$this->expectException(MagicBindingException::class);
//
//		$target = new MissingTestSubject1;
//
//		$content = "My value 1";
//		$target->val_1 = $content;
//		$this->assertTrue(isset($target->val_1));
//		$this->assertEquals($target->internal_storage["val_1"], $content);
//		$this->assertEquals($target->internal_storage["val_1"], $target->val_1);
//		unset($target->val_1);
//		$this->assertFalse(isset($target->val_1));
//		$this->assertFalse(isset($target->internal_storage["val_1"]));
//	}

	/**
	 * @covers \spaf\metamagic\attributes\magic\Invoke
	 * @covers \spaf\metamagic\traits\MagicMethodsTrait::__invoke
	 */
	function testInvoke() {
		$this->expectException(MagicBindingException::class);

		$target = new MissingTestSubject1;
		$res = $target();
	}

	/**
	 * @covers \spaf\metamagic\attributes\magic\ToString
	 * @covers \spaf\metamagic\traits\MagicMethodsTrait::__toString
	 */
	function testToString() {
		$this->expectException(MagicBindingException::class);

		$target = new MissingTestSubject1;
		$res = "obj: {$target}";
	}

}