<?php

namespace DotBlue\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHP_CodeSniffer\Sniffs\AbstractVariableSniff;


class PropertySpacingSniff extends AbstractVariableSniff
{

	use FixEmptyLines;



	const ALLOWED_NEXT_MODIFIER = [
		T_PRIVATE => [T_PRIVATE, T_PROTECTED, T_PUBLIC],
		T_PROTECTED => [T_PROTECTED, T_PUBLIC],
		T_PUBLIC => [T_PUBLIC],
	];



	protected function processMemberVar(File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();

		$modifier = $phpcsFile->findPrevious(Tokens::$scopeModifiers, $stackPtr);

		if ($modifier && ($tokens[$modifier]['line'] === $tokens[$stackPtr]['line'])) {
			// ignore static for now
			$isStatic = $phpcsFile->findNext(T_STATIC, $modifier + 1, $stackPtr);
			if ($isStatic) {
				return;
			}

			// find next declaration or docblock
			$next = $phpcsFile->findNext(array_merge(
				[T_DOC_COMMENT_OPEN_TAG],
				Tokens::$scopeModifiers
			), ($stackPtr + 1));
			$semicolon = $phpcsFile->findNext(T_SEMICOLON, $stackPtr);
			if ($next) {
				$diff = abs($tokens[$next]['line'] - $tokens[$semicolon]['line']);
				$function = $phpcsFile->findNext(T_FUNCTION, $next + 1);
				// if there is no variable between whatever we found
				// and the function, this is the last variable and
				// there should be three empty lines
				$variable = $phpcsFile->findNext(T_VARIABLE, $next + 1, $function);
				if ($variable) {
					// we want three empty lines between different
					// visibility levels, otherwise two
					$expected = 3;
					$nextModifier = $phpcsFile->findNext(Tokens::$scopeModifiers, ($stackPtr + 1), $variable);
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
						$fix = $phpcsFile->addFixableError("Must have %d empty lines between properties%s, found %d", $stackPtr, 'EmptyLines', [
							$expected - 1,
							($expected === 4 ? " with different visibility scopes" : ""),
							$diff - 1,
						]);
						if ($fix === TRUE) {
							self::fixSpacing($phpcsFile, $semicolon, $diff, $expected);
						}
					}
				} elseif ($diff !== 4) {
					$fix = $phpcsFile->addFixableError("Must have 3 empty lines between last property and first function, found %d", $stackPtr, 'EmptyLines', [$diff - 1]);
					if ($fix === TRUE) {
						self::fixSpacing($phpcsFile, $semicolon, $diff, 4);
					}
				}
			}
		}
	}



	protected function processVariable(File $phpcsFile, $stackPtr)
	{
		/* We don't care about normal variables. */
	}



	protected function processVariableInString(File $phpcsFile, $stackPtr)
	{
		/* We don't care about normal variables. */
	}

}
