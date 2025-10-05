<?php

/**
 * Syntactic sugar enum
 */
enum CastTo: string {

	case Int = ToInt::class;
	case Float = ToFloat::class;
	case Str = ToStr::class;
	case Bool = ToBool::class;
	case Array = ToArray::class;

}
