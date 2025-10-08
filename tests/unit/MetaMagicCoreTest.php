<?php

namespace unit;

use Attribute;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use spaf\metamagic\abstract\AbstractSpell;
use spaf\metamagic\enums\TargetType;
use spaf\metamagic\exceptions\ClassReferenceException;
use spaf\metamagic\MetaMagic;
use spaf\metamagic\spells\SpellClass;
use spaf\metamagic\spells\SpellConstant;
use spaf\metamagic\spells\SpellField;
use spaf\metamagic\spells\SpellMethod;
use function is_null;


#[Attribute]
class MyAttr1 {}


#[Attribute]
class MyAttr2 {
	public function __construct(
		public $name = null,
	) {}
}


class MyObject {

	#[MyAttr1]
	const CONST1 = "val-const1";
	const CONST2 = "val-const2";

	#[MyAttr1]
	public $field1 = null;
	public $field2 = null;

	#[MyAttr2("Method number one")]
	public function method1() {}
	public function method2() {}

}

#[TestDox("Core tests for MetaMagic class")]
#[UsesClass(AbstractSpell::class)]
#[CoversClass(MetaMagic::class)]
class MetaMagicCoreTest extends TestCase {

	static protected $expected_names = [
		"unit\MyObject" => ["type" => SpellClass::class],

		"CONST1" => ["type" => SpellConstant::class],
		"CONST2" => ["type" => SpellConstant::class],

		"field1" => ["type" => SpellField::class],
		"field2" => ["type" => SpellField::class],

		"method1" => ["type" => SpellMethod::class],
		"method2" => ["type" => SpellMethod::class],
	];

	#[TestDox("Search spells without attribute filter")]
	#[Test]
	function searchSpellsWithoutAttribute() {

		$generator = MetaMagic::findSpells(MyObject::class);
		$this->assertNotNull($generator);
		$this->assertInstanceOf(Generator::class, $generator);

		$total = 0;
		foreach ($generator as $spell) {
			$this->assertNotNull($spell);
			$this->assertInstanceOf(AbstractSpell::class, $spell);
			$this->assertNull($spell->object);
			$this->assertNull($spell->attr);

			$this->assertArrayHasKey(
				$spell->target->reflection->name,
				static::$expected_names
			);

			$config = static::$expected_names[$spell->target->name];

			$expected_type = $config["type"];

			$this->assertInstanceOf($expected_type, $spell);
			$total++;
		}

		$this->assertEquals(count(static::$expected_names), $total);
	}

	#[TestDox("Search spells with custom filter on object")]
	#[Test]
	function searchSpellsWithCustomFilterObject() {
		$obj = new MyObject();

		$generator = MetaMagic::findSpells(
			refs: $obj,
			attrs: MyAttr2::class,
			filter: function ($spell) {
				/** @var AbstractSpell $spell */
				/** @var MyAttr2|MyAttr1 $attr */
				$attr = $spell->attr;
				return !is_null($attr?->name)
					?$spell
					:null;
			}
		);
		$this->assertNotNull($generator);
		$this->assertInstanceOf(Generator::class, $generator);

		$total = 0;
		$haystack = [];
		foreach ($generator as $spell) {
			$this->assertNotNull($spell);
			$this->assertInstanceOf(AbstractSpell::class, $spell);
			$this->assertNotNull($spell->object);
			$this->assertNotNull($spell->attr);
			$haystack[] = $spell->attr;

			$total++;
		}

		$this->assertContainsOnlyInstancesOf(MyAttr2::class, $haystack);

		$this->assertEquals(1, $total);
	}

	#[TestDox("Search a single spell without attribute filter")]
	#[Test]
	function searchSingleSpellWithoutAttribute() {
		$spell = MetaMagic::findSpellOne(MyObject::class);
		$this->assertNotNull($spell);
		$this->assertInstanceOf(SpellClass::class, $spell);
	}

	#[TestDox("Search spells without attribute filter on object")]
	#[Test]
	function searchSpellsWithoutAttributeOnObject() {
		$obj = new MyObject();

		$generator = MetaMagic::findSpells($obj);
		$this->assertNotNull($generator);
		$this->assertInstanceOf(Generator::class, $generator);

		$total = 0;
		foreach ($generator as $spell) {
			$this->assertNotNull($spell);
			$this->assertInstanceOf(AbstractSpell::class, $spell);
			$this->assertNotNull($spell->object);
			$this->assertNull($spell->attr);

			$this->assertArrayHasKey(
				$spell->target->reflection->name,
				static::$expected_names
			);

			$config = static::$expected_names[$spell->target->name];

			$expected_type = $config["type"];

			$this->assertInstanceOf($expected_type, $spell);
			$total++;
		}

		$this->assertEquals(count(static::$expected_names), $total);
	}

	#[TestDox("Search a single spell without attribute filter on object")]
	#[Test]
	function searchSingleSpellWithoutAttributeOnObject() {
		$obj = new MyObject();

		$spell = MetaMagic::findSpellOne($obj);
		$this->assertNotNull($spell);
		$this->assertInstanceOf(SpellClass::class, $spell);
	}

	#[TestDox("Search spells with type filter on object")]
	#[Test]
	function searchSpellsWithTypeFilterObject() {
		$obj = new MyObject();

		$types = [
			TargetType::ConstType,
			TargetType::FieldType
		];
		$gen = MetaMagic::findSpells(
			refs: $obj,
			types: $types,
		);

		$total = 0;
		foreach ($gen as $spell) {
			/** @var AbstractSpell $spell */
			$this->assertContains($spell->type, $types);
			$total++;
		}
		$this->assertEquals(4, $total);

		$spell = MetaMagic::findSpellOne(
			refs: $obj,
			types: TargetType::MethodType,
		);

		$this->assertInstanceOf(SpellMethod::class, $spell);
	}

	#[TestDox("Search spells with attribute filter on object")]
	#[Test]
	function searchSpellsWithAttrFilterObject() {
		$obj = new MyObject();

		$attrs = [
			MyAttr1::class,
			MyAttr2::class,
		];
		$gen = MetaMagic::findSpells(
			refs: $obj,
			attrs: $attrs,
		);

		$total = 0;
		foreach ($gen as $spell) {
			/** @var AbstractSpell $spell */
			$this->assertNotNull($spell->attr);
			$this->assertContains($spell->attr::class, $attrs);
			$total++;
		}
		$this->assertEquals(3, $total);

		$spell = MetaMagic::findSpellOne(
			refs: $obj,
			attrs: MyAttr2::class,
		);

		$this->assertNotNull($spell->attr);
	}

	#[TestDox("Check an exception when wrong class-string ref")]
	#[Test]
	#[RunInSeparateProcess]
	function wrongClassStringRefException() {
		$this->expectException(ClassReferenceException::class);

		MetaMagic::findSpellOne("non-existing-class-ref");
	}

}