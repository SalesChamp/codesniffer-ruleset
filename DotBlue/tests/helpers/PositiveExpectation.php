<?php

namespace DotBlue\CodeSniffer\Helpers;

use PHP_CodeSniffer;
use PHP_CodeSniffer\Files\LocalFile;
use Tester\Assert;


class PositiveExpectation implements Expectation
{

	/** @var string */
	private $expectedMessage;

	/** @var int[] */
	private $expectedOnLines = [];

	/** @var bool */
	private $isFixable = FALSE;

	/** @var PHP_CodeSniffer\Runner */
	private $sniffer;

	/** @var PHP_CodeSniffer\Ruleset */
	private $ruleset;

	/** @var PHP_CodeSniffer\Config */
	private $config;

	/** @var TestedFile */
	private $testedFile;



	public function __construct($message, TestedFile $testedFile)
	{
		$this->expectedMessage = $message;
		$this->testedFile = $testedFile;
	}



	/**
	 * {@inheritdoc}
	 */
	public function onLine($line)
	{
		$this->expectedOnLines[] = $line;
		return $this;
	}



	/**
	 * {@inheritdoc}
	 */
	public function onLines(array $lines)
	{
		foreach ($lines as $line) {
			$this->expectedOnLines[] = $line;
		}
		return $this;
	}



	/**
	 * {@inheritdoc}
	 */
	public function isFixable()
	{
		$this->isFixable = TRUE;
		return $this;
	}



	public function evaluate(PHP_CodeSniffer\Runner $sniffer)
	{
		$this->sniffer = $sniffer;
		$this->testValid();
		$this->testInvalid();

		if ($this->isFixable) {
			$this->testFix();
		}
	}



	/**
	 * {@inheritdoc}
	 */
	public function getFile()
	{
		return $this->testedFile;
	}



	private function testValid()
	{
		$file = new LocalFile(Tester::$setup['validDir'] . $this->testedFile->getName() . '.php', $this->sniffer->ruleset, $this->sniffer->config);
		$this->sniffer->processFile($file);
		$errors = $file->getErrors();
		Assert::equal([], $errors);
	}



	private function testInvalid()
	{
		$file = new LocalFile(Tester::$setup['invalidDir'] . $this->testedFile->getName() . '.php', $this->sniffer->ruleset, $this->sniffer->config);
		$this->sniffer->processFile($file);
		$errors = $file->getErrors();

		foreach ($this->expectedOnLines as $line) {
			Assert::true(isset($errors[$line]));

			$errorsOnLine = $errors[$line];

			$errorFound = FALSE;
			foreach ($errorsOnLine as $error) {
				if ($error[0]['message'] === $this->expectedMessage) {
					$errorFound = TRUE;
					break;
				}
			}

			if (!$errorFound) {
				Assert::fail('Required error message "' . $this->expectedMessage . '" not found on line "' . $line . '"');
			}
		}

	}



	private function testFix()
	{
		exec(Tester::$setup['fixerPath'] . ' ' . Tester::$setup['invalidDir'] . $this->testedFile->getName() . '.php --standard=../ruleset.xml --suffix=.fixed', $out, $status);
		if ($status === 1) {
			$content = file_get_contents(Tester::$setup['invalidDir'] . $this->testedFile->getName() . '.php.fixed');
			Assert::matchFile(Tester::$setup['validDir'] . $this->testedFile->getName() . '.php', $content);
		} else {
			Assert::fail(sprintf("Fixer was unable to fix %s:\n%s", $this->testedFile->getName(), implode("\n", $out)));
		}
	}

}
