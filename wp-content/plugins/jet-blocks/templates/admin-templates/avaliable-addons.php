<div
	class="jet-blocks-settings-page jet-blocks-settings-page__avaliable-addons"
>
	<div class="jet-blocks-settings-page__avaliable-controls">
		<div class="cx-vui-subtitle"><?php _e( 'Available Widgets', 'jet-blocks' ); ?></div>
		<div
			class="jet-blocks-settings-page__avaliable-control"
			v-for="(option, index) in pageOptions.avaliable_widgets.options">
			<cx-vui-switcher
				:key="index"
				:name="`avaliable-widget-${option.value}`"
				:label="option.label"
				:wrapper-css="[ 'equalwidth' ]"
				return-true="true"
				return-false="false"
				v-model="pageOptions.avaliable_widgets.value[option.value]"
			>
			</cx-vui-switcher>
		</div>
	</div>

	<div class="jet-blocks-settings-page__avaliable-controls">
		<div class="cx-vui-subtitle"><?php _e( 'Available Extensions', 'jet-blocks' ); ?></div>
		<div
			class="jet-blocks-settings-page__avaliable-control"
			v-for="(option, index) in pageOptions.avaliable_extensions.options">
			<cx-vui-switcher
				:key="index"
				:name="`avaliable-extension-${option.value}`"
				:label="option.label"
				:wrapper-css="[ 'equalwidth' ]"
				return-true="true"
				return-false="false"
				v-model="pageOptions.avaliable_extensions.value[option.value]"
			>
			</cx-vui-switcher>
		</div>
	</div>
</div>

