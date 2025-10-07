<?php

namespace spaf\metamagic\spells;


use spaf\metamagic\abstract\AbstractSpell;

/**
 * Spell representing a constant of a class ref
 */
class SpellConstant extends AbstractSpell {

	/** @var mixed value of the constant */
	public mixed $value {
		get {
			return $this->target->reflection->getValue();
		}
	}

}
