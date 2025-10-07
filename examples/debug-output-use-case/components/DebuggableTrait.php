<?php


use spaf\metamagic\enums\TargetType;
use spaf\metamagic\MetaMagic;
use spaf\metamagic\spells\SpellField;


trait DebuggableTrait {
	function __debugInfo() {

		$generator = MetaMagic::findSpells(
			refs: $this,
			types: TargetType::FieldType,
			// We don't use any attribute classes for search, so
			// spells will find all the selected members
			// without filtering by attributes
		);

		$res = [];
		foreach ($generator as $spell) {
			/** @var SpellField $spell */
			$target_reflection = $spell->target->reflection;
			$target_obj = $spell->target->object;

			$name = $target_reflection->name;
			$val = $target_obj->$name;

			$debug_hide_reflection = $target_reflection->getAttributes(DebugHide::class);

			if (!empty($debug_hide_reflection)) {
				$debug_hide_reflection = $debug_hide_reflection[0];
				/** @var DebugHide $debug_hide_attr */
				$debug_hide_attr = $debug_hide_reflection->newInstance();
				$val = $debug_hide_attr->text;
			}
			$res[$name] = $val;
		}

		return $res;
	}
}
