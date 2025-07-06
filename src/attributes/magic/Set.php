<?php

namespace spaf\metamagic\attributes\magic;

use Attribute;
use spaf\metamagic\basic\BasicMetaMagicAttribute;
use spaf\metamagic\components\Spell;
use spaf\metamagic\traits\MetaMagicAttributeTrait;

/**
 * `Set` is triggered when non-existing field is accessed for value writing.
 *
 * This is a shortcut for `__set()` PHP magic method.
 *
 * PHP Magic Method - https://www.php.net/manual/en/language.oop5.overloading.php#object.set
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Set extends BasicMetaMagicAttribute {
	use MetaMagicAttributeTrait;

	function process(Spell $spell, ...$args) {
		$entity = $spell->entity;

		static::runMethod($entity, $args);
	}
}
