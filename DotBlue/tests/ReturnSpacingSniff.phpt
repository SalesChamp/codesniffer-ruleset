<?php

use DotBlue\CodeSniffer\Helpers\Tester;


require __DIR__ . '/bootstrap.php';

$tester = new Tester();
$tester->setFile('ReturnSpacing_1')
    ->setSniff('WhiteSpace.ReturnSpacing')
    ->expectMessage('There must be no empty lines between the opening brace and the return statement. Found 1')
    ->onLine(5)
    ->isFixable()
    ->getFile()
    ->expectMessage('There must be no empty lines between the opening brace and the return statement. Found 2')
    ->onLine(13)
    ->isFixable();

$tester->setFile('ReturnSpacing_2')
    ->setSniff('WhiteSpace.ReturnSpacing')
    ->expectMessage('There must be one empty line between the closing brace or semicolon and the return statement. Found 0')
    ->onLine(6)
    ->isFixable()
	->getFile()
	->expectMessage('There must be one empty line between the closing brace or semicolon and the return statement. Found 0')
    ->onLine(13)
    ->isFixable();

$tester->setFile('ReturnSpacing_3')
    ->setSniff('WhiteSpace.ReturnSpacing')
    ->expectMessage('There must be one empty line between the closing brace or semicolon and the return statement. Found 2')
    ->onLine(8)
    ->isFixable()
	->getFile()
	->expectMessage('There must be one empty line between the closing brace or semicolon and the return statement. Found 2')
    ->onLine(17)
    ->isFixable();

$tester->test();
