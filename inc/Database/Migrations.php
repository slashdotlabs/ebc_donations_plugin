<?php

namespace SlashEbc\Database;

class Migrations
{
    public static function createDonationsTable()
    {
        global $table_prefix, $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $table_prefix."ebc_donations";

        if ($wpdb->get_var("show tables like '$table_name'") != $table_name) {
            $sql = "CREATE TABLE $table_name(
	    	id int AUTO_INCREMENT NOT NULL,
	    	transaction_id varchar(125) NOT NULL,
	    	name varchar(125) NOT NULL,
	    	email varchar(125) NOT NULL,
	    	phone varchar(125) NOT NULL,
	    	tribute text NULL,
	    	currency ENUM('KES', 'USD') DEFAULT 'KES',
	    	amount double(8,2) NOT NULL,
	    	transaction_ref varchar(125) NULL,
	    	payment_type varchar(125) NULL,
	    	payment_date timestamp NULL,
	    	status ENUM('initiated', 'cancelled', 'completed') DEFAULT 'initiated',
	    	PRIMARY KEY  (id),
	    	UNIQUE (transaction_id(50)),
	    	UNIQUE (payment_type(50), transaction_ref(50))
	    	) $charset_collate;";

            require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }

    public static function dropDonationsTable()
    {
        global $wpdb, $table_prefix;

        $table_name = $table_prefix."ebc_donations";

        $wpdb->query("DROP TABLE IF EXISTS $table_name");
    }

    public static function createGivingTable()
    {
        global $table_prefix, $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $table_prefix."ebc_giving";

        if ($wpdb->get_var("show tables like '$table_name'") != $table_name) {
            $sql = "CREATE TABLE $table_name(
	    	id int AUTO_INCREMENT NOT NULL,
	    	transaction_id varchar(125) NOT NULL,
	    	name varchar(125) NOT NULL,
	    	email varchar(125) NOT NULL,
	    	phone varchar(125) NOT NULL,
	    	purpose varchar(255) NOT NULL,
	    	currency ENUM('KES', 'USD') DEFAULT 'KES',
	    	amount double(8,2) NOT NULL,
	    	transaction_ref varchar(125) NULL,
	    	payment_type varchar(125) NULL,
	    	payment_date timestamp NULL,
	    	status ENUM('initiated', 'cancelled', 'completed') DEFAULT 'initiated',
	    	PRIMARY KEY  (id),
	    	UNIQUE (transaction_id(50)),
	    	UNIQUE (payment_type(50), transaction_ref(50))
	    	) $charset_collate;";

            require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }

    public static function dropGivingTable()
    {
        global $wpdb, $table_prefix;

        $table_name = $table_prefix."ebc_giving";

        $wpdb->query("DROP TABLE IF EXISTS $table_name");
    }
}