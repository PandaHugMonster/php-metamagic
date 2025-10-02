<?php

namespace spaf\metamagic\enums;

enum TargetType: string {

	case ClassType = "class";
	case ConstType = "constant";
	case FieldType = "field";
	case MethodType = "method";

}
