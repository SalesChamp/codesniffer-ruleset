<?php

namespace DotBlue\Sniffs\Php;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;


class EmptyInlineMethodsSniff implements Sniff
{

	public function register()
	{
		return [
			T_FUNCTION,
			T_CLOSURE
		];
	}



	public function process(File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();

		$openBrace = $phpcsFile->findNext(T_OPEN_CURLY_BRACKET, $stackPtr);
		if ($openBrace === FALSE) {
			return;
		}
		$openBraceLine = $tokens[$openBrace]['line'];
		$closeBrace = $phpcsFile->findNext(T_CLOSE_CURLY_BRACKET, $openBrace);
		if ($closeBrace === FALSE) {
			return;
		}
		$closeBraceLine = $tokens[$closeBrace]['line'];
		$isMultiline = $closeBraceLine - $openBraceLine > 0;

		if ($isMultiline || $openBrace === FALSE || $closeBrace === FALSE) {
			return;
		}

		$bodyTokens = array_slice($tokens, ++$openBrace, $closeBrace - $openBrace);
		$hasBody = FALSE;

		foreach ($bodyTokens as $bodyToken) {
			if ($bodyToken['type'] !== T_WHITESPACE) {
				$hasBody = TRUE;
				break;
			}
		}

		if ($hasBody) {
			$phpcsFile->addError('Inline method is allowed only for methods without body', $openBrace, 'EmptyInline');
		}
	}

}
