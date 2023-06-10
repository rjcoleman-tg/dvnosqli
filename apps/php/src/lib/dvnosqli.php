<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

function printObj($o) {
  echo "<pre>" . print_r($o,1) . "</pre>";
}
