<?php

namespace DotBlue\Sniffs\Php;

use PHP_CodeSniffer\Standards\Squiz;


class ForbiddenFunctionsSniff extends Squiz\Sniffs\PHP\DiscouragedFunctionsSniff
{

	public $forbiddenFunctions = [
		'd' => NULL,
		'dump' => NULL,
		'var_dump' => NULL,
	];

}
