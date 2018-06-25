<?php

namespace DotBlue\Sniffs\Conventions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;


class BoolNamingSniff implements Sniff
{

	public function register()
	{
		return [
			T_DOC_COMMENT_STRING,
		];
	}



	public function process(File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();
		$content = $tokens[$stackPtr]['content'];
		if (preg_match('/boolean/', $content)) {
			$fix = $phpcsFile->addFixableError('Usage of "boolean" is forbidden. Use "bool" instead.', $stackPtr, 'NoBoolean');

			if ($fix) {
				$phpcsFile->fixer->beginChangeset();
				$phpcsFile->fixer->replaceToken($stackPtr, str_replace('boolean', 'bool', $content));
				$phpcsFile->fixer->endChangeset();
			}
		}
	}

}
