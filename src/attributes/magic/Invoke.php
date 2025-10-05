<?php

namespace spaf\metamagic\attributes\magic;

use Attribute;
use spaf\metamagic\basic\BasicMetaMagicAttribute;
use spaf\metamagic\components\Spell;
use spaf\metamagic\traits\MetaMagicAttributeTrait;

/**
 * `Invoke` is triggered when object is being invoked as a function.
 *
 * This is a shortcut for `__invoke()` PHP magic method.
 *
 * PHP Magic Method - https://www.php.net/manual/en/language.oop5.magic.php#object.invoke
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Invoke extends BasicMetaMagicAttribute {
	use MetaMagicAttributeTrait;

	function process(Spell $spell, ...$args) {
		$entity = $spell->entity;
        return static::runMethod($entity, $args);
	}
}
