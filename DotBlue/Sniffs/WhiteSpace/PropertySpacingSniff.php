<?php

namespace DotBlue\Sniffs\WhiteSpace;

use PHP_CodeSniffer_File;
use PHP_CodeSniffer_Tokens;
use PHP_CodeSniffer_Standards_AbstractVariableSniff;


class PropertySpacingSniff extends PHP_CodeSniffer_Standards_AbstractVariableSniff
{

	const ALLOWED_NEXT_MODIFIER = [
		T_PRIVATE => [T_PRIVATE, T_PROTECTED, T_PUBLIC],
		T_PROTECTED => [T_PROTECTED, T_PUBLIC],
		T_PUBLIC => [T_PUBLIC],
	];



	protected function processMemberVar(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();

		$modifier = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$scopeModifiers, $stackPtr);

		if ($modifier && ($tokens[$modifier]['line'] === $tokens[$stackPtr]['line'])) {
			// ignore static for now
			$isStatic = $phpcsFile->findNext(T_STATIC, $modifier + 1, $stackPtr);
			if ($isStatic) {
				return;
			}

			// find next declaration or docblock
			$next = $phpcsFile->findNext(array_merge(
				[T_DOC_COMMENT_OPEN_TAG],
				PHP_CodeSniffer_Tokens::$scopeModifiers
			), ($stackPtr + 1));
			$semicolon = $phpcsFile->findNext(T_SEMICOLON, $stackPtr);
			if ($next) {
				$diff = abs($tokens[$next]['line'] - $tokens[$semicolon]['line']);
				$function = $phpcsFile->findNext(T_FUNCTION, $next + 1);
				// if there is no variable between whatever we
				// found and the function, this is the last
				// variable and there should be three spaces
				$variable = $phpcsFile->findNext(T_VARIABLE, $next + 1, $function);
				if ($variable) {
					// we want three spaces between different
					// visibility levels, otherwise two
					$expected = 3;
					$nextModifier = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$scopeModifiers, ($stackPtr + 1), $variable);
					if ($nextModifier) {
						$thisCode = $tokens[$modifier]['code'];
						$nextCode = $tokens[$nextModifier]['code'];
						if ($thisCode !== $nextCode && !in_array($nextCode, self::ALLOWED_NEXT_MODIFIER[$thisCode])) {
							$phpcsFile->addError("Property visibility must be ordered from private to protected to public, found %s, next is %s", $stackPtr, 'VisibilityOrder', [token_name($thisCode), token_name($nextCode)]);
							return;
						}
					}
					if ($tokens[$modifier]['code'] !== $tokens[$nextModifier]['code']) {
						$expected = 4;
					}
					if ($diff !== $expected) {
						$phpcsFile->addError("Must have %d spaces between properties%s, found %d", $stackPtr, 'EmptyLines', [
							$expected - 1,
							($expected === 4 ? " with different visibility scopes" : ""),
							$diff - 1,
						]);
					}
				} elseif ($diff !== 4) {
					$phpcsFile->addError("Must have 3 spaces between last property and first function, found %d", $stackPtr, 'EmptyLines', [$diff - 1]);
				}
			}
		}
	}



	protected function processVariable(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		/* We don't care about normal variables. */
	}



	protected function processVariableInString(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		/* We don't care about normal variables. */
	}

}
