<?php

namespace spaf\metamagic\basic;

use Attribute;
use spaf\metamagic\components\Spell;
use spaf\metamagic\MetaMagic;
use function is_object;

/**
 */
#[Attribute]
abstract class BasicMetaMagicAttribute extends BasicAttribute {

	abstract function process(Spell $spell, ...$args);

	private static function _find(
		$entity,
		int $attr_target = Attribute::TARGET_ALL | Attribute::IS_REPEATABLE,
		?callable $filter = null,
		bool $first = false,
	) {

		$attr_class = static::class;
		if (is_object($entity)) {
			// NOTE Dynamic
			$entity_type = "D";
		} else {
			// NOTE Static
			$entity_type = "S";
		}

		return MetaMagic::find(
			entity: $entity,
			attr_class: $attr_class,
			attr_targets: $attr_target,
			first: $first,
			filter: $filter,
			static: $entity_type == "S",
			dynamic: $entity_type == "D",
		);

	}

	/**
	 * @param $entity
	 * @param int $attr_target
	 * @param callable|null $filter
	 * @return Spell[]|null
	 */
	static function getSpells(
		$entity,
		int $attr_target = Attribute::TARGET_ALL | Attribute::IS_REPEATABLE,
		?callable $filter = null
	): array|null {
		return static::_find($entity, $attr_target, $filter);
	}

	/**
	 * @param $entity
	 * @param int $attr_target
	 * @param callable|null $filter
	 * @return Spell|null
	 */
	static function getFirstSpell(
		$entity,
		int $attr_target = Attribute::TARGET_ALL | Attribute::IS_REPEATABLE,
		?callable $filter = null
	): ?Spell {
		return static::_find($entity, $attr_target, $filter, first: true);
	}

}
