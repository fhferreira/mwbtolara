<?php

// .mwb parser
require_once 'MWB.php';
$oMWB = new MWB('../tmp/laravel.mwb');
$aMWBTables = $oMWB->getMWBTablesArray();

// .mwb xml parser

// database name and charset

// database tables (name and charset)

// table fields (name, type, properties, default, comment)

// foreign keys

// tables connections


// laravel migrations and models generator
require_once 'Lara.php';
$oLara = new Lara;
$oLara->generateMigrationsAndModels($aMWBTables);

// generate migrations

// generate foreign file

// generate model with placeholders

// set model base class

// set Optional namespace

// set Mass Assignment (guarded id)

// set Ardent

// set Factory Muff

// set validator texts by comment

