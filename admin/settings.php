<?php
defined( 'ABSPATH' ) or die();
?>

    <div id="refiner-settings-page" class="refiner-page-panel">

        <div class="refiner-logo-wrapper">
            <img src="<?php echo plugins_url( 'assets/refiner-logo.png', __FILE__ ); ?>" alt="Refiner Logo" class="refiner-logo">
        </div>

        <form method="post" action="options.php">
            <?php 
            settings_fields('refiner');
            do_settings_sections('refiner'); 
            ?>

            <p class="refiner-page-description">
                <?php
                _e( 'This plugin provides a simple installation of Refiner on your WordPress site. Visit our <a href="https://refiner.io/documentation/kb/getting-started/popup-survey-wordpress/" target="_blank">documenation</a> for a step by step installation guide.');
                ?>
            </p>

            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row">
                            <label for="refiner_project_id">
                                <?php esc_html_e( 'Refiner Project ID', 'refiner'); ?>        
                            </label>
                        </th>

                        <td>
                            <input type="text" name="refiner_project_id" id="refiner_project_id" value="<?php echo esc_attr( get_option('refiner_project_id') ); ?>" placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxx" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="refiner_identify_users">
                                <?php esc_html_e( 'Identify Logged-in Users', 'refiner'); ?>        
                            </label>
                        </th>

                        <td>
                            <?php 
                            $refiner_identify_users = esc_attr(get_option('refiner_identify_users')); 
                            $refiner_identify_users = $refiner_identify_users ? $refiner_identify_users : 'no';
                            ?>
                            <select name="refiner_identify_users" id="refiner_identify_users">
                                <option value="yes" <?= $refiner_identify_users === 'yes' ? 'SELECTED' : '' ?>>
                                    <?php esc_html_e( 'Yes, identify logged-in users', 'refiner'); ?> 
                                </option>
                                <option value="no" <?= $refiner_identify_users === 'no' ? 'SELECTED' : '' ?>>
                                    <?php esc_html_e( 'No, use anonymous mode', 'refiner'); ?> 
                                </option>
                            </select>
                        </td>
                    </tr>
                </tbody>

            </table>

            <?php submit_button(); ?>

        </form>
    </div>
