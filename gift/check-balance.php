<?php
require '../common.php';

$card= $db->real_escape_string($_GET['card']);

$q= "SELECT id, active FROM giftcard WHERE id = SUBSTRING('$card', 1, 7) AND pin = SUBSTRING('$card',-4)";
$r= $db->query($q);
if (!$r) die(generate_jsonp(array("error" => "Unable to check card info.",
                                  "detail" => $db->error)));
$row= $r->fetch_row();
if (!$r->num_rows || !$row[1]) {
  die(generate_jsonp(array("error" => "No such gift card is active.")));
}
$card= $row[0];

# card is active, now check the balance!

$q= "SELECT DATE_FORMAT(MAX(entered), '%W, %M %e, %Y') AS latest,
            SUM(amount) AS balance
       FROM giftcard_txn
      WHERE card = '$card'";
$r= $db->query($q);
if (!$r) die(generate_jsonp(array("error" => "Unable to check balance.",
                                  "detail" => $db->error)));
generate_jsonp($r->fetch_assoc());
