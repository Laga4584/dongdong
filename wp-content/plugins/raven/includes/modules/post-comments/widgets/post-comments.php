<?php
namespace Raven\Modules\Post_Comments\Widgets;

use Raven\Base\Base_Widget;
use Raven\Modules\Post_Comments\Skins;
use Elementor\Plugin;

defined( 'ABSPATH' ) || die();

class Post_Comments extends Base_Widget {

	protected $_has_template_content = false;

	public function get_name() {
		return 'raven-post-comments';
	}

	public function get_title() {
		return __( 'Post Comments', 'raven' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-post-comments';
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'raven' ),
				'description' => __( 'No settings available.', 'raven' ),
			]
		);

		$this->add_control(
			'help',
			[
				'type' => 'raw_html',
				'raw' => __( 'No settings available', 'elementor' ),
			]
		);

		$this->end_controls_section();
	}

	protected function render_warning() {
		?>
		<div class="elementor-alert elementor-alert-danger" role="alert">
			<span class="elementor-alert-title"> <?php esc_html_e( 'Comments are closed. Switch on comments from', 'raven' ); ?></span><br />
			<div class="elementor-alert-description">
				<ul>
					<li> <?php esc_html_e( 'WordPress Customizer or', 'raven' ); ?> </li>
					<li> <?php esc_html_e( 'Discussion box on the WordPress post edit screen or', 'raven' ); ?> </li>
					<li> <?php esc_html_e( 'WordPress discussion settings or', 'raven' ); ?> </li>
					<li> <?php esc_html_e( 'Page/post meta fields.', 'raven' ); ?> </li>
				</ul>
			</div>
		</div>
		<?php
	}

	protected function render() {
		$is_preview_or_edit = Plugin::instance()->preview->is_preview_mode() || Plugin::instance()->editor->is_edit_mode();

		if ( function_exists( 'jupiterx_post_element_enabled' ) && ! jupiterx_post_element_enabled( 'comments' ) && $is_preview_or_edit ) {
			$this->render_warning();

			return;
		}

		if ( ! comments_open() && $is_preview_or_edit ) {
			$this->render_warning();

			return;
		}

		if ( function_exists( 'jupiterx_comments_template' ) ) {
			jupiterx_comments_template();

			return;
		}

		comments_template();
	}
}
