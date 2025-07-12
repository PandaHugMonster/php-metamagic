<?php

namespace spaf\metamagic\spells;


use spaf\metamagic\abstract\AbstractSpell;

class SpellMethod extends AbstractSpell {

	public $closure {
		get {
			// NOTE If $object == null, then static method is used
			return $this->target->reflection->getClosure($this->target->object);
		}
	}

	function invoke(...$args) {
		$closure = $this->closure;
		return $closure(...$args);
	}

	function __invoke(...$args) {
		return $this->invoke(...$args);
	}

}
