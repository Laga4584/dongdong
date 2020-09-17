<?php
/**
 * The Kirki autoloader.
 * Handles locating and loading other class-files.
 *
 * @package     Kirki
 * @category    Core
 * @author      Aristeides Stathopoulos
 * @copyright   Copyright (c) 2017, Aristeides Stathopoulos
 * @license     http://opensource.org/licenses/https://opensource.org/licenses/MIT
 * @since       1.0
 */

/**
 * Autoloader class.
 *
 * @since 3.0.10
 */
class Kirki_Autoload {

	/**
	 * Cached paths.
	 *
	 * @access private
	 * @since 3.0.10
	 * @var array
	 */
	private $cached_paths = array();

	/**
	 * Class constructor.
	 *
	 * @access public
	 * @since 3.0.10
	 */
	public function __construct() {
		$this->cached_paths = [
			'Kirki_Controls' => 'controls/class-kirki-controls.php',
			'Kirki_Control_Background' => 'controls/php/class-kirki-control-background.php',
			'Kirki_Control_Base' => 'controls/php/class-kirki-control-base.php',
			'Kirki_Control_Checkbox' => 'controls/php/class-kirki-control-checkbox.php',
			'Kirki_Control_Code' => 'controls/php/class-kirki-control-code.php',
			'Kirki_Control_Color_Palette' => 'controls/php/class-kirki-control-color-palette.php',
			'Kirki_Control_Color' => 'controls/php/class-kirki-control-color.php',
			'Kirki_Control_Cropped_Image' => 'controls/php/class-kirki-control-cropped-image.php',
			'Kirki_Control_Custom' => 'controls/php/class-kirki-control-custom.php',
			'Kirki_Control_Dashicons' => 'controls/php/class-kirki-control-dashicons.php',
			'Kirki_Control_Date' => 'controls/php/class-kirki-control-date.php',
			'Kirki_Control_Dimension' => 'controls/php/class-kirki-control-dimension.php',
			'Kirki_Control_Dimensions' => 'controls/php/class-kirki-control-dimensions.php',
			'Kirki_Control_Editor' => 'controls/php/class-kirki-control-editor.php',
			'Kirki_Control_Generic' => 'controls/php/class-kirki-control-generic.php',
			'Kirki_Control_Image' => 'controls/php/class-kirki-control-image.php',
			'Kirki_Control_MultiCheck' => 'controls/php/class-kirki-control-multicheck.php',
			'Kirki_Control_Multicolor' => 'controls/php/class-kirki-control-multicolor.php',
			'Kirki_Control_Number' => 'controls/php/class-kirki-control-number.php',
			'Kirki_Control_Palette' => 'controls/php/class-kirki-control-palette.php',
			'Kirki_Control_Radio_Buttonset' => 'controls/php/class-kirki-control-radio-buttonset.php',
			'Kirki_Control_Radio_Image' => 'controls/php/class-kirki-control-radio-image.php',
			'Kirki_Control_Radio' => 'controls/php/class-kirki-control-radio.php',
			'Kirki_Control_Repeater' => 'controls/php/class-kirki-control-repeater.php',
			'Kirki_Control_Select' => 'controls/php/class-kirki-control-select.php',
			'Kirki_Control_Slider' => 'controls/php/class-kirki-control-slider.php',
			'Kirki_Control_Sortable' => 'controls/php/class-kirki-control-sortable.php',
			'Kirki_Control_Switch' => 'controls/php/class-kirki-control-switch.php',
			'Kirki_Control_Toggle' => 'controls/php/class-kirki-control-toggle.php',
			'Kirki_Control_Typography' => 'controls/php/class-kirki-control-typography.php',
			'Kirki_Control_Upload' => 'controls/php/class-kirki-control-upload.php',
			'Kirki_Settings_Repeater_Setting' => 'controls/php/class-kirki-settings-repeater-setting.php',
			'Kirki_Config' => 'core/class-kirki-config.php',
			'Kirki_Control' => 'core/class-kirki-control.php',
			'Kirki_Field' => 'core/class-kirki-field.php',
			'Kirki_Helper' => 'core/class-kirki-helper.php',
			'Kirki_Init' => 'core/class-kirki-init.php',
			'Kirki_L10n' => 'core/class-kirki-l10n.php',
			'Kirki_Modules' => 'core/class-kirki-modules.php',
			'Kirki_Panel' => 'core/class-kirki-panel.php',
			'Kirki_Sanitize_Values' => 'core/class-kirki-sanitize-values.php',
			'Kirki_Section' => 'core/class-kirki-section.php',
			'Kirki_Sections' => 'core/class-kirki-sections.php',
			'Kirki_Setting_Site_Option' => 'core/class-kirki-setting-site-option.php',
			'Kirki_Setting_User_Meta' => 'core/class-kirki-setting-user-meta.php',
			'Kirki_Settings' => 'core/class-kirki-settings.php',
			'Kirki_Toolkit' => 'core/class-kirki-toolkit.php',
			'Kirki_Util' => 'core/class-kirki-util.php',
			'Kirki_Values' => 'core/class-kirki-values.php',
			'Kirki' => 'core/class-kirki.php',
			'Kirki_Active_Callback' => 'deprecated/classes.php',
			'Kirki_Installer_Section' => 'docs/files/class-kirki-installer-section.php',
			'Kirki_Modules_Webfonts_Embed' => 'docs/files/class-kirki-modules-webfonts-embed.php',
			'Kirki_Modules_Webfonts_Link' => 'docs/files/class-kirki-modules-webfonts-link.php',
			'Kirki_Field_Background' => 'field/class-kirki-field-background.php',
			'Kirki_Field_Checkbox' => 'field/class-kirki-field-checkbox.php',
			'Kirki_Field_Code' => 'field/class-kirki-field-code.php',
			'Kirki_Field_Color_Alpha' => 'field/class-kirki-field-color-alpha.php',
			'Kirki_Field_Color_Palette' => 'field/class-kirki-field-color-palette.php',
			'Kirki_Field_Color' => 'field/class-kirki-field-color.php',
			'Kirki_Field_Custom' => 'field/class-kirki-field-custom.php',
			'Kirki_Field_Dashicons' => 'field/class-kirki-field-dashicons.php',
			'Kirki_Field_Date' => 'field/class-kirki-field-date.php',
			'Kirki_Field_Dimension' => 'field/class-kirki-field-dimension.php',
			'Kirki_Field_Dimensions' => 'field/class-kirki-field-dimensions.php',
			'Kirki_Field_Editor' => 'field/class-kirki-field-editor.php',
			'Kirki_Field_FontAwesome' => 'field/class-kirki-field-fontawesome.php',
			'Kirki_Field_Generic' => 'field/class-kirki-field-generic.php',
			'Kirki_Field_Group_Title' => 'field/class-kirki-field-group-title.php',
			'Kirki_Field_Image' => 'field/class-kirki-field-image.php',
			'Kirki_Field_Kirki_Generic' => 'field/class-kirki-field-kirki-generic.php',
			'Kirki_Field_Link' => 'field/class-kirki-field-link.php',
			'Kirki_Field_Multicheck' => 'field/class-kirki-field-multicheck.php',
			'Kirki_Field_Multicolor' => 'field/class-kirki-field-multicolor.php',
			'Kirki_Field_Number' => 'field/class-kirki-field-number.php',
			'Kirki_Field_Palette' => 'field/class-kirki-field-palette.php',
			'Kirki_Field_Preset' => 'field/class-kirki-field-preset.php',
			'Kirki_Field_Radio_Buttonset' => 'field/class-kirki-field-radio-buttonset.php',
			'Kirki_Field_Radio_Image' => 'field/class-kirki-field-radio-image.php',
			'Kirki_Field_Radio' => 'field/class-kirki-field-radio.php',
			'Kirki_Field_Repeater' => 'field/class-kirki-field-repeater.php',
			'Kirki_Field_Select' => 'field/class-kirki-field-select.php',
			'Kirki_Field_Select2_Multiple' => 'field/class-kirki-field-select2-multiple.php',
			'Kirki_Field_Select2' => 'field/class-kirki-field-select2.php',
			'Kirki_Field_Slider' => 'field/class-kirki-field-slider.php',
			'Kirki_Field_Sortable' => 'field/class-kirki-field-sortable.php',
			'Kirki_Field_Spacing' => 'field/class-kirki-field-spacing.php',
			'Kirki_Field_Switch' => 'field/class-kirki-field-switch.php',
			'Kirki_Field_Text' => 'field/class-kirki-field-text.php',
			'Kirki_Field_Textarea' => 'field/class-kirki-field-textarea.php',
			'Kirki_Field_Toggle' => 'field/class-kirki-field-toggle.php',
			'Kirki_Field_Typography' => 'field/class-kirki-field-typography.php',
			'Kirki_Field_Upload' => 'field/class-kirki-field-upload.php',
			'Kirki_Field_URL' => 'field/class-kirki-field-url.php',
			'Kirki_Color' => 'lib/class-kirki-color.php',
			'Kirki_Modules_CSS_Vars' => 'modules/css-vars/class-kirki-modules-css-vars.php',
			'Kirki_CSS_To_File' => 'modules/css/class-kirki-css-to-file.php',
			'Kirki_Modules_CSS_Generator' => 'modules/css/class-kirki-modules-css-generator.php',
			'Kirki_Modules_CSS' => 'modules/css/class-kirki-modules-css.php',
			'Kirki_Output' => 'modules/css/class-kirki-output.php',
			'Kirki_Output_Field_Background' => 'modules/css/field/class-kirki-output-field-background.php',
			'Kirki_Output_Field_Dimensions' => 'modules/css/field/class-kirki-output-field-dimensions.php',
			'Kirki_Output_Field_Image' => 'modules/css/field/class-kirki-output-field-image.php',
			'Kirki_Output_Field_Multicolor' => 'modules/css/field/class-kirki-output-field-multicolor.php',
			'Kirki_Output_Field_Typography' => 'modules/css/field/class-kirki-output-field-typography.php',
			'Kirki_Output_Property_Background_Image' => 'modules/css/property/class-kirki-output-property-background-image.php',
			'Kirki_Output_Property_Background_Position' => 'modules/css/property/class-kirki-output-property-background-position.php',
			'Kirki_Output_Property_Font_Family' => 'modules/css/property/class-kirki-output-property-font-family.php',
			'Kirki_Output_Property' => 'modules/css/property/class-kirki-output-property.php',
			'Kirki_Modules_Custom_Sections' => 'modules/custom-sections/class-kirki-modules-custom-sections.php',
			'Kirki_Panels_Nested_Panel' => 'modules/custom-sections/panels/class-kirki-panels-nested-panel.php',
			'Kirki_Sections_Default_Section' => 'modules/custom-sections/sections/class-kirki-sections-default-section.php',
			'Kirki_Sections_Expanded_Section' => 'modules/custom-sections/sections/class-kirki-sections-expanded-section.php',
			'Kirki_Sections_Nested_Section' => 'modules/custom-sections/sections/class-kirki-sections-nested-section.php',
			'Kirki_Modules_Customizer_Branding' => 'modules/customizer-branding/class-kirki-modules-customizer-branding.php',
			'Kirki_Modules_Customizer_Styling' => 'modules/customizer-styling/class-kirki-modules-customizer-styling.php',
			'Kirki_Modules_Field_Dependencies' => 'modules/field-dependencies/class-kirki-modules-field-dependencies.php',
			'Kirki_Modules_Gutenberg' => 'modules/gutenberg/class-kirki-modules-gutenberg.php',
			'Kirki_Modules_Icons' => 'modules/icons/class-kirki-modules-icons.php',
			'Kirki_Modules_Loading' => 'modules/loading/class-kirki-modules-loading.php',
			'Kirki_Modules_Post_Meta' => 'modules/post-meta/class-kirki-modules-post-meta.php',
			'Kirki_Modules_PostMessage' => 'modules/postmessage/class-kirki-modules-postmessage.php',
			'Kirki_Modules_Preset' => 'modules/preset/class-kirki-modules-preset.php',
			'Kirki_Modules_Selective_Refresh' => 'modules/selective-refresh/class-kirki-modules-selective-refresh.php',
			'Kirki_Modules_Tooltips' => 'modules/tooltips/class-kirki-modules-tooltips.php',
			'Kirki_Modules_Webfont_Loader' => 'modules/webfont-loader/class-kirki-modules-webfont-loader.php',
			'Kirki_Fonts_Google_Local' => 'modules/webfonts/class-kirki-fonts-google-local.php',
			'Kirki_Fonts_Google' => 'modules/webfonts/class-kirki-fonts-google.php',
			'Kirki_Fonts' => 'modules/webfonts/class-kirki-fonts.php',
			'Kirki_Modules_Webfonts_Async' => 'modules/webfonts/class-kirki-modules-webfonts-async.php',
			'Kirki_Modules_Webfonts_Local' => 'modules/webfonts/class-kirki-modules-webfonts-local.php',
			'Kirki_Modules_Webfonts' => 'modules/webfonts/class-kirki-modules-webfonts.php',
 		];

		spl_autoload_register( array( $this, 'autoload' ) );
	}

	/**
	 * The Kirki class autoloader.
	 * Finds the path to a class that we're requiring and includes the file.
	 *
	 * @access protected
	 * @since 3.0.10
	 * @param string $class_name The name of the class we're trying to load.
	 */
	protected function autoload( $class_name ) {
		// Not a Kirki file, early exit.
		if ( 0 !== stripos( $class_name, 'Kirki' ) ) {
			return;
		}

		if ( ! isset( $this->cached_paths[ $class_name ] ) ) {
			return;
		}

		$file_path = dirname( __FILE__ ) . '/' . $this->cached_paths[ $class_name ];

		if ( ! file_exists( $file_path ) ) {
			return;
		}

		include_once $file_path;
	}
}
