<?php
if (!defined('WP_UNINSTALL_PLUGIN')) exit;

global $wpdb;

$wpdb->query("DROP TABLE IF EXISTS {$wpdb->base_prefix}pofw_optiondefault_option");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->base_prefix}pofw_optiondefault_option_value");

