<?php

namespace spaf\metamagic;

use Attribute;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionException;
use ReflectionMethod;
use ReflectionObject;
use ReflectionProperty;
use spaf\metamagic\components\Spell;
use spaf\simputils\Math;
use T;
use function array_merge;
use function is_callable;
use function is_object;
use function is_string;
use function spaf\simputils\basic\pd;

/**
 * Meta Magic Helper
 */
class MetaMagic {

	//	static function conjure($obj, $spell, ...$args) {
	//		$reflection_entrypoint = new ReflectionMethod($obj, "")
	//	}

	static public array $spelling_cache = [];

	static public array $reflection_class_cache = [];

	static public array $reflection_object_cache = [];

	static public array $attributes_custom_cache = [];

	static private $steps = [
		// NOTE Do not change order!
		Attribute::TARGET_METHOD => "getMethods",
		Attribute::TARGET_PROPERTY => "getProperties",
		Attribute::TARGET_CLASS_CONSTANT => "getReflectionConstants",
	];

	static private array $deep_cache = [];

	/**
	 * Find Spells
	 *
	 * @template T
	 * @template A
	 *
	 * @param object|class-string<T> $entity
	 * @param class-string<A>        $attr_class
	 * @param int                    $attr_targets
	 * @param ?callable              $filter
	 * @param bool                   $first
	 *
	 * @return Spell|Spell[]
	 * @throws ReflectionException
	 */
	static function find(
		object|string $entity,
		string        $attr_class,
		int           $attr_targets = Attribute::TARGET_ALL | Attribute::IS_REPEATABLE,
		bool          $first = false,
		?callable     $filter = null,
        bool          $static = true,
        bool          $dynamic = true,
	): Spell|array {
		$spells = [];

//        $k = $attr_targets;
//        $s = $attr_targets = static::preProcessAttrTargets($attr_class, $attr_targets);

        $entity_reflection = self::getReflectionClass($entity);

        // NOTE Class-level attrs
        if ($attr_targets & Attribute::TARGET_CLASS) {
            $cached = static::_findAllEntitySpells(
                [$entity_reflection],
                $attr_class,
                $entity,
                $first,
                $filter,
                $static,
                $dynamic
            );

            if ($first && $cached) {
                return $cached[0];
            }

            $spells = array_merge($spells, $cached);
        }

        foreach (self::$steps as $processing_target => $reflection_func_name) {
            // NOTE Checking if processing target conforms the allowed ones
            if ($attr_targets & $processing_target) {
                $cached = static::_findAllEntitySpells(
                    $entity_reflection->$reflection_func_name(),
                    $attr_class,
                    $entity,
                    $first,
                    $filter,
                    $static,
                    $dynamic
                );

                if ($first && $cached) {
                    return $cached[0];
                }

                $spells = array_merge($spells, $cached);
            }
        }

		return $spells;
	}

	/**
	 *
	 * NOTE This ensures, that only supported by attribute cases are
	 *      iterated over to spare some cycles.
	 *
	 * @param $attr_class
	 * @param $attr_targets
	 *
	 * @return null|int
	 */
	static protected function preProcessAttrTargets($attr_class, $attr_targets) {
		$attr_of_attr_reflection =
			static::getReflectionClass($attr_class)->getAttributes(Attribute::class)[0];

		$attr_supported_targets_mask =
			$attr_of_attr_reflection->getArguments()[0]
			?? Attribute::TARGET_ALL;

		if ($attr_supported_targets_mask == Attribute::IS_REPEATABLE) {
			$attr_supported_targets_mask |= Attribute::TARGET_ALL;
		}

		$res = $attr_targets & $attr_supported_targets_mask;
		return $res;
	}

	static protected function getReflectionClass(string|object $object_or_class) {
		if (is_object($object_or_class)) {
			$class = $object_or_class::class;
		} else {
			$class = $object_or_class;
		}
		$res = empty(self::$reflection_class_cache[$class])
			?null
			:self::$reflection_class_cache[$class];
		if (!$res) {
			$res = self::$reflection_class_cache[$class] = new ReflectionClass($class);
		}

		return $res;
	}

//	static protected function getReflectionObject($obj) {
//		$class = $obj::class;
//		$res = empty(self::$reflection_object_cache[$class])
//			?null
//			:self::$reflection_object_cache[$class];
//		if (!$res) {
//			$res = self::$reflection_object_cache[$class] = new ReflectionObject($obj);
//		}
//
//		return $res;
//	}

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
		$first,
		$filter,
        $static,
        $dynamic,
	): array {
		$spells = [];
        if (is_string($entity)) {
            $entity_class = $entity;
        } else {
            $entity_class = $entity::class;
        }

		foreach ($item_reflections as $item_reflection) {
            /**
             * @var ReflectionMethod|ReflectionObject|ReflectionClassConstant|ReflectionProperty $item_reflection
             */
            if (!($static && $dynamic)) {
                if ($static && !$item_reflection->isStatic()) {
                    continue;
                }
                if ($dynamic && $item_reflection->isStatic()) {
                    continue;
                }
            }
            $item_reflection_class = $item_reflection::class;
			$applied_attr_reflections = $item_reflection->getAttributes($attr_class);
			if (empty($applied_attr_reflections)) {
				continue;
			}
			$spell = self::$spelling_cache[$entity_class][$item_reflection_class][$item_reflection->getName()] ?? null;
			if (!$spell) {
				$spell = static::buildSpell(
					$item_reflection,
					$attr_class,
					$entity,
				);
				self::$spelling_cache[$entity_class][$item_reflection_class][$item_reflection->getName()] = $spell;
			}
			// MARK Filter callback must be here!
			if ($filter && is_callable($filter)) {
				$is_in = $filter($spell);
				if ($is_in === true || $is_in === null) {
					$spells[] = $spell;
				}
			} else {
				$spells[] = $spell;
			}
			////
			if ($spell && $first) {
				break;
			}
		}

		return $spells;
	}

	protected static function buildSpell(
		$entity_reflection,
		$attr_class,
		$owner_entity,
	): Spell|array {
		$attr_reflections = $entity_reflection->getAttributes();

		$object = null;
		if (is_string($owner_entity)) {
			$class = $owner_entity;
		} else {
			$object = $owner_entity;
			$class = $object::class;
		}

		$spell = new Spell(
			$class,
			$object,
			$entity_reflection,
			$attr_reflections,
		);

		return $spell;
	}

	static function findAttributeProcessedData(
		object|string $entity,
		string        $attr_class,
		              ...$args,
	): mixed {
		return $attr_class::process($entity, ...$args);
	}

}
