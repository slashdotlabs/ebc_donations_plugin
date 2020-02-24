<?php


namespace SlashEbc\Api\Callbacks;

use SlashEbc\Base\BaseController;
use SlashEbc\Database\DonationsModel;
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

    function ebcSettingsValidate($input)
    {
        // TODO:
        $output = get_option('ebc_donations_plugin');
        return $output;
    }
}
