<?php 
/**
 * Custom Content
 *
 */

$plugin = elgg_get_plugin_from_id('jobsin');
$html = $plugin->html_content;

$title = elgg_echo("jobsin:demo:title"); 
//$text = elgg_echo("jobsin:demo:text");

echo elgg_view_module('aside', $title, $html);
