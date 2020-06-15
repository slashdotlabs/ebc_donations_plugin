<?php


namespace SlashEbc\Base;


use SlashEbc\Api\IpayGateway;
use SlashEbc\Database\DonationsModel;
use SlashEbc\Database\GivingModel;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class DonationsForm extends BaseController
{

    public function register()
    {
        // musyimi form shortcode
        add_shortcode("ebc_donations_form", [$this, 'form_shortcode']);

        // giving form shortcode
        add_shortcode("ebc_giving_form", [$this, 'giving_form_shortcode']);

        // thank you message shortcode
        add_shortcode("ebc_thank_you", [$this, 'thank_you_shortcode']);

        // Add ajax handler both logged in and non logged in users
        add_action('wp_ajax_submit_donation', [$this, 'submit_donation']);
        add_action('wp_ajax_nopriv_submit_donation', [$this, 'submit_donation']);

        // Hook code to handle payment callback on each post page
        add_action('wp_head', [$this, 'payment_cb_handler']);
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    function form_shortcode()
    {
        return $this->twig->render('shortcodes/donations_form.twig');
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    function giving_form_shortcode()
    {
        return $this->twig->render('shortcodes/giving_form.twig');
    }

    function submit_donation()
    {
        // Verify nonce
        check_ajax_referer('ebc_submit_donation');

        // Get form data
        $data = $_POST['data'];

        $data['name'] = $data['name'] ?: "Anonymous Donor";
        $data['email'] = $data['email'] ?: "admin@ebcnairobi.org";
        $data['phone_number'] = $data['phone_number'] ?: '0708847952';

        // remove non-numeric characters
        $data['phone_number'] = preg_replace('~\D~', '', $data['phone_number']);

        // Insert transactions
        $model = null;
        if ($data['donation_type'] === 'musyimi') {
            $model = new DonationsModel();
        }

        if ($data['donation_type'] === 'general_giving') {
            $model = new GivingModel();
        }

        $transaction_id = time();
        $insert_data = [
            'transaction_id' => $transaction_id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone_number'],
            'currency' => $data['currency'],
            'amount' => $data['amount'],
        ];

        if ($data['donation_type'] === 'musyimi') {
            $insert_data['tribute'] = $data['tribute'];
        }

        if ($data['donation_type'] === 'general_giving') {
            $insert_data['purpose'] = $data['purpose'];
        }

        if (!$model->insert($insert_data)) wp_send_json_error(['msg' => 'Could not process request. Try again later']);

        // Get ipay url and forward
        $meta_data = [
            'order_id' => $insert_data['transaction_id'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'total_amount' => $data['amount'],
            'currency' => $data['currency'],
            'p1' => $data['donation_type']
        ];

        if ($data['donation_type'] === 'musyimi') {
            $meta_data['cbk'] = site_url('/thank-you/');
        }

        if ($data['donation_type'] === 'general_giving') {
            $meta_data['cbk'] = site_url('/giving-thank-you/');
        }
        $ipay_url = (new IpayGateway())
            ->set_ipay_vendor($data['donation_type'])
            ->retriveUrl($meta_data);
        if (!$ipay_url) wp_send_json_error(['msg' => 'Could not process request']);
        wp_send_json_success(['ipay_url' => $ipay_url]);
        wp_die();
    }

    /**
     * @param $args
     * @param string $content
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function thank_you_shortcode($args, $content = "")
    {
        return $this->twig->render('partials/payment_success.twig', ['content' => $content]);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function payment_cb_handler()
    {
        // Only show on post pages and from ipay
        $required_ipay_cb_fields = ['status', 'txncd', 'mc', 'channel', 'id', 'ivm', 'qwh', 'afd', 'poi', 'uyt', 'ifd', 'agt', 'p1'];
        $valid_ipay_call = array_intersect($required_ipay_cb_fields, array_keys($_GET));
        if (is_page(['thank-you', 'giving-thank-you']) && !empty($valid_ipay_call) && count($valid_ipay_call) == count($required_ipay_cb_fields)) {
            (new IpayGateway())
                ->set_ipay_vendor($_GET['p1'])->payment_cb_handler();
        }
    }
}