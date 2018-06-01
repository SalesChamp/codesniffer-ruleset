<?php

namespace DotBlue\Sniffs\WhiteSpace;

use PHP_CodeSniffer_File;
use PHP_CodeSniffer_Sniff;


class NoSpaceAfterNotSniff implements PHP_CodeSniffer_Sniff
{

    public function register()
    {
        return array(T_BOOLEAN_NOT);
    }



    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $spacing = 0;
        if ($tokens[($stackPtr + 1)]['code'] === T_WHITESPACE) {
            $spacing = $tokens[($stackPtr + 1)]['length'];
        }

        if ($spacing === 0) {
            return;
        }

        $message = 'There must be no space after a NOT operator; %s found';
        $fix     = $phpcsFile->addFixableError($message, $stackPtr - 2, 'Incorrect', [$spacing]);

        if ($fix === TRUE) {
			$phpcsFile->fixer->replaceToken(($stackPtr + 1), '');
        }
    }

}
