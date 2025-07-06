<?php

namespace spaf\metamagic\attributes\magic;

use Attribute;
use spaf\metamagic\basic\BasicMetaMagicAttribute;
use spaf\metamagic\components\Spell;
use spaf\metamagic\traits\MetaMagicAttributeTrait;

/**
 * `Get` is triggered when non-existing field is accessed for value retrieval.
 *
 * This is a shortcut for `__get()` PHP magic method.
 *
 * PHP Magic Method - https://www.php.net/manual/en/language.oop5.overloading.php#object.get
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Get extends BasicMetaMagicAttribute {
	use MetaMagicAttributeTrait;

	function process(Spell $spell, ...$args) {
		$entity = $spell->entity;

		return static::runMethod($entity, $args);
	}
}
