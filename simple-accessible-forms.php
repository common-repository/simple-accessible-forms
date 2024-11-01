<?php
/**
 * Plugin Name: Simple Accessible Forms
 * Plugin URI: https://wordpress.org/plugins/simple-accessible-forms/
 * Description: Easily make any html form accessible using the Simple Accessible Forms Plugin. The Simple Accessible Forms Plugin allows you to define form field validation information which will be used to add the necessary features to make the form accessible. It will add form field labels, required field markings, add form field validation and display screen reader friendly error message when a user navigates away from a field, moves focus to the first field in error in the event of a form submission failure.
 * Version: 1.0.11
 * Author: AlumniOnline Web Services LLC
 * Author URI: https://www.alumnionlineservices.com/php-scripts/simple-accessible-forms/
 * Text Domain: simple-accessible-forms
 * License: GPLv3
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

require plugin_dir_path( __FILE__ ) . 'settings.php';

/**
 * Import public scripts
 **/
function simple_accessible_forms_scripts() {
	wp_register_script( 'simple-accessible-forms-scripts', plugin_dir_url( __FILE__ ) . 'scripts/jquery_form_validation.js', array( 'jquery' ), filemtime( plugin_dir_path( __FILE__ ) . 'scripts/jquery_form_validation.js' ), '', true );
	wp_enqueue_script( 'simple-accessible-forms-scripts' );

	wp_register_script( 'simple-accessible-forms-publicscripts', plugin_dir_url( __FILE__ ) . 'scripts/public-scripts.js', array( 'jquery' ), filemtime( plugin_dir_path( __FILE__ ) . 'scripts/public-scripts.js' ), '', true );
	wp_enqueue_script( 'simple-accessible-forms-publicscripts' );

	wp_register_style( 'simple-accessible-forms-public-styles', plugin_dir_url( __FILE__ ) . 'publicstyles.css', array(), filemtime( plugin_dir_path( __FILE__ ) . 'publicstyles.css' ) );
	wp_enqueue_style( 'simple-accessible-forms-public-styles' );
}
add_action( 'wp_enqueue_scripts', 'simple_accessible_forms_scripts' );

/**
 * Import admin scripts
 **/
function simple_accessible_forms_admin_scripts() {
	wp_register_script( 'simple-accessible-forms-settings-scripts', plugin_dir_url( __FILE__ ) . 'scripts/scripts.js', array( 'jquery' ), filemtime( plugin_dir_path( __FILE__ ) . 'scripts/scripts.js' ), '', true );
	wp_enqueue_script( 'simple-accessible-forms-settings-scripts' );

	wp_localize_script(
		'simple-accessible-forms-settings-scripts',
		'simpleaccessibleformsVariables',
		array(
			'emptyvalueerror'    => __( 'Enter a valid value for all requested fields.', 'simple-accessible-forms' ),
			'invalidformvalue'   => __( 'Enter a value using only letters, numbers, punctuation or spaces before continuing.' ),
			'invalidformurl'     => __( 'Enter a valid url.', 'simple-accessible-forms' ),
			'filetypeformvalue'  => __( 'Enter one or more mime types seperated by a comma (application/pdf).', 'simple-accessible-forms' ),
			'numericformvalue'   => __( 'Enter a numeric value..', 'simple-accessible-forms' ),
			'resturl'            => esc_url_raw( get_rest_url() ),
			'nonce'              => wp_create_nonce( 'wp_rest' ),
			'formupdated'        => __( 'Form updated.', 'simple-accessible-forms' ),
			'nofields'           => __( 'You do not have any fields yet.', 'simple-accessible-forms' ),
			'formremoved'        => __( 'Form removed.', 'simple-accessible-forms' ),
			'formloaded'         => __( 'The requested action was completed and form was loaded.', 'simple-accessible-forms' ),
			'fieldremoved'       => __( 'Field removed.', 'simple-accessible-forms' ),
			'formadded'          => __( 'Form added.', 'simple-accessible-forms' ),
			'formcleared'        => __( 'Form values cleared, please enter your form values below.', 'simple-accessible-forms' ),
			'fieldupdated'       => __( 'Field updated.', 'simple-accessible-forms' ),
			'fieldadded'         => __( 'Field added.', 'simple-accessible-forms' ),
			'fieldsimported'     => __( 'Form fields successfully imported, edit each field to add the required validation information.', 'simple-accessible-forms' ),
			'formlimitexceeded'  => __( 'The basic version is limited to 2 forms. Add the Pro extension to unlock unlimited forms and formfields.', 'simple-accessible-forms' ),
			'fieldlimitexceeded' => __( 'The basic version is limited to 4 form fields. Add the Pro extension to unlock unlimited forms and formfields.', 'simple-accessible-forms' ),
			'fieldsimportfailed' => __( 'Form fields could not be imported. You may still use the "Add a form field" option to create your form fields.', 'simple-accessible-forms' ),
			'invalidlicense'     => __( 'Upgrade to Simple Accessible Forms Pro to import form fields.', 'simple-accessible-forms' ),
			'invalidurl'         => __( 'Form must be hosted on the same website.', 'simple-accessible-forms' ),
			'unknownerror'       => __( 'An unexpected error occured. Record was not saved.', 'simple-accessible-forms' ),

		)
	);

	wp_register_style( 'simple-accessible-forms-settings-styles', plugin_dir_url( __FILE__ ) . 'style.css', array(), filemtime( plugin_dir_path( __FILE__ ) . 'style.css' ) );
	wp_enqueue_style( 'simple-accessible-forms-settings-styles' );
}
add_action( 'admin_enqueue_scripts', 'simple_accessible_forms_admin_scripts' );


/**
 * DEFINE FORM EDITOR PAGE
 * Add admin menu
 **/
function simple_accessible_forms_builder_admin_menu() {
	add_menu_page( __( 'Simple Accessible Forms Form Builder', 'simple-accessible-forms' ), __( 'Simple Accessible Forms Form Builder', 'simple-accessible-forms' ), 'edit_pages', 'simple-accessible-forms-builder/builder.php', 'simple_accessible_forms_formbuilder_page', 'dashicons-forms', 7 );

	add_submenu_page( 'simple-accessible-forms-builder/builder.php', __( 'Form Builder', 'simple-accessible-forms' ), __( 'Form Builder', 'simple-accessible-forms' ), 'edit_pages', 'simple-accessible-forms-builder/builder.php', 'simple_accessible_forms_formbuilder_page' );

	add_submenu_page( 'simple-accessible-forms-builder/builder.php', __( 'Settings', 'simple-accessible-forms' ), __( 'Settings', 'simple-accessible-forms' ), 'edit_pages', 'simple-accessible-forms-options.php', 'simple-accessible-forms-options' );
}
add_action( 'admin_menu', 'simple_accessible_forms_builder_admin_menu' );

/**
 * Create form builder page
 **/
