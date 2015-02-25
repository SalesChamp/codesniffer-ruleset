<?php

namespace DotBlue\Sniffs\WhiteSpace;

use PHP_CodeSniffer_File;
use PHP_CodeSniffer_Sniff;


class OperatorsSpacingSniff implements PHP_CodeSniffer_Sniff
{

	/**
	 * {@inheritdoc}
	 */
	public function register()
	{
		return [
			T_EQUAL,
			T_MINUS,
			T_MINUS_EQUAL,
			T_PLUS,
			T_PLUS_EQUAL,
			T_MULTIPLY,
			T_MUL_EQUAL,
			T_DIVIDE,
			T_DIV_EQUAL,
			T_MODULUS,
			T_MOD_EQUAL,
			T_CONCAT_EQUAL,
			T_STRING_CONCAT,
			T_IS_EQUAL,
			T_IS_IDENTICAL,
			T_IS_NOT_EQUAL,
			T_IS_NOT_IDENTICAL,
			T_IS_GREATER_OR_EQUAL,
			T_IS_SMALLER_OR_EQUAL,
			T_IS_GREATER_OR_EQUAL,
			T_IS_SMALLER_OR_EQUAL,
			T_OR_EQUAL,
			T_XOR_EQUAL,
			T_BOOLEAN_AND,
			T_BOOLEAN_OR,
			T_AND_EQUAL,
			T_DOUBLE_ARROW,
		];
	}



	/**
	 * {@inheritdoc}
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();

		$operand = $tokens[$stackPtr];
		$prev = $tokens[$stackPtr - 1];
		$next = $tokens[$stackPtr + 1];

		if ($prev['code'] !== T_WHITESPACE) {
			$fix = $phpcsFile->addFixableError('There must be one space before "' . $operand['content'] . '". No space found.', $stackPtr);

			if ($fix) {
				$phpcsFile->fixer->beginChangeset();
				$phpcsFile->fixer->addContentBefore($stackPtr, ' ');
				$phpcsFile->fixer->endChangeset();
			}
		} else {
			$whitespace = strlen($tokens[$stackPtr - 1]['content']);
			if ($whitespace > 1) {
				$fix = $phpcsFile->addFixableError('There must be one space before "' . $operand['content'] . '". ' . $whitespace . ' spaces found.', $stackPtr);

				if ($fix) {
					$phpcsFile->fixer->beginChangeset();
					$phpcsFile->fixer->replaceToken($stackPtr - 1, ' ');
					$phpcsFile->fixer->endChangeset();
				}
			}
		}

		if ($next['code'] !== T_WHITESPACE) {
			$fix = $phpcsFile->addFixableError('There must be one space after "' . $operand['content'] . '". No space found.', $stackPtr);

			if ($fix) {
				$phpcsFile->fixer->beginChangeset();
				$phpcsFile->fixer->addContent($stackPtr, ' ');
				$phpcsFile->fixer->endChangeset();
			}
		} else {
			$whitespace = strlen($tokens[$stackPtr + 1]['content']);
			if ($whitespace > 1) {
				$fix = $phpcsFile->addFixableError('There must be one space after "' . $operand['content'] . '". ' . $whitespace . ' spaces found.', $stackPtr);

				if ($fix) {
					$phpcsFile->fixer->beginChangeset();
					$phpcsFile->fixer->replaceToken($stackPtr + 1, ' ');
					$phpcsFile->fixer->endChangeset();
				}
			}
		}
	}

}
