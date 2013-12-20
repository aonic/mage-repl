#!/usr/bin/env php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Ulrichsg\Getopt;
use Boris\Boris;

$getopt = new Getopt(array(
	array('d', 'developer', Getopt::NO_ARGUMENT, 'Disable developer mode using Mage::setIsDeveloperMode', true),
	array('p', 'path', Getopt::OPTIONAL_ARGUMENT, 'Optional: Path to your Magento project. Uses current directory by default.', getcwd()),
	array('s', 'store', Getopt::OPTIONAL_ARGUMENT, 'Optional: store code used in Mage::app', 'admin'),
	array('h', 'help', Getopt::NO_ARGUMENT, 'Shows this help menu.'),
));
$getopt->parse();
if ($getopt->getOption('help') == true) {
	$getopt->showHelp();
	exit(0);
}

$path = $getopt->getOption('path');
$mageFile = 'app/Mage.php';
if (file_exists($path . '/' . $mageFile) == false) {
	echo "Error: Unable to find $mageFile" . PHP_EOL . PHP_EOL;
	$getopt->showHelp();
	exit(1);
}
 
umask(0);
chdir($path);
require_once $mageFile;
Mage::setIsDeveloperMode($getopt->getOption('developer'));
$app = Mage::app($getopt->getOption('store'));

restore_error_handler();
restore_exception_handler(); 

$boris = new Boris('mage> ');
$boris->setLocal(array('app' => $app));
$boris->onStart(function($worker, $scope) {
	echo "Welcome to mage-repl.\n\n";
	echo "Mage::app() is accessible using \$app.\n";
	echo "Loaded store: " . $scope['app']->getStore()->getName() . "\n\n";
});
$boris->start();