function simple_accessible_forms_formbuilder_page() {

	if ( ! isset( $_REQUEST['_wpnonce'] ) || wp_verify_nonce( sanitize_key( $_REQUEST['_wpnonce'] ) ) ) {
		echo '<div id="simple_accessible_forms_page_wrapper">';
	}

	?>
<form>
	<h1>
		<?php esc_html_e( 'Simple Accessible Forms Builder', 'simple-accessible-forms' ); ?>
	</h1>
	<?php
		echo '<button type="button" class="simple_accessible_forms_new_form_btn" >';
		esc_html_e( 'Create New Form', 'simple-accessible-forms' );
		echo ' </button>';
	if ( function_exists( 'simple_accessible_forms_pro_validate_license_message' ) ) {
		simple_accessible_forms_pro_validate_license_message();
	}
		echo '<h2>';
		esc_html_e( 'Your Forms', 'simple-accessible-forms' );
		echo '</h2>';
		echo '<div id="simple_accessible_forms_list">';
		simple_accessible_forms_form_list();
		echo '</div>';
		echo '<p class="simple_accessible_forms_savemessage" aria-live="polite">';
		echo '</p>';
		simple_accessible_forms_marketing_setting();
		echo '<div class="simple_accessible_forms_form_element">';
		echo '<input type="hidden" name="simple_accessible_forms_form_update_formid" id="simple_accessible_forms_form_update_formid" value=""> ';
		echo '<p><label for="simple_accessible_forms_form_to_correct_form_name_add">';
		esc_html_e( 'FORM NAME: ', 'simple-accessible-forms' );
		echo '<input type="text" size="35" name="simple_accessible_forms_form_to_correct_form_name" id="simple_accessible_forms_form_to_correct_form_name_add" value="" />';
		esc_html_e( '(enter a name/label for this form, will be used as an aria-label on the form element)', 'simple-accessible-forms' );

		echo '</label></p> ';
		echo '<p><label for="simple_accessible_forms_form_to_correct_form_url_add">';
		esc_html_e( 'FORM URL: ', 'simple-accessible-forms' );
		echo '<input type="text" size="35" name="simple_accessible_forms_form_to_correct_form_url" id="simple_accessible_forms_form_to_correct_form_url_add" value="" />';
		esc_html_e( '(enter the website url where your form is located)', 'simple-accessible-forms' );
		echo '</label></p> ';

		echo '<p><label for="simple_accessible_forms_form_to_correct_form_element_id_add">';
		esc_html_e( 'FORM ELEMENT ID: ', 'simple-accessible-forms' );
		echo '<input type="text" size="35" name="simple_accessible_forms_form_to_correct_form_element_id" id="simple_accessible_forms_form_to_correct_form_element_id_add" value="" />';
		echo '(i.e... &lt;form id="myform"&gt;)</label></p>';

		echo '<p><label for="simple_accessible_forms_form_to_correct_form_response_id">';
		esc_html_e( 'STATUS MESSAGE ELEMENT ID: ', 'simple-accessible-forms' );
		echo '<input type="text" size="35" name="simple_accessible_forms_form_to_correct_form_response_id" id="simple_accessible_forms_form_to_correct_form_response_id" value="" />';
		esc_html_e( '(optional field used to add alert role to form status message displayed after the form is submitted.', 'simple-accessible-forms' );
		echo 'i.e... &lt;div id="myform"&gt;';
		esc_html_e( 'An error occured', 'simple-accessible-forms' );
		echo '&lt;/div&gt; ';
		echo ')</label></p>';

		echo '<p><label for="simple_accessible_forms_form_to_correct_form_apply_required_markings">';
		echo '<input type="checkbox" name="simple_accessible_forms_form_to_correct_form_apply_required_markings" id="simple_accessible_forms_form_to_correct_form_apply_required_markings" value="1" />';
		esc_html_e( 'Automatically apply required markings to required form fields ', 'simple-accessible-forms' );
		echo '(i.e... * &lt;span class="screen-reader-text"&gt;Required&lt;/span&gt;).</label></p>';

		echo '<p><label for="simple_accessible_forms_validate_on_submit">';
		echo '<input type="checkbox" name="simple_accessible_forms_validate_on_submit" id="simple_accessible_forms_validate_on_submit" value="1" />';
		esc_html_e( 'Enable form field validation on submit.', 'simple-accessible-forms' );
		echo '</label></p>';

		echo '<p><label for="simple_accessible_forms_recaptcha">';
		echo '<input type="checkbox" name="simple_accessible_forms_recaptcha" id="simple_accessible_forms_recaptcha" value="1" />';
		esc_html_e( 'Hide Google Recaptcha V3 from screen reader users (Recaptcha will still work).', 'simple-accessible-forms' );
		echo '</label></p>';

		echo '<p><label for="simple_accessible_forms_active">';
		echo '<input type="checkbox" name="simple_accessible_forms_active" id="simple_accessible_forms_active" value="1" />';
		esc_html_e( 'Activate this form.', 'simple-accessible-forms' );
		echo '</label></p>';

		echo '<button type="button" class="simple_accessible_forms_form_save_btn" >';
		esc_html_e( 'Save Form', 'simple-accessible-forms' );
		echo ' </button>';
		echo '</div>';
		echo '<div class="simple_accessible_forms_create_form">';

		echo '<div id="simple_accessible_forms_dynamic_fields">';
		echo '</div>';

		echo '</div>';

	?>
</form>
	<?php
	if ( ! isset( $_REQUEST['_wpnonce'] ) || wp_verify_nonce( sanitize_key( $_REQUEST['_wpnonce'] ) ) ) {
		echo '</div>';
	}
}

/**
 * Register endpoints
*/
add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'simple_accessible_forms_update/v1',
			'/update',
			array(
				'methods'             => 'GET',
				'permission_callback' => function () {
					return current_user_can( 'edit_pages' );
				},
				'callback'            => 'simple_accessible_forms_form_values_updates_rest',
			)
		);

		register_rest_route(
			'simple_accessible_forms_field_update/v1',
			'/update',
			array(
				'methods'             => 'GET',
				'permission_callback' => function () {
					return current_user_can( 'edit_pages' );
				},
				'callback'            => 'simple_accessible_forms_formfield_updates_rest',
			)
		);

		register_rest_route(
			'simple_accessible_forms_load/v1',
			'/load',
			array(
				'methods'             => 'GET',
				'permission_callback' => function () {
					return current_user_can( 'edit_pages' );
				},
				'callback'            => 'simple_accessible_forms_load_form_rest',
			)
		);

		register_rest_route(
			'simple_accessible_forms_remove/v1',
			'/remove',
			array(
				'methods'             => 'GET',
				'permission_callback' => function () {
					return current_user_can( 'edit_pages' );
				},
				'callback'            => 'simple_accessible_forms_form_remove_form_rest',
			)
		);

		register_rest_route(
			'simple_accessible_forms_refresh/v1',
			'/refresh',
			array(
				'methods'             => 'GET',
				'permission_callback' => function () {
					return current_user_can( 'edit_pages' );
				},
				'callback'            => 'simple_accessible_forms_form_refresh_rest',
			)
		);

		if ( function_exists( 'simple_accessible_forms_pro_formfield_import_rest' ) ) {
			register_rest_route(
				'simple_accessible_forms_fieldimport/v1',
				'/import',
				array(
					'methods'             => 'GET',
					'permission_callback' => function () {
						return current_user_can( 'edit_pages' );
					},
					'callback'            => 'simple_accessible_forms_pro_formfield_import_rest',
				)
			);
		}
	}
);

/**
 * Reload content
 **/
function simple_accessible_forms_form_refresh_rest() {
	check_ajax_referer( 'wp_rest', '_wpnonce' );

	simple_accessible_forms_formbuilder_page();
}

