<div
	class="jet-blocks-settings-page jet-blocks-settings-page__general"
>
	<cx-vui-select
		name="widgets_load_level"
		label="<?php _e( 'Editor Load Level', 'jet-blocks' ); ?>"
		description="<?php _e( 'Choose a certain set of options in the widget’s Style tab by moving the slider, and improve your Elementor editor performance by selecting appropriate style settings fill level (from None to Full level)', 'jet-blocks' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		size="fullwidth"
		:options-list="pageOptions.widgets_load_level.options"
		v-model="pageOptions.widgets_load_level.value">
	</cx-vui-select>
</div>
