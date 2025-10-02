<?php

namespace spaf\metamagic\abstract;

use spaf\metamagic\components\TargetReference;
use function is_null;

abstract class AbstractSpell {

	/**
	 * @var TargetReference|null Target member
	 */
	public TargetReference|null $target;
	public object|null $attr;

	public string $name {
		get {
			return $this->target->name;
		}
	}

	public bool $is_static {
		get {
			return $this->target->is_static;
		}
	}

	public bool $has_object {
		get {
			return !is_null($this->object);
		}
	}

	public object|null $object {
		get {
			return $this->target->object;
		}
	}

	function __construct(TargetReference|null $target = null, object|null $attr = null) {
		$this->target = $target;
		$this->attr = $attr;
	}

}