/**
 * Load form REST
 **/
function simple_accessible_forms_load_form_rest() {
	check_ajax_referer( 'wp_rest', '_wpnonce' );

	global $wpdb;

	$formid = 0;

	if ( isset( $_REQUEST['formid'] ) ) {
		$formid = sanitize_text_field( wp_unslash( $_REQUEST['formid'] ) );

		$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'simple_accessible_forms_forms where id = %d', $formid ), ARRAY_A );

		foreach ( $results as $row ) {
			$data['formelementid']      = $row['formid'];
			$data['formnicename']       = $row['formnicename'];
			$data['formid']             = $row['id'];
			$data['formurl']            = $row['formurl'];
			$data['addrequiredmarking'] = $row['addrequiredmarking'];
			$data['validate_on_submit'] = $row['validate_on_submit'];
			$data['active']             = $row['active'];
			$data['recaptcha']          = $row['recaptcha'];
			$data['responseid']         = $row['responseid'];
		}

		$data['fieldlist'] = simple_accessible_forms_form_field_list( $formid );

		return $data;

	}
}

/**
 * Load form
 **/
function simple_accessible_forms_load_form( $formid = 0, $fieldid = 0 ) {
	global $wpdb;
	$form = '';

	$fieldname          = '';
	$fieldtype          = '';
	$fieldlabel         = '';
	$requiredfield      = '';
	$requiredif         = '';
	$eitheror           = '';
	$filetypes          = '';
	$fieldformat        = '';
	$maxuploadsize      = '';
	$autocompleteformat = '';

	if ( $fieldid > 0 ) {

		$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'simple_accessible_forms_formfields where id = %d', $fieldid ), ARRAY_A );

		foreach ( $results as $row ) {
			$fieldname          = $row['fieldname'];
			$fieldtype          = $row['fieldtype'];
			$fieldlabel         = $row['fieldlabel'];
			$requiredfield      = $row['requiredfield'];
			$requiredif         = $row['requiredif'];
			$eitheror           = $row['eitheror'];
			$filetypes          = $row['filetypes'];
			$fieldid            = $row['id'];
			$maxuploadsize      = $row['maxuploadsize'];
			$fieldformat        = $row['fieldformat'];
			$formid             = $row['formid'];
			$autocompleteformat = $row['autocompleteformat'];
		}
	}

	$form .= '<p class="simple_accessible_forms_instructions">';
	$form .= '</p>';
	$form .= '<input type="hidden" name="simple_accessible_forms_form_to_correct_formid" class="simple_accessible_forms_form_to_correct_formid" value="' . esc_attr( $formid ) . '"> ';
	$form .= '<input type="hidden" name="simple_accessible_forms_form_to_correct_fieldid" class="simple_accessible_forms_form_to_correct_fieldid" value="' . esc_attr( $fieldid ) . '"> ';
	$form .= '<p><label for="simple_accessible_forms_form_to_correct_field_name_add_' . esc_attr( $fieldid ) . '">';
	$form .= __( 'FORM FIELD NAME:', 'simple-accessible-forms' );
	$form .= '<input type="text" size="15" name="simple_accessible_forms_form_to_correct_field_name" id="simple_accessible_forms_form_to_correct_field_name_add_' . esc_attr( $fieldid ) . '" value="' . esc_attr( $fieldname ) . '" />';
	$form .= '(i.e... &lt; input name="firstname"&gt;)</label> </p>';
	$form .= '<p><label for="simple_accessible_forms_form_to_correct_field_label_add_' . esc_attr( $fieldid ) . '" >';
	$form .= __( 'FORM FIELD LABEL: ', 'simple-accessible-forms' );
	$form .= '<input type="text" size="15" name="simple_accessible_forms_form_to_correct_field_name" id="simple_accessible_forms_form_to_correct_field_label_add_' . esc_attr( $fieldid ) . '" value="' . esc_attr( $fieldlabel ) . '" />';
	$form .= '(i.e... &lt;label&gt;First Name&lt;/label&gt;)</label> </p>';

	$form .= '<p><label for="simple_accessible_forms_form_to_correct_fieldtype_add_' . esc_attr( $fieldid ) . '">';
	$form .= __( 'FORM FIELD TYPE: ', 'simple-accessible-forms' );
	$form .= '<select name="simple_accessible_forms_form_to_correct_fieldtype" id="simple_accessible_forms_form_to_correct_fieldtype_add_' . esc_attr( $fieldid ) . '">
<option value="text" ';
	if ( 'text' === $fieldtype ) {
		$form .= 'selected';
	}
	$form .= '>' . __( 'text', 'simple-accessible-forms' ) . '</option>
<option value="textarea" ';
	if ( 'textarea' === $fieldtype ) {
		$form .= 'selected';
	}
	$form .= '>' . __( 'teaxarea', 'simple-accessible-forms' ) . '</option>
<option value="file" ';
	if ( 'file' === $fieldtype ) {
		$form .= 'selected';
	}
	$form .= '>' . __( 'file', 'simple-accessible-forms' ) . '</option>
<option value="group" ';
	if ( 'group' === $fieldtype ) {
		$form .= 'selected';
	}
	$form .= '>' . __( 'radio/checkbox group', 'simple-accessible-forms' ) . '</option>
<option value="radio" ';
	if ( 'radio' === $fieldtype ) {
		$form .= 'selected';
	}
	$form .= '>' . __( 'radio', 'simple-accessible-forms' ) . '</option>
<option value="select" ';
	if ( 'select' === $fieldtype ) {
		$form .= 'selected';
	}
	$form .= '>' . __( 'select', 'simple-accessible-forms' ) . '</option>
<option value="checkbox" ';
	if ( 'checkbox' === $fieldtype ) {
		$form .= 'selected';
	}
	$form .= '>' . __( 'checkbox', 'simple-accessible-forms' ) . '</option> 
<option value="checkbox" ';
	if ( 'hidden' === $fieldtype ) {
		$form .= 'selected';
	}
	$form .= '>' . __( 'hidden', 'simple-accessible-forms' ) . '</option> 
<option value="checkbox" ';
	if ( 'password' === $fieldtype ) {
		$form .= 'selected';
	}
	$form .= '>' . __( 'password', 'simple-accessible-forms' ) . '</option> 
<option value="duetdatepicker" ';
	if ( 'duetdatepicker' === $fieldtype ) {
		$form .= 'selected';
	}
	$form .= '>' . __( 'duet datepicker', 'simple-accessible-forms' ) . '</option> 
