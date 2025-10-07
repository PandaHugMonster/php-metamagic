<?php

namespace spaf\metamagic\spells;


use Closure;
use spaf\metamagic\abstract\AbstractSpell;

/**
 * Spell representing a method of a class/object ref
 */
class SpellMethod extends AbstractSpell {

	/** @var Closure|null closure of the method on the object */
	public Closure|null $closure {
		get {
			// NOTE If $object == null, then static method is used
			return $this->target?->reflection->getClosure($this->target->object);
		}
	}

	/**
	 * Invoking the method with args
	 *
	 * @param mixed ...$args args to be passed into the method on object
	 * @return mixed
	 */
	function invoke(mixed ...$args): mixed {
		$closure = $this->closure;
		return $closure(...$args);
	}

	function __invoke(...$args) {
		return $this->invoke(...$args);
	}

}
