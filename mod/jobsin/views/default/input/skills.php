<?php 
elgg_require_js('jobsin/tags_autocomplete');
$name = elgg_extract("name", $vars);
$value = elgg_extract("value", $vars);
$form = "<textarea rows='10' cols='50' name='".$name."' id='elgg-input-tags-autocomplete' class='elgg-input-tags elgg-input-autocomplete'>";
if (is_array($value)) {
	foreach($value as $val) {
		$form .= $val.'&#13;&#10;';
	}
} else {
	$form .= $value;
}
$form .= "</textarea><div id='elgg-input-tags-autocomplete-results'></div>";
echo $form;
