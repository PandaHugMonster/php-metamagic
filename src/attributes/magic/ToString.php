<?php

namespace spaf\metamagic\attributes\magic;

use Attribute;
use ReflectionMethod;
use spaf\metamagic\basic\BasicMetaMagicAttribute;
use spaf\metamagic\components\Spell;
use spaf\metamagic\exceptions\TypeNotAllowedException;
use spaf\metamagic\traits\MetaMagicAttributeTrait;
use function is_null;

/**
 */
#[Attribute(Attribute::TARGET_METHOD)]
class ToString extends BasicMetaMagicAttribute {
	use MetaMagicAttributeTrait;

	function __construct(
		public $null_conversion = true,
	) {

	}

	function process(Spell $spell, ...$args) {
		$entity = $spell->entity;

		/** @var ReflectionMethod $refl */
		$refl = $spell->item_reflection;

		$res = $refl->invoke($entity, $args);

		if (is_null($res)) {
			if (!$this->null_conversion) {
				throw new TypeNotAllowedException("Value of `null` to string conversion is not allowed");
			}
			$res = "";
		}
		return $res;
	}
}
