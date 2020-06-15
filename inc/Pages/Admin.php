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
                'page_title' => 'Musyimi Donations',
                'menu_title' => 'Musyimi Donations',
                'capability' => 'manage_options',
                'menu_slug' => 'ebc_donations_view',
                'callback' => array($this->callbacks, 'donationsPage'),
            ],
            [
                'parent_slug' => 'ebc_donations_plugin',
                'page_title' => 'Online Giving Donations',
                'menu_title' => 'Online Giving Donations',
                'capability' => 'manage_options',
                'menu_slug' => 'ebc_general_donations_view',
                'callback' => array($this->callbacks, 'generalDonationsPage'),
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
                'id' => 'ebc_ipay_index',
                'title' => 'Musyimi iPay Vendor Settings',
                'callback' => array($this->callbacks, 'ebcIpaySection'),
                'page' => 'ebc_donations_plugin'

            ],
            [
                'id' => 'ebc_ipay_general_giving_index',
                'title' => 'Online Giving iPay Vendor Settings',
                'callback' => array($this->callbacks, 'ebcIpaySection'),
                'page' => 'ebc_donations_plugin'

            ],
        );
        $this->settings->setSections($args);

    }

    public function setFields()
    {
        $args = array_merge(
            $this->ipayDetailsFieldArgs('ebc_ipay_index', 'ipay', $this->getiPayFields()),
            $this->ipayDetailsFieldArgs('ebc_ipay_general_giving_index', 'ipay_general_giving', $this->getiPayFields())
        );
        $this->settings->setFields($args);
    }

    private function ipayDetailsFieldArgs(string $section, string $parentField, array $fieldsList): array
    {
        $args = [];
        foreach ($fieldsList as $field => $attributes) {
            switch ($attributes['input-type']) {
                case "toggle":
                    $args[] = array(
                        'id' => $field,
                        'title' => $attributes['title'],
                        'callback' => array($this->callbacks, 'ebcCheckboxFields'),
                        'page' => 'ebc_donations_plugin',
                        'section' => $section,
                        'args' => array(
                            'label_for' => $field,
                            'field' => $parentField,
                            'option_name' => 'ebc_donations_plugin',
                            'class' => 'example-class',
                        )
                    );
                    break;
                default:
                    $args[] = array(
                        'id' => $field,
                        'title' => $attributes['title'],
                        'callback' => array($this->callbacks, 'ebcTextFields'),
                        'page' => 'ebc_donations_plugin',
                        'section' => $section,
                        'args' => array(
                            'label_for' => $field,
                            'placeholder' => $attributes['placeholder'] ?? '',
                            'field' => $parentField,
                            'class' => 'example-class',
                            'option_name' => 'ebc_donations_plugin',
                        )
                    );
            }
        }
        return $args;
    }

    private function getiPayFields(): array
    {
        return [
            'live' => [
                'title' => 'Set iPay to live',
                'input-type' => 'toggle'
            ],
            'vendor_id' => [
                'title' => 'Vendor ID',
                'input-type' => 'text'
            ],
            'hashkey' => [
                'title' => 'Hashkey',
                'input-type' => 'text'
            ],
        ];
    }

}