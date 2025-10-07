<?php

namespace spaf\metamagic\spells;


use spaf\metamagic\abstract\AbstractSpell;

/**
 * Spell representing a field/property of a class/object ref
 */
class SpellField extends AbstractSpell {

	/** @var mixed value of the field/property */
	public mixed $value {
		get {
			return $this->target->reflection->getValue($this->target->object);
		}
	}

}
