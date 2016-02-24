<?php
$bid = elgg_extract('bid',$vars);
$readonly = elgg_extract('readonly',$vars,false);
$body .= '<p>'.elgg_echo('jobsin:task_rate').'</p>';
$body .= '<p>'.elgg_view('input/text',array('maxlength' => 5, 'name' => 'rate', 'value' => $bid->rate)).'</p>';
$body .= '<p>'.elgg_echo('jobsin:submission:end_date').'</p>';
$end_date = date(TASKS_FORMAT_DATE_EVENTDAY, $bid->end_date);
$body .= '<p>'.elgg_view('input/date',array('name' => 'end_date', 'value' => $bid->end_date, 'disabled' => $readonly)).'</p>';
$body .= elgg_view('input/hidden',array('name' => 'bid_guid', 'value' => $bid->getGUID()));
$body .= elgg_view("input/submit", array('name' => 'submit', "value" => elgg_echo("save")));
$body .= '</div>';
echo $body;
