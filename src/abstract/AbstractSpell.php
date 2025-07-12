<?php

namespace spaf\metamagic\abstract;

use spaf\metamagic\components\TargetReference;

abstract class AbstractSpell {

	/**
	 * @var TargetReference|null Target member
	 */
	public TargetReference|null $target;

	public string $name {
		get {
			return $this->target->name;
		}
	}

}