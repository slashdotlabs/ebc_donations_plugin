<?php

if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

// Drop the Payments table
SlashEbc\Database\Migrations::dropDonationsTable();
SlashEbc\Database\Migrations::dropGivingTable();

// Clear plugin options
delete_option("ebc_donations_plugin");