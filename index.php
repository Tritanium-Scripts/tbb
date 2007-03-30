<?php

if(phpversion() < 5) die('Your version of PHP is currently '.phpversion().' but you need at least PHP 5 so please update. This software will not work under PHP 4!');

include('core/Version.php');
include('core/Functions.class.php');
include('core/Factory.class.php');
include('core/ConfigTemplate.class.php');
include('core/ModuleTemplate.class.php');
include('core/Core.class.php');

$Core = new Core;
$Core->executeMe();

?>