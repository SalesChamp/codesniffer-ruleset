<?php

namespace DotBlue\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;


class ReferenceSpacingSniff implements Sniff
{

	/**
	 * {@inheritdoc}
	 */
	public function register()
	{
		return [
			T_VARIABLE,
		];
	}



	/**
	 * {@inheritdoc}
	 */
	public function process(File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();
		$amp = $phpcsFile->findPrevious(T_BITWISE_AND, $stackPtr, $stackPtr - 2);
		if (!$amp) {
			return;
		}
		$prev = $tokens[$stackPtr - 1];
		if ($prev['code'] === T_WHITESPACE) {
			if ($prev['length'] === 1) {
				return;
			}
			$fix = $phpcsFile->addFixableError('There must be exactly one space between & and variable. Found ' . $prev['length'], $stackPtr, 'MultipleSpaces');
			if ($fix) {
				$phpcsFile->fixer->beginChangeset();
				$phpcsFile->fixer->replaceToken($stackPtr - 1, " ");
				$phpcsFile->fixer->endChangeset();
			}
		} else {
			$fix = $phpcsFile->addFixableError('There must be exactly one space between & and variable. Found 0', $stackPtr, 'ZeroSpaces');
			if ($fix) {
				$phpcsFile->fixer->beginChangeset();
				$phpcsFile->fixer->addContentBefore($stackPtr, " ");
				$phpcsFile->fixer->endChangeset();
			}
		}
	}

}
