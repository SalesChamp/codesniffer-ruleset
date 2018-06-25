<?php

namespace DotBlue\CodeSniffer\Helpers;

use PHP_CodeSniffer;


class TestedFile
{

	/** @var Expectation[] */
	private $expectations = [];

	/** @var string */
	private $file;

	/** @var string */
	private $sniff;



	public function __construct($file)
	{
		$this->file = $file;
	}



	/**
	 * @param  string
	 * @return Expectation
	 */
	public function expectMessage($message)
	{
		$expectation = new PositiveExpectation($message, $this);
		$this->expectations[] = $expectation;
		return $expectation;
	}



	/**
	 * @param  string
	 * @return NegativeExpectation
	 */
	public function doNotExpectMessage($message)
	{
		$expectation = new NegativeExpectation($message, $this);
		$this->expectations[] = $expectation;
		return $expectation;
	}



	public function evaluate(PHP_CodeSniffer\Runner $sniffer, PHP_CodeSniffer\Ruleset $ruleset, PHP_CodeSniffer\Config $config)
	{
		foreach ($this->expectations as $expectation) {
			$expectation->evaluate($sniffer, $ruleset, $config);
		}
	}



	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->file;
	}



	/**
	 * @return string
	 */
	public function getSniff()
	{
		return $this->sniff;
	}



	/**
	 * @param  string
	 * @return $this
	 */
	public function setSniff($sniff)
	{
		$this->sniff = $sniff;
		return $this;
	}

}
