<?php


namespace SlashEbc\Base;


class Enqueue extends BaseController
{
    public function register()
    {
        // admin
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);

        // site
        add_action('wp_enqueue_scripts', [$this, 'enqueue_front_scripts'], 999);
    }

    public function enqueue_admin_scripts()
    {
        // enqueue all scripts
        wp_enqueue_style('ebc-donations-coupon-plugin-style', $this->plugin_url . 'assets/css/admin.css');

        // Only in donations page TODO: handle this appropriately don't use custom jquery
        if (array_key_exists('page', $_GET) && in_array($_GET['page'], ["ebc_donations_view", "ebc_general_donations_view"])) {

//            Datatable js
            wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.3.1.js');
            wp_enqueue_script("datatable-jquery", "https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js");
            wp_enqueue_script("datatable-bootstrap", "https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js");

//            Datatble css
            wp_enqueue_style("bootstrap", "https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css");
            wp_enqueue_style("datatable-bootstrap", "https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css");

            wp_enqueue_script('ebc-donations-coupon-donations-script', $this->plugin_url . 'assets/js/donations.js');
        }
    }

    public function enqueue_front_scripts()
    {
        // Show only in relevant pages
        if (is_page(['musyimi', 'thank-you', 'give', 'giving-thank-you'])) {
            wp_enqueue_style('ebc-main', $this->plugin_url . 'assets/css/ebc_donations.css');
        }

        if (is_page(['musyimi', 'give'])) {
            wp_enqueue_script('ebc-main-script', $this->plugin_url . 'assets/js/main.js', [
                'jquery'
            ]);


            $title_nonce = wp_create_nonce('ebc_submit_donation');
            wp_localize_script('ebc-main-script', 'donations_form_ajax', [
                'ajax_url' => admin_url('admin-ajax.php'), 'nonce' => $title_nonce
            ]);
        }

    }
}