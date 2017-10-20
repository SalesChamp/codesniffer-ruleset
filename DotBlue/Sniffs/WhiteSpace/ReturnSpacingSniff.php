<?php

namespace DotBlue\Sniffs\WhiteSpace;

use PHP_CodeSniffer_File;
use PHP_CodeSniffer_Sniff;


class ReturnSpacingSniff implements PHP_CodeSniffer_Sniff
{

	use FixEmptyLines;



	/**
	 * {@inheritdoc}
	 */
	public function register()
	{
		return [
			T_RETURN,
		];
	}



	/**
	 * {@inheritdoc}
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();
		$prev = $phpcsFile->findPrevious(T_WHITESPACE, $stackPtr - 1, $stackPtr - 10, $exclude = TRUE);

		if (!$prev) {
			return;
		}

		$lines = abs($tokens[$stackPtr]['line'] - $tokens[$prev]['line']);
		if ($tokens[$prev]['code'] === T_OPEN_CURLY_BRACKET) {
			if ($lines > 1) {
				$fix = $phpcsFile->addFixableError('There must be no empty lines between the opening brace and the return statement. Found ' . ($lines - 1), $stackPtr);
				if ($fix) {
					self::fixSpacing($phpcsFile, $prev, $lines, 3);
				}
			}
		} elseif (in_array($tokens[$prev]['code'], [T_CLOSE_CURLY_BRACKET, T_SEMICOLON])) {
			if ($lines !== 2) {
				$fix = $phpcsFile->addFixableError('There must be one empty line between the closing brace or semicolon and the return statement. Found ' . ($lines - 1), $stackPtr);
				if ($fix) {
					self::fixSpacing($phpcsFile, $prev, $lines, 2);
				}
			}
		}
	}

}
