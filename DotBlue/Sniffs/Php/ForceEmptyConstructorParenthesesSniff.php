<?php

namespace DotBlue\Sniffs\Php;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;


class ForceEmptyConstructorParenthesesSniff implements Sniff
{

	public function register()
	{
		return [
			T_NEW,
		];
	}



	public function process(File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();

		$end = $phpcsFile->findNext([
			T_CLOSE_PARENTHESIS,
			T_COMMA,
			T_SEMICOLON,
		], $stackPtr);

		$hasParentheses = $phpcsFile->findNext(T_OPEN_PARENTHESIS, $stackPtr, $end);

		if (!$hasParentheses) {
			$fix = $phpcsFile->addFixableError('There must be parentheses after constructor call.', $stackPtr, 'EmptyParens');

			if ($fix) {
				$phpcsFile->fixer->beginChangeset();
				$phpcsFile->fixer->addContentBefore($end, '()');
				$phpcsFile->fixer->endChangeset();
			}
		}
	}

}
