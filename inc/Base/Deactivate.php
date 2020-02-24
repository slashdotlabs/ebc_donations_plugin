<?php


namespace SlashEbc\Base;

class Deactivate
{
    public static function run()
    {
        flush_rewrite_rules();
    }
}