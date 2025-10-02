<?php

namespace spaf\metamagic\enums;

enum TargetAspect: string {

	case IsPublic = "is-public";
	case IsProtected = "is-protected";
	case IsPrivate = "is-private";

	case IsStatic = "is-static";
	case IsFinal = "is-final";
	case IsAbstract = "is-abstract";
	case IsReadOnly = "is-read-only";

}
