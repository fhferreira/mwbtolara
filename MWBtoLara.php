<?php

require_once 'MWB.php';
require_once 'Lara.php';

$sPath = '..';
$oMWB = new MWB($sPath.'/tmp/laravel.mwb');
$aMWBTables = $oMWB->getMWBTablesArray();

$oLara = new Lara;
$oLara->generateMigrationsAndModels($aMWBTables);
