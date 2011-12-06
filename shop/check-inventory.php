<?php

function generate_jsonp($data) {
  if (preg_match('/\W/', $_GET['callback'])) {
    // if $_GET['callback'] contains a non-word character,
    // this could be an XSS attack.
    header('HTTP/1.1 400 Bad Request');
    exit();
  }
  header('Content-type: application/javascript; charset=utf-8');
  print sprintf('%s(%s);', $_GET['callback'], json_encode($data));
}

$code= $_GET['code'];
if ($code) {
  mysql_connect("localhost","rawmats","winsteadadams");
  mysql_select_db("rawmats");
  $code= addslashes(substr($code, 1, -1)); // trim []
  $r= mysql_query("SELECT code FROM inventory WHERE code = '$code' AND NOT stopped");
  if ($r && mysql_num_rows($r)) {
    generate_jsonp(array("stocked" => true));
    exit;
  }
}

$codes= $_GET['codes'];
if ($codes && is_array($codes)) {
  $result= array();
  mysql_connect("localhost","rawmats","winsteadadams");
  mysql_select_db("rawmats");
  foreach ($codes as $code) {
    $code= addslashes(substr($code, 2, -2)); // trim &nbsp;
    $r= mysql_query("SELECT code FROM inventory WHERE code = '$code' AND NOT stopped");
    $result[]= array('code' => $code, 'stocked' => $r && mysql_num_rows($r));
  }
  generate_jsonp($result);
  exit;
}

generate_jsonp(array("stocked" => false));
