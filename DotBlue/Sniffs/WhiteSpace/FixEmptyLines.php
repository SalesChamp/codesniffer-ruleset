<?php

namespace DotBlue\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Files\File;


trait FixEmptyLines
{

	private static function fixSpacing(File $phpcsFile, $updateFrom, $diff, $expected)
	{
		if ($diff < $expected) {
			$padding = str_repeat($phpcsFile->eolChar, $expected - $diff);
			$phpcsFile->fixer->addContent($updateFrom, $padding);
		} else {
			$nextContent = $phpcsFile->findNext(T_WHITESPACE, ($updateFrom + 1), NULL, TRUE);
			$phpcsFile->fixer->beginChangeset();
			for ($i = $updateFrom + 1; $i < ($nextContent - 2); $i++) {
				$phpcsFile->fixer->replaceToken($i, '');
			}

			$phpcsFile->fixer->replaceToken($i, str_repeat($phpcsFile->eolChar, max($expected - 2, 0)));
			$phpcsFile->fixer->endChangeset();
		}
	}

}
