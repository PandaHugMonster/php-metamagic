<?php


class FakeObject {
	use ToStrTrait;

	#[ToStr]
	public function toStr() {
		return "I'm a fake object";
	}

}
