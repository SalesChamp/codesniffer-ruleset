<?php

use DotBlue\CodeSniffer\Helpers\Tester;


require __DIR__ . '/bootstrap.php';

$tester = new Tester();
$tester->setFile('PropertySpacing_1')
    ->setSniff('WhiteSpace.PropertySpacing')
    ->expectMessage('Must have 3 empty lines between properties with different visibility scopes, found 2')
    ->onLine(9)
    ->isFixable()
    ->getFile()
    ->expectMessage('Must have 3 empty lines between properties with different visibility scopes, found 1')
    ->onLine(12)
    ->isFixable();

$tester->setFile('PropertySpacing_2')
    ->setSniff('WhiteSpace.PropertySpacing')
    ->expectMessage('Must have 2 empty lines between properties, found 1')
    ->onLine(9)
    ->isFixable();

$tester->setFile('PropertySpacing_3')
    ->setSniff('WhiteSpace.PropertySpacing')
    ->expectMessage('Property visibility must be ordered from private to protected to public, found T_PUBLIC, next is T_PROTECTED')
    ->onLine(6)
    ->getFile()
    ->expectMessage('Property visibility must be ordered from private to protected to public, found T_PROTECTED, next is T_PRIVATE')
    ->onLine(10);

$tester->setFile('PropertySpacing_4')
    ->setSniff('WhiteSpace.PropertySpacing')
    ->expectMessage('Must have 3 empty lines between last property and first function, found 5')
    ->onLine(9)
    ->isFixable();


$tester->test();