</select>';
	$form .= '</label></p>';
	$form .= '<p><label for="simple_accessible_forms_form_to_correct_required_field_add_' . esc_attr( $fieldid ) . '" >';
	$form .= __( 'REQUIRED FIELD:', 'simple-accessible-forms' );
	$form .= ' <input type="checkbox" size="15" name="simple_accessible_forms_form_to_correct_required_field" id="simple_accessible_forms_form_to_correct_required_field_add_' . esc_attr( $fieldid ) . '" value="required" ';
	if ( 'required' === $requiredfield ) {
		$form .= 'checked';
	}
	$form .= '/>';
	$form .= ' (should be unchecked if not the first or last elements in a radio or checkbox group or if a requiredif value is entered)</label> </p>';
	$form .= '<p><label for="simple_accessible_forms_form_to_correct_required_if_field_add_' . esc_attr( $fieldid ) . '" >';
	$form .= __( 'REQUIRED IF:', 'simple-accessible-forms' );
	$form .= ' <input type="text" size="15" name="simple_accessible_forms_form_to_correct_required_if_field" id="simple_accessible_forms_form_to_correct_required_if_field_add_' . esc_attr( $fieldid ) . '" value="' . esc_attr( $requiredif ) . '" /> ';
	$form .= __( '(OPTIONAL - enter a fieldname and value seperated by an equal sign to require a field only if another field is set to a certain value. i.e... FieldName=FieldValue)', 'simple-accessible-forms' );
	$form .= '</label> </p>';

	$form .= '<p><label for="simple_accessible_forms_form_to_correct_either_or_field_add_' . esc_attr( $fieldid ) . '" >';
	$form .= __( 'EITHER OR:', 'simple-accessible-forms' );
	$form .= ' <input type="text" size="15" name="simple_accessible_forms_form_to_correct_either_or_field" id="simple_accessible_forms_form_to_correct_either_or_field_add_' . esc_attr( $fieldid ) . '" value="' . esc_attr( $eitheror ) . '" /> ';

	$form .= __( '(OPTIONAL - include a comma separated list of field names. When set, at least one of the fields must be a non-empty value. To ensure that users are prompted after leaving the final field in the group, this attribute should be placed on the form field that is lowest in the html structure. If the field is a  radio group the attribute should be placed on the first and last elements in the radio group.)', 'simple-accessible-forms' );
	$form .= '</label> </p>';

	$form .= '<p><label for="simple_accessible_forms_form_to_correct_file_types_field_add_' . esc_attr( $fieldid ) . '" >';
	$form .= __( 'ALLOWED FILE TYPES:', 'simple-accessible-forms' );
	$form .= ' <input type="text" size="15" name="simple_accessible_forms_form_to_correct_file_types_field" id="simple_accessible_forms_form_to_correct_file_types_field_add_' . esc_attr( $fieldid ) . '" value="' . esc_attr( $filetypes ) . '" /> ';
	$form .= __( '(for file fields, include a comma seperated list of supported mime types i.e...application/pdf,application/ppt)', 'simple-accessible-forms' );

	$form .= '</label> </p>';

	$form .= '<p><label for="simple_accessible_forms_form_to_correct_file_maxuploadsize_field_add_' . esc_attr( $fieldid ) . '" >';
	$form .= __( 'MAX UPLOAD SIZE:', 'simple-accessible-forms' );
	$form .= ' <input type="text" size="15" name="simple_accessible_forms_form_to_correct_file_maxuploadsize_field" id="simple_accessible_forms_form_to_correct_file_maxuploadsize_field_add_' . esc_attr( $fieldid ) . '" value="' . esc_attr( $maxuploadsize ) . '" /> ';
	$form .= __( '(for file fields, set to the file size limit in bytes i.e.... 20000000 = 2MB)', 'simple-accessible-forms' );

	$form .= '</label> </p>';

	$form .= '<p><label for="simple_accessible_forms_form_to_correct_format_add_' . esc_attr( $fieldid ) . '">';
	$form .= __( 'EXPECTED FORMAT OF FORM VALUE: ', 'simple-accessible-forms' );
	$form .= '<select name="simple_accessible_forms_form_to_correct_format" id="simple_accessible_forms_form_to_correct_format_add_' . esc_attr( $fieldid ) . '">
<option value="" ';
	if ( '' === $fieldformat ) {
		$form .= 'selected';
	}
	$form .= '>' . __( 'none', 'simple-accessible-forms' ) . '</option>
<option value="alpha-puncuation-space" ';
	if ( 'alpha-puncuation-space' === $fieldformat ) {
		$form .= 'selected';
	}
	$form .= '>' . __( 'letters, puncuation and spaces', 'simple-accessible-forms' ) . '</option>
<option value="alnum-puncuation-space" ';
	if ( 'alnum-puncuation-space' === $fieldformat ) {
		$form .= 'selected';
	}
	$form .= '>' . __( 'letters, numbers, puncuation and spaces', 'simple-accessible-forms' ) . '</option>
<option value="phone" ';
	if ( 'phone' === $fieldformat ) {
		$form .= 'selected';
	}
	$form .= '>' . __( 'phone', 'simple-accessible-forms' ) . '</option>
<option value="url" ';
	if ( 'url' === $fieldformat ) {
		$form .= 'selected';
	}
	$form .= '>' . __( 'url', 'simple-accessible-forms' ) . '</option>
<option value="email" ';
	if ( 'email' === $fieldformat ) {
		$form .= 'selected';
	}
	$form .= '>' . __( 'email', 'simple-accessible-forms' ) . ' </option>
<option value="numeric" ';
	if ( 'numeric' === $fieldformat ) {
		$form .= 'selected';
	}
	$form .= '>' . __( 'numbers only', 'simple-accessible-forms' ) . '</option>
<option value="alpha" ';
	if ( 'alpha' === $fieldformat ) {
		$form .= 'selected';
	}
	$form .= '>' . __( 'letters only', 'simple-accessible-forms' ) . '</option>
<option value="alnum" ';
	if ( 'alnum' === $fieldformat ) {
		$form .= 'selected';
	}
	$form .= '>' . __( 'letters or numbers', 'simple-accessible-forms' ) . '</option>
<option value="zip" ';
	if ( 'zip' === $fieldformat ) {
		$form .= 'selected';
	}
	$form .= '>' . __( 'zip code', 'simple-accessible-forms' ) . '</option>
<option value="date-YYYY-MM-DD" ';
	if ( 'date-YYYY-MM-DD' === $fieldformat ) {
		$form .= 'selected';
	}
	$form .= '>' . __( 'date (2022-10-23)', 'simple-accessible-forms' ) . '</option>
<option value="date-MM-DD-YYYY" ';
	if ( 'date-MM-DD-YYYY' === $fieldformat ) {
		$form .= 'selected';
	}
	$form .= '>' . __( 'date (12/12/2021 or 12-12-2021)', 'simple-accessible-forms' ) . '</option>
