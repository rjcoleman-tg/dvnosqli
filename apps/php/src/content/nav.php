<?php
$class_attr = array_merge($_PAGES, array('home'=>''));
$class_attr = array_fill_keys(array_keys($class_attr), '');

if (isset($_GET['db']) && isset($class_attr[$_GET['db']])) {
  $class_attr[$_GET['db']] = ' class="active"';
} else {
  $class_attr['home'] = ' class="active"';
}

?>
<div class="topnav" id="myTopnav">
  <a href="/"<?= $class_attr['home'] ?>>Home</a>
  <a href="/?db=neo4j"<?= $class_attr['neo4j'] ?>>neo4j</a>
  <a href="/?db=mongodb"<?= $class_attr['mongodb'] ?>>mongodb</a>
  <a href="/?db=redis"<?= $class_attr['redis'] ?>>redis</a>
  <a href="javascript:void(0);" class="icon" onclick="handleHamburger()">
    <i class="sm-menu"></i>
    <i class="sm-menu"></i>
    <i class="sm-menu"></i>
  </a>
</div>
