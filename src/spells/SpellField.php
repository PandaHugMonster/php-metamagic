<?php

namespace spaf\metamagic\spells;


use spaf\metamagic\abstract\AbstractSpell;

class SpellField extends AbstractSpell {
	public mixed $value {
		get {
			return $this->target->reflection->getValue($this->target->object);
		}
	}
}
