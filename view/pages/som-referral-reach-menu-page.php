<?php

/**
 * The admin-specific functionality's UI of the plugin. 
 *
 * @since 1.0.0
 *
 * @package SOM Referral Reach
 * @subpackage som-referral-reach/view
 */


class SOM_Referral_Reach_Menu_Page
{

    /**
     * The main page for the plugin
     *
     * @since 1.0.0
     */
    public function create()
    {
        ob_start();

?>



        <div class="flex flex-col justify-center items-center mx-auto">
            <h1 class="px-10 py-4 bg-dark text-light rounded-r-md">Referral Reach</h1>
            <p>Welcome to the Referral Reach plugin. This plugin provides a simple referral and point systems for your website.</p>

        </div>

<?php


        return ob_get_clean();
    }
}