</select>';
	$form .= '</label></p>';

	$autocompletevalues['off']                  = __( 'off', 'simple-accessible-forms' );
	$autocompletevalues['name']                 = __( 'Full name', 'simple-accessible-forms' );
	$autocompletevalues['honorific-prefix']     = __( 'Prefix or title (e.g., "Mr.", "Ms.", "Dr.")', 'simple-accessible-forms' );
	$autocompletevalues['given-name']           = __( 'First Name', 'simple-accessible-forms' );
	$autocompletevalues['additional-name']      = __( 'Middle Mame, Forename', 'simple-accessible-forms' );
	$autocompletevalues['family-name']          = __( 'Last Name, surname', 'simple-accessible-forms' );
	$autocompletevalues['honorific-suffix']     = __( 'Suffix (e.g., "Jr.", "B.Sc.", "MBASW", "II")', 'simple-accessible-forms' );
	$autocompletevalues['nickname']             = __( 'Nickname, screen name, handle', 'simple-accessible-forms' );
	$autocompletevalues['email']                = __( 'E-mail address', 'simple-accessible-forms' );
	$autocompletevalues['tel-national']         = __( 'Phone - Telephone number without the county code component', 'simple-accessible-forms' );
	$autocompletevalues['url']                  = __( 'URL / Web Site', 'simple-accessible-forms' );
	$autocompletevalues['organization-title']   = __( 'Job Title', 'simple-accessible-forms' );
	$autocompletevalues['organization']         = __( 'Company Name', 'simple-accessible-forms' );
	$autocompletevalues['street-address']       = __( 'Street Address', 'simple-accessible-forms' );
	$autocompletevalues['address-line1']        = __( 'Address Line 1', 'simple-accessible-forms' );
	$autocompletevalues['address-line2']        = __( 'Address Line 2', 'simple-accessible-forms' );
	$autocompletevalues['address-line3']        = __( 'Address Line 3', 'simple-accessible-forms' );
	$autocompletevalues['address-line4']        = __( 'Address Line 4', 'simple-accessible-forms' );
	$autocompletevalues['address-level2']       = __( 'City/Town', 'simple-accessible-forms' );
	$autocompletevalues['address-level1']       = __( 'State/Province', 'simple-accessible-forms' );
	$autocompletevalues['postal-code']          = __( 'Zip/Postal Code', 'simple-accessible-forms' );
	$autocompletevalues['country-name']         = __( 'Country Name', 'simple-accessible-forms' );
	$autocompletevalues['country']              = __( 'Country Code', 'simple-accessible-forms' );
	$autocompletevalues['username']             = __( 'Username', 'simple-accessible-forms' );
	$autocompletevalues['new-password']         = __( 'New Password', 'simple-accessible-forms' );
	$autocompletevalues['current-password']     = __( 'Current Password', 'simple-accessible-forms' );
	$autocompletevalues['cc-name']              = __( 'Credit Card - Full Name', 'simple-accessible-forms' );
	$autocompletevalues['cc-number']            = __( 'Credit Card number', 'simple-accessible-forms' );
	$autocompletevalues['cc-exp']               = __( 'Credit Card Expiration date ', 'simple-accessible-forms' );
	$autocompletevalues['cc-exp-month']         = __( 'Credit Card Expiration Month', 'simple-accessible-forms' );
	$autocompletevalues['cc-exp-year']          = __( 'Credit Card Expiration Year', 'simple-accessible-forms' );
	$autocompletevalues['cc-csc']               = __( 'Credit Card Security code (CVC, CVV) ', 'simple-accessible-forms' );
	$autocompletevalues['cc-type']              = __( 'Credit Card Type', 'simple-accessible-forms' );
	$autocompletevalues['transaction-currency'] = __( 'Currency', 'simple-accessible-forms' );
	$autocompletevalues['transaction-amount']   = __( 'Transaction Amount', 'simple-accessible-forms' );
	$autocompletevalues['language ']            = __( 'Preferred Language', 'simple-accessible-forms' );
	$autocompletevalues['bday']                 = __( 'Birthday', 'simple-accessible-forms' );
	$autocompletevalues['bday-day']             = __( 'Day component of birthday', 'simple-accessible-forms' );
	$autocompletevalues['bday-month']           = __( 'Month component of birthday', 'simple-accessible-forms' );
	$autocompletevalues['bday-year']            = __( 'Year component of birthday', 'simple-accessible-forms' );
	$autocompletevalues['sex']                  = __( 'Gender', 'simple-accessible-forms' );
	$autocompletevalues['photo']                = __( 'Photograph, icon, or other image', 'simple-accessible-forms' );
	$autocompletevalues['tel']                  = __( 'Full telephone number, including country code', 'simple-accessible-forms' );
	$autocompletevalues['tel-country-code']     = __( 'Country code component of the telephone number', 'simple-accessible-forms' );
	$autocompletevalues['tel-area-code']        = __( 'Area code component of the telephone number, with a country-internal prefix applied if applicable', 'simple-accessible-forms' );
	$autocompletevalues['tel-local']            = __( ' Telephone number without the country code and area code components', 'simple-accessible-forms' );
	$autocompletevalues['tel-local-prefix']     = __( 'First part of the component of the telephone number that follows the area code, when that component is split into two components', 'simple-accessible-forms' );
	$autocompletevalues['tel-local-suffix']     = __( 'Second part of the component of the telephone number that follows the area code, when that component is split into two components', 'simple-accessible-forms' );
	$autocompletevalues['tel-extension']        = __( 'Telephone number internal extension code', 'simple-accessible-forms' );
	$autocompletevalues['impp']                 = __( 'URL representing an instant messaging protocol endpoint (for example, "aim:goim?screenname=example" or "xmpp:fred@example.net")', 'simple-accessible-forms' );

	$form .= '<p><label for="simple_accessible_forms_field_autocomplete_attr_' . esc_attr( $fieldid ) . '">';
	$form .= __( 'AUTO COMPLETE FORMAT: ', 'simple-accessible-forms' );
	$form .= '<select name="simple_accessible_forms_field_autocomplete_attr" id="simple_accessible_forms_field_autocomplete_attr_' . esc_attr( $fieldid ) . '">
<option value="" ';
	if ( '' === $autocompleteformat ) {
		$form .= 'selected';
	}
	$form .= '>' . __( 'none', 'simple-accessible-forms' ) . '</option>';

	foreach ( $autocompletevalues as $key => $value ) {
		$form .= '<option value="' . esc_attr( $key ) . '" ';
		if ( $autocompleteformat === $key ) {
			$form .= 'selected';
		}
		$form .= '>' . esc_attr( $value ) . '</option>';
	}

	$form .= '</select>';
	$form .= ' (OPTIONAL - adds an autocomplete attribute to allow browsers to better predict the content)</label></p>';

	$form .= '<button type="button" class="simple_accessible_forms_form_field_save_btn" ><i class="fas fa-plus" aria-hidden="true"></i>' . __( 'Save', 'simple-accessible-forms' ) . ' </button>';

	return $form;
}
/**
 * Add or update form
 **/
function simple_accessible_forms_form_values_updates_rest() {
	check_ajax_referer( 'wp_rest', '_wpnonce' );

	if ( isset( $_REQUEST['variables'] ) ) {

		$variablearray = explode( ':|:', sanitize_text_field( wp_unslash( $_REQUEST['variables'] ) ) );

		if ( count( $variablearray ) < 9 ) {
			echo 'erroroccured';
		}

		foreach ( $variablearray as $key => $value ) {
			$value = sanitize_text_field( $value );
		}
		$formname              = $variablearray[0];
		$formelementid         = $variablearray[1];
		$formid                = $variablearray[2];
		$formurl               = $variablearray[3];
		$applyrequiredmarkings = $variablearray[4];
		$validate_on_submit    = $variablearray[5];
		$active                = $variablearray[6];
		$recaptcha             = $variablearray[7];
		$responseid            = $variablearray[8];
		$postid                = url_to_postid( $formurl );

		if ( ! strstr( $formurl, get_site_url() ) ) {
			echo 'invalidurl';
			return;
		}

		$formid = simple_accessible_forms_form_existing_form_check( $formid );

		if ( 0 == $formid || '' === $formid ) {
			$formid = simple_accessible_forms_add_form( $formelementid, $formname, $formurl, $applyrequiredmarkings, $validate_on_submit, $active, $recaptcha, $responseid, $postid );
		} else {
			simple_accessible_forms_update_form( $formelementid, $formname, $formid, $formurl, $applyrequiredmarkings, $validate_on_submit, $active, $recaptcha, $responseid, $postid );
		}

		echo esc_attr( $formid );

	}
}


