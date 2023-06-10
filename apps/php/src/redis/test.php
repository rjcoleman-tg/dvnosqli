<?php

//$a = array('x','y');
$a = 'mystring';
print base64_encode($a);
print "\n";
print base64_encode(json_encode($a));
