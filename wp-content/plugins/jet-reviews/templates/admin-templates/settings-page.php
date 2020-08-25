<?php
/**
 * Main dashboard template
 */
?><div id="jet-reviews-settings-page">
	<div class="jet-reviews-settings-page">
		<h1 class="cs-vui-title"><?php _e( 'JetReviews Settings', 'jet-reviews' ); ?></h1>
		<div class="cx-vui-panel">
			<div class="cx-vui-title"><?php _e( 'Enable Review Meta Box For:', 'jet-reviews' ); ?></div>
			<div class="jet-reviews-settings-page__avaliable-controls">
				<div
					class="jet-reviews-settings-page__avaliable-control"
					v-for="(option, index) in pageOptions['allowed-post-types']['options']">
					<cx-vui-switcher
						:key="index"
						:name="`allowed-post-type-${option.value}`"
						:label="option.label"
						:wrapper-css="[ 'equalwidth' ]"
						return-true="true"
						return-false="false"
						v-model="pageOptions['allowed-post-types']['value'][option.value]"
					>
					</cx-vui-switcher>
				</div>
			</div>
		</div>
	</div>
</div>
