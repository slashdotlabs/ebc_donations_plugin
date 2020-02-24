<?php


namespace SlashEbc\Base;


use SlashEbc\Api\Twig;

abstract class BaseController
{
    public $plugin_path;
    public $plugin_url;
    public $plugin_name;
    public $plugin_file;
    public $plugin_slug;
    public $twig;

    public $ebc_settings = array();

    public function __construct()
    {
        $this->plugin_path = plugin_dir_path($this->dirname_r(__FILE__, 2));
        $this->plugin_url = plugin_dir_url($this->dirname_r(__FILE__, 2));
        $this->plugin_name = SLASH_COUPON_PLUGIN_NAME;

        $name_parts = explode('/', $this->plugin_name); //'/' <-> DIRECTORY SEPARATOR  -- FOR NON-WINDOWS SYSTEMS
        $this->plugin_slug = $name_parts[0];
        $this->plugin_file = $this->plugin_path.$name_parts[1];

        $this->ebc_settings = array(
            'ipay' => 
            [
                'live' => ['Set iPay to Live', '', 'toggle'],
                'vendor_id' => ['Vendor ID','', 'text'],
                'hashkey' => ['Hash Key','', 'text'],
                
            ],
        );

        $this->twig = Twig::instance();
    }

    // Backward compatibility for PHP < 7.0
    function dirname_r($path, $count=1){
        if ($count > 1){
            return dirname($this->dirname_r($path, --$count));
        }else{
            return dirname($path);
        }
    }

}