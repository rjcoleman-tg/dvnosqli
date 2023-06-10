<?php

$redis = new Redis(); 
$redis->connect('dvnosqli-redis-1', 6379); 
echo "<p>clearing current data...</p>";
echo "<p>loading new data...</p>";
$redis->flushAll();
for ($i = 0; $i < 100; $i++) {
  $sid = base64_encode(session_create_id());
  $redis->set('user:session:'.$sid, "{'user':$i}");
}
echo "<p>loaded $i records </p>";
for ($i = 0; $i < 100; $i++) {
  $sid = session_create_id();
  $redis->set($sid, "{'user':$i}");
}
echo "<p>loaded $i records </p>";