/**
 * Add || update form fieldS
 **/
function simple_accessible_forms_formfield_updates_rest() {
	check_ajax_referer( 'wp_rest', '_wpnonce' );

	if ( isset( $_REQUEST['variables'] ) ) {

		$variablearray = explode( ':|:', sanitize_text_field( wp_unslash( $_REQUEST['variables'] ) ) );

		if ( count( $variablearray ) < 12 ) {
			echo 'erroroccured';
		}

		foreach ( $variablearray as $key => $value ) {
			$value = sanitize_text_field( $value );
		}
		$fieldname          = $variablearray[0];
		$fieldlabel         = $variablearray[1];
		$fieldtype          = $variablearray[2];
		$requiredvalue      = $variablearray[3];
		$requiredifvalue    = $variablearray[4];
		$eitherorvalue      = $variablearray[5];
		$filetypesvalue     = $variablearray[6];
		$filemaxuploadsize  = $variablearray[7];
		$fieldformat        = $variablearray[8];
		$formid             = $variablearray[9];
		$fieldid            = $variablearray[10];
		$autocompleteformat = $variablearray[11];

		if ( '' === $fieldid || '0' == $fieldid ) {
			simple_accessible_forms_add_formfield( $formid, $fieldname, $fieldlabel, $fieldtype, $requiredvalue, $requiredifvalue, $eitherorvalue, $filetypesvalue, $filemaxuploadsize, $fieldformat, $autocompleteformat );
		} else {
			simple_accessible_forms_update_formfield( $fieldid, $fieldname, $fieldlabel, $fieldtype, $requiredvalue, $requiredifvalue, $eitherorvalue, $filetypesvalue, $filemaxuploadsize, $fieldformat, $autocompleteformat );
		}

		echo esc_attr( $formid );

	}
}


/**
 * Remove existing form record
 **/
function simple_accessible_forms_form_remove_form_rest() {
	check_ajax_referer( 'wp_rest', '_wpnonce' );

	global $wpdb;

	if ( isset( $_REQUEST['id'] ) && isset( $_REQUEST['type'] ) ) {
		$type = sanitize_text_field( wp_unslash( $_REQUEST['type'] ) );
		$id   = sanitize_text_field( wp_unslash( $_REQUEST['id'] ) );
	}
	if ( 'formid' === $type ) {
		$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . 'simple_accessible_forms_forms where id = %s', $id ) );

		$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . 'simple_accessible_forms_formfields where id = %s', $id ) );
	}

	if ( 'fieldid' === $type ) {
		$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . 'simple_accessible_forms_formfields where id  = %s', $id ) );
	}
}

/**
 * Check for existing form record
 **/
function simple_accessible_forms_form_existing_form_check( $formid ) {
	global $wpdb;

	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT id FROM ' . $wpdb->prefix . 'simple_accessible_forms_forms where id = %d', $formid ), ARRAY_A );

	foreach ( $results as $row ) {
		return $row['id'];
	}
	return 0;
}

/***
 * Add form record
 **/
function simple_accessible_forms_add_form( $formelementid, $formname, $formurl, $addrequiredmarking, $validate_on_submit, $active, $recaptcha, $responseid, $postid ) {
	global $wpdb, $simple_accessible_forms_formlimit;

	if ( ! isset( $simple_accessible_forms_formlimit ) || '' === $simple_accessible_forms_formlimit ) {
		$simple_accessible_forms_formlimit = 2;
	}

	$current = simple_accessible_forms_count_existing_forms();

	if ( $current < $simple_accessible_forms_formlimit ) {

		$wpdb->query( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix . 'simple_accessible_forms_forms (formid, formnicename, formurl, addrequiredmarking, validate_on_submit, active, recaptcha, responseid, postid) values(%s, %s, %s, %s, %d, %d, %d, %s, %d)', $formelementid, $formname, $formurl, $addrequiredmarking, $validate_on_submit, $active, $recaptcha, $responseid, $postid ) );

		return $wpdb->insert_id;
	}
	return 0;
}

/**
 * Update form  records
 **/
function simple_accessible_forms_update_form( $formelementid, $formname, $formid, $formurl, $addrequiredmarking, $validate_on_submit, $active, $recaptcha, $responseid, $postid ) {
	global $wpdb;

	$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'simple_accessible_forms_forms set formid = %s, formnicename = %s, formurl = %s, addrequiredmarking = %s, validate_on_submit = %d, active = %d, postid = %d, recaptcha = %d, responseid = %s where id = %d ', $formelementid, $formname, $formurl, $addrequiredmarking, $validate_on_submit, $active, $postid, $recaptcha, $responseid, $formid ) );
}

/**
 * Check for existing form field record
 **/
function simple_accessible_forms_form_existing_formfield_check( $formid, $fieldname, $fieldtype ) {
	global $wpdb;

	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT id FROM ' . $wpdb->prefix . 'simple_accessible_forms_formfields where formid = %s and fieldname = %s and fieldtype = %s', $formid, $fieldname, $fieldtype ), ARRAY_A );

	foreach ( $results as $row ) {
		return $row['id'];
	}
	return 0;
}

/**
 * Count existing forms
 **/
function simple_accessible_forms_count_existing_forms() {
	global $wpdb;

	$results = $wpdb->get_results( 'SELECT id FROM ' . $wpdb->prefix . 'simple_accessible_forms_forms', ARRAY_A );

	return count( $results );
}

/**
 * Count existing form fields
 **/
function simple_accessible_forms_count_existing_formfields( $formid ) {
	global $wpdb;

	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT id FROM ' . $wpdb->prefix . 'simple_accessible_forms_formfields where formid = %d', $formid ), ARRAY_A );

	return count( $results );
}

/**
 * Add form field records
 **/
function simple_accessible_forms_add_formfield( $formid, $fieldname, $fieldlabel, $fieldtype, $requiredvalue, $requiredifvalue, $eitherorvalue, $filetypesvalue, $filemaxuploadsize, $fieldformat, $autocompleteformat ) {
	global $wpdb, $simple_accessible_forms_formfieldlimit;

	if ( ! isset( $simple_accessible_forms_formfieldlimit ) || '' === $simple_accessible_forms_formfieldlimit ) {
		$simple_accessible_forms_formfieldlimit = 4;
	}

	$current = simple_accessible_forms_count_existing_formfields( $formid );
	if ( $current < $simple_accessible_forms_formfieldlimit ) {
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix . 'simple_accessible_forms_formfields (fieldname,fieldlabel,fieldtype,requiredfield,requiredif,eitheror,filetypes,maxuploadsize,fieldformat, formid, autocompleteformat) values(%s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %s)', $fieldname, $fieldlabel, $fieldtype, $requiredvalue, $requiredifvalue, $eitherorvalue, $filetypesvalue, $filemaxuploadsize, $fieldformat, $formid, $autocompleteformat ) );

	} else {
		echo 0;
	}
}

/**
 * Update form field records
 **/
