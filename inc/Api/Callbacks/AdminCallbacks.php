<?php


namespace SlashEbc\Api\Callbacks;

use SlashEbc\Base\BaseController;
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
        // TODO: fetch records
        echo $this->twig->render('donations.twig', ['payments' => []]);
    }

    function ebcSettingsValidate($input)
    {
        // TODO:
        $output = get_option('ebc_donations_plugin');
        return $output;
    }
}
