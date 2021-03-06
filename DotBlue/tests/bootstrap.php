<?php

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/squizlabs/php_codesniffer/autoload.php';

Tester\Environment::setup();

DotBlue\CodeSniffer\Helpers\Tester::setup([
	'validDir' => __DIR__ . '/valid/',
	'invalidDir' => __DIR__ . '/invalid/',
	'ruleset' => __DIR__ . '/../ruleset.xml',
	'fixerPath' => realpath(__DIR__ . '/../../vendor/bin/phpcbf'),
	'sniffsDir' => realpath(__DIR__ . '/../../DotBlue/Sniffs/'),
	'configData' => [
		'DotBlue\Sniffs\WhiteSpace\FunctionSpacingSniff' => [
			'spacing' => 3
		],
	],
]);
