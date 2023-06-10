<?php

$class_attr = array('0' => '',
                    '1' => '',
                    '2' => '',
                    '3' => '');

if (isset($_COOKIE['level']) && in_array($_COOKIE['level'], array_keys($class_attr))) {
  $class_attr[$_COOKIE['level']] = ' class="active"';     
} else {
  $class_attr['0'] = ' class="active"';     
}


?><div class="level" id="myLevel">
  <a href="/?level=0"<?= $class_attr['0'] ?>>easy</a>
  <a href="/?level=1"<?= $class_attr['1'] ?>>medium</a>
  <a href="/?level=2"<?= $class_attr['2'] ?>>hard</a>
  <a href="/?level=3"<?= $class_attr['3'] ?>>impossible</a>
</div>
