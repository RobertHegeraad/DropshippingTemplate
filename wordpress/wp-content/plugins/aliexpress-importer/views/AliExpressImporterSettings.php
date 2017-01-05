<?php
/**
 * Created by PhpStorm.
 * User: Robert
 * Date: 1-1-2017
 * Time: 18:44
 */
?>

<style>

    .importer {
        margin: 20px 12px;
    }

    .importer .form-section {
        margin-bottom: 10px;
    }
    .importer label {
        display: block;
    }
    .importer input[type=text], textarea {
        padding: 10px;
        border-radius: 4px;
        border: 1px solid #dbdbdb;
    }
    .importer button, .importer .btn {
        padding: 10px 14px;
        border-radius: 4px;
        border: 1px solid #dbdbdb !important;
        background-color: #f9f9f9;
        color: #333333;
    }

</style>

<div class="importer">

    <h1>Settings</h1>

    <?php
        if (isset($_GET['settings-updated'])) {
            add_settings_error('wporg_messages', 'wporg_message', __('Settings Saved', 'wporg'), 'updated');
        }

        settings_errors('wporg_messages');
    ?>

    <form method="post" action="options.php">

        <input type="hidden" value="/wp-admin/options.php?page=aliexpress-importer-settings" name="_wp_http_referer">

        <?php settings_fields( 'aliexpress-importer-settings' ); ?>
        <?php do_settings_sections( 'aliexpress-importer-settings' ); ?>

        <p class="instructions">
            Get your App key and Tracking ID by registering your website using the following link:
            <a href="https://portals.aliexpress.com/adcenter/apiSetting.htm" target="_blank">https://portals.aliexpress.com/adcenter/apiSetting.htm</a>
        </p>

        <div class="form-section">
            <label for="settings-app-key">App key</label>
            <input id="settings-app-key" name="app_key" type="text" placeholder="App key" value="<?php echo esc_attr( get_option('app_key') ); ?>">
        </div>

        <div class="form-section">
            <label for="settings-tracking-id">Tracking ID</label>
            <input id="settings-tracking-id" name="tracking_id" type="text" placeholder="Tracking ID" value="<?php echo esc_attr( get_option('tracking_id') ); ?>">
        </div>

        <?php submit_button(); ?>

    </form>

</div>