<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Advanced_Shipment_Tracking_Admin_notice {

	/**
	 * Instance of this class.
	 *
	 * @var object Class Instance
	 */
	private static $instance;
	
	/**
	 * Initialize the main plugin function
	*/
    public function __construct() {
		$this->init();	
    }
	
	/**
	 * Get the class instance
	 *
	 * @return WC_Advanced_Shipment_Tracking_Admin_notice
	*/
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
	
	/*
	* init from parent mail class
	*/
	public function init(){						
		//add_action( 'admin_notices', array( $this, 'admin_notice_after_update' ) );		
		//add_action('admin_init', array( $this, 'ast_plugin_notice_ignore' ) );											
	}		
	
	/*
	* Display admin notice on plugin install or update
	*/
	public function admin_notice_after_update(){ 		
		
		if ( get_option('ast_review_notice_ignore') ) return;
		
		$dismissable_url = esc_url(  add_query_arg( 'ast-review-ignore-notice', 'true' ) );
		?>		
		<style>		
		.wp-core-ui .notice.ast-dismissable-notice{
			position: relative;
			padding-right: 38px;
		}
		.wp-core-ui .notice.ast-dismissable-notice a.notice-dismiss{
			padding: 9px;
			text-decoration: none;
		} 
		.wp-core-ui .button-primary.btn_review_notice {
			background: transparent;
			color: #005b9a;
			border-color: #74c2e1;
			text-transform: uppercase;
			padding: 0 11px;
			font-size: 12px;
			height: 30px;
			line-height: 28px;
			margin: 5px 0 15px;
		}
		</style>	
		<div class="notice updated notice-success ast-dismissable-notice">
			<a href="<?php echo $dismissable_url; ?>" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></a>
			<p>Hey, I noticed you are using the Advanced Shipment Tracking - that’s awesome!</br>Could you please do me a BIG favor and give it a 5-star rating on WordPress to help us spread the word and boost our motivation?</p>
			<p>Eran Shor</br>Founder of zorem</p>
			<a class="button-primary btn_review_notice" target="blank" href="https://wordpress.org/support/plugin/woo-advanced-shipment-tracking/reviews/#new-post">Ok, you deserve it</a>
			<a class="button-primary btn_review_notice" href="<?php echo $dismissable_url; ?>">Nope, maybe later</a>
			<a class="button-primary btn_review_notice" href="<?php echo $dismissable_url; ?>">I already did</a>
		</div>
	<?php 		
	}	

	public function ast_plugin_notice_ignore(){
		if (isset($_GET['ast-review-ignore-notice'])) {
			update_option( 'ast_review_notice_ignore', 'true' );
		}
	}		
}