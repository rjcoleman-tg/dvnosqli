<?php

session_start();
// set level in cookie
$levels = array('0','1','2','3');

if (isset($_GET['level']) && in_array($_GET['level'], $levels)) {
  setcookie('level', $_GET['level']);
  header("location: " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/'));
} else if (!isset($_COOKIE['level'])) {
  setcookie('level', '0');
  header("location: " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/'));
}
