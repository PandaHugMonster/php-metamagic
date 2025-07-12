<?php

namespace spaf\metamagic\spells;


use spaf\metamagic\abstract\AbstractSpell;

class SpellConstant extends AbstractSpell {

	public mixed $value {
		get {
			return $this->target->reflection->getValue();
		}
	}

}
