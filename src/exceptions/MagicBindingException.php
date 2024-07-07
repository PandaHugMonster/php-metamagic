<?php

namespace spaf\metamagic\exceptions;

use Exception;

class MagicBindingException extends Exception {

	const TYPE_CALL = "Call";
	const TYPE_COPY_SHALLOW = "CopyShallow";
	const TYPE_DEBUG_INFO = "DebugInfo";
	const TYPE_DEL = "Del";
	const TYPE_GET = "Get";
	const TYPE_INVOKE = "Invoke";
	const TYPE_IS_ASSIGNED = "IsAssigned";
	const TYPE_SET = "Set";
	const TYPE_TO_STRING = "ToString";

	public $magic_method_class;
	public $magic_method_type;

}