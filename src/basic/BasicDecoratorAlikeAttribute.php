<?php

namespace spaf\metamagic\basic;

use Attribute;
use spaf\metamagic\components\Spell;

/**
 */
#[Attribute]
abstract class BasicDecoratorAlikeAttribute extends BasicAttribute {

	public ?Spell $spell = null;

	abstract function conjure(Spell $spell, ...$args);

	function __invoke(...$args) {
		return $this->conjure($this->spell, ...$args);
	}

}