function simple_accessible_forms_update_formfield( $fieldid, $fieldname, $fieldlabel, $fieldtype, $requiredvalue, $requiredifvalue, $eitherorvalue, $filetypesvalue, $filemaxuploadsize, $fieldformat, $autocompleteformat ) {
	global $wpdb;

	$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'simple_accessible_forms_formfields set fieldname = %s, fieldlabel = %s,fieldtype = %s, requiredfield = %s, requiredif= %s,eitheror= %s,filetypes= %s,maxuploadsize= %s,fieldformat= %s, autocompleteformat = %s where id = %d ', $fieldname, $fieldlabel, $fieldtype, $requiredvalue, $requiredifvalue, $eitherorvalue, $filetypesvalue, $filemaxuploadsize, $fieldformat, $autocompleteformat, $fieldid ) );
}

/**
 * Set max upload size
 **/
function simple_accessible_forms_update_maxupload_size_formfield( $formid, $maxuploadsize ) {
	global $wpdb;

	$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'simple_accessible_forms_formfields set maxuploadsize = %s where formid = %d ', $maxuploadsize, $formid ) );
}

/**
 * Create forms list
 **/
function simple_accessible_forms_form_list() {
	global $wpdb;

	$results = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'simple_accessible_forms_forms ', ARRAY_A );
	if ( count( $results ) > 0 ) {
		echo '<ul class="simple_accessible_forms_formlist">';
		foreach ( $results as $row ) {
			echo '<li><a href="' . esc_url( $row['formurl'] ) . '" title="';
			esc_html_e( 'Edit Form', 'simple-accessible-forms' );
			echo '" role="button" class="simple_accessible_forms_loadform simple_accessible_forms_list_links" data-fieldid="0" id="simple_accessible_forms_loadform' . esc_attr( $row['id'] ) . '" data-formid="' . esc_attr( $row['id'] ) . '">' . esc_attr( $row['formnicename'] ) . ' <span class="dashicons dashicons-edit-page"></span></a>
    
    <a href="' . esc_url( $row['formurl'] ) . '" class="simple_accessible_forms_list_links" target="_blank" title="';
			esc_html_e( 'View Form - opens in a new window', 'simple-accessible-forms' );
			echo '"><span class="dashicons dashicons-admin-site"></span></a>
    
    <a href="#" role="button" data-fieldid="0" data-formid="' . esc_attr( $row['id'] ) . '" class="simple_accessible_forms_remove_btn" title="';
			esc_html_e( 'Remove Form', 'simple-accessible-forms' );
			echo '"><span class="dashicons dashicons-remove"></span></a>
    </li>';
		}
		echo '</ul>';
	}
}



/**
 * Create field list
 **/
function simple_accessible_forms_form_field_list( $formid ) {
	global $wpdb;
	$fields = '';

	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'simple_accessible_forms_formfields where formid = %d ', $formid ), ARRAY_A );

	foreach ( $results as $row ) {
		$fields .= '<li role="button" class="simple_accessible_show_fields" tabindex="0" data-fieldid="' . esc_attr( $row['id'] ) . '" aria-expanded="false">' . esc_attr( $row['fieldname'] ) . ' <a href="#" role="button" data-fieldid="' . esc_attr( $row['id'] ) . '" data-formid="' . esc_attr( $row['formid'] ) . '" class="simple_accessible_forms_remove_btn" title="' . __( 'remove', 'simple-accessible-forms' ) . '"><span class="dashicons dashicons-remove"></span></a></a>
</li>';
		$fields .= '<div class="simple_accessible_forms_field_details" id="simple_accessible_forms_field_details' . esc_attr( $row['id'] ) . '">';
		$fields .= simple_accessible_forms_load_form( (int) $row['formid'], (int) $row['id'] );
		$fields .= '</div>';

	}
	if ( '' !== $fields ) {
		$fields = '<ul class="simple_accessible_fields_formlist">' . $fields . '</ul>';
	} else {
		$fields = '<p>Create form fields manually by clicking on the "Add a form field" button or import the form fields automatically using the "Import form fields" button.</p>';
	}

	$fields .= '<button class="simple_accessible_forms_new_field_btn">';
	$fields .= __( 'Add a form field', 'simple-accessible-forms' );
	$fields .= '</button> ';
	$fields .= '<button class="simple_accessible_forms_import" data-formid="' . esc_attr( $formid ) . '">';
	$fields .= __( 'Import form fields', 'simple-accessible-forms' );
	$fields .= '</button> ';
	$fields .= '<div id="simple_accessible_forms_add_field">';
	$fields .= simple_accessible_forms_load_form( $formid, 0 );
	$fields .= '</div>';

	return $fields;
}

/**
 * Create scripts
 */
