<?php

namespace spaf\metamagic\attributes\magic;

use Attribute;
use spaf\metamagic\basic\BasicMetaMagicAttribute;
use spaf\metamagic\components\Spell;
use spaf\metamagic\traits\MetaMagicAttributeTrait;

/**
 * `Del` is triggered when `unset()` PHP functions is invoked on an object.
 *
 * This is a shortcut for `__unset()` PHP magic method.
 *
 * PHP Magic Method - https://www.php.net/manual/en/language.oop5.overloading.php#object.unset
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Del extends BasicMetaMagicAttribute {
	use MetaMagicAttributeTrait;

	function process(Spell $spell, ...$args) {
		$entity = $spell->entity;

		static::runMethod($entity, $args);
	}
}
