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

$config= parse_ini_file(dirname(__FILE__) . "/.db.ini");

$db= mysqli_init();
if (!$db) die("mysqli_init failed");

if (!$db->real_connect($config['host'],$config['user'],$config['password'],
                       $config['database']))
  die('connect failed: ' . mysqli_connect_error());
$db->set_charset('utf8');
