<?php

require_once 'MWB.php';
$sPath = '..';
$oMWB = new MWB($sPath.'/tmp/laravel.mwb');
$aMWBTables = $oMWB->getMWBTablesArray();

require_once 'Lara.php';
$oLara = new Lara;
$oLara->generateMigrationsAndModels($aMWBTables);
