<?php
/**
* Plugin name:        JDA - Login Page
* Description:        Simple plugin that enables simple white label customization, primarily to the login page. Requires <a href="https://www.advancedcustomfields.com/pro/" target="_blank" rel="noopener">ACF PRO</a> and <a href="https://www.acf-extended.com/">ACF Extended</a>.
* Version:            1.0.5
* Requires at least:  5.8.0
* License:            GPLv2 or later
* License URI:        http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
* Author:             Austin Bange & JDA Worldwide
* Author URI:         https://jdaworldwide.com/
*/

// Check Plugin Compatibility
add_action( 'admin_init', 'child_plugin_has_parent_plugin' );
function child_plugin_has_parent_plugin() {
  if ( is_admin() && current_user_can( 'activate_plugins' ) && ( !is_plugin_active('advanced-custom-fields-pro/acf.php') || ( !is_plugin_active('acf-extended/acf-extended.php') && !is_plugin_active('acf-extended-pro/acf-extended.php') ) ) ) {

    add_action('after_plugin_row_' . plugin_basename( __FILE__ ), 'plugin_row', 5, 3);

    deactivate_plugins( plugin_basename( __FILE__ ) );

    if ( isset( $_GET['activate'] ) ) {
      unset( $_GET['activate'] );
    }
  }
}

function plugin_row($plugin_file, $plugin_data, $status) {

  $acf_active = !is_plugin_active( 'advanced-custom-fields-pro/acf.php' );
  $acfe_active = !is_plugin_active( 'acf-extended/acf-extended.php' ) && !is_plugin_active( 'acf-extended-pro/acf-extended.php' );

  // Check WP version
  $colspan = version_compare($GLOBALS['wp_version'], '5.5', '<') ? 3 : 4;

  ?>

  <tr class="plugin-update-tr active acfe-plugin-tr">
    <td colspan="4" class="plugin-update colspanchange">
      <div class="update-message notice inline notice-error notice-alt">
        <?php if ( $acf_active ) { ?>
          <p><?php echo 'JDA - Login Page requires <a href="https://www.advancedcustomfields.com/pro/" target="_blank">Advanced Custom Fields Pro</a> (minimum: 5.8).'; ?></p>
        <?php } ?>
        <?php if ( $acfe_active ) { ?>
          <p><?php echo 'JDA - Login Page requires <a href="https://www.acf-extended.com/" target="_blank">Advanced Custom Fields Extended</a>, free or Pro.'; ?></p>
        <?php } ?>
      </div>
    </td>
  </tr>
  <?php

}


// Create Options Page
// acf_add_local_field_group() explained here: https://awhitepixel.com/blog/advanced-custom-fields-complete-reference-adding-fields-groups-by-code/
function jda_login_init() {
  if( function_exists('acf_add_options_page') ) {

    acf_add_options_page(array(
      'parent_slug' => 'themes.php',
      'page_title' 	=> 'JDA - Login Page',
    	'menu_title'	=> 'JDA - Login Page',
    	'menu_slug' 	=> 'jda_login',
    	'capability'	=> 'manage_options',
    	'redirect'		=> false,
      'post_id'     => 'jda_login',
      'autoload'    => true,
    ));

  }
}

add_action('acf/init', 'jda_login_init');


// Add Fields to Options Page
if( function_exists('acf_add_local_field_group') ) {

  acf_add_local_field_group(array (
  	'key' => 'input_group_image',
  	'title' => 'Image Settings',
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
  	'fields' => array (
      // Image
  		array (
  			'key' => 'jda_login_field_image',
  			'label' => 'Image',
  			'name' => 'image',
  			'type' => 'image',
  			'prefix' => '',
  			'instructions' => '',
  			'required' => 0,
  			'conditional_logic' => 0,
  			'wrapper' => array (
  				'width' => '50',
  				'class' => '',
  				'id' => '',
  			),
  			'default_value' => '',
  			'placeholder' => '',
  			'prepend' => '',
  			'append' => '',
  			'maxlength' => '',
  			'readonly' => 0,
  			'disabled' => 0,
  		),
      // Admin Bar Logo
      array(
        'key' => 'jda_login_field_admin_bar_logo',
        'label' => 'Overwrite Admin Bar Logo',
        'name' => 'admin_bar_logo',
        'type' => 'true_false',
        'instructions' => 'Overwrite the WordPress logo in the top left with this site\'s favicon/title icon. Will only work if an icon has been set.',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '50',
          'class' => '',
          'id' => '',
        ),
        'default_value' => 1,
        'ui' => 1,
        'ui_on_text' => 'Yes',
        'ui_off_text' => 'No',
      ),
      // Image Height
      array(
  			'key' => 'jda_login_field_image_height',
  			'label' => 'Image Height',
  			'name' => 'image_height',
  			'type' => 'range',
  			'instructions' => '',
  			'required' => 0,
  			'conditional_logic' => 0,
  			'wrapper' => array(
  				'width' => '50',
  				'class' => '',
  				'id' => '',
  			),
  			'default_value' => '100',
  			'min' => 100,
  			'max' => 250,
  			'step' => '',
  			'prepend' => '',
  			'append' => 'px',
  			'acfe_field_group_condition' => 0,
  		),
      // Background Color
      array(
  			'key' => 'jda_login_field_bg_color',
  			'label' => 'Background Color',
  			'name' => 'bg_color',
  			'type' => 'color_picker',
  			'instructions' => '',
  			'required' => 0,
  			'conditional_logic' => 0,
  			'wrapper' => array(
  				'width' => '50',
  				'class' => '',
  				'id' => '',
  			),
        'default_value' => '#ff0000',
  			'placeholder' => '',
  			'prepend' => '',
  			'append' => '',
  			'maxlength' => '',
  			'readonly' => 0,
  			'disabled' => 0,
  		),
  	),
  	'location' => array (
  		array (
  			array (
  				'param' => 'options_page',
  				'operator' => '==',
  				'value' => 'jda_login',
  			),
  		),
  	),
  ));

}

// Login Page CSS
function jda_login() {
  $image = esc_url( get_field('image', 'jda_login')['url'] );
  $image_height = get_field('image_height', 'jda_login');
  $bg_color = get_field('bg_color', 'jda_login');
  ?>
  <style type="text/css">
    body.login {
      background-color:<?php echo $bg_color; ?>;
    }

    #login-message {
      border-radius:4px;
      box-shadow:0 3px 4px rgba(0,0,0,.1);
    }

    #loginform {
      border:0;
      border-radius:4px;
      box-shadow:0 3px 4px rgba(0,0,0,.1);
    }

    #login h1 a, .login h1 a {
			background-image: url(<?php echo esc_url($image); ?>);
	    min-height: <?php echo esc_html($image_height); ?>px;
	    width: 100%;
	    background-size: contain;
	    background-repeat: no-repeat;
	    padding:0;
    }
  </style>
<?php }
add_action( 'login_enqueue_scripts', 'jda_login' );

// Login Page Logo URL
add_filter( 'login_headerurl', 'jda_login_url' );
function jda_login_url($url) {
  return esc_url( home_url() );
}

// Admin Bar Logo
function jda_custom_logo() {
  $image = get_site_icon_url(15);
  if ( $image && get_field('admin_bar_logo', 'jda_login') ) {

  ?>
    <style type="text/css">
      #wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon::before {
        background-image: url(<?php echo $image; ?>);
        background-position: 0 0;
        background-size:cover;
        color:rgba(0, 0, 0, 0);
      }
    </style>
  <?php
  }

}
add_action('wp_before_admin_bar_render', 'jda_custom_logo');

?>
