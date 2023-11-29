<?php

namespace initial;

use Attribute;
use initial\samples\SimpleForAllAttribute;
use initial\samples\SimpleLimitedAttribute;
use initial\samples\SimpleRepeatableAttribute;
use initial\samples\VeryCommonClass;
use PHPUnit\Framework\TestCase;
use ReflectionClassConstant;
use ReflectionMethod;
use ReflectionObject;
use ReflectionProperty;
use spaf\metamagic\components\Spell;
use spaf\metamagic\MetaMagic;
use function count;
use function spaf\simputils\basic\pd;
use function spaf\simputils\basic\pr;

/**
 * @covers \spaf\metamagic\MetaMagic
 * @uses \spaf\metamagic\components\Spell
 */
class MetaMagicTest extends TestCase {

	private function checkTypes($spells) {
		foreach ($spells as $spell) {
			pr($spell->item_reflection);
		}
		pd();
	}

	private function mapAttributeTargetToString($target) {
		return match ($target) {
			Attribute::TARGET_CLASS => "TARGET_CLASS",
			Attribute::TARGET_METHOD => "TARGET_METHOD",
			Attribute::TARGET_PROPERTY => "TARGET_PROPERTY",
			Attribute::TARGET_CLASS_CONSTANT => "TARGET_CLASS_CONSTANT",
		};
	}

	function dataProviderJustSingleTargets(): array {
		return [
			[Attribute::TARGET_CLASS, ReflectionObject::class],
			[Attribute::TARGET_METHOD, ReflectionMethod::class],
			[Attribute::TARGET_PROPERTY, ReflectionProperty::class],
			[Attribute::TARGET_CLASS_CONSTANT, ReflectionClassConstant::class],
		];
	}

	function dataProviderPerTargetSingleSpell(): array {
		return [
			[Attribute::TARGET_CLASS, 1, "Filtered class targets amount check"],
			[Attribute::TARGET_METHOD, 7, "Filtered method targets amount check"],
			[Attribute::TARGET_PROPERTY, 6, "Filtered property targets amount check"],
			[Attribute::TARGET_CLASS_CONSTANT, 3, "Filtered class-constants targets amount check"],
		];
	}

	/**
	 * @dataProvider dataProviderJustSingleTargets
	 * @return void
	 */
	function testFirstItemOnly($target, $reflection_class) {
		$obj = new VeryCommonClass;

		$spell = MetaMagic::find(
			$obj,
			SimpleForAllAttribute::class,
			$target,
			first: true,
		);


		$this->assertIsNotArray(
			$spell,
			"Received not array bu Spell"
		);


		$this->assertInstanceOf(
			Spell::class,
			$spell,
			"Received a single/first item only, and of a class Spell"
		);


		$this->assertInstanceOf(
			$reflection_class,
			$spell->item_reflection,
			"Item reflection of type {$reflection_class}"
		);
	}

	/**
	 * @dataProvider dataProviderPerTargetSingleSpell
	 * @return void
	 */
	function testFindPerTargetSingleSpell($target, $expected, $message) {
		$obj = new VeryCommonClass;

		$spells = MetaMagic::find(
			$obj,
			SimpleForAllAttribute::class,
			$target
		);


		$this->assertIsArray($spells, "Received array of Spells");

		$this->assertEquals($expected, count($spells), $message);

	}

	function dataProviderFindGroupTargetSingleSpell() {
		return [
			[
				Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY,
				13,
				"Check grouped targets of METHOD and PROPERTY",
			],
			[
				Attribute::TARGET_CLASS_CONSTANT | Attribute::TARGET_CLASS,
				4,
				"Check grouped targets of CLASS_CONSTANT and CLASS",
			],
			[
				Attribute::TARGET_CLASS_CONSTANT | Attribute::TARGET_PROPERTY,
				9,
				"Check grouped targets of CLASS_CONSTANT and CLASS",
			],
			[
				Attribute::TARGET_METHOD | Attribute::TARGET_CLASS,
				8,
				"Check grouped targets of METHOD and CLASS",
			],
			[
				Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY,
				14,
				"Check grouped targets of METHOD, CLASS and PROPERTY",
			],
		];
	}

	/**
	 * @param $group_target
	 * @param $expected_amount
	 * @param $message
	 *
	 * @dataProvider dataProviderFindGroupTargetSingleSpell
	 * @return void
	 */
	function testFindPerGroupTargetSingleSpell($group_target, $expected_amount, $message) {
		$obj = new VeryCommonClass;

		$spells = MetaMagic::find(
			$obj,
			SimpleForAllAttribute::class,
			$group_target
		);
		$this->assertEquals($expected_amount, count($spells), $message);
	}

	function testFindEverySingleSpell() {
		$obj = new VeryCommonClass;

		$spells = MetaMagic::find($obj, SimpleForAllAttribute::class);
		$this->assertEquals(17, count($spells), "Find all Attribute targets");
	}

	function testFindLimitedAttributesSpell() {
		$obj = new VeryCommonClass;

		$spells = MetaMagic::find($obj, SimpleLimitedAttribute::class);
		$this->assertEquals(
			2,
			count($spells),
			"Find limited attribute to METHOD"
		);

		$spells = MetaMagic::find(
			$obj,
			SimpleRepeatableAttribute::class,
			Attribute::IS_REPEATABLE | Attribute::TARGET_ALL
		);
		$this->assertEquals(
			3,
			count($spells),
			"Find repeatable attributes"
		);
	}

}
