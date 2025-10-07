<?php

namespace spaf\metamagic;

use Generator;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionObject;
use spaf\metamagic\abstract\AbstractSpell;
use spaf\metamagic\components\TargetReference;
use spaf\metamagic\enums\TargetType;
use spaf\metamagic\exceptions\ClassReferenceException;
use spaf\metamagic\spells\SpellClass;
use spaf\metamagic\spells\SpellConstant;
use spaf\metamagic\spells\SpellField;
use spaf\metamagic\spells\SpellMethod;
use function count;
use function is_array;
use function is_string;

class MetaMagic {

	/**
	 * @param class-string|object|class-string[]|object[] $refs
	 * @param class-string[]|string|null $attrs
	 * @param TargetType[]|null $types
	 * @param bool $is_instance_of
	 * @param callable|null $filter
	 * @return Generator
	 * @throws ClassReferenceException
	 */
	static function findSpells(
		string|object|array   $refs,
		array|string|null     $attrs = null,
		array|TargetType|null $types = null,
		bool                  $is_instance_of = true,
		null|callable         $filter = null,
	): Generator {
		if (!is_array($refs)) {
			$refs = [$refs];
		}
		foreach ($refs as $ref) {
			try {
				yield from static::findThem(
					class_or_obj: $ref,
					attrs: $attrs,
					types: $types,
					is_instance_of: $is_instance_of,
					filter: $filter,
				);
			} catch (ReflectionException $e) {
				throw new ClassReferenceException(
					"Wrong Class-Reference: \"{$ref}\""
				);
			}
		}
	}

	/**
	 * @param class-string|object|class-string[]|object[] $refs
	 * @param class-string[]|string|null $attrs
	 * @param TargetType[]|null $types
	 * @param bool $is_instance_of
	 * @param callable|null $filter
	 * @return AbstractSpell|null
	 * @throws ClassReferenceException
	 */
	static public function findSpellOne(
		string|object|array   $refs,
		array|string|null     $attrs = null,
		array|TargetType|null $types = null,
		bool                  $is_instance_of = true,
		null|callable         $filter = null,
	): AbstractSpell | null {
		$generator = static::findSpells(
			refs: $refs,
			attrs: $attrs,
			types: $types,
			is_instance_of: $is_instance_of,
			filter: $filter,
		);

		return $generator->current();
	}

	/**
	 * @param string|object $value
	 * @return ReflectionClass|ReflectionObject
	 * @throws ReflectionException
	 */
	static protected function getClassReflection(
		string|object $value
	): ReflectionClass|ReflectionObject {
		if (is_string($value)) {
			return new ReflectionClass($value);
		}

		return new ReflectionObject($value);
	}

	static protected function prepareTypes(array|TargetType|null $types): array {
		if (empty($types)) {
			return [
				TargetType::ClassType,
				TargetType::ConstType,
				TargetType::FieldType,
				TargetType::MethodType,
			];
		}

		if (!is_array($types)) {
			return [$types];
		}

		return $types;
	}

	static protected function prepareAttrFlags(bool $is_instance_of): int {
		$res = $is_instance_of
			?ReflectionAttribute::IS_INSTANCEOF
			:0;
		return $res;
	}

	static protected function getTargetReferenceFromReflection(
		$reflection,
		$obj_or_class
	): TargetReference {
		$target = new TargetReference();
		$target->reflection = $reflection;
		if (!is_string($obj_or_class)) {
			$target->object = $obj_or_class;
		}

		return $target;
	}

	/**
	 * @param $spell_class
	 * @param $class_or_obj
	 * @param $member_reflections
	 * @param $attrs
	 * @param $attr_flags
	 * @param $filter
	 * @return Generator
	 */
	static protected function createSpells(
		$spell_class,
		$class_or_obj,
		$member_reflections,
		$attrs,
		$attr_flags,
		$filter,
	): Generator {
		if (!is_array($attrs)) {
			$attrs = [$attrs];
		}
		foreach ($member_reflections as $reflection) {
			if (count($attrs) > 0) {
				$target = static::getTargetReferenceFromReflection(
					$reflection,
					$class_or_obj
				);
				$spell = new $spell_class($target);

				if ($filter) {
					if ($filter($spell)) {
						yield $spell;
					}
				} else {
					yield $spell;
				}

			} else {
				foreach ($attrs as $attr) {
					$found_attrs = $reflection->getAttributes($attr, $attr_flags);

					if ($found_attrs) {
						$target = static::getTargetReferenceFromReflection(
							$reflection,
							$class_or_obj
						);
						foreach ($found_attrs as $attr_reflection) {
							/** @var ReflectionAttribute $attr_reflection */
							$attr_instance = $attr_reflection->newInstance();
							$spell = new $spell_class($target, $attr_instance);

							if ($filter) {
								if ($filter($spell)) {
									yield $spell;
								}
							} else {
								yield $spell;
							}
						}
					}
				}
			}
		}
	}

	/**
	 * @param class-string|object $class_or_obj
	 * @param class-string[]|string|null $attrs
	 * @param TargetType[]|null $types
	 * @param bool $is_instance_of
	 * @param callable|null $filter
	 * @return Generator
	 * @throws ReflectionException
	 */
	static protected function findThem(
		string|object           $class_or_obj,
		array|string|null       $attrs,
		array|TargetType|null   $types,
		bool                    $is_instance_of,
		callable|null           $filter
	): Generator {
		$class_reflection = static::getClassReflection($class_or_obj);
		$types = static::prepareTypes($types);
		$attr_flags = static::prepareAttrFlags($is_instance_of);

		$common_args = [
			"class_or_obj" => $class_or_obj,
			"attrs" => $attrs,
			"attr_flags" => $attr_flags,
		];

		foreach ($types as $type) {
			yield from match ($type) {
				TargetType::ClassType => static::createSpells(
					...$common_args,
					spell_class: SpellClass::class,
					member_reflections: [$class_reflection],
					filter: $filter,
				),
				TargetType::ConstType => static::createSpells(
					...$common_args,
					spell_class: SpellConstant::class,
					member_reflections: $class_reflection->getReflectionConstants(),
					filter: $filter,
				),
				TargetType::FieldType => static::createSpells(
					...$common_args,
					spell_class: SpellField::class,
					member_reflections: $class_reflection->getProperties(),
					filter: $filter,
				),
				TargetType::MethodType => static::createSpells(
					...$common_args,
					spell_class: SpellMethod::class,
					member_reflections: $class_reflection->getMethods(),
					filter: $filter,
				),
			};
		}
	}

}