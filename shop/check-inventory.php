<?php
require '../common.php';

$code= $_GET['code'];
if ($code) {
  $code= $db->real_escape_string(substr($code, 1, -1)); // trim []
  $q= "SELECT code FROM inventory WHERE code = '$code' AND NOT stopped";
  $r= $db->query($q);
  if ($r && $r->num_rows) {
    generate_jsonp(array("stocked" => true));
    exit;
  }
}

$codes= $_GET['codes'];
if ($codes && is_array($codes)) {
  $result= array();
  foreach ($codes as $code) {
    $code= $db->real_escape_string(substr($code, 2, -2)); // trim &nbsp;
    $q= "SELECT code FROM inventory WHERE code = '$code' AND NOT stopped";
    $r= mysql_query($q);
    $result[]= array('code' => $code, 'stocked' => $r && $r->num_rows);
  }
  generate_jsonp($result);
  exit;
}

generate_jsonp(array("stocked" => false));
