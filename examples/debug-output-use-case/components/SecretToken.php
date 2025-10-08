<?php

class SecretToken {
	use DebuggableTrait;

	function __construct(
		public string|null $name = null,

		#[DebugHide("**hidden-username**")]
		public string|null $secret_username = null,

		#[DebugHide]
		public string|null $secret_token = null,
	) {}

	function __toString() {
		$res = "";
		if (!is_null($this->secret_username)) {
			$res = "{$this->secret_username}@";
		}
		$res .= "{$this->secret_token}";
		return $res;
	}

}
