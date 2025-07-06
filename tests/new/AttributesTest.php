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
use spaf\metamagic\exceptions\TypeNotAllowedException;
use spaf\metamagic\traits\MagicMethodsTrait;
use function ob_end_clean;
use function ob_get_contents;
use function ob_start;
use function var_dump;

require_once 'vendor/autoload.php';

class TestSubject1 {
	use MagicMethodsTrait;

	protected $_copied_shallow = false;
	public $internal_storage = null;

	function __construct() {
		$this->internal_storage = [];
	}

	function getCopiedShallow(): bool {
		return $this->_copied_shallow;
	}

	#[Call]
	function sampleCall() {
		return "non-existing-dynamic-call";
	}

	#[Call]
	static function sampleStaticCall() {
		return "non-existing-static-call";
	}

	#[CopyShallow]
	function sampleCopyShallow() {
		$this->_copied_shallow = true;
	}

	#[DebugInfo]
	function sampleDebugInfo() {
		return [
			"info-key-1" => "val-1",
			"info-key-2" => "val-2",
			"info-key-3" => "val-3",
		];
	}

	#[Invoke]
	function sampleInvoke() {
		return "I have just been invoked";
	}

	#[Set]
	function sampleSet($key, $val) {
		$this->internal_storage[$key] = $val;
	}

	#[Get]
	function sampleGet($key) {
		return $this->internal_storage[$key];
	}

	#[Del]
	function sampleDel($key) {
		unset($this->internal_storage[$key]);
	}

	#[IsAssigned]
	function sampleIsAssigned($key) {
		return isset($this->internal_storage[$key]);
	}

	#[ToString]
	function sampleToString() {
		return "my obj ".static::class;
	}

}

class TestSubject2 extends TestSubject1 {

	#[ToString(null_conversion: false)]
	function sampleToStringNullConversionFalse() {
		return null;
	}
}

class TestSubject3 extends TestSubject1 {

	#[ToString(null_conversion: true)]
	function sampleToStringNullConversionFalse() {
		return null;
	}
}

/**
 * @uses \spaf\metamagic\MetaMagic
 * @uses \spaf\metamagic\basic\BasicMetaMagicAttribute
 * @uses \spaf\metamagic\components\Spell
 */
class AttributesTest extends TestCase {

	/**
	 * @covers \spaf\metamagic\attributes\magic\Call
	 * @covers \spaf\metamagic\traits\MagicMethodsTrait::__call
	 */
	function testCall() {
		$target = new TestSubject1;
		$res = $target->testestest();
		$this->assertEquals($target->sampleCall(), $res);
	}

	/**
	 * @covers \spaf\metamagic\attributes\magic\Call
	 * @covers \spaf\metamagic\traits\MagicMethodsTrait::__callStatic
	 */
	function testStaticCall() {
		$res = TestSubject1::testtesttestStatic();
		$this->assertEquals(TestSubject1::sampleStaticCall(), $res);
	}

	/**
	 * @covers \spaf\metamagic\attributes\magic\CopyShallow
	 * @covers \spaf\metamagic\traits\MagicMethodsTrait::__clone
	 */
	function testCopyShallow() {
		$target = new TestSubject1;
		$this->assertFalse($target->getCopiedShallow());

		$res = clone $target;
		$this->assertTrue($res->getCopiedShallow());

		$target->sampleCopyShallow();
		$this->assertTrue($target->getCopiedShallow());
	}

	/**
	 * @covers \spaf\metamagic\attributes\magic\DebugInfo
	 * @covers \spaf\metamagic\traits\MagicMethodsTrait::__debugInfo
	 */
	function testDebugInfo() {
		$target = new TestSubject1;
		ob_start();
		var_dump($target);
		$buffer = ob_get_contents();
		ob_end_clean();

		$ref_data = $target->sampleDebugInfo();

		foreach ($ref_data as $key => $val) {
			$this->assertMatchesRegularExpression(
				"/.*({$key}).*/",
				$buffer
			);
			$this->assertMatchesRegularExpression(
				"/.*({$val}).*/",
				$buffer
			);
		}

	}

	/**
	 * @covers \spaf\metamagic\attributes\magic\Set
	 * @covers \spaf\metamagic\attributes\magic\Get
	 * @covers \spaf\metamagic\attributes\magic\IsAssigned
	 * @covers \spaf\metamagic\attributes\magic\Del
	 * @covers \spaf\metamagic\traits\MagicMethodsTrait::__get
	 * @covers \spaf\metamagic\traits\MagicMethodsTrait::__isset
	 * @covers \spaf\metamagic\traits\MagicMethodsTrait::__set
	 * @covers \spaf\metamagic\traits\MagicMethodsTrait::__unset
	 *
	 */
	function testSetGetDel() {
		$target = new TestSubject1;

		$content = "My value 1";
		$target->val_1 = $content;
		$this->assertTrue(isset($target->val_1));
		$this->assertEquals($target->internal_storage["val_1"], $content);
		$this->assertEquals($target->internal_storage["val_1"], $target->val_1);
		unset($target->val_1);
		$this->assertFalse(isset($target->val_1));
		$this->assertFalse(isset($target->internal_storage["val_1"]));
	}

	/**
	 * @covers \spaf\metamagic\attributes\magic\Invoke
	 * @covers \spaf\metamagic\traits\MagicMethodsTrait::__invoke
	 */
	function testInvoke() {
		$target = new TestSubject1;
		$res = $target();
		$this->assertEquals($target->sampleInvoke(), "$res");
	}

	/**
	 * @covers \spaf\metamagic\attributes\magic\ToString
	 * @covers \spaf\metamagic\traits\MagicMethodsTrait::__toString
	 */
	function testToString() {
		$target = new TestSubject1;
		$res = "obj: {$target}";
		$this->assertEquals("obj: {$target->sampleToString()}", $res);
	}

	/**
	 * @covers \spaf\metamagic\attributes\magic\ToString
	 * @covers \spaf\metamagic\traits\MagicMethodsTrait::__toString
	 */
	function testToStringNullConversionTrue() {
		$target = new TestSubject3;
		$res = "obj: {$target}";
		$this->assertEquals("obj: ", $res);
	}

	/**
	 * @covers \spaf\metamagic\attributes\magic\ToString
	 * @covers \spaf\metamagic\traits\MagicMethodsTrait::__toString
	 * @covers \spaf\metamagic\exceptions\TypeNotAllowedException
	 */
	function testToStringNullConversionFalse() {
		$this->expectException(TypeNotAllowedException::class);

		$target = new TestSubject2;
		$res = "obj: {$target}";
	}

}