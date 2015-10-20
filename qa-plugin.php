<?php
/*
	Plugin Name: get round number celebrate mail
	Plugin URI: 
	Plugin Description: send celebrate mail to user who get round number
	Plugin Version: 0.3
	Plugin Date: 2015-10-18
	Plugin Author:
	Plugin Author URI:
	Plugin License: GPLv2
	Plugin Minimum Question2Answer Version: 1.7
	Plugin Update Check URI: 
*/
if (!defined('QA_VERSION')) {
	header('Location: ../../');
	exit;
}

qa_register_plugin_module('module', 'q2a-get-roundnumber-celebrate-email-admin.php', 'q2a_get_roundnumber_celebrate_email_admin', 'round number celebrate admin');
qa_register_plugin_module('event', 'q2a-get-roundnumber-celebrate-email-event.php', 'q2a_get_roundnumber_celebrate_email_event', 'Round Number Celebrate');

/*
	Omit PHP closing tag to help avoid accidental output
*/
