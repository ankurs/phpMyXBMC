<?php
// get app config
require_once("config.php");
require_once("Controllers.class.php");

$con = new Controllers();
$con->route();

?>
