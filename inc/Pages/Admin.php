<?php

namespace SlashEbc\Pages;

use SlashEbc\Api\Callbacks\AdminCallbacks;
use SlashEbc\Api\SettingsApi;
use SlashEbc\Base\BaseController;

class Admin extends BaseController
{

    public $settings;
    public $callbacks;

    public $pages = array();
    public $subpages = array();

    public function __construct()
    {
        parent::__construct();
        $this->settings = new SettingsApi();
        $this->callbacks = new AdminCallbacks();
    }

    public function register()
    {
        $this->setPages();
        $this->setSubPages();

        $this->setSettings();
        $this->setSections();
        $this->setFields();

        $this->settings->addPages($this->pages)
            ->withSubPage('Configuration Settings')
            ->addSubPages($this->subpages)
            ->register();
    }

    public function setPages()
    {

        $this->pages = array(
            [
                'page_title' => 'EBC Donations',
                'menu_title' => 'Donations Form',
                'capability' => 'manage_options',
                'menu_slug' => 'ebc_donations_plugin',
                'callback' => array($this->callbacks, 'confSettings'),
                'icon_url' => 'dashicons-tickets-alt',
                'position' => 80
            ],
        );
    }

    public function setSubPages()
    {
        $this->subpages = array(
            [
                'parent_slug' => 'ebc_donations_plugin',
                'page_title' => 'Donations',
                'menu_title' => 'Donations',
                'capability' => 'manage_options',
                'menu_slug' => 'ebc_donations_view',
                'callback' => array($this->callbacks, 'donationsPage'),
            ]
        );
    }


    public function setSettings()
    {
        $args = array(
            array(
                'option_group' => 'ebc_donations_settings',
                'option_name' => 'ebc_donations_plugin',
                'callback' => array($this->callbacks, 'ebcSettingsValidate')
            )
        );
        $this->settings->setSettings($args);
    }

    public function setSections()
    {
        $args = array(
            [
                'id' => 'ebc_ipay_live_index',
                'page' => 'ebc_donations_plugin'

            ],
            [
                'id' => 'ebc_ipay_index',
                'title' => 'iPay Settings',
                'callback' => array($this->callbacks, 'ebcIpaySection'),
                'page' => 'ebc_donations_plugin'

            ],
        );
        $this->settings->setSections($args);

    }

    public function setFields()
    {
        $args = array();
        foreach ($this->ebc_settings['ipay'] as $key => $value) {
            switch ($value[2]) {
                case "toggle":
                    $args[] = array(
                        'id' => $key,
                        'title' => $value[0],
                        'callback' => array($this->callbacks, 'ebcCheckboxFields'),
                        'page' => 'ebc_donations_plugin',
                        'section' => 'ebc_ipay_live_index',
                        'args' => array(
                            'label_for' => $key,
                            'field' => 'ipay',
                            'option_name' => 'ebc_donations_plugin',
                            'class' => 'example-class',
                        )
                    );
                    break;
                default:
                    $args[] = array(
                        'id' => $key,
                        'title' => $value[0],
                        'callback' => array($this->callbacks, 'ebcTextFields'),
                        'page' => 'ebc_donations_plugin',
                        'section' => 'ebc_ipay_index',
                        'args' => array(
                            'label_for' => $key,
                            'placeholder' => $value[1],
                            'field' => 'ipay',
                            'class' => 'example-class',
                            'option_name' => 'ebc_donations_plugin',
                        )
                    );
            }
        }
        $this->settings->setFields($args);
    }

}