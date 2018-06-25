<?php

namespace DotBlue\CodeSniffer\Helpers;


use Exception;
use PHP_CodeSniffer;


class Tester
{

	public static $setup = [];

	/** @var TestedFile[] */
	private $testedFiles = [];



	/**
	 * @param  array
	 */
	public static function setup($setup)
	{
		self::$setup = $setup;
	}



	/**
	 * @param  string
	 * @return TestedFile
	 */
	public function setFile($file)
	{
		$testedFile = new TestedFile($file);
		$this->testedFiles[] = $testedFile;
		return $testedFile;
	}



	public function test()
	{
		define('PHP_CODESNIFFER_IN_TESTS', TRUE);
		define('PHP_CODESNIFFER_CBF', TRUE);

		foreach ($this->testedFiles as $testedFile) {
			$runner = new PHP_CodeSniffer\Runner();
			$runner->config = new PHP_CodeSniffer\Config([
				'-s',
			]);
			$runner->init();

			$runner->reporter = new PHP_CodeSniffer\Reporter($runner->config);
			if (!$testedFile->getSniff()) {
				throw new Exception('Sniff file not set. Please set sniff by using ' . TestedFile::class . '::setSniff($sniff) method.');
			}
			$runner->ruleset->registerSniffs([
				Tester::$setup['sniffsDir'] . '/' . str_replace('.', '/', $testedFile->getSniff()) . 'Sniff.php',
			], [], []);
			$runner->ruleset->populateTokenListeners();
			$testedFile->evaluate($runner, $runner->ruleset, $runner->config);
		}
	}

}
