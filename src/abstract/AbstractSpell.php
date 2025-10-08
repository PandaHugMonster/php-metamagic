<?php

namespace spaf\metamagic\abstract;

use spaf\metamagic\components\TargetReference;
use spaf\metamagic\enums\TargetType;
use function is_null;


/**
 * Abstract Spell
 *
 * Targeted spells are usually inherited from it
 *
 * Spell is basically aggregation of target reference of a member
 * and related (if applicable) attribute.
 *
 * Spell is encapsulating:
 * * Single {@see TargetReference}
 * * Single target attribute object
 *
 * And provide some simple methods and dynamic fields
 *
 * @link docs/about-spells.md About Spells
 */
abstract class AbstractSpell {

	/** @var TargetReference|null Target member */
	public TargetReference|null $target;

	/** @var object|null Target attribute (if applicable) */
	public object|null $attr;

	/** @var TargetType|null Target type */
	public TargetType|null $type;

	/** @var string Name got from `$target` field if available */
	public string $name {
		get {
			return $this?->target->name;
		}
	}

	/** @var bool Is Static flag got from `$target` field if available */
	public bool $is_static {
		get {
			return $this?->target->is_static;
		}
	}

	/** @var bool Return true if `$object` is not null (object ref, and not just a class ref) */
	public bool $has_object {
		get {
			return !is_null($this->object);
		}
	}

	/** @var object|null Target object got from `$target` field if available */
	public object|null $object {
		get {
			return $this?->target->object;
		}
	}

	function __construct(
		TargetReference|null $target = null,
		object|null $attr = null,
		TargetType|null $type = null,
	) {
		$this->target = $target;
		$this->attr = $attr;
		$this->type = $type;
	}

}