<?php

use spaf\metamagic\enums\TargetType;
use spaf\metamagic\MetaMagic;
use spaf\metamagic\spells\SpellMethod;

/**
 * Handler to perform setting/getting based on "set" or "get" method name prefix
 */
trait GetterSetterHandlerTrait {

	protected function _prepareTargetSpell($prefix, $name, $attrs): SpellMethod | null {
		$orig_name = $name;
		$name = "{$prefix}{$orig_name}";

		$generator = MetaMagic::findSpells(
			refs: $this,
			attrs: $attrs,
			types: TargetType::MethodType,
		);

		$target_spell = null;
		foreach ($generator as $spell) {
			/** @var SpellMethod $spell */
			if ($spell->target->reflection->name == $name) {
				$target_spell = $spell;
				break;
			}
		}

		return $target_spell;
	}

	function __set($name, $val) {
		$spell = $this->_prepareTargetSpell("set", $name, Setter::class);
		if (empty($spell)) {
			throw new ValueError("No such settable property \"{$name}\" found");
		}

		$spell($val);
	}

	function __get($name) {
		$spell = $this->_prepareTargetSpell("get", $name, Getter::class);
		if (empty($spell)) {
			throw new ValueError("No such gettable property \"{$name}\" found");
		}

		return $spell();
	}

}