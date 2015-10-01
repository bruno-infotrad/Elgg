<?php
/**
 * Page Layout
 *
 * Contains CSS for the page shell and page layout
 *
 * Default layout: 990px wide, centered. Used in default page shell
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>

/* ***************************************
	PAGE LAYOUT
*************************************** */
/***** DEFAULT LAYOUT ******/
<?php // the width is on the page rather than topbar to handle small viewports ?>
.elgg-page-default {
	min-width: 998px;
}
.elgg-page-default .elgg-page-header > .elgg-inner {
	width: 990px;
	margin: 0 auto;
	height: 120px;
}
.elgg-page-body {
	margin-top: 120px;
}
.elgg-page-default .elgg-page-body > .elgg-inner {
	width: 990px;
	margin: 0 auto;
}
.elgg-page-default .elgg-page-footer > .elgg-inner {
	width: 990px;
	margin: 0 auto;
	padding: 5px 0;
	border-top: 1px solid #D1D7DB;
}
.elgg-page-default .elgg-page-body { 
    background: url(<?php echo elgg_get_site_url(); ?>mod/jobsin/graphics/bg_shadow.png) repeat-x left top;
}

/***** TOPBAR ******/

/***** PAGE MESSAGES ******/
.elgg-system-messages {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	z-index: 2000;
}
.elgg-system-messages li p {
	margin: 0;
}

/***** PAGE HEADER ******/
.elgg-page-topbar {
	position: fixed;
	right: 25%;
	top: 0;
	width: 100%;
	z-index: 20;
}
.elgg-page-header {
	position: fixed;
	width: 100%;
	top: 0;
	z-index: 10;
    border-bottom: 1px solid #BBB;
    background: -webkit-radial-gradient(28% 46%, closest-corner, #1af7a0, #FFFFFF);
    /*background: url(<?php echo elgg_get_site_url(); ?>mod/jobsin/graphics/bg_header.png) repeat left top;*/
}
.elgg-page-header > .elgg-inner {
	position: relative;
}
/***** PAGE BODY LAYOUT ******/
.elgg-layout {
	min-height: 360px;
}
.elgg-layout-one-sidebar {
	background: none;
}
.elgg-layout-two-sidebar {
	background: none;
}
.elgg-layout-error {
	margin-top: 20px;
}
.elgg-sidebar {
	position: relative;
	padding: 22px 0 20px 20px;
	float: right;
	width: 210px;
	margin: 0 0 0 20px;
}
.elgg-sidebar-alt {
	position: relative;
	padding: 24px 20px 20px 0;
	float: left;
	width: 160px;
	margin: 0 20px 0 0;
}
.elgg-main {
	position: relative;
	min-height: 360px;
	padding: 20px 0 10px 0;
}
.elgg-main > .elgg-head {
	margin-bottom: 10px;
}

/***** PAGE FOOTER ******/
.elgg-page-footer {
	position: relative;
}
.elgg-page-footer {
	color: #999;
}
.elgg-page-footer a:hover {
	color: #666;
}
