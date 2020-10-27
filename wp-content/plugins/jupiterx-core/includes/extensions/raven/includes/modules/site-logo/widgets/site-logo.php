<?php
namespace JupiterX_Core\Raven\Modules\Site_Logo\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;

defined( 'ABSPATH' ) || die();

class Site_Logo extends Base_Widget {

	public function get_name() {
		return 'raven-site-logo';
	}

	public function get_title() {
		return __( 'Site Logo', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-site-logo';
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Logo', 'jupiterx-core' ),
			]
		);

		$this->add_responsive_control(
			'logo',
			[
				'label' => __( 'Choose Logo', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'primary'   => __( 'Primary', 'jupiterx-core' ),
					'secondary' => __( 'Secondary', 'jupiterx-core' ),
					'sticky'    => __( 'Sticky', 'jupiterx-core' ),
					'mobile'    => __( 'Mobile', 'jupiterx-core' ),
				],
				'default' => 'primary',
				'tablet_default' => 'primary',
				'mobile_default' => 'primary',
				'description' => sprintf(
					/* translators: %1$s: Choose logo name | %2$s: Link to Customizer page */
					__( 'Please select or upload your <strong>Logo</strong> in the <a target="_blank" href="%1$s"><em>Customizer</em></a>.', 'jupiterx-core' ),
					add_query_arg( [ 'autofocus[section]' => 'jupiterx_logo' ], admin_url( 'customize.php' ) )
				),
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'jupiterx-core' ),
				'type' => 'url',
				'placeholder' => __( 'Enter your web address', 'jupiterx-core' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_logo',
			[
				'label' => __( 'Logo', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label' => __( 'Width', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-site-logo img, {{WRAPPER}} .raven-site-logo svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'max_width',
			[
				'label' => __( 'Max Width', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-site-logo img, {{WRAPPER}} .raven-site-logo svg' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'  => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => is_rtl() ? 'right' : 'left',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'jupiterx-core' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'jupiterx-core' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'jupiterx-core' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-site-logo' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Temporary suppressed.
	 *
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$devices = [
			'desktop' => '',
			'tablet'  => '_tablet',
			'mobile'  => '_mobile',
		];

		$logos = [];

		foreach ( $devices as $device => $device_setting_key ) {
			$device_setting = $settings[ 'logo' . $device_setting_key ];

			$logo = 'primary' !== $device_setting ? "jupiterx_logo_{$device_setting}" : 'jupiterx_logo';

			if ( in_array( $logo, $logos, true ) ) {
				$this->add_render_attribute( $logos[ $logo ], 'class', 'raven-site-logo-' . $device );
				continue;
			}

			$logos[ $logo ] = $logo;

			$image_src = get_theme_mod( $logo, '' );

			if ( empty( $image_src ) ) {
				$image_src = \Elementor\Utils::get_placeholder_image_src();
			}

			$this->add_render_attribute( $logo, [
				'src'   => esc_url( $image_src ),
				'alt'   => get_bloginfo( 'title' ),
				'class' => 'raven-site-logo-' . $device,
				'data-no-lazy' => 1,
			] );

			$retina_logo = 'primary' !== $device_setting ? "jupiterx_logo_{$device_setting}_retina" : 'jupiterx_logo_retina';

			$retina_image_src = get_theme_mod( $retina_logo, '' );

			if ( ! empty( $retina_image_src ) ) {
				$this->add_render_attribute( $logo, 'srcset', "{$image_src} 1x, {$retina_image_src} 2x" );
			}
		}

		$link = $settings['link'];

		if ( ! isset( $link['url'] ) || empty( $link['url'] ) ) {
			$link['url'] = get_bloginfo( 'url' );
		}

		if ( ! empty( $link['url'] ) ) {
			$this->add_render_attribute( 'link', 'class', 'raven-site-logo-link' );

			$this->add_render_attribute( 'link', 'href', esc_url( $link['url'] ) );

			if ( ! empty( $link['is_external'] ) ) {
				$this->add_render_attribute( 'link', 'target', '_blank' );
			}

			if ( ! empty( $link['nofollow'] ) ) {
				$this->add_render_attribute( 'link', 'rel', 'nofollow' );
			}
		}

		?>
		<div class="raven-widget-wrapper">
			<div class="raven-site-logo">
				<?php if ( ! empty( $link['url'] ) ) : ?>
					<a <?php echo $this->get_render_attribute_string( 'link' ); ?>>
				<?php endif; ?>
				<?php foreach ( $logos as $device_logo ) : ?>
					<img <?php echo $this->get_render_attribute_string( $device_logo ); ?> />
				<?php endforeach; ?>
				<?php if ( ! empty( $link['url'] ) ) : ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
