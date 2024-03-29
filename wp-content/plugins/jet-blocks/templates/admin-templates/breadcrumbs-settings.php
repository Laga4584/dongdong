<div
	class="jet-blocks-settings-page jet-blocks-settings-page__breadcrumbs"
>
	<div class="cx-vui-subtitle"><?php _e( 'Taxonomy to show in breadcrumbs for content types', 'jet-blocks' ); ?></div>

	<?php
	$post_types = get_post_types( array( 'public' => true ), 'objects' );
	$deny_list = array( 'elementor_library', 'jet-theme-core' );

	if ( is_array( $post_types ) && ! empty( $post_types ) ) {

		foreach ( $post_types as $post_type ) {

			if ( in_array( $post_type->name, $deny_list ) ) {
				continue;
			}

			$taxonomies = get_object_taxonomies( $post_type->name, 'objects' );

			if ( is_array( $taxonomies ) && ! empty( $taxonomies ) ) {

				$post_type_name = 'breadcrumbs_taxonomy_' . $post_type->name;
				?>
				<cx-vui-select
					name="<?php echo $post_type_name; ?>"
					label="<?php echo $post_type->label; ?>"
					:wrapper-css="[ 'equalwidth' ]"
					size="fullwidth"
					:options-list="pageOptions['<?php echo $post_type_name; ?>']['options']"
					v-model="pageOptions['<?php echo $post_type_name; ?>']['value']"
				></cx-vui-select><?php
			}
		}
	}
	?>
</div>
