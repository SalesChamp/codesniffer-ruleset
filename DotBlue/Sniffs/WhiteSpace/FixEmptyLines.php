<?php

namespace DotBlue\Sniffs\WhiteSpace;


trait FixEmptyLines
{

	private static function fixSpacing(PHP_CodeSniffer_File $phpcsFile, $semicolon, $diff, $expected)
	{
		// TODO: This method is duplicated in multiple "empty line"
		// fixing sniffs.  Move it into a helper/trait.
		$nextWhitespace = $phpcsFile->findNext(T_WHITESPACE, $semicolon);
		if ($diff < $expected) {
			$padding = str_repeat($phpcsFile->eolChar, $expected - $diff);
			$phpcsFile->fixer->addContent($nextWhitespace, $padding);
		} else {
			$nextContent = $phpcsFile->findNext(T_WHITESPACE, ($semicolon + 1), null, true);
			$phpcsFile->fixer->beginChangeset();
			for ($i = $semicolon + 1; $i < ($nextContent - 1); $i++) {
				$phpcsFile->fixer->replaceToken($i, '');
			}

			$phpcsFile->fixer->replaceToken($i, str_repeat($phpcsFile->eolChar, $expected));
			$phpcsFile->fixer->endChangeset();
		}
	}

}
