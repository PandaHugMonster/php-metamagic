<?php

namespace spaf\metamagic;

use Attribute;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionObject;
use spaf\metamagic\components\Spell;
use spaf\simputils\Math;
use T;
use function array_merge;
use function count;
use function is_object;
use function spaf\simputils\basic\pd;
use function spaf\simputils\basic\pr;

/**
 * Meta Magic Helper
 */
class MetaMagic {

	//	static function conjure($obj, $spell, ...$args) {
	//		$reflection_entrypoint = new ReflectionMethod($obj, "")
	//	}

	/**
	 * Find Spells
	 *
	 * @template T
	 *
	 * @param object|class-string<T> $entity
	 * @param class-string<T>        $attr_class
	 * @param int                    $attr_targets
	 *
	 * @param null|array|callable    $filter
	 * @param bool                   $first
	 *
	 * @return Spell|Spell[]
	 */
	static function find(
		object|string       $entity,
		string              $attr_class,
		int                 $attr_targets = Attribute::TARGET_ALL,
		null|array|callable $filter = null,
		bool                $first = false,
	): Spell|array {
		$spells = [];
		if (is_object($entity)) {
			// NOTE Object processing

			$attr_class_reflection = new ReflectionClass($attr_class);
			/** @var ReflectionAttribute $attr_of_attr_reflection */
			$attr_of_attr_reflection = $attr_class_reflection->getAttributes(Attribute::class)[0];
			$attr_supported_targets_mask = $attr_of_attr_reflection->getArguments()[0]
				?? Attribute::TARGET_ALL;

			if ($attr_supported_targets_mask === Attribute::IS_REPEATABLE) {
				$attr_supported_targets_mask |= Attribute::TARGET_ALL;
			}

			// NOTE This ensures, that only supported by attribute cases are
			//      iterated over to spare some cycles.
			$attr_targets &= $attr_supported_targets_mask;

			$entity_reflection = new ReflectionObject($entity);

			// NOTE Class-level attrs
			if ($attr_targets & Attribute::TARGET_CLASS) {
				$spells += static::_findAllEntitySpells(
					[$entity_reflection],
					$attr_class,
					$entity,
					$first
				);
				if ($spells && $first) {
					return $spells[0];
				}
			}

			$steps = [
				// NOTE Do not change order!
				Attribute::TARGET_METHOD => "getMethods",
				Attribute::TARGET_PROPERTY => "getProperties",
				Attribute::TARGET_CLASS_CONSTANT => "getReflectionConstants",
			];

			foreach ($steps as $processing_target => $reflection_func_name) {
				// NOTE Checking if processing target conforms the allowed ones
//				pr("{$attr_targets}, {$processing_target}, {$t}");
//				pr(
//					"----------- {$reflection_func_name}",
//					Math::dec2bin($attr_targets),
//					Math::dec2bin($processing_target),
//					Math::dec2bin($attr_targets & $processing_target),
//					"-----------"
//				);
				if ($attr_targets & $processing_target) {
					$spells = array_merge($spells, static::_findAllEntitySpells(
							$entity_reflection->$reflection_func_name(),
							$attr_class,
							$entity,
							$first
						)
					);
//					$spells += static::_findAllEntitySpells(
//						$entity_reflection->$reflection_func_name(),
//						$attr_class,
//						$entity,
//						$first
//					);
					if ($spells && $first) {
						return $spells[0];
					}
				}
			}

		} else {
			// NOTE Class processing
		}

		return $spells;
	}

	protected static function buildSpell(
		$entity_reflection,
		$attr_class,
		$owner_entity
	): Spell|array {
		$attr_reflections = $entity_reflection->getAttributes();

		$major_attr_reflections = [];
		$all_attr_reflections = [];

		foreach ($attr_reflections as $attr_reflection) {
			$all_attr_reflections[] = $attr_reflection;

			if ($attr_reflection->getName() === $attr_class) {
				$major_attr_reflections[] = $attr_reflection;
			}
		}

		$spell = new Spell(
			$owner_entity,
			$entity_reflection,
			$major_attr_reflections,
			$all_attr_reflections,
		);
		return $spell;
	}

	/**
	 * @param $item_reflections
	 * @param $attr_class
	 * @param $entity
	 * @param $first
	 *
	 * @return Spell[]
	 */
	private static function _findAllEntitySpells(
		$item_reflections,
		$attr_class,
		$entity,
		$first
	): array {
		$spells = [];
		foreach ($item_reflections as $item_reflection) {
			$applied_attr_reflections = $item_reflection->getAttributes($attr_class);
			if (empty($applied_attr_reflections)) {
				continue;
			}
			$sub = static::buildSpell(
				$item_reflection,
				$attr_class,
				$entity
			);
			$spells[] = $sub;
			if ($sub && $first) {
				break;
			}
		}
		return $spells;
	}

}
