<?php

use DotBlue\CodeSniffer\Helpers\Tester;


require __DIR__ . '/bootstrap.php';

$tester = new Tester();
$tester->setFile('OpeningPHPTagSpacing_1')
    ->setSniff('WhiteSpace.OpeningPHPTagSpacing')
    ->expectMessage("There should be 1 empty line after the opening php tag, found 0")
    ->onLine(1)
    ->isFixable();

$tester->setFile('OpeningPHPTagSpacing_2')
    ->setSniff('WhiteSpace.OpeningPHPTagSpacing')
    ->expectMessage("There should be 1 empty line after the opening php tag, found 2")
    ->onLine(1)
    ->isFixable();


$tester->test();
