<?php
namespace Jet_Reviews\DB;

/**
 * Database manager class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Base DB class
 */
class Manager {

	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Constructor for the class
	 */
	public function __construct() {
		self::init_db_required();
	}

	/**
	 * [wpdb description]
	 * @return [type] [description]
	 */
	public function wpdb() {
		global $wpdb;
		return $wpdb;
	}

	/**
	 * Return table name by key
	 *
	 * @param  string $table table key.
	 * @return string
	 */
	public static function tables( $table = null, $return = 'all' ) {

		global $wpdb;

		$prefix = 'jet_';

		$tables = array(
			'reviews' => array(
				'name'        => $wpdb->prefix . $prefix . 'reviews',
				'export_name' => $prefix . 'reviews',
				'query'       => "
					id bigint(20) NOT NULL AUTO_INCREMENT,
					post_id bigint(20),
					post_type text,
					author bigint(20),
					date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
					title text,
					content longtext,
					type_slug text,
					rating_data longtext,
					rating int,
					likes bigint(20) DEFAULT 0 NOT NULL,
					dislikes bigint(20) DEFAULT 0 NOT NULL,
					approved varchar(20) DEFAULT 1 NOT NULL,
					pinned varchar(20) DEFAULT 0 NOT NULL,
					PRIMARY KEY (id)
				",
			),
			'review_meta' => array(
				'name'        => $wpdb->prefix . $prefix . 'review_meta',
				'export_name' => $prefix . 'review_meta',
				'query'       => "
					id bigint(20) NOT NULL AUTO_INCREMENT,
					review_id bigint(20),
					meta_key varchar(255),
					meta_value longtext,
					PRIMARY KEY (id)
				",
			),
			'review_types' => array(
				'name'        => $wpdb->prefix . $prefix . 'review_types',
				'export_name' => $prefix . 'review_types',
				'query'       => "
					id bigint(20) NOT NULL AUTO_INCREMENT,
					name text,
					slug varchar(255),
					description longtext DEFAULT '' NOT NULL,
					source varchar(255) DEFAULT 'default' NOT NULL,
					fields longtext DEFAULT '' NOT NULL,
					meta_data longtext DEFAULT '' NOT NULL,
					PRIMARY KEY (id)
				",
			),
			'review_comments' => array(
				'name'        => $wpdb->prefix . $prefix . 'review_comments',
				'export_name' => $prefix . 'review_comments',
				'query'       => "
					id bigint(20) NOT NULL AUTO_INCREMENT,
					post_id bigint(20),
					parent_id bigint(20),
					review_id bigint(20),
					author bigint(20),
					content longtext,
					date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
					likes bigint(20) DEFAULT 0 NOT NULL,
					dislikes bigint(20) DEFAULT 0 NOT NULL,
					approved varchar(20),
					PRIMARY KEY (id)
				",
			)
		);

		if ( ! $table && 'all' === $return ) {
			return $tables;
		}

		switch ( $return ) {
			case 'all':
				return isset( $tables[ $table ] ) ? $tables[ $table ] : false;

			case 'name':
				return isset( $tables[ $table ] ) ? $tables[ $table ]['name'] : false;

			case 'query':
				return isset( $tables[ $table ] ) ? $tables[ $table ]['query'] : false;
		}

		return false;

	}

	/**
	 * [init_db_required description]
	 * @return [type] [description]
	 */
	public static function init_db_required() {
		global $wpdb;

		if ( 'wp_jet_reviews' !== $wpdb->get_var( "SHOW TABLES LIKE 'wp_jet_reviews'" ) ) {
			self::create_all_tables();
			self::add_default_data();
		}
	}

	/**
	 * Create all tables on activation
	 *
	 * @return [type] [description]
	 */
	public static function create_all_tables() {

		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		foreach ( self::tables() as $table ) {
			$table_name  = $table['name'];
			$table_query = $table['query'];

			if ( $table_name !== $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) ) {

				$sql = "CREATE TABLE $table_name (
					$table_query
				) $charset_collate;";

				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

				dbDelta( $sql );
			}
		}

	}

	/**
	 * [add_dafault_data description]
	 */
	public static function add_default_data() {

		global $wpdb;

		$query = $wpdb->replace( 'wp_jet_review_types', array(
			'id'          => 1,
			'name'        => 'Default',
			'slug'        => 'default',
			'description' => '',
			'source'      => 'default',
			'fields'      => maybe_serialize( array(
				array(
					'label' => 'Rating',
					'step'  => 1,
					'max'   => 5,
				)
			) ),
			'meta_data' => '',
		) );

	}

	/**
	 * Check if table is exists
	 *
	 * @param  string  $table Table name.
	 * @return boolean
	 */
	public function is_table_exists( $table = null ) {

		global $wpdb;

		$table_name = $this->tables( $table, 'name' );

		if ( ! $table_name ) {
			return false;
		}

		return ( $table_name === $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) );
	}

}
