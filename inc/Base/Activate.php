<?php


namespace SlashEbc\Base;

use SlashEbc\Database\Migrations;

class Activate
{
    public static function run()
    {
        flush_rewrite_rules();
        Migrations::createDonationsTable();
        Migrations::createGivingTable();
    }

}