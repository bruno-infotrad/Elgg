<?php

/**
 * Basic Light Bronco css
 */

?>

a {
	color: #362E23;
}
a:hover,
a.selected {
	color: #B98F4F;
	text-decoration: none;
}
.elgg-page-header {
    border-bottom: 1px solid #999691;
    background: url(<?php echo $vars['url']; ?>mod/jobsin/graphics/bg_header_bronco.png) repeat left top;
}
.elgg-heading-site, .elgg-heading-site:hover {
	color: #2B2A29;
}
.elgg-heading-main {
    color: #362E23;
}
.elgg-module .elgg-head {
    color: #362E23;
}
.pages-nav.treeview a.selected {
	color: #B98F4F;
}
.pages-nav.treeview .hover {
	color: #B98F4F;
}
/* Navigation */
.elgg-pagination a:hover {
    color: #B98F4F;
}
.elgg-pagination .elgg-state-selected span {
    color: #B98F4F;
}
.elgg-tabs .elgg-state-selected a {
    color: #B98F4F;
}
.elgg-menu-filter > .elgg-state-selected > a {
    color: #B98F4F;
}
.elgg-breadcrumbs > li {
	display: inline-block;
    color: #B98F4F;
}
.elgg-menu-site-default > li > a {
	color: #362E23;
}
.elgg-menu-site-more > li > a:hover {
	background: #362E23;
}
.elgg-menu-site-default > .elgg-state-selected > a,
.elgg-menu-site-default > li:hover > a {
	color: #B98F4F;
}
.elgg-menu-hover a:hover {
	background: #362E23;
}
.elgg-menu-page li.elgg-state-selected > a {
	color: #B98F4F;
}
.elgg-menu-owner-block li.elgg-state-selected > a {
	color: #B98F4F;
}
/* Form Elements */
input, textarea {
	border: 1px solid #D0CCC6;
}
input[type=text]:focus, textarea:focus {
	border: solid 1px #C29C64;
	background: #F2F0EE;
}
