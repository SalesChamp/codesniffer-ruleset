<?php

namespace DotBlue\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;


class OpeningPHPTagSpacingSniff implements Sniff
{

    use FixEmptyLines;



    public function register()
    {
        return [T_OPEN_TAG];
    }



    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        if ($tokens[$stackPtr]['code'] === T_OPEN_TAG) {
            $nextToken = $phpcsFile->findNext(T_WHITESPACE, $stackPtr+1, NULL, TRUE);

            if ($nextToken) {
                $diff = $tokens[$nextToken]['line'] - $tokens[$stackPtr]['line'];

                if ($diff !== 2) {
                    $fix = $phpcsFile->addFixableError(
                        "There should be 1 empty line after the opening php tag, found %d",
                        $stackPtr,
                        'OpeningTag',
                        [$diff - 1]
                    );
                    if ($fix === TRUE) {
                        self::fixSpacing($phpcsFile, $stackPtr, $diff, 2);
                    }
                }
            }
        }
    }

}
