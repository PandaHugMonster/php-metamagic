<?php

namespace initial;

use Attribute;
use initial\samples\SimpleForAllAttribute;
use initial\samples\VeryCommonClass;
use PHPUnit\Framework\TestCase;
use spaf\metamagic\MetaMagic;
use function spaf\simputils\basic\pd;
use function spaf\simputils\basic\pr;

/**
 * @covers \spaf\metamagic\MetaMagic
 */
class MetaMagicTest extends TestCase {

	private function checkTypes($spells) {
		foreach ($spells as $spell) {
			pr($spell->item_reflection);
		}
		pd();
	}

	function testFindPerTargetSingleSpell() {
		$obj = new VeryCommonClass;

		$spells = MetaMagic::find(
			$obj,
			SimpleForAllAttribute::class,
			Attribute::TARGET_CLASS
		);
		$this->assertEquals(1, count($spells));


		$spells = MetaMagic::find(
			$obj,
			SimpleForAllAttribute::class,
			Attribute::TARGET_METHOD
		);
		$this->assertEquals(7, count($spells));


		$spells = MetaMagic::find(
			$obj,
			SimpleForAllAttribute::class,
			Attribute::TARGET_PROPERTY
		);
		$this->assertEquals(6, count($spells));


		$spells = MetaMagic::find(
			$obj,
			SimpleForAllAttribute::class,
			Attribute::TARGET_CLASS_CONSTANT
		);
		$this->assertEquals(3, count($spells));
	}

	function testFindPerGroupTargetSingleSpell() {
		$obj = new VeryCommonClass;

		$spells = MetaMagic::find(
			$obj,
			SimpleForAllAttribute::class,
			Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY
		);
		$this->assertEquals(13, count($spells));


		$spells = MetaMagic::find(
			$obj,
			SimpleForAllAttribute::class,
			Attribute::TARGET_CLASS_CONSTANT | Attribute::TARGET_CLASS
		);
		$this->assertEquals(4, count($spells));


		$spells = MetaMagic::find(
			$obj,
			SimpleForAllAttribute::class,
			Attribute::TARGET_CLASS_CONSTANT | Attribute::TARGET_PROPERTY
		);
		$this->assertEquals(9, count($spells));


		$spells = MetaMagic::find(
			$obj,
			SimpleForAllAttribute::class,
			Attribute::TARGET_METHOD | Attribute::TARGET_CLASS
		);
		$this->assertEquals(8, count($spells));


		$spells = MetaMagic::find(
			$obj,
			SimpleForAllAttribute::class,
			Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY
		);
		$this->assertEquals(14, count($spells));
	}

	function testFindEverySingleSpell() {
		$obj = new VeryCommonClass;

		$spells = MetaMagic::find($obj, SimpleForAllAttribute::class);

		$this->assertEquals(17, count($spells));
	}

}
