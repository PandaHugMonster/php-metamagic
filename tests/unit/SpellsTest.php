<?php /** @noinspection PhpIllegalPsrClassPathInspection */

namespace unit;

use Attribute;
use Closure;
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
use function iterator_to_array;
use function ksort;
use function sort;
use function spaf\simputils\basic\pd;
use const SORT_NATURAL;


#[Attribute(Attribute::TARGET_METHOD)]
class OrderedGroup {

	function __construct(
		public int $order,
	) {}

}

class MethodCollection {

	#[OrderedGroup(3)]
	static public function method1(SpellMethod $spell) {
		/** @var OrderedGroup $attr */
		$attr = $spell->attr;
		return "method1; Order {$attr->order}";
	}

	#[OrderedGroup(2)]
	static public function method2(SpellMethod $spell) {
		/** @var OrderedGroup $attr */
		$attr = $spell->attr;
		return "method2; Order {$attr->order}";
	}

	#[OrderedGroup(1)]
	static public function method3(SpellMethod $spell) {
		/** @var OrderedGroup $attr */
		$attr = $spell->attr;
		return "method3; Order {$attr->order}";
	}

	/**
	 * @return Closure[]
	 * @throws ClassReferenceException
	 */
	static public function getCallables(): array {
		$res = [];

		$gen = MetaMagic::findSpells(
			refs: MethodCollection::class,
			attrs: OrderedGroup::class,
			types: TargetType::MethodType,
		);
		foreach ($gen as $spell) {
			/** @var SpellMethod $spell */
			/** @var OrderedGroup $attr */
			$attr = $spell->attr;
			$res[$attr->order] = function () use ($spell) {
				return $spell($spell);
			};
		}

		ksort($res, SORT_NATURAL);

//		pd($res);

		return $res;
	}
}

#[TestDox("Spell classes tests")]
#[CoversClass(AbstractSpell::class)]
#[CoversClass(SpellMethod::class)]
#[UsesClass(MetaMagic::class)]
class SpellsTest extends TestCase {

	#[TestDox("Check an exception when wrong class-string ref")]
	#[Test]
	function runGroupedMethodsInOrder() {
		$callables = MethodCollection::getCallables();

		$expectation_array = [
			1 => "method3; Order 1",
			2 => "method2; Order 2",
			3 => "method1; Order 3",
		];

		foreach ($callables as $i => $closure) {
			$expected = $expectation_array[$i];
			$res = $closure();
			$this->assertEquals($expected, $res);
		}
	}

}