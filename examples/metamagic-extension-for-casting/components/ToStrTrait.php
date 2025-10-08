<?php

trait ToStrTrait {
	function __toString() {
		return cast($this, CastTo::Str);
	}
}