function simple_accessible_forms_create_scripts() {
	global $wpdb;

	if ( ! isset( $_SERVER['REQUEST_URI'] ) ) {
		return;
	} else {
		$serveruri = esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );

	}

	$results = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'simple_accessible_forms_forms where active = 1', ARRAY_A );
	if ( count( $results ) > 0 ) {
		echo '
<script>
   let jquery_form_validation_form_forms_array = [';

		foreach ( $results as $row ) {
			if ( '' !== $row['formid'] && '' !== $row['formurl'] ) {
				$path = parse_url( $row['formurl'], PHP_URL_PATH );

				if ( strstr( $serveruri, $path ) ) {
					echo " ['" . esc_attr( $row['formid'] ) . "', '" . esc_attr( $row['formnicename'] ) . "',";

					$results2 = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'simple_accessible_forms_formfields where formid = %d ', $row['id'] ), ARRAY_A );

					foreach ( $results2 as $row2 ) {
						if ( 'phone' === $row2['fieldformat'] ) {
							$phoneclass = 'phonevalidator';
						} else {
							$phoneclass = '';
						}
						if ( '' !== $row2['fieldname'] && '' !== $row2['fieldlabel'] && ( '' !== $row2['fieldformat'] || 'file' === $row2['fieldtype'] ) ) {
							echo "['" . esc_attr( $row2['fieldname'] ) . "',             
   '" . esc_attr( $row2['fieldlabel'] ) . "',              
   'formfield " . esc_attr( $phoneclass ) . ' ' . esc_attr( $row2['requiredfield'] ) . "',     
   '" . esc_attr( $row2['fieldformat'] ) . "',
   '" . esc_attr( $row2['requiredif'] ) . "',                      
   '" . esc_attr( $row2['eitheror'] ) . "',                     
   '" . esc_attr( $row2['filetypes'] ) . "',                      
   '" . esc_attr( $row2['maxuploadsize'] ) . "',   
   '" . esc_attr( $row2['autocompleteformat'] ) . "',                      
   ],";
						}
					}
					echo '],';
				}
			}
		}
		echo '];';

		echo 'jQuery( document ).ready(function() {';

		$results = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'simple_accessible_forms_forms where active = 1 ', ARRAY_A );
		if ( count( $results ) > 0 ) {
			foreach ( $results as $row ) {
				echo '
if(jQuery("form[id=' . esc_attr( $row['formid'] ) . ']").length > -1){
    jQuery("form[id=' . esc_attr( $row['formid'] ) . ']").addClass("jquery_form_validation_form");';

				echo ' jQuery("form[id=' . esc_attr( $row['formid'] ) . ']").attr("data-hiderecaptcha","' . esc_attr( $row['recaptcha'] ) . '");';

				echo ' jQuery("form[id=' . esc_attr( $row['formid'] ) . ']").attr("data-validateonsubmit","' . esc_attr( $row['validate_on_submit'] ) . '");';

				echo ' jQuery("form[id=' . esc_attr( $row['formid'] ) . ']").attr("data-addrequiredmarking","' . esc_attr( $row['addrequiredmarking'] ) . '"); ';
				echo '}';

				if ( '' !== $row['responseid'] ) {
					echo '
jQuery("#' . esc_attr( $row['responseid'] ) . '").on("DOMSubtreeModified", function() {
    if(jQuery("form[id=' . esc_attr( $row['formid'] ) . ']").length > 0 && jQuery("#' . esc_attr( $row['responseid'] ) . '").length > 0){
    ';
					echo ' jQuery("#' . esc_attr( $row['responseid'] ) . '").attr("role","alert");';
					echo ' jQuery("#' . esc_attr( $row['responseid'] ) . '").attr("aria-hidden","false");';
					echo '}';
					echo '});';

				}
			}
		}

		echo '});';

		echo '</script>';

	}
}
add_action( 'wp_head', 'simple_accessible_forms_create_scripts' );


/**
 * Installation functions
 */
register_activation_hook( __FILE__, 'simple_accessible_forms_install' );
register_uninstall_hook( __FILE__, 'simple_accessible_forms_uninstall' );
register_deactivation_hook( __FILE__, 'simple_accessible_forms_deactivate' );


/**
 * Deactivate plugin
 */
function simple_accessible_forms_deactivate() {
}

/**
 * Activate plugin
 */
function simple_accessible_forms_install( $network_wide = false ) {

	global $wpdb;

	if ( is_multisite() ) {

		$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
		foreach ( $blog_ids as $blog_id ) {
			switch_to_blog( $blog_id );

			simple_accessible_forms_create_tables();
			restore_current_blog();
		}
	} else {
		simple_accessible_forms_create_tables();
	}
}

/**
 * Create tables
 */
function simple_accessible_forms_create_tables() {
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();
	$table_name1     = $wpdb->prefix . 'simple_accessible_forms_forms';
	$table_name2     = $wpdb->prefix . 'simple_accessible_forms_formfields';

	$sql = "CREATE TABLE $table_name1 (
		id int(11) NOT NULL AUTO_INCREMENT, 
		formid text NOT NULL,
		formnicename text NOT NULL,
        formurl text NOT NULL,
        addrequiredmarking text NOT NULL,
        validate_on_submit text NOT NULL,
        active text NOT NULL,
        recaptcha text NOT NULL,
        responseid text NOT NULL,
        postid int(11) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	$sql2 = " CREATE TABLE $table_name2 (
		id int(11) NOT NULL AUTO_INCREMENT, 
        fieldname text NOT NULL,
        fieldtype text NOT NULL,
		fieldlabel text NOT NULL,
		requiredfield text NOT NULL,
        requiredif text NOT NULL,
        eitheror text NOT NULL,
        filetypes text NOT NULL,
        maxuploadsize text NOT NULL,
        formid int(11) NOT NULL, 
        fieldformat text NOT NULL,
        autocompleteformat text NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	dbDelta( $sql );
	dbDelta( $sql2 );
}

/**
 * UNINSTALL FUNCTIONS
 ***/
function simple_accessible_forms_uninstall( $deactivate = 0 ) {
	global $wpdb;

	if ( is_multisite() ) {

		$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
		foreach ( $blog_ids as $blog_id ) {
			switch_to_blog( $blog_id );
			simple_accessible_forms_delete_tables();
			simple_accessible_forms_remove_options();

			restore_current_blog();
		}
	} else {

		simple_accessible_forms_delete_tables();
		simple_accessible_forms_remove_options();
	}
}
/**
 * Remove tables
 */
function simple_accessible_forms_delete_tables() {
	global $wpdb;

	$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'simple_accessible_forms_formfields' );

	$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'simple_accessible_forms_forms' );
}

/**
 * Remove options
 */
function simple_accessible_forms_remove_options() {
	foreach ( wp_load_alloptions() as $option => $value ) {
		if ( 0 === strpos( $option, 'simple_accessible_forms_' ) ) {
			delete_option( $option );
		}
	}
}

/**
 * Deleting the table whenever a blog is deleted
 */
function simple_accessible_forms_delete_blog( $tables ) {
	global $wpdb;
	$tables[] = $wpdb->prefix . 'simple_accessible_forms_formfields';
	$tables[] = $wpdb->prefix . 'simple_accessible_forms_forms';

	return $tables;
}
add_filter( 'wpmu_drop_tables', 'simple_accessible_forms_delete_blog' );

/**
 * Check version number for database updates
 **/
function simple_accessible_forms_check_version() {
	$current_version       = simple_accessible_forms_plugin_get( 'Version' );
	$stored_option_version = get_option( 'simple_accessible_forms_version' );
	if ( $current_version != $stored_option_version ) {
		simple_accessible_forms_install();

		update_option( 'simple_accessible_forms_version', $current_version );
	}
}
/**
 * Returns current plugin info.
 **/
function simple_accessible_forms_plugin_get( $i ) {

	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
		$plugin_folder = get_plugins( '/' . plugin_basename( __DIR__ ) );
		$plugin_file   = basename( ( __FILE__ ) );
		return $plugin_folder[ $plugin_file ][ $i ];
}
add_action( 'admin_init', 'simple_accessible_forms_check_version' );

/**
 * Wp ada compliance validate form
 **/
function simple_accessible_forms_wp_ada_compliance_validate_form( $formid, $postid, $fieldname = '' ) {
	global $wpdb;

	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'simple_accessible_forms_forms where formid = %d and postid = %d and active = 1', $formid, $postid ), ARRAY_A );
	foreach ( $results as $row ) {
		if ( '' !== $fieldname ) {
			if ( simple_accessible_forms_wp_ada_compliance_validate_form_labels( $fieldname, $row['id'] ) ) {
				return 1;
			} else {
				return 0;
			}
		} else {
			return 1;
		}
	}
}
/**
 * Wp ada compliance validate form labels
 ***/
function simple_accessible_forms_wp_ada_compliance_validate_form_labels( $fieldname, $formid ) {
	global $wpdb;

	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT fieldlabel FROM ' . $wpdb->prefix . 'simple_accessible_forms_formfields where formid = %d and fieldname = %s and fieldtype != %s ', $formid, $fieldname, 'group' ), ARRAY_A );
	foreach ( $results as $row ) {

		if ( '' !== $row['fieldlabel'] ) {
			return 1;
		}
	}

	return 0;
}

/**
 * Validate true false values DEFAULT to true
 **/
function simple_accessible_forms_validate_false_default_true( $value ) {
	if ( in_array( $value, array( 'true', 'false' ) ) ) {
		return sanitize_text_field( $value );
	} else {
		return 'true';
	}
}
/**
 * Validate true false values DEFAULT to false
 **/
function simple_accessible_forms_validate_false_default_false( $value ) {
	if ( in_array( $value, array( 'true', 'false' ) ) ) {
		return sanitize_text_field( $value );
	} else {
		return 'true';
	}
}
?>
