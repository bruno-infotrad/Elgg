<?php
$bid_guid = elgg_extract('bid_guid',$vars);
$body .= elgg_view('input/hidden',array('name' => 'bid_guid', 'value' => $bid_guid));
$body .= elgg_view("input/submit", array('name' => 'submit', "value" => elgg_echo("select")));
echo $body;
