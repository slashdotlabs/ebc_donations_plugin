<div class = "wrap">
    <h1> Configuration Settings </h1>
    <?php settings_errors(); ?>

    <form method="post" action="options.php">
        <?php
            settings_fields( 'ebc_donations_settings' );
            do_settings_sections( 'ebc_donations_plugin' );
            submit_button();
        ?>
    </form>
</div>