<?php
$bid_guid = elgg_extract('bid_guid',$vars);
$bid = get_entity($bid_guid);
if ($bid->status != 'pending') {
                $readonly = true;
        } else {
                $readonly = false;
        }

$body .= '<p>'.elgg_echo('jobsin:task_rate').'</p>';
$body .= '<p>'.elgg_view('input/text',array('maxlength' => 5, 'name' => 'rate', 'value' => $bid->rate,'readonly' => $readonly)).'</p>';
if (! $readonly) {
	$body .= elgg_view('input/hidden',array('name' => 'bid_guid', 'value' => $bid_guid));
	$body .= elgg_view("input/submit", array('name' => 'submit', "value" => elgg_echo("jobsin:submit_bid"), "onclick" => "return confirm(\"" . elgg_echo("jobsin:submit_bid:confirm") . "\");"));
}
echo $body;
