<?php
/**
 * Basic Light settings
 * 
 */

	$plugin = elgg_get_plugin_from_id('jobsin');

	if (!isset($plugin->active_theme)) {
		$plugin->active_theme = 'default';
	}
	if (!isset($plugin->columns)) {
		$plugin->columns = 'no';
	}
	if (!isset($plugin->show_thewire)) {
		$plugin->show_thewire = 'yes';
	}

echo "<div class=\"label\">" . elgg_echo('jobsin:info:general') . "</div>";

echo '<div class="item">';
echo elgg_echo('jobsin:label:columns');
echo ' ';
echo elgg_view('input/dropdown', array(
	'name' => 'params[columns]',
	'options_values' => array(
		'no' => elgg_echo('jobsin:option:two'),
		'yes' => elgg_echo('jobsin:option:three')
	),
	'value' => $plugin->columns,
));
echo '</div>';

echo '<div class="item">';
echo elgg_echo('jobsin:label:theme');
echo ' ';
echo elgg_view('input/dropdown', array(
	'name' => 'params[active_theme]',
	'options_values' => array(
		'default' => elgg_echo('jobsin:option:default'),
		'palesky' => elgg_echo('jobsin:option:palesky'),
		'bronco' => elgg_echo('jobsin:option:bronco')
	),
	'value' => $plugin->active_theme,
));
echo '</div>';

echo '<div class="item">';
echo elgg_echo('jobsin:label:thewire');
echo ' ';
echo elgg_view('input/dropdown', array(
	'name' => 'params[show_thewire]',
	'options_values' => array(
		'yes' => elgg_echo('option:yes'),
		'no' => elgg_echo('option:no')
	),
	'value' => $plugin->show_thewire,
));
echo '</div>';

echo elgg_view('input/submit', array('value' => elgg_echo("save")));
