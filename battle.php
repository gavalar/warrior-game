<?php
require_once('models/Combat.php');
require_once('models/WarriorFactory.php');

$combat = Combat::getInstance();

foreach($_POST['warrior'] as $key => $value)
{
    $name = $_POST['name'][$key];
    $player = WarriorFactory::getWarrior($value)->setName($name);
    
    $combat->addWarrior($player);
}

$combat->doBattle();

$combat->render('no_annimation');
