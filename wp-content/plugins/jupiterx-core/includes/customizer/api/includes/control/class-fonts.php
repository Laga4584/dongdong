<?php
/**
 * Handles fonts control class.
 *
 * @package JupiterX\Framework\API\Customizer
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fonts control class.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 */
class JupiterX_Customizer_Control_Fonts extends JupiterX_Customizer_Base_Control {

	/**
	 * Control's type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $type = 'jupiterx-fonts';

	/**
	 * API keys source.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $api_source = [];

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 1.0.0
	 */
	public function to_json() {
		parent::to_json();

		$this->json['responsive']   = false;
		$this->json['fontTypes']    = JupiterX_Fonts::get_font_types();
		$this->json['fontFamilies'] = JupiterX_Fonts::get_fonts();
		$this->json['apiSource']    = $this->api_source;
	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 *
	 * @since 1.0.0
	 */
	protected function content_template() {
		?>
		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{ data.description }}</span>
		<# } #>
		<div class="jupiterx-control jupiterx-fonts-control">
			<div class="jupiterx-fonts-control-register" tabindex="-1">
				<span class="jupiterx-fonts-control-register-icon"><img src="<?php echo esc_url( JupiterX_Customizer_Utils::get_assets_url() ); ?>/img/plus.svg" /></span>
				<span class="jupiterx-fonts-control-register-text"><?php esc_html_e( 'Add Font Family', 'jupiterx-core' ); ?></span>
			</div>
		</div>
		<?php
	}
}
