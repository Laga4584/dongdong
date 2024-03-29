<?php
/**
 * Main dashboard template
 */
?><div id="jet-woo-builder-settings-page">
	<div class="jet-woo-builder-settings-page">
		<h1 class="cs-vui-title"><?php _e( 'JetWooBuilder Settings', 'jet-woo-builder' ); ?></h1>
		<div class="cx-vui-panel">
			<cx-vui-tabs
				:in-panel="false"
				value="available-widgets"
				layout="vertical">

				<?php do_action( 'jet-woo-builder/settings-page-template/tabs-start' ); ?>

				<cx-vui-tabs-panel
					name="available-widgets"
					label="<?php _e( 'Available Widgets', 'jet-woo-builder' ); ?>"
					key="available-widgets">

					<div class="avaliable-widgets">
						<div class="avaliable-widgets__option-info">
							<div class="avaliable-widgets__option-info-name"><?php _e( 'Global Available Widgets', 'jet-woo-builder' ); ?></div>
							<div class="avaliable-widgets__option-info-desc"><?php _e( 'List of widgets that will be available when editing the page', 'jet-woo-builder' ); ?></div>
						</div>
						<div class="avaliable-widgets__controls">
							<div
								class="avaliable-widgets__control"
								v-for="(option, index) in pageOptions.global_available_widgets.options">
								<cx-vui-switcher
									:key="index"
									:name="`global-avaliable-widget-${option.value}`"
									:label="option.label"
									:wrapper-css="[ 'equalwidth' ]"
									return-true="true"
									return-false="false"
									v-model="pageOptions.global_available_widgets.value[option.value]"
								>
								</cx-vui-switcher>
							</div>
						</div>
					</div>

					<div class="avaliable-widgets">
						<div class="avaliable-widgets__option-info">
							<div class="avaliable-widgets__option-info-name"><?php _e( 'Single Product Available Widgets', 'jet-woo-builder' ); ?></div>
							<div class="avaliable-widgets__option-info-desc"><?php _e( 'List of widgets that will be available when editing the single product template', 'jet-woo-builder' ); ?></div>
						</div>
						<div class="avaliable-widgets__controls">
							<div
								class="avaliable-widgets__control"
								v-for="(option, index) in pageOptions.single_product_available_widgets.options">
								<cx-vui-switcher
									:key="index"
									:name="`single-product-avaliable-widget-${option.value}`"
									:label="option.label"
									:wrapper-css="[ 'equalwidth' ]"
									return-true="true"
									return-false="false"
									v-model="pageOptions.single_product_available_widgets.value[option.value]"
								>
								</cx-vui-switcher>
							</div>
						</div>
					</div>

					<div class="avaliable-widgets">
						<div class="avaliable-widgets__option-info">
							<div class="avaliable-widgets__option-info-name"><?php _e( 'Archive Product Available Widgets', 'jet-woo-builder' ); ?></div>
							<div class="avaliable-widgets__option-info-desc"><?php _e( 'List of widgets that will be available when editing the archive product template', 'jet-woo-builder' ); ?></div>
						</div>
						<div class="avaliable-widgets__controls">
							<div
								class="avaliable-widgets__control"
								v-for="(option, index) in pageOptions.archive_product_available_widgets.options">
								<cx-vui-switcher
									:key="index"
									:name="`archive-product-avaliable-widget-${option.value}`"
									:label="option.label"
									:wrapper-css="[ 'equalwidth' ]"
									return-true="true"
									return-false="false"
									v-model="pageOptions.archive_product_available_widgets.value[option.value]"
								>
								</cx-vui-switcher>
							</div>
						</div>
					</div>

					<div class="avaliable-widgets">
						<div class="avaliable-widgets__option-info">
							<div class="avaliable-widgets__option-info-name"><?php _e( 'Archive Category Available Widgets', 'jet-woo-builder' ); ?></div>
							<div class="avaliable-widgets__option-info-desc"><?php _e( 'List of widgets that will be available when editing the archive category template', 'jet-woo-builder' ); ?></div>
						</div>
						<div class="avaliable-widgets__controls">
							<div
								class="avaliable-widgets__control"
								v-for="(option, index) in pageOptions.archive_category_available_widgets.options">
								<cx-vui-switcher
									:key="index"
									:name="`archive-category-avaliable-widget-${option.value}`"
									:label="option.label"
									:wrapper-css="[ 'equalwidth' ]"
									return-true="true"
									return-false="false"
									v-model="pageOptions.archive_category_available_widgets.value[option.value]"
								>
								</cx-vui-switcher>
							</div>
						</div>
					</div>

					<div class="avaliable-widgets">
						<div class="avaliable-widgets__option-info">
							<div class="avaliable-widgets__option-info-name"><?php _e( 'Shop Product Available Widgets', 'jet-woo-builder' ); ?></div>
							<div class="avaliable-widgets__option-info-desc"><?php _e( 'List of widgets that will be available when editing the archive product template', 'jet-woo-builder' ); ?></div>
						</div>
						<div class="avaliable-widgets__controls">
							<div
								class="avaliable-widgets__control"
								v-for="(option, index) in pageOptions.shop_product_available_widgets.options">
								<cx-vui-switcher
									:key="index"
									:name="`shop-product-avaliable-widget-${option.value}`"
									:label="option.label"
									:wrapper-css="[ 'equalwidth' ]"
									return-true="true"
									return-false="false"
									v-model="pageOptions.shop_product_available_widgets.value[option.value]"
								>
								</cx-vui-switcher>
							</div>
						</div>
					</div>

					<div class="avaliable-widgets">
						<div class="avaliable-widgets__option-info">
							<div class="avaliable-widgets__option-info-name"><?php _e( 'Cart Available Widgets', 'jet-woo-builder' ); ?></div>
							<div class="avaliable-widgets__option-info-desc"><?php _e( 'List of widgets that will be available when editing the cart template', 'jet-woo-builder' ); ?></div>
						</div>
						<div class="avaliable-widgets__controls">
							<div
									class="avaliable-widgets__control"
									v-for="(option, index) in pageOptions.cart_available_widgets.options">
								<cx-vui-switcher
										:key="index"
										:name="`cart-avaliable-widget-${option.value}`"
										:label="option.label"
										:wrapper-css="[ 'equalwidth' ]"
										return-true="true"
										return-false="false"
										v-model="pageOptions.cart_available_widgets.value[option.value]"
								>
								</cx-vui-switcher>
							</div>
						</div>
					</div>

					<div class="avaliable-widgets">
						<div class="avaliable-widgets__option-info">
							<div class="avaliable-widgets__option-info-name"><?php _e( 'Checkout Available Widgets', 'jet-woo-builder' ); ?></div>
							<div class="avaliable-widgets__option-info-desc"><?php _e( 'List of widgets that will be available when editing the checkout template', 'jet-woo-builder' ); ?></div>
						</div>
						<div class="avaliable-widgets__controls">
							<div
									class="avaliable-widgets__control"
									v-for="(option, index) in pageOptions.checkout_available_widgets.options">
								<cx-vui-switcher
										:key="index"
										:name="`checkout-avaliable-widget-${option.value}`"
										:label="option.label"
										:wrapper-css="[ 'equalwidth' ]"
										return-true="true"
										return-false="false"
										v-model="pageOptions.checkout_available_widgets.value[option.value]"
								>
								</cx-vui-switcher>
							</div>
						</div>
					</div>

					<div class="avaliable-widgets">
						<div class="avaliable-widgets__option-info">
							<div class="avaliable-widgets__option-info-name"><?php _e( 'Thank You Page Available Widgets', 'jet-woo-builder' ); ?></div>
							<div class="avaliable-widgets__option-info-desc"><?php _e( 'List of widgets that will be available when editing the thank you page template', 'jet-woo-builder' ); ?></div>
						</div>
						<div class="avaliable-widgets__controls">
							<div
									class="avaliable-widgets__control"
									v-for="(option, index) in pageOptions.thankyou_available_widgets.options">
								<cx-vui-switcher
										:key="index"
										:name="`thankyou-avaliable-widget-${option.value}`"
										:label="option.label"
										:wrapper-css="[ 'equalwidth' ]"
										return-true="true"
										return-false="false"
										v-model="pageOptions.thankyou_available_widgets.value[option.value]"
								>
								</cx-vui-switcher>
							</div>
						</div>
					</div>

					<div class="avaliable-widgets">
						<div class="avaliable-widgets__option-info">
							<div class="avaliable-widgets__option-info-name"><?php _e( 'My Account Page Available Widgets', 'jet-woo-builder' ); ?></div>
							<div class="avaliable-widgets__option-info-desc"><?php _e( 'List of widgets that will be available when editing the my account page template', 'jet-woo-builder' ); ?></div>
						</div>
						<div class="avaliable-widgets__controls">
							<div
									class="avaliable-widgets__control"
									v-for="(option, index) in pageOptions.myaccount_available_widgets.options">
								<cx-vui-switcher
										:key="index"
										:name="`myaccount-avaliable-widget-${option.value}`"
										:label="option.label"
										:wrapper-css="[ 'equalwidth' ]"
										return-true="true"
										return-false="false"
										v-model="pageOptions.myaccount_available_widgets.value[option.value]"
								>
								</cx-vui-switcher>
							</div>
						</div>
					</div>

				</cx-vui-tabs-panel>

				<cx-vui-tabs-panel
					name="product-thumb-effect"
					label="<?php _e( 'Product Thumb Effect', 'jet-woo-builder' ); ?>"
					key="product-thumb-effect">

					<cx-vui-switcher
						name="enable_product_thumb_effect"
						label="<?php _e( 'Enable Thumbnails Effect', 'jet-woo-builder' ); ?>"
						description="<?php _e( 'Enable thumbnails switch on hover', 'jet-woo-builder' ); ?>"
						:wrapper-css="[ 'equalwidth' ]"
						return-true="true"
						return-false="false"
						v-model="pageOptions['enable_product_thumb_effect'].value">
					</cx-vui-switcher>

					<cx-vui-select
						name="product_thumb_effect"
						label="<?php _e( 'Thumbnails Effect', 'jet-woo-builder' ); ?>"
						description="<?php _e( 'Choose thumbnails hover effect', 'jet-woo-builder' ); ?>"
						:wrapper-css="[ 'equalwidth' ]"
						size="fullwidth"
						:options-list="pageOptions.product_thumb_effect.options"
						v-model="pageOptions.product_thumb_effect.value">
					</cx-vui-select>

				</cx-vui-tabs-panel>

				<cx-vui-tabs-panel
						name="custom-templates-misc"
						label="<?php _e( 'Misc', 'jet-woo-builder' ); ?>"
						key="custom-templates-misc">

					<cx-vui-switcher
							name="enable_custom_templates_styles"
							label="<?php _e( 'Enable Woocommerce Pages Templates Styles', 'jet-woo-builder' ); ?>"
							description="<?php _e( 'Makes browsers render all widgets more consistently.', 'jet-woo-builder' ); ?>"
							:wrapper-css="[ 'equalwidth' ]"
							return-true="true"
							return-false="false"
							v-model="pageOptions['enable_custom_templates_styles'].value">
					</cx-vui-switcher>

				</cx-vui-tabs-panel>

				<?php do_action( 'jet-woo-builder/settings-page-template/tabs-end' ); ?>
			</cx-vui-tabs>
		</div>
	</div>
</div>
