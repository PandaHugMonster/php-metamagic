<?php

namespace spaf\metamagic\attributes\magic;

use Attribute;
use ReflectionMethod;
use spaf\metamagic\basic\BasicDecoratorAlikeAttribute;
use spaf\metamagic\components\Spell;
use function spaf\simputils\basic\pd;

/**
 * Magic Method Serialize through Attribute
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Invoke extends BasicDecoratorAlikeAttribute {

	function conjure(Spell $spell, ...$args) {
		/** @var ReflectionMethod $method_reflection */
		$method_reflection = $spell->item_reflection;
		$callback = $method_reflection->getClosure($spell->entity);
		return $callback(...$args);
	}
}
