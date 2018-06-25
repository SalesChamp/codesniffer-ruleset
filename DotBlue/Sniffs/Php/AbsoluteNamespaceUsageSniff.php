<?php

namespace DotBlue\Sniffs\Php;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;


class AbsoluteNamespaceUsageSniff implements Sniff
{

	public function register()
	{
		return [
			T_NS_SEPARATOR,
		];
	}



	public function process(File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();

		if ($tokens[$stackPtr - 1]['code'] !== T_STRING) {
			$namespace = '';
			$ptr = $stackPtr;
			do {
				$token = $tokens[$ptr++];
				$namespace .= $token['content'];
			} while (in_array($token['code'], [
				T_NS_SEPARATOR,
				T_STRING,
			]));
			$namespace = trim($namespace);

			$phpcsFile->addError('Using absolute namespaces if forbidden. Import class \'' . $namespace . '\' with use statement.', $stackPtr, 'AbsoluteNamespace');
		}
	}

}
