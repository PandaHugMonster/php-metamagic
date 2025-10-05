<?php

namespace spaf\metamagic\attributes\magic;

use Attribute;
use spaf\metamagic\basic\BasicMetaMagicAttribute;
use spaf\metamagic\components\Spell;
use spaf\metamagic\traits\MetaMagicAttributeTrait;
use function is_string;

/**
 * `Call` is triggered when invoking inaccessible methods (static or not).
 *
 * This is a shortcut for `__call()` and `__callStatic()` PHP magic methods.
 *
 * For objects - https://www.php.net/manual/en/language.oop5.overloading.php#object.call
 * For classes - https://www.php.net/manual/en/language.oop5.overloading.php#object.callstatic
 *
 * If method signature is marked as `static` it will automatically detect that it's a `__callStatic` and not `__call`.
 * In both of cases signature of the attribute is the same: `#[Call]`.
 *
 * This is designed like that for convenience.
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Call extends BasicMetaMagicAttribute {
	use MetaMagicAttributeTrait;

	function process(Spell $spell, ...$args) {
		$entity = $spell->entity;

		$filter = is_string($entity)
			?fn(Spell $spell) => $spell->is_static
			:null;
        return static::runMethod(
            $entity,
            $args,
            $filter
        );
	}
}
