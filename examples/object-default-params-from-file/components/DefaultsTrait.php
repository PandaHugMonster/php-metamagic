<?php

use components\DefaultsAttr;
use spaf\metamagic\enums\TargetType;
use spaf\metamagic\MetaMagic;
use spaf\metamagic\spells\SpellClass;
use function spaf\simputils\basic\pd;

trait DefaultsTrait {
	protected function defaultsInit() {
		/** @var SpellClass $spell */
		$spell = MetaMagic::findSpellOne(
			refs: $this,
			attrs: DefaultsAttr::class,
			types: TargetType::ClassType,
		);
//		pd($spell, $this);
		$obj = $spell->target->object;
		/** @var DefaultsAttr $attr */
		$attr = $spell->attr;
		$attr->applyOnObject($obj);
	}
}
