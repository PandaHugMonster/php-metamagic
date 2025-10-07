<?php

namespace spaf\metamagic;

use Generator;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionObject;
use Reflector;
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


/**
 * Meta Magic major static helper class.
 *
 * Almost all the main functionality of Meta Magic is concentrated in this class.
 *
 * Main methods:
 * * {@see MetaMagic::findSpells()} Searching spells through generator
 * * {@see MetaMagic::findSpellOne()} Searching a first available spell
 */
class MetaMagic {

	/**
	 * Searching for class/object members with specific filtering parameters
	 *
	 * Method returns a generator that will return children of {@see AbstractSpell} Spells.
	 * The purpose of generator is to reduce overhead when searching and filtering to improve
	 * performance.
	 *
	 * @param class-string|object|class-string[]|object[] $refs
	 *        class or object or array of classes and/or objects
	 * @param class-string[]|string|null $attrs
	 *        class of a target attribute or array of classes of target attributes or nothing
	 * @param TargetType|TargetType[]|null $types
	 *        Enum value or array of enum values of {@see TargetType}
	 * @param bool $is_instance_of
	 *        If set to true (default) then children of the target attribute classes
	 *        also will fit the search pattern.
	 *        If set to false - only exact target attributes will fit the search pattern.
	 * @param null|callable(AbstractSpell): null|AbstractSpell $filter
	 *        Callable filtering out in place the found Spells
	 *        (Can improve performance in case of very custom search requirements).
	 *        Returning `null` from callable will skip the current Spell from the result.
	 *
	 * @return Generator
	 *
	 * @throws ClassReferenceException In case of incorrect class-string ref provided
	 *
	 * @noinspection PhpDocSignatureInspection
	 */
	static public function findSpells(
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
				yield from static::findSpellOnSingleRef(
					ref: $ref,
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
	 * Searching for class/object first member with specific filtering parameters
	 *
	 * This method is exactly like {@see static::findSpells()} but returns only a first occurrence
	 * if exists or null.
	 *
	 * Strongly recommended to use `$filter` parameter to filter out unnecessary
	 * Spells with max quality of search.
	 *
	 * @param class-string|object|class-string[]|object[] $refs
	 *        class or object or array of classes and/or objects
	 * @param class-string[]|string|null $attrs
	 *        class of a target attribute or array of classes of target attributes or nothing
	 * @param TargetType|TargetType[]|null $types
	 *        Enum value or array of enum values of {@see TargetType}
	 * @param bool $is_instance_of
	 *        If set to true (default) then children of the target attribute classes
	 *        also will fit the search pattern.
	 *        If set to false - only exact target attributes will fit the search pattern.
	 * @param null|callable(AbstractSpell): null|AbstractSpell $filter
	 *        Callable filtering out in place the found Spells
	 *        (Can improve performance in case of very custom search requirements).
	 *        Returning `null` from callable will skip the current Spell from the result.
	 *
	 * @return AbstractSpell | null
	 *
	 * @throws ClassReferenceException In case of incorrect class-string ref provided
	 *
	 * @noinspection PhpDocSignatureInspection
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
	 * Returns proper reflection wrapper object in case of object/class-string is provided
	 *
	 * @param class-string|object $value Class reference or object reference
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

	/**
	 * Preparing selected target types
	 *
	 * * In case of TargetType object provided - it is being wrapped into array
	 * * In case of null provided - all the types are included
	 *
	 * @param array|TargetType|null $types
	 * @return array|TargetType[]
	 */
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

	/**
	 * Small translation function of `bool $is_instance_of`
	 * into `ReflectionAttribute::IS_INSTANCEOF`
	 *
	 * @param bool $is_instance_of
	 * @return int
	 */
	static protected function prepareAttrFlags(bool $is_instance_of): int {
		return $is_instance_of
			?ReflectionAttribute::IS_INSTANCEOF
			:0;
	}

	/**
	 * Preparing TargetReference object based on provided params
	 *
	 * @param Reflector $reflection reference reflection
	 * @param class-string|object $obj_or_class target object or class
	 *
	 * @return TargetReference
	 */
	static protected function getTargetReferenceFromReflection(
		Reflector $reflection,
		string|object $obj_or_class
	): TargetReference {
		$target = new TargetReference();
		$target->reflection = $reflection;
		if (!is_string($obj_or_class)) {
			$target->object = $obj_or_class;
		}

		return $target;
	}

	/**
	 * Generating spells based on the filtering parameters
	 *
	 * @param string $spell_class Target spell class
	 * @param string|object $ref Reference class or object
	 * @param Reflector[] $member_reflections Target member reflections (for method, const, etc.)
	 * @param class-string[]|class-string|null $attrs Target attr class or array of attr classes
	 * @param int $attr_flags Attr flags (instance of, etc.)
	 * @param null|callable(AbstractSpell): null|AbstractSpell $filter Custom filtering callable
	 *
	 * @return Generator
	 *
	 * @noinspection PhpDocSignatureInspection
	 */
	static protected function generateSpells(
		string            $spell_class,
		string|object     $ref,
		array             $member_reflections,
		array|string|null $attrs,
		int               $attr_flags,
		callable|null     $filter,
	): Generator {
		if (!is_array($attrs)) {
			$attrs = [$attrs];
		}
		foreach ($member_reflections as $reflection) {
			if (count($attrs) == 0) {
				$target = static::getTargetReferenceFromReflection(
					$reflection,
					$ref
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
							$ref
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
	 * Search spells on a single ref
	 *
	 * @param class-string|object $ref Reference class-string or object
	 * @param class-string[]|string|null $attrs Target attr class or array of target attr classes
	 * @param TargetType|TargetType[]|null $types Target type or array of types
	 * @param bool $is_instance_of
	 *        If set to true (default) then children of the target attribute classes
	 *        also will fit the search pattern.
	 *        If set to false - only exact target attributes will fit the search pattern.
	 * @param null|callable(AbstractSpell): null|AbstractSpell $filter Custom filtering callable
	 *
	 * @return Generator
	 *
	 * @throws ReflectionException
	 * @noinspection PhpDocSignatureInspection
	 */
	static protected function findSpellOnSingleRef(
		string|object         $ref,
		array|string|null     $attrs,
		array|TargetType|null $types,
		bool                  $is_instance_of,
		callable|null         $filter
	): Generator {
		$class_reflection = static::getClassReflection($ref);
		$types = static::prepareTypes($types);
		$attr_flags = static::prepareAttrFlags($is_instance_of);

		$common_args = [
			"ref" => $ref,
			"attrs" => $attrs,
			"attr_flags" => $attr_flags,
		];

		foreach ($types as $type) {
			yield from match ($type) {
				TargetType::ClassType => static::generateSpells(
					...$common_args,
					spell_class: SpellClass::class,
					member_reflections: [$class_reflection],
					filter: $filter,
				),
				TargetType::ConstType => static::generateSpells(
					...$common_args,
					spell_class: SpellConstant::class,
					member_reflections: $class_reflection->getReflectionConstants(),
					filter: $filter,
				),
				TargetType::FieldType => static::generateSpells(
					...$common_args,
					spell_class: SpellField::class,
					member_reflections: $class_reflection->getProperties(),
					filter: $filter,
				),
				TargetType::MethodType => static::generateSpells(
					...$common_args,
					spell_class: SpellMethod::class,
					member_reflections: $class_reflection->getMethods(),
					filter: $filter,
				),
			};
		}
	}

}