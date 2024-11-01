<?php
/**
 * SETTING FUNCTIONS
 **/

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * ADMIN MENU
 */
function simple_accessible_forms_admin_menu() {
	add_options_page( __( 'Simple Accessible Forms', 'simple-accessible-forms' ), __( 'Simple Accessible Forms', 'simple-accessible-forms' ), 'manage_options', 'simple-accessible-forms-options.php', 'simple_accessible_forms_options_page' );
}
/**
 * Create options page
 **/
function simple_accessible_forms_options_page() {
	?>
<form action="options.php" method="post" id="simple-accessible-forms-options">

	<h2> <?php esc_html_e( 'Simple Accessible Forms Settings', 'simple-accessible-forms' ); ?>
	</h2>

	<?php
		settings_fields( 'simple_accessible_forms_options_page' );

	if ( function_exists( 'simple_accessible_forms_pro_settings_disable_protected_by' ) ) {
		submit_button();
	}
		simple_accessible_forms_do_settings_sections( 'simple_accessible_forms_options_page' );
	?>

</form>
	<?php
}
add_action( 'admin_menu', 'simple_accessible_forms_admin_menu' );

/**
 * Create settings
 **/
function simple_accessible_forms_settings_init() {

	add_settings_section(
		'simple_accessible_forms_options_page_section',
		'',
		'simple_accessible_forms_text',
		'simple_accessible_forms_options_page'
	);

	register_setting(
		'simple_accessible_forms_options_page',
		'simple_accessible_forms_marketing_setting',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	add_settings_field(
		'simple_accessible_forms_form_to_correct',
		__( 'Forms to Correct', 'simple-accessible-forms' ),
		'simple_accessible_forms_marketing_setting',
		'simple_accessible_forms_options_page',
		'simple_accessible_forms_options_page_section'
	);
}
add_action( 'admin_init', 'simple_accessible_forms_settings_init' );

/**
 * Display settings section
 **/
function simple_accessible_forms_do_settings_sections( $page ) {
	global $wp_settings_sections, $wp_settings_fields;

	if ( ! isset( $wp_settings_sections[ $page ] ) ) {
		return;
	}

	foreach ( (array) $wp_settings_sections[ $page ] as $section ) {
		if ( $section['title'] ) {
			echo '<h2>' . esc_attr( $section['title'] ) . "</h2>\n";
		}

		if ( $section['callback'] ) {
			call_user_func( $section['callback'], $section );
		}

		if ( ! isset( $wp_settings_fields ) || ! isset( $wp_settings_fields[ $page ] ) || ! isset( $wp_settings_fields[ $page ][ $section['id'] ] ) ) {
			continue;
		}
		echo '<div>';
		simple_accessible_forms_do_settings_fields( $page, $section['id'] );
		echo '</div>';
	}
}
/**
 * Display WordPress settings without table
 **/
function simple_accessible_forms_do_settings_fields( $page, $section ) {
	global $wp_settings_fields;

	if ( ! isset( $wp_settings_fields[ $page ][ $section ] ) ) {
		return;
	}

	foreach ( (array) $wp_settings_fields[ $page ][ $section ] as $field ) {
		$class = '';

		if ( ! empty( $field['args']['class'] ) ) {
			$class = ' class="' . esc_attr( $field['args']['class'] ) . '"';
		}

		echo '<div ' . esc_attr( $class ) . '>';

		call_user_func( $field['callback'], $field['args'] );

		echo '</div>';
	}
}
/**
 * Display section text
 */
function simple_accessible_forms_text() {
}
/**
 * Display settings
 **/
function simple_accessible_forms_marketing_setting() {

	if ( ! function_exists( 'simple_accessible_forms_pro_validate_license' ) || ! simple_accessible_forms_pro_validate_license() ) {
		echo '<div class="simple-accessible-forms-marketing">';
		echo '<h2>';
		esc_html_e( 'Simple Accessible Forms Pro', 'simple-accessible-forms' );
		echo '</h2>';
		echo '<p>';
		esc_html_e( 'Upgrade to ', 'simple-accessible-forms' );
		echo '<a href="https://www.alumnionlineservices.com/free-plugins/simple-accessible-forms/">';
		esc_html_e( 'Simple Accessible Forms Pro ', 'simple-accessible-forms' );
		echo '</a>';
		esc_html_e( 'to unlock these great features:', 'simple-accessible-forms' );
		echo '</p>';
		echo '<ul>';
		echo '<li>';
		esc_html_e( 'Unlimited forms. (basic is limited to 2)', 'simple-accessible-forms' );
		echo '</li>';
		echo '<li>';
		esc_html_e( 'Unlimited form fields. (basic is limited to 4)', 'simple-accessible-forms' );
		echo '</li>';
		echo '<li>';
		esc_html_e( 'Automatically import form fields.', 'simple-accessible-forms' );
		echo '</li>';
		echo '<li>';
		esc_html_e( 'Add ids to all form elements.', 'simple-accessible-forms' );
		echo '</li>';
		echo '<li>';
		esc_html_e( 'Adds option to remove "Form accessibility enhanced by Simple <br>Accessible Forms" from forms', 'simple-accessible-forms' );
		echo '</li>';
		echo '</ul>';
		echo '</div>';
	}
}

?>
