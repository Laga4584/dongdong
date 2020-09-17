<?php
if ( ! JUPITERX_CONTROL_PANEL_SYSTEM_STATUS ) {
	return;
}

$sysinfo = JupiterX_Control_Panel_System_Status::compile_system_status();
$sysinfo_warnings = JupiterX_Control_Panel_System_Status::compile_system_status_warnings();
?>
<div class="jupiterx-cp-pane-box" id="jupiterx-cp-system-status">
	<h3>
		<?php esc_html_e( 'System Status', 'jupiterx-core' ); ?>
		<?php jupiterx_the_help_link( 'https://themes.artbees.net/docs/checking-server-requirements-x-theme/', __( 'Checking Server Requirements', 'jupiterx-core' ) ); ?>
	</h3>

	<a class="btn btn-primary jupiterx-button--get-system-report" href="#">
		<?php esc_html_e( 'Get System Report', 'jupiterx-core' ); ?>
	</a>

	<div id="jupiterx-textarea--get-system-report">
		<textarea readonly="readonly" onclick="this.focus();this.select()"></textarea>
	</div>
	<br>
	<table class="table" cellspacing="0">
		<thead class="thead-light">
		<tr>
			<th colspan="3" data-export-label="WordPress Environment">
				<?php esc_html_e( 'WordPress Environment', 'jupiterx-core' ); ?>
			</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td data-export-label="Home URL">
				<?php esc_html_e( 'Home URL', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The URL of your site\'s homepage.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>

			<td><code><?php echo wp_kses_post( $sysinfo['home_url'] ); ?></code></td>
		</tr>
		<tr>
			<td data-export-label="Site URL">
				<?php esc_html_e( 'Site URL', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The root URL of your site.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<code><?php echo esc_url( $sysinfo['site_url'] ); ?></code>
			</td>
		</tr>
		<tr>
			<td data-export-label="WP Content URL">
				<?php esc_html_e( 'WP Content URL', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The URL of WordPress\'s content directory.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<code><?php echo esc_url( $sysinfo['wp_content_url'] ); ?></code>
			</td>
		</tr>
		<tr>
			<td data-export-label="WP Upload Path">
				<?php esc_html_e( 'WP Upload Path', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The absolute path to WordPress\'s upload directory.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<code><?php echo esc_url( $sysinfo['wp_upload_dir'] ); ?></code>
			</td>
		</tr>
		<tr>
			<td data-export-label="WP Upload URL">
				<?php esc_html_e( 'WP Upload URL', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The URL of WordPress\'s upload directory.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<code><?php echo esc_url( $sysinfo['wp_upload_url'] ); ?></code>
			</td>
		</tr>
		<tr>
			<td data-export-label="WP Version">
				<?php esc_html_e( 'WP Version', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The version of WordPress installed on your site.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<?php bloginfo( 'version' ); ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="WP Multisite">
				<?php esc_html_e( 'WP Multisite', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'Whether or not you have WordPress Multisite enabled.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<?php if ( false == $sysinfo['wp_multisite'] ) : ?>
					<span class="status-invisible">False</span>
					<span><?php echo esc_html_e( 'No', 'jupiterx-core' ); ?></span>
				<?php else : ?>
					<span class="status-invisible">True</span><span class="status-state status-true"></span>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Permalink Structure">
				<?php esc_html_e( 'Permalink Structure', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The current permalink structure as defined in WordPress Settings->Permalinks.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<code><?php echo esc_html( $sysinfo['permalink_structure'] ); ?></code>
			</td>
		</tr>
		<?php $sof = $sysinfo['front_page_display']; ?>
		<tr>
			<td data-export-label="Front Page Display">
				<?php esc_html_e( 'Front Page Display', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The current Reading mode of WordPress.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td><?php echo esc_html( $sof ); ?></td>
		</tr>

		<?php
		if ( 'page' == $sof ) {
?>
		<tr>
			<td data-export-label="Front Page">
				<?php esc_html_e( 'Front Page', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The currently selected page which acts as the site\'s Front Page.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<?php echo esc_html( $sysinfo['front_page'] ); ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Posts Page">
				<?php esc_html_e( 'Posts Page', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The currently selected page in where blog posts are displayed.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<?php echo esc_html( $sysinfo['posts_page'] ); ?>
			</td>
		</tr>
<?php
		}
?>
		<tr class="<?php esc_attr_e( isset( $sysinfo_warnings['wp_mem_limit'] ) ? 'jupiterx-sysinfo-warning' : '' ); ?>">
			<td data-export-label="WP Memory Limit">
				<?php esc_html_e( 'WP Memory Limit', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The maximum amount of memory (RAM) that your site can use at one time.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<span class="jupiterx-sysinfo-value">
					<?php echo esc_html( $sysinfo['wp_mem_limit']['size'] ); ?>
				</span>
				<?php if ( isset( $sysinfo_warnings['wp_mem_limit'] ) ): ?>
					<span class="jupiterx-sysinfo-warning-msg">
						<i class="jupiterx-icon-info-circle"></i>
						<?php echo $sysinfo_warnings['wp_mem_limit']['message']; ?>
					</span>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Database Table Prefix">
				<?php esc_html_e( 'Database Table Prefix', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The prefix structure of the current WordPress database.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<?php echo esc_html( $sysinfo['db_table_prefix'] ); ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="WP Debug Mode">
				<?php esc_html_e( 'WP Debug Mode', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'Displays whether or not WordPress is in Debug Mode.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<?php if ( 'false' == $sysinfo['wp_debug'] ) : ?>
					<span class="status-invisible">False</span>
					<span><?php echo esc_html_e( 'Disabled', 'jupiterx-core' ); ?></span>
				<?php else : ?>
					<span class="status-invisible">True</span><span class="status-state status-true"></span>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Language">
				<?php esc_html_e( 'Language', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The current language used by WordPress. Default = English', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<?php echo esc_html( $sysinfo['wp_lang'] ); ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="The Main WP Directory">
				<?php esc_html_e( 'The Main WP Directory', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'Check if main WP directory is writable.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<?php if ( wp_is_writable( $sysinfo['wp_writable'] ) ) : ?>
					<span class="status-invisible">True</span><span class="status-state status-true"></span>
					<span><?php esc_html_e( 'Writable', 'jupiterx-core' ); ?></span>
				<?php else : ?>
					<span class="status-invisible">False</span><span class="status-state status-false"></span>
					<span><?php printf( __( 'Make sure <code>%s</code> directory is writable.', 'jupiterx-core' ), $sysinfo['wp_writable'] ); ?></span>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="The wp-content Directory">
				<?php esc_html_e( 'The wp-content Directory', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'Check if wp-content directory is writable.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<?php if ( wp_is_writable( $sysinfo['wp_content_writable'] ) ) : ?>
					<span class="status-invisible">True</span><span class="status-state status-true"></span>
					<span><?php esc_html_e( 'Writable', 'jupiterx-core' ); ?></span>
				<?php else : ?>
					<span class="status-invisible">False</span><span class="status-state status-false"></span>
					<span><?php printf( __( 'Make sure <code>%s</code> directory is writable.', 'jupiterx-core' ), $sysinfo['wp_content_writable'] ); ?></span>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="The uploads Directory">
				<?php esc_html_e( 'The uploads Directory', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'Check if uploads directory is writable.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<?php if ( wp_is_writable( $sysinfo['wp_uploads_writable'] ) ) : ?>
					<span class="status-invisible">True</span><span class="status-state status-true"></span>
					<span><?php esc_html_e( 'Writable', 'jupiterx-core' ); ?></span>
				<?php else : ?>
					<span class="status-invisible">False</span><span class="status-state status-false"></span>
					<span><?php printf( __( 'Make sure <code>%s</code> directory is writable.', 'jupiterx-core' ), $sysinfo['wp_uploads_writable'] ); ?></span>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="The plugins Directory">
				<?php esc_html_e( 'The plugins Directory', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'Check if plugins directory is writable.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<?php if ( wp_is_writable( $sysinfo['wp_plugins_writable'] ) ) : ?>
					<span class="status-invisible">True</span><span class="status-state status-true"></span>
					<span><?php esc_html_e( 'Writable', 'jupiterx-core' ); ?></span>
				<?php else : ?>
					<span class="status-invisible">False</span><span class="status-state status-false"></span>
					<span><?php printf( __( 'Make sure <code>%s</code> directory is writable.', 'jupiterx-core' ), $sysinfo['wp_plugins_writable'] ); ?></span>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="The themes Directory">
				<?php esc_html_e( 'The themes Directory', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'Check if themes directory is writable.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<?php if ( wp_is_writable( $sysinfo['wp_themes_writable'] ) ) : ?>
					<span class="status-invisible">True</span><span class="status-state status-true"></span>
					<span><?php esc_html_e( 'Writable', 'jupiterx-core' ); ?></span>
				<?php else : ?>
					<span class="status-invisible">False</span><span class="status-state status-false"></span>
					<span><?php printf( __( 'Make sure <code>%s</code> directory is writable.', 'jupiterx-core' ), $sysinfo['wp_themes_writable'] ); ?></span>
				<?php endif; ?>
			</td>
		</tr>
		</tbody>
	</table>
	<br><br>
	<table class="table" cellspacing="0">
		<thead class="thead-light">
		<tr>
			<th colspan="3" data-export-label="Theme"><?php esc_html_e( 'Theme', 'jupiterx-core' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td data-export-label="Name"><?php esc_html_e( 'Name', 'jupiterx-core' ); ?>:</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The name of the current active theme.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td><?php echo esc_html( $sysinfo['theme']['name'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="Version"><?php esc_html_e( 'Version', 'jupiterx-core' ); ?>:</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The installed version of the current active theme.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<?php echo esc_html( $sysinfo['theme']['version'] ); ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Author URL"><?php esc_html_e( 'Author URL', 'jupiterx-core' ); ?>:</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The theme developers URL.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td><?php echo esc_url( $sysinfo['theme']['author_uri'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="Child Theme"><?php esc_html_e( 'Child Theme', 'jupiterx-core' ); ?>:</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'Displays whether or not the current theme is a child theme.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<?php if ( is_child_theme() ) : ?>
					<span class="status-invisible">True</span><span class="status-state status-true"></span>
				<?php else : ?>
					<span class="status-invisible">False</span>
					<span><?php echo esc_html_e( 'No', 'jupiterx-core' ); ?></span>
				<?php endif; ?>
			</td>
		</tr>
			<?php if ( is_child_theme() ) : ?>
				<tr>
				<td data-export-label="Parent Theme Name"><?php esc_html_e( 'Parent Theme Name', 'jupiterx-core' ); ?>:
				</td>
				<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The name of the parent theme.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
				</td>
				<td><?php echo esc_html( $sysinfo['theme']['parent_name'] ); ?></td>
				</tr>
				<tr>
				<td data-export-label="Parent Theme Version">
				<?php esc_html_e( 'Parent Theme Version', 'jupiterx-core' ); ?>:
				</td>
				<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The installed version of the parent theme.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
				</td>
				<td><?php echo esc_html( $sysinfo['theme']['parent_version'] ); ?></td>
				</tr>
				<tr>
				<td data-export-label="Parent Theme Author URL">
				<?php esc_html_e( 'Parent Theme Author URL', 'jupiterx-core' ); ?>:
				</td>
				<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The parent theme developers URL.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
				</td>
				<td><?php echo esc_url( $sysinfo['theme']['parent_author_uri'] ); ?></td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
	<br><br>

	<table class="table" cellspacing="0">
		<thead class="thead-light">
		<tr>
			<th colspan="3" data-export-label="Browser">
				<?php esc_html_e( 'Browser', 'jupiterx-core' ); ?>
			</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td data-export-label="Browser Info">
				<?php esc_html_e( 'Browser Info', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'Information about the web browser currently in use.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
			<?php
			foreach ( $sysinfo['browser'] as $key => $value ) {
				echo '<strong>' . esc_html( ucfirst( $key ) ) . '</strong>: ' . esc_html( $value ) . '<br/>';
			}
			?>
			</td>
		</tr>
		</tbody>
	</table>
	<br><br>



	<table class="table" cellspacing="0">
		<thead class="thead-light">
		<tr>
			<th colspan="3" data-export-label="Server Environment">
				<?php esc_html_e( 'Server Environment', 'jupiterx-core' ); ?>
			</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td data-export-label="Server Info">
				<?php esc_html_e( 'Server Info', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'Information about the web server that is currently hosting your site.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<?php echo esc_html( $sysinfo['server_info'] ); ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Localhost Environment">
				<?php esc_html_e( 'Localhost Environment', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'Is the server running in a localhost environment.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
			<?php if ( 'true' == $sysinfo['localhost'] ) : ?>
				<span class="status-invisible">True</span><span class="status-state status-true"></span>
			<?php else : ?>
				<span class="status-invisible">False</span>
				<span><?php echo esc_html_e( 'No', 'jupiterx-core' ); ?></span>
			<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="PHP Version">
				<?php esc_html_e( 'PHP Version', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The version of PHP installed on your hosting server.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<?php echo esc_html( $sysinfo['php_ver'] ); ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="ABSPATH">
				<?php esc_html_e( 'ABSPATH', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The ABSPATH variable on the server.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<?php echo '<code>' . esc_html( $sysinfo['abspath'] ) . '</code>'; ?>
			</td>
		</tr>

				<?php
				if ( function_exists( 'ini_get' ) ) {
				?>
					<tr class="<?php esc_attr_e( isset( $sysinfo_warnings['php_mem_limit'] ) ? 'jupiterx-sysinfo-warning' : '' ); ?>">
				<td data-export-label="PHP Memory Limit"><?php esc_html_e( 'PHP Memory Limit', 'jupiterx-core' ); ?>:</td>
				<td class="help">
					<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The largest file size that can be contained in one post.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
				</td>
				<td>
					<span class="jupiterx-sysinfo-value">
						<?php echo esc_html( $sysinfo['php_mem_limit']['size'] ); ?>
					</span>
					<?php if ( isset( $sysinfo_warnings['php_mem_limit'] ) ): ?>
						<span class="jupiterx-sysinfo-warning-msg">
							<i class="jupiterx-icon-info-circle"></i>
							<?php echo $sysinfo_warnings['php_mem_limit']['message']; ?>
						</span>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td data-export-label="PHP Post Max Size"><?php esc_html_e( 'PHP Post Max Size', 'jupiterx-core' ); ?>:</td>
				<td class="help">
					<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The largest file size that can be contained in one post.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
				</td>
				<td><?php echo esc_html( $sysinfo['php_post_max_size'] ); ?></td>
			</tr>
			<tr>
				<td data-export-label="PHP Time Limit"><?php esc_html_e( 'PHP Time Limit', 'jupiterx-core' ); ?>:</td>
				<td class="help">
					<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'max_execution_time : The amount of time (in seconds) that your site will spend on a single operation before timing out (to avoid server lockups).', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
				</td>
				<td><?php echo esc_html( $sysinfo['php_time_limit'] ); ?></td>
			</tr>

			<tr>
				<td data-export-label="PHP Max Input Vars"><?php esc_html_e( 'PHP Max Input Vars', 'jupiterx-core' ); ?>:</td>
				<td class="help">
					<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The maximum number of variables your server can use for a single function to avoid overloads.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
				</td>
				<td><?php echo esc_html( $sysinfo['php_max_input_var'] ); ?></td>
			</tr>

		<?php
		if ( true == $sysinfo['suhosin_installed'] ) {
		?>
		<tr>
			<td data-export-label="PHP Max Input Vars"><?php esc_html_e( 'Suhosin Max Request Vars', 'jupiterx-core' ); ?>:</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The maximum number of variables your server running Suhosin can use for a single function to avoid overloads.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td><?php echo esc_html( $sysinfo['suhosin_request_max_vars'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="PHP Max Input Vars"><?php esc_html_e( 'Suhosin Max Post Vars', 'jupiterx-core' ); ?>:</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The maximum number of variables your server running Suhosin can use for a single function to avoid overloads.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td><?php echo esc_html( $sysinfo['suhosin_post_max_vars'] ); ?></td>
		</tr>
		<?php
		}
			?>
			<tr>
				<td data-export-label="PHP Display Errors"><?php esc_html_e( 'PHP Display Errors', 'jupiterx-core' ); ?>:</td>
				<td class="help">
					<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'Determines if PHP will display errors within the browser.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
				</td>
				<td>
				<?php if ( 'false' == $sysinfo['php_display_errors'] ) : ?>
					<span class="status-invisible">False</span>
					<span><?php echo esc_html_e( 'Disabled', 'jupiterx-core' ); ?></span>
				<?php else : ?>
					<span class="status-invisible">True</span><span class="status-state status-true"></span>
				<?php endif; ?>
						</td>
					</tr>
				<?php
				}
		?>
		<tr>
			<td data-export-label="SUHOSIN Installed"><?php esc_html_e( 'SUHOSIN Installed', 'jupiterx-core' ); ?>:</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'Suhosin is an advanced protection system for PHP installations. It was designed to protect your servers on the one hand against a number of well known problems in PHP applications and on the other hand against potential unknown vulnerabilities within these applications or the PHP core itself.  If enabled on your server, Suhosin may need to be configured to increase its data submission limits.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<?php if ( false == $sysinfo['suhosin_installed'] ) : ?>
					<span class="status-invisible">False</span>
					<span><?php echo esc_html_e( 'Disabled', 'jupiterx-core' ); ?></span>
				<?php else : ?>
					<span class="status-invisible">True</span><span class="status-state status-true"></span>
				<?php endif; ?>
			</td>
		</tr>

		<tr>
			<td data-export-label="MySQL Version"><?php esc_html_e( 'MySQL Version', 'jupiterx-core' ); ?>:</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The version of MySQL installed on your hosting server.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td><?php echo esc_html( $sysinfo['mysql_ver'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="Max Upload Size"><?php esc_html_e( 'Max Upload Size', 'jupiterx-core' ); ?>:</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The largest file size that can be uploaded to your WordPress installation.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td><?php echo esc_html( $sysinfo['max_upload_size'] ); ?></td>
		</tr>
		<?php if ( is_multisite() ) : ?>
		<tr>
			<td data-export-label="Network Upload Limit"><?php esc_html_e( 'Network Upload Limit', 'jupiterx-core' ); ?>:</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'Maximum file size that you can upload based on network settings.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td><?php echo esc_html( $sysinfo['network_upload_limit'] ); ?></td>
		</tr>
		<?php endif; ?>
		<tr>
			<td data-export-label="Default Timezone is UTC">
				<?php esc_html_e( 'Default Timezone is UTC', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'The default timezone for your server.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
			<?php if ( 'false' == $sysinfo['def_tz_is_utc'] ) : ?>
				<span class="status-invisible">False</span><span class="status-state status-false"></span>
				<?php sprintf( __( 'Default timezone is %s - it should be UTC', 'jupiterx-core' ), esc_html( date_default_timezone_get() ) ); ?>
			<?php else : ?>
				<span class="status-invisible">True</span><span class="status-state status-true"></span>
			<?php endif; ?>
			</td>
		</tr>
		<tr>

			<td data-export-label="PHP XML">
				<?php esc_html_e( 'PHP XML', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'Theme requires PHP XML Library to be installed.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<?php if ( 'false' == $sysinfo['phpxml'] ) : ?>
					<span class="status-invisible">False</span><span class="status-state status-false"></span>
				<?php else : ?>
					<span class="status-invisible">True</span><span class="status-state status-true"></span>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="MBString">
				<?php esc_html_e( 'MBString', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'Theme requires MBString PHP Library to be installed.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<?php if ( 'false' == $sysinfo['mbstring'] ) : ?>
					<span class="status-invisible">False</span><span class="status-state status-false"></span>
				<?php else : ?>
					<span class="status-invisible">True</span><span class="status-state status-true"></span>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="SimpleXML">
				<?php esc_html_e( 'SimpleXML', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'Theme requires SimpleXML PHP Library to be installed.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<?php if ( 'false' == $sysinfo['simplexml'] ) : ?>
					<span class="status-invisible">False</span><span class="status-state status-false"></span>
				<?php else : ?>
					<span class="status-invisible">True</span><span class="status-state status-true"></span>
				<?php endif; ?>
			</td>
		</tr>
		<?php
			$posting = array();

			$posting['fsockopen_curl']['name'] = esc_html__( 'Fsockopen/cURL', 'jupiterx-core' );
			$posting['fsockopen_curl']['help'] = esc_attr__( 'Used when communicating with remote services with PHP.', 'jupiterx-core' );

		if ( 'true' == $sysinfo['fsockopen_curl'] ) {
			$posting['fsockopen_curl']['success'] = true;
		} else {
			$posting['fsockopen_curl']['success'] = false;
			$posting['fsockopen_curl']['note']    = esc_html__( 'Your server does not have fsockopen or cURL enabled - cURL is used to communicate with other servers. Please contact your hosting provider.', 'jupiterx-core' );
		}

			$posting['soap_client']['name'] = esc_html__( 'SoapClient', 'jupiterx-core' );
			$posting['soap_client']['help'] = esc_attr__( 'Some webservices like shipping use SOAP to get information from remote servers, for example, live shipping quotes from FedEx require SOAP to be installed.', 'jupiterx-core' );

		if ( true == $sysinfo['soap_client'] ) {
			$posting['soap_client']['success'] = true;
		} else {
			$posting['soap_client']['success'] = false;
			$posting['soap_client']['note']    = sprintf( __( 'Your server does not have the <a href="%s">SOAP Client</a> class enabled - some gateway plugins which use SOAP may not work as expected.', 'jupiterx-core' ), 'http://php.net/manual/en/class.soapclient.php' );
		}

			$posting['dom_document']['name'] = esc_html__( 'DOMDocument', 'jupiterx-core' );
			$posting['dom_document']['help'] = esc_attr__( 'HTML/Multipart emails use DOMDocument to generate inline CSS in templates.', 'jupiterx-core' );

		if ( true == $sysinfo['dom_document'] ) {
			$posting['dom_document']['success'] = true;
		} else {
			$posting['dom_document']['success'] = false;
			$posting['dom_document']['note']    = sprintf( __( 'Your server does not have the <a href="%s">DOMDocument</a> class enabled - HTML/Multipart emails, and also some extensions, will not work without DOMDocument.', 'jupiterx-core' ), 'http://php.net/manual/en/class.domdocument.php' );
		}


			$posting['gzip']['name'] = esc_html__( 'GZip', 'jupiterx-core' );
			$posting['gzip']['help'] = esc_attr__( 'GZip (gzopen) is used to open the GEOIP database from MaxMind.', 'jupiterx-core' );

		if ( true == $sysinfo['gzip'] ) {
			$posting['gzip']['success'] = true;
		} else {
			$posting['gzip']['success'] = false;
			$posting['gzip']['note'] = sprintf( __( 'Your server does not support the <a href="%s">gzopen</a> function - this is required to use the GeoIP database from MaxMind. The API fallback will be used instead for geolocation.', 'jupiterx-core' ), 'http://php.net/manual/en/zlib.installation.php' );
		}

		// Zip Archive.
		$posting['zip_archive']['name'] = esc_html__( 'Zip Archive', 'jupiterx-core' );
		$posting['zip_archive']['help'] = esc_attr__( 'Used to read or write ZIP compressed archives and the files inside them.', 'jupiterx-core' );

		if ( class_exists( 'ZipArchive' ) ) {
			$posting['zip_archive']['success'] = true;
		} else {
			$posting['zip_archive']['note'] = esc_html__( 'ZipArchive library is missing. Install the Zip extension. Contact your hosting provider.', 'jupiterx-core' );
			$posting['zip_archive']['success'] = false;
		}

		// Iconv.
		$posting['iconv']['name'] = esc_html__( 'Iconv', 'jupiterx-core' );
		$posting['iconv']['help'] = esc_attr__( 'Used in CSS parser to handle the character set conversion.', 'jupiterx-core' );

		if ( extension_loaded( 'iconv' ) ) {
			$posting['iconv']['success'] = true;
		} else {
			$posting['iconv']['note']    = esc_html__( 'Iconv library is missing. Install the iconv extension. Contact your hosting provider.', 'jupiterx-core' );
			$posting['iconv']['success'] = false;
		}

		// OPcache.
		$posting['opcache']['name'] = esc_html__( 'OPcache', 'jupiterx-core' );
		$posting['opcache']['help'] = esc_attr__( 'OPcache improves PHP performance by storing precompiled script bytecode in shared memory, thereby removing the need for PHP to load and parse scripts on each request.', 'jupiterx-core' );

		if ( function_exists( 'opcache_get_status' ) && opcache_get_status() ) {
			$posting['opcache']['success'] = true;
		} else {
			$posting['opcache']['note']    = esc_html__( 'OPcache extension is disabled. Contact your hosting provider to enable it.', 'jupiterx-core' );
			$posting['opcache']['success'] = false;
		}

		// Echo the fields.
		foreach ( $posting as $post ) {
			$mark = ! empty( $post['success'] ) ? 'yes' : 'error';
			?>
			<tr>
			<td data-export-label="<?php echo esc_html( $post['name'] ); ?>">
				<?php echo esc_html( $post['name'] ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo isset( $post['help'] ) ? wp_kses_post( $post['help'] ) : ''; ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
					<?php echo ! empty( $post['success'] ) ? '<span class="status-invisible">True</span><span class="status-state status-true"></span>' : '<span class="status-invisible">False</span><span class="status-state status-false"></span>'; ?>
					<?php echo ! empty( $post['note'] ) ? wp_kses_data( $post['note'] ) : ''; ?>
			</td>
			</tr>
			<?php
		}
		?>
			<tr data-jupiterx-ajax="http_requests">
				<td data-export-label="HTTP Requests">
					<?php esc_html_e( 'HTTP Requests', 'jupiterx-core' ); ?>:
				</td>
				<td class="help">
					<a class="jupiterx-tooltip" data-content="<?php esc_attr_e( 'Check if HTTP requests (get, post and ...) are working properly.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
				</td>
				<td>
					<span class="status-state"><span class="spinner is-active"></span></span>
					<span class="status-text"></span>
				</td>
			</tr>
			<tr data-jupiterx-ajax="artbees_server">
				<td data-export-label="Communication with artbees.net">
					<?php esc_html_e( 'Communication with artbees.net', 'jupiterx-core' ); ?>:
				</td>
				<td class="help">
					<a class="jupiterx-tooltip" data-content="<?php esc_attr_e( 'Check if you have proper access to artbees.net server.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
				</td>
				<td>
					<span class="status-state"><span class="spinner is-active"></span></span>
					<span class="status-text"></span>
				</td>
			</tr>
		</tbody>
	</table>
	<br><br>

	<table class="table" cellspacing="0">
		<thead class="thead-light">
			<tr>
				<th colspan="3" data-export-label="Database">
					<?php esc_html_e( 'Database', 'jupiterx-core' ); ?>
				</th>
			</tr>
		</thead>
	<tbody>
		<tr>
			<td data-export-label="Database Info">
				<?php esc_html_e( 'Database Size', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'Information about database.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
			<?php

			global $wpdb;
			$total_size = 0;
			$data_usage = 0;
			$index_usage = 0;

			foreach ( $wpdb->tables('all') as $table ) {
				$sql = $wpdb->prepare( "SHOW TABLE STATUS LIKE %s", $table );
				$results = $wpdb->get_results( $sql, ARRAY_A );

				if ( ! isset( $results[0] ) ) {
					continue;
				}

				$data_usage += $results[0]['Data_length'];
				$index_usage += $results[0]['Index_length'];
			}

			$total_size = round( ($data_usage + $index_usage) / ( 1024 * 1024 ), 2 );
			echo $total_size . 'MB';
			?>
			</td>
		</tr>

		<?php

		$prefix = $wpdb->prefix;

		$tables = [
			'options',
			'links',
			'commentmeta',
			'term_relationships',
			'postmeta',
			'posts',
			'term_taxonomy',
			'terms',
			'comments',
			'termmeta',
		];

		$users_tables = [
			'usermeta',
			'users',
		];

		$multisite_only_tables = [
			'blogs',
			'blogs_versions ',
			'registration_log',
			'signups',
			'site',
			'sitemeta',
		];

		// Order of conditions should not be changed.
		if ( ! is_multisite() ) {
			$tables = array_merge( $tables, $users_tables );
		}

		if ( is_multisite() && is_super_admin() ) {
			$tables = array_merge( $tables, $users_tables, $multisite_only_tables );
		}

		foreach ( $tables as $key => $wp_table ) {
			$tables[ $key ] = $wpdb->prefix . $wp_table;
		}

		foreach ( $tables as $table ) {
			$sql     = $wpdb->prepare( "SHOW TABLE STATUS LIKE %s", $table );
			$results = $wpdb->get_results( $sql, ARRAY_A );

			if ( ! isset( $results[0] ) ) {
				continue;
			}

			$data_usage  = $results[0]['Data_length'];
			$index_usage = $results[0]['Index_length'];
			?>
			<tr>
				<td data-export-label="<?php echo esc_attr( $table ); ?> Info">
					<?php echo esc_html( $table ); ?>:
				</td>
				<td></td>
				<td>
				<?php
					$total_size = round( ($data_usage + $index_usage) / ( 1024 * 1024 ), 2 );
					echo $total_size . ' MB';
				?>
				</td>
			</tr>
			<?php
		}

		?>
	</tbody>
		<tr>
			<td data-export-label="Theme Mods Info">
				<?php esc_html_e( 'Theme Mods Size', 'jupiterx-core' ); ?>:
			</td>
			<td class="help">
				<a class="jupiterx-tooltip" data-content="<?php echo esc_attr__( 'Size of customizer options.', 'jupiterx-core' ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
			</td>
			<td>
				<?php
					$mods = get_theme_mods();
					$has_numeric_value = false;

					foreach ( $mods as $key => $value ) {
						if ( is_numeric( $key ) ) {
							$has_numeric_value = true;
							break;
						}
					}
				?>
				<span class="jupiterx-cp-inline-text"><?php echo round( mb_strlen( serialize( get_theme_mods() ) ) / ( 1024  ), 2 ) . ' KB'; ?></span>
				<?php if ( $has_numeric_value ) : ?>
					<span><a href="#" id="jupiterx-mods-cleanup" class="button button-secondary" data-nonce="<?php echo esc_attr( wp_create_nonce( 'jupiterx_mods_cleanup' ) ); ?>"> <?php esc_html_e( 'Cleanup', 'jupiterx-core' ); ?></a></span>
				<?php endif; ?>
			</td>
		</tr>
	</table>
	<br><br>

	<table class="table" cellspacing="0">
		<thead class="thead-light">
		<tr>
			<th colspan="3" data-export-label="Active Plugins (<?php echo esc_html( count( (array) get_option( 'active_plugins' ) ) ); ?>)">
				<?php esc_html_e( 'Active Plugins', 'jupiterx-core' ); ?> (<?php echo esc_html( count( (array) get_option( 'active_plugins' ) ) ); ?>)
			</th>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach ( $sysinfo['plugins'] as $name => $plugin_data ) {

			if ( ! empty( $plugin_data['Name'] ) ) {
				$plugin_name = esc_html( $plugin_data['Name'] );

				if ( ! empty( $plugin_data['PluginURI'] ) ) {
					$plugin_name = '<a href="' . esc_url( $plugin_data['PluginURI'] ) . '" title="' . esc_attr__( 'Visit plugin homepage', 'jupiterx-core' ) . '">' . esc_html( $plugin_name ) . '</a>';
				}
				?>
				<tr>
					<td><?php echo wp_kses_post( $plugin_name ); ?></td>
					<td class="help">
						<a class="jupiterx-tooltip" data-content="<?php echo esc_attr( strip_tags( $plugin_data['Description'] ) ); ?>" href="#" data-toggle="popover" data-placement="top"></a>
					</td>
					<td>
						<?php echo sprintf( _x( 'by %s', 'by author', 'jupiterx-core' ), wp_kses_post( $plugin_data['Author'] ) ) . ' &ndash; ' . esc_html( $plugin_data['Version'] ); ?>
					</td>
				</tr>
			<?php
			}
		}
		?>
		</tbody>
	</table>

	</div>
</div>
