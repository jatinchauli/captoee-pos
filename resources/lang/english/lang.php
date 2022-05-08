<?php
$default = include_once "default.php";
$custom = include_once "custom.php";
$application = require "application.php";

return array_merge($default, $custom, $application);