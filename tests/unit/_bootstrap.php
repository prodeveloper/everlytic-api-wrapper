<?php
$chencha_base_path='/var/www/clients/ihub_projects/ihub_backend/bootstrap/';
require $chencha_base_path. 'autoload.php';
$app = require_once $chencha_base_path .'start.php';
$app->boot();