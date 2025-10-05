<?php

namespace spaf\metamagic\attributes\magic;

use Attribute;
use spaf\metamagic\basic\BasicMetaMagicAttribute;
use spaf\metamagic\components\Spell;
use spaf\metamagic\traits\MetaMagicAttributeTrait;

/**
 * `IsAssigned` is triggered when non-existing field is checked for being set.
 *
 * This is a shortcut for `__isset()` PHP magic method.
 *
 * PHP Magic Method - https://www.php.net/manual/en/language.oop5.overloading.php#object.isset
 */
#[Attribute(Attribute::TARGET_METHOD)]
class IsAssigned extends BasicMetaMagicAttribute {
	use MetaMagicAttributeTrait;

	function process(Spell $spell, ...$args) {
		$entity = $spell->entity;

		return static::runMethod($entity, $args);
	}
}
