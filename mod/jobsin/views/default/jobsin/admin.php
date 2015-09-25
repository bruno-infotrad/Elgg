<?php

/**
 *  Admin CSS
 */

?>

/* ***************************************
	SETTINGS
*****************************************/
.elggzone-options-panel {
	background-color: #333;
	border: 1px solid #444;
	margin-top: 15px;
	width: 100%;
}
.elggzone-options-panel .elgg-button {
	font-size: 14px;
	font-weight: normal;
	text-decoration: none;
    float: left;
    
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;

	width: auto;
	padding: 6px 10px;
	cursor: pointer;
}
.elggzone-options-panel .item {
	border-bottom: 1px solid #C0C0C0;
	padding: 10px 0;
}
.elggzone-holder {
	background-color: #EEEEEE;
	border: 2px solid #FFFFFF;
	margin: 20px;
	
	color: #222222;
	padding: 30px;
}
.panel-header {
	padding-bottom: 10px;
    margin-bottom: 4px;
    font-size: 18px;
}
.panel-line {
	height:1px;
    width: 100%;
	background:#CCC;
	border-bottom:1px solid #FFF;
	overflow:hidden;
}
.settings-optionspanel {
    min-height: 260px;
    padding: 20px 0 0;
}
.settings-optionspanel .label {
	font-size: 1.2em;
    font-weight: bold;
}
.settings-optionspanel .elgg-input-dropdown {
	float: right;
    margin-left: 8px;
}
.settings-optionspanel .elgg-input-text {
	float: right;
    margin-bottom: 15px;
}
.settings-optionspanel .elgg-list li {
	padding: 10px 10px 8px;
    margin-bottom: 3px;
}
.settings-optionspanel .no {
	border: 1px solid #CCC;
	background: none;
}
.settings-optionspanel .sidebar,
.settings-optionspanel .sidebar_alt {
	border: 1px solid #B4C25C;
	background: #F5F7E9;
}
.settings-optionspanel .elgg-list li #title {
    vertical-align: top;
    display: inline-block;
}
.elgg-handle {
	cursor: move;
    display: inline-block;
    padding-right: 10px;
}
.elgg-handle-bar {
    display: block;
    width: 24px;
    height: 2px;
    background-color: #ECECEC;
    
    -webkit-border-radius: 1px;
    -moz-border-radius: 1px;
    border-radius: 1px;
    
    -webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.25);
    -moz-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.25);
    box-shadow: 0 1px 0 rgba(0, 0, 0, 0.25);
}
.elgg-handle .elgg-handle-bar + .elgg-handle-bar {
  margin-top: 3px;
}
.settings-optionspanel #target {
	padding: 10px;
    height: auto;
}
/* ***************************************
	TABS
*****************************************/
.elggzone-holder .elgg-tabs {
    margin-top: 20px;
	border-bottom: 1px solid #ccc;
	display: table;
	width: 100%;
}
.elggzone-holder .elgg-tabs li {
	float: left;
	border: 1px solid #CCCCCC;
	border-bottom-width: 0;
	margin: 0 2px 0 0;    
    background: #dedede;
    background-image: linear-gradient(to bottom, #FFFFFF, #dedede);
    
    -webkit-border-radius: 3px 3px 0 0;
	-moz-border-radius: 3px 3px 0 0;
	border-radius: 3px 3px 0 0;
}
.elggzone-holder .elgg-tabs a {
	text-decoration: none;
	display: block;
	padding: 6px 20px 2px;
	text-align: center;
	height: 23px;
	color: #777;
}
.elggzone-holder .elgg-tabs a:hover {
	background: #EEEEEE;
	color: #333;
}
.elggzone-holder .elgg-tabs .elgg-state-selected {
	border-color: #CCCCCC;
	background: #FFFFFF;
}
.elggzone-holder .elgg-tabs .elgg-state-selected a {
	color: #333;
	position: relative;
	top: 2px;
    
	background: #EEEEEE;
    background-image: linear-gradient(to bottom, #FFFFFF, #EEEEEE);
}
