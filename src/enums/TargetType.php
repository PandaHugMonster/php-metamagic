<?php

namespace spaf\metamagic\enums;

/**
 * Target types enum
 *
 * Used for filtering by member type
 */
enum TargetType: string {

	case ClassType = "class";
	case ConstType = "constant";
	case FieldType = "field";
	case MethodType = "method";

}
