<?php


namespace SlashEbc\Api\Callbacks;

use SlashEbc\Base\BaseController;
use SlashEbc\Database\DonationsModel;
use SlashEbc\Database\GivingModel;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AdminCallbacks extends BaseController
{
    public function confSettings()
    {
        return require_once("$this->plugin_path/templates/settings.php");
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    function donationsPage()
    {
        $donations = (new DonationsModel())->fetchDonations();
        $status_badges_map = [
            "initiated" => "info",
            "completed" => "success",
            "cancelled" => "danger"
        ];
        echo $this->twig->render('donations.twig', ['donations' => $donations, 'status_badges_map' => $status_badges_map]);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    function generalDonationsPage()
    {
        $donations = (new GivingModel())->fetchDonations();
        $status_badges_map = [
            "initiated" => "info",
            "completed" => "success",
            "cancelled" => "danger"
        ];
        echo $this->twig->render('general_donations.twig', ['donations' => $donations, 'status_badges_map' => $status_badges_map]);
    }

    public function ebcIpaySection()
    {
        echo '<i>Enter the Vendor ID and Hashkey issued by iPay.</i>';
    }

    function ebcSettingsValidate($input)
    {
        $output = get_option('ebc_donations_plugin');

        foreach ($input as $parentKey => $values) {
            foreach ($values as $fieldKey => $fieldValue) {
                if ($fieldKey == 'live') {
                    $output[$parentKey][$fieldKey] = isset($fieldValue);
                }
                $output[$parentKey][$fieldKey] = $fieldValue;
            }
            if (!in_array('live', $values)) {
                $output[$parentKey]['live'] = false;
            }
        }

        return $output;
    }

    public function ebcCheckboxFields($args)
    {
        $name = $args['label_for'];
        $field = $args['field'];
        $option_name = $args['option_name'];
        $class = $args['class'];
        $checkbox = get_option($option_name);
        $value = esc_attr($checkbox[$field][$name] ?? '');
        echo '
            <input type="checkbox" id="' . $name . '" name="' . $option_name . '[' . $field . '][' . $name . ']" class="' . $class . '"  value = "1" ' . (($value == '1') ? 'checked' : '') . '>
        ';
    }

    public function ebcTextFields($args)
    {
        $name = $args['label_for'];
        $field = $args['field'];
        $option_name = $args['option_name'];
        $placeholder = $args['placeholder'];
        $settings = (array)get_option($option_name);
        $value = esc_attr($settings[$field][$name] ?? '');
        echo '
            <input type="text" id="' . $name . '" name="' . $option_name . '[' . $field . '][' . $name . ']" placeholder="' . $placeholder . '" value= "' . $value . '" required>
        ';
    }
}
