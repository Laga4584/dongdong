<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 *  Admin class
 *  
 * @package WooCommerce - Minimum/Maximum Quantities
 * @since 1.0.0
 */
class WOO_Min_Max_Quantities_Admin {

    function __construct() {
        # code...
    }

    /**
     * Add tab for WC version < 2.6
     *
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 2.0.4
     */
    public function woo_min_max_qty_tab() { ?>
        <li class="woo_min_max_qty_tab"><a href="#woo_min_max_qty"><span><?php _e( 'Minimum/Maximum Quantities', 'woo_min_max_quantities' ); ?></span></a></li>
        <?php
    }

    /**
     * Add tab for WC version > 2.6
     *
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 2.0.4
     */
    public function woo_min_max_qty_register_tab( $tabs ) {
        
        $tabs['woo_min_max_qty'] = array(
            'label'  => __( 'Minimum/Maximum Quantities', 'woo_min_max_quantities' ),
            'target' => 'woo_min_max_qty',
            'class'  => array(
                'woo_min_max_qty_tab'
            )            
        );

        return $tabs;
    }

    /**
     * Show the min/max quantity tabs
     *
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 2.0.4
     */
    public function woo_min_max_qty_panel() {
        include( 'views/html-min-max-qty.php' );
    }

    /**
     * write_panel_save function.
     *
     * Save minmimum and maximum quantities/order settins on genral tab
     *
     * @access public
     * @param mixed $post_id
     * @return void
     *
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.0
     */
    function woo_min_max_quantites_write_panel_save($post_id) {

        // simple product save 2.1+ - 2.3
        if (isset($_POST['minimum_allowed_quantity'])) {
            update_post_meta($post_id, 'minimum_allowed_quantity', esc_attr($_POST['minimum_allowed_quantity']));
        }

        if (isset($_POST['maximum_allowed_quantity'])) {
            update_post_meta($post_id, 'maximum_allowed_quantity', esc_attr($_POST['maximum_allowed_quantity']));
        }

        if (isset($_POST['group_of_quantity'])) {
            update_post_meta($post_id, 'group_of_quantity', esc_attr($_POST['group_of_quantity']));
        }		         
		
        update_post_meta($post_id, 'minmax_do_not_count', empty($_POST['minmax_do_not_count']) ? 'no' : 'yes' );

        update_post_meta($post_id, 'minmax_cart_exclude', empty($_POST['minmax_cart_exclude']) ? 'no' : 'yes' );

        update_post_meta($post_id, 'minmax_category_group_of_exclude', empty($_POST['minmax_category_group_of_exclude']) ? 'no' : 'yes' );            

        // variable product save 2.1 - 2.2
        if (isset($_POST['variable_post_id']) && defined('WC_VERSION') && version_compare(WC_VERSION, '2.3.0', '<')) {

            $variable_post_id = $_POST['variable_post_id'];

            $min_max_rules = isset($_POST['min_max_rules']) ? array_map('sanitize_text_field', $_POST['min_max_rules']) : null;

            $minimum_allowed_quantity = isset($_POST['variation_minimum_allowed_quantity']) ? array_map('sanitize_text_field', $_POST['variation_minimum_allowed_quantity']) : '';

            $maximum_allowed_quantity = isset($_POST['variation_maximum_allowed_quantity']) ? array_map('sanitize_text_field', $_POST['variation_maximum_allowed_quantity']) : '';

            $group_of_quantity = isset($_POST['variation_group_of_quantity']) ? array_map('sanitize_text_field', $_POST['variation_group_of_quantity']) : '';

            $minmax_do_not_count = isset($_POST['variation_minmax_do_not_count']) ? array_map('sanitize_text_field', $_POST['variation_minmax_do_not_count']) : null;

            $minmax_cart_exclude = isset($_POST['variation_minmax_cart_exclude']) ? array_map('sanitize_text_field', $_POST['variation_minmax_cart_exclude']) : null;

            $minmax_category_group_of_exclude = isset($_POST['variation_minmax_category_group_of_exclude']) ? array_map('sanitize_text_field', $_POST['variation_minmax_category_group_of_exclude']) : null;

            $max_loop = max(array_keys($_POST['variable_post_id']));

            for ($i = 0; $i <= $max_loop; $i ++) {

                if (!isset($variable_post_id[$i]))
                    continue;

                $variation_id = absint($variable_post_id[$i]);

                if (isset($min_max_rules[$i])) {
                    update_post_meta($variation_id, 'min_max_rules', 'yes');
                    update_post_meta($variation_id, 'minimum_allowed_quantity', $minimum_allowed_quantity[$i]);
                    update_post_meta($variation_id, 'maximum_allowed_quantity', $maximum_allowed_quantity[$i]);
                    update_post_meta($variation_id, 'group_of_quantity', $group_of_quantity[$i]);

                    if (isset($minmax_do_not_count[$i])) {
                        update_post_meta($variation_id, 'minmax_do_not_count', 'yes');
                    } else {
                        update_post_meta($variation_id, 'minmax_do_not_count', 'no');
                    }

                    if (isset($minmax_cart_exclude[$i])) {
                        update_post_meta($variation_id, 'minmax_cart_exclude', 'yes');
                    } else {
                        update_post_meta($variation_id, 'minmax_cart_exclude', 'no');
                    }

                    if (isset($minmax_category_group_of_exclude[$i])) {
                        update_post_meta($variation_id, 'minmax_category_group_of_exclude', 'yes');
                    } else {
                        update_post_meta($variation_id, 'minmax_category_group_of_exclude', 'no');
                    }
                } else {
                    update_post_meta($variation_id, 'min_max_rules', 'no');
                }
            }
        }
    }

    /**
     * write_panel_save variations.
     *
     * Save minmimum and maximum quantities/order settins on variation level
     *
     * @access public
     * @param mixed $post_id
     * @return void
     *
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.0
     *
     */
    public function woo_min_max_quantites_save_variation_settings($variation_id, $i) {
        $min_max_rules = isset($_POST['min_max_rules']) ? array_map('sanitize_text_field', $_POST['min_max_rules']) : null;

        $minimum_allowed_quantity = isset($_POST['variation_minimum_allowed_quantity']) ? array_map('sanitize_text_field', $_POST['variation_minimum_allowed_quantity']) : '';

        $maximum_allowed_quantity = isset($_POST['variation_maximum_allowed_quantity']) ? array_map('sanitize_text_field', $_POST['variation_maximum_allowed_quantity']) : '';

        $group_of_quantity = isset($_POST['variation_group_of_quantity']) ? array_map('sanitize_text_field', $_POST['variation_group_of_quantity']) : '';

        $minmax_do_not_count = isset($_POST['variation_minmax_do_not_count']) ? array_map('sanitize_text_field', $_POST['variation_minmax_do_not_count']) : null;

        $minmax_cart_exclude = isset($_POST['variation_minmax_cart_exclude']) ? array_map('sanitize_text_field', $_POST['variation_minmax_cart_exclude']) : null;

        $minmax_category_group_of_exclude = isset($_POST['variation_minmax_category_group_of_exclude']) ? array_map('sanitize_text_field', $_POST['variation_minmax_category_group_of_exclude']) : null;

        if (isset($min_max_rules[$i])) {
            update_post_meta($variation_id, 'min_max_rules', 'yes');
        } else {
            update_post_meta($variation_id, 'min_max_rules', 'no');
        }

        update_post_meta($variation_id, 'variation_minimum_allowed_quantity', $minimum_allowed_quantity[$i]);
        update_post_meta($variation_id, 'variation_maximum_allowed_quantity', $maximum_allowed_quantity[$i]);
        update_post_meta($variation_id, 'variation_group_of_quantity', $group_of_quantity[$i]);

        if (isset($minmax_do_not_count[$i])) {
            update_post_meta($variation_id, 'variation_minmax_do_not_count', 'yes');
        } else {
            update_post_meta($variation_id, 'variation_minmax_do_not_count', 'no');
        }

        if (isset($minmax_cart_exclude[$i])) {
            update_post_meta($variation_id, 'variation_minmax_cart_exclude', 'yes');
        } else {
            update_post_meta($variation_id, 'variation_minmax_cart_exclude', 'no');
        }

        if (isset($minmax_category_group_of_exclude[$i])) {
            update_post_meta($variation_id, 'variation_minmax_category_group_of_exclude', 'yes');
        } else {
            update_post_meta($variation_id, 'variation_minmax_category_group_of_exclude', 'no');
        }
    }

    /**
     * variation_options function.
     *
     * Add checkbox to enable or disable min/max rules on variation level
     *
     * @access public
     * @return void
     *
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.0
     */
    function woo_min_max_quantites_variation_options($loop, $variation_data, $variation) {
        if (defined('WC_VERSION') && version_compare(WC_VERSION, '2.3.0', '>=')) {
            $min_max_rules = get_post_meta($variation->ID, 'min_max_rules', true);
            ?>
            <label><input type="checkbox" class="checkbox min_max_rules" name="min_max_rules[<?php echo $loop; ?>]" <?php if ($min_max_rules) checked($min_max_rules, 'yes'); ?> /> <?php _e('Min/Max Rules', 'woo_min_max_quantities'); ?> <a class="tips" data-tip="<?php _e('Enable this option to override min/max settings at variation level', 'woo_min_max_quantities'); ?>" href="#">[?]</a></label>
            <?php
        } else {
            ?>

            <label><input type="checkbox" class="checkbox min_max_rules" name="min_max_rules[<?php echo $loop; ?>]" <?php if (isset($variation_data['min_max_rules'][0])) checked($variation_data['min_max_rules'][0], 'yes'); ?> /> <?php _e('Min/Max Rules', 'woo_min_max_quantities'); ?> <a class="tips" data-tip="<?php _e('Enable this option to override min/max settings at variation level', 'woo_min_max_quantities'); ?>" href="#">[?]</a></label>

            <?php
        }
    }

    /**
     * variation_panel function.
     *
     * Add minmimum and maximum quantities/order settins on variations level
     *
     * @access public
     * @param mixed $loop
     * @param mixed $variation_data
     * @return void
     *
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.0
     */
    function woo_min_max_quantites_variation_panel($loop, $variation_data, $variation) {
        $min_max_rules = get_post_meta($variation->ID, 'min_max_rules', true);

        if (isset($min_max_rules) && 'no' === $min_max_rules) {
            $visible = 'style="display:none"';
        } else {
            $visible = '';
        }

        if (defined('WC_VERSION') && version_compare(WC_VERSION, '2.3.0', '>=')) {
            $min_qty = get_post_meta($variation->ID, 'variation_minimum_allowed_quantity', true);
            $max_qty = get_post_meta($variation->ID, 'variation_maximum_allowed_quantity', true);
            $group_of = get_post_meta($variation->ID, 'variation_group_of_quantity', true);
            $do_not_count = get_post_meta($variation->ID, 'variation_minmax_do_not_count', true);
            $cart_exclude = get_post_meta($variation->ID, 'variation_minmax_cart_exclude', true);
            $category_group_of_exclude = get_post_meta($variation->ID, 'variation_minmax_category_group_of_exclude', true);
            ?>

            <div class="min_max_rules_options" <?php echo $visible; ?>>
                <p class="form-row form-row-first">
                    <label><?php _e('Minimum quantity', 'woo_min_max_quantities'); ?>
                        <input type="number" size="5" name="variation_minimum_allowed_quantity[<?php echo $loop; ?>]" value="<?php if ($min_qty) echo esc_attr($min_qty); ?>" /></label>
                </p>

                <p class="form-row form-row-last">
                    <label><?php _e('Maximum quantity', 'woo_min_max_quantities'); ?>
                        <input type="number" size="5" name="variation_maximum_allowed_quantity[<?php echo $loop; ?>]" value="<?php if ($max_qty) echo esc_attr($max_qty); ?>" /></label>
                </p>

                <p class="form-row form-row-first">
                    <label><?php _e('Group of (Step)...', 'woo_min_max_quantities'); ?>
                        <input type="number" size="5" name="variation_group_of_quantity[<?php echo $loop; ?>]" value="<?php if ($group_of) echo esc_attr($group_of); ?>" /></label>
                </p>

                <p class="form-row form-row-last">
                    <label><input type="checkbox" class="checkbox" name="variation_minmax_do_not_count[<?php echo $loop; ?>]" <?php if ($do_not_count) checked($do_not_count, 'yes') ?> /> <?php _e('Order rules: Do not count', 'woo_min_max_quantities'); ?> <a class="tips" data-tip="<?php _e('Don\'t count this product against your minimum order quantity/value rules.', 'woo_min_max_quantities'); ?>" href="#">[?]</a></label>

                    <label><input type="checkbox" class="checkbox" name="variation_minmax_cart_exclude[<?php echo $loop; ?>]" <?php if ($cart_exclude) checked($cart_exclude, 'yes') ?> /> <?php _e('Order rules: Exclude', 'woo_min_max_quantities'); ?> <a class="tips" data-tip="<?php _e('Exclude this product from minimum order quantity/value rules. If this is the only item in the cart, rules will not apply.', 'woo_min_max_quantities'); ?>" href="#">[?]</a></label>

                    <label><input type="checkbox" class="checkbox" name="variation_minmax_category_group_of_exclude[<?php echo $loop; ?>]" <?php if ($category_group_of_exclude) checked($category_group_of_exclude, 'yes') ?> /> <?php _e('Category group-of rules: Exclude', 'woo_min_max_quantities'); ?> <a class="tips" data-tip="<?php _e('Exclude this product from category group-of-quantity rules. This product will not be counted towards category groups.', 'woo_min_max_quantities'); ?>" href="#">[?]</a></label>
                </p>
            </div>
        <?php } else { ?>
            <tr class="min_max_rules_options" <?php echo $visible; ?>>
                <td>
                    <label><?php _e('Minimum quantity', 'woo_min_max_quantities'); ?></label>
                    <input type="number" size="5" name="variation_minimum_allowed_quantity[<?php echo $loop; ?>]" value="<?php if (isset($variation_data['minimum_allowed_quantity'][0])) echo $variation_data['minimum_allowed_quantity'][0]; ?>" />
                </td>
                <td>
                    <label><?php _e('Maximum quantity', 'woo_min_max_quantities'); ?> <input type="text" size="5" name="variation_maximum_allowed_quantity[<?php echo $loop; ?>]" value="<?php if (isset($variation_data['maximum_allowed_quantity'][0])) echo $variation_data['maximum_allowed_quantity'][0]; ?>" />
                </td>
            </tr>
            <tr class="min_max_rules_options" <?php echo $visible; ?>>
                <td>
                    <label><?php _e('Group of (Step)...', 'woo_min_max_quantities'); ?></label>
                    <input type="number" size="5" name="variation_group_of_quantity[<?php echo $loop; ?>]" value="<?php if (isset($variation_data['group_of_quantity'][0])) echo $variation_data['group_of_quantity'][0]; ?>" />
                </td>
                <td>

                    <label><input type="checkbox" class="checkbox" name="variation_minmax_do_not_count[<?php echo $loop; ?>]" <?php if (isset($variation_data['minmax_do_not_count'][0])) checked($variation_data['minmax_do_not_count'][0], 'yes') ?> /> <?php _e('Order rules: Do not count', 'woo_min_max_quantities'); ?> <a class="tips" data-tip="<?php _e('Don\'t count this product against your minimum order quantity/value rules.', 'woo_min_max_quantities'); ?>" href="#">[?]</a></label>

                    <label><input type="checkbox" class="checkbox" name="variation_minmax_cart_exclude[<?php echo $loop; ?>]" <?php if (isset($variation_data['minmax_cart_exclude'][0])) checked($variation_data['minmax_cart_exclude'][0], 'yes') ?> /> <?php _e('Order rules: Exclude', 'woo_min_max_quantities'); ?> <a class="tips" data-tip="<?php _e('Exclude this product from minimum order quantity/value rules. If this is the only item in the cart, rules will not apply.', 'woo_min_max_quantities'); ?>" href="#">[?]</a></label>

                    <label><input type="checkbox" class="checkbox" name="variation_minmax_category_group_of_exclude[<?php echo $loop; ?>]" <?php if (isset($variation_data['minmax_category_group_of_exclude'][0])) checked($variation_data['minmax_category_group_of_exclude'][0], 'yes') ?> /> <?php _e('Category group-of rules: Exclude', 'woo_min_max_quantities'); ?> <a class="tips" data-tip="<?php _e('Exclude this product from category group-of-quantity rules. This product will not be counted towards category groups.', 'woo_min_max_quantities'); ?>" href="#">[?]</a></label>

                </td>
            </tr>
            <?php
        }
    }

    /**
     * Category thumbnail fields.
     *
     * @access public
     * @return void
     *
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.0
     */
    function woo_min_max_quantites_add_category_fields() {
        global $woocommerce;
        ?>
        <div class="form-field">
            <label><?php _e('Minimum quantity', 'woo_min_max_quantities'); ?></label>
            <input type="number" size="5" name="group_of_quantity_min" />
            <p class="description"><?php _e('Enter a minimum allowable quantity for products belongs to this category to be purchased.', 'woo_min_max_quantities'); ?></p>
        </div>        
        <div class="form-field">
            <label><?php _e('Maximum quantity', 'woo_min_max_quantities'); ?></label>
            <input type="number" size="5" name="group_of_quantity_max" />
            <p class="description"><?php _e('Enter a maximum allowable quantity for products belongs to this category to be purchased.', 'woo_min_max_quantities'); ?></p>
        </div>
        <div class="form-field">
            <label><?php _e('Group of...', 'woo_min_max_quantities'); ?></label>
            <input type="number" size="5" name="group_of_quantity" />
            <p class="description"><?php _e('Products belongs to this category can be purchased only in groups of X.', 'woo_min_max_quantities'); ?></p>
        </div>
        <?php
    }

    /**
     * Edit category thumbnail field.
     *
     * @access public
     * @param mixed $term Term (category) being edited
     * @param mixed $taxonomy Taxonomy of the term being edited
     * @return void
     *
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.0
     */
    function woo_min_max_quantites_edit_category_fields($term, $taxonomy) {
        global $woocommerce;
       
        $display_type = version_compare( WC_VERSION, '3.6', 'ge' ) ? get_term_meta( $term->term_id, 'group_of_quantity', true ) : get_woocommerce_term_meta( $term->term_id, 'group_of_quantity', true );
        $group_of_max = version_compare( WC_VERSION, '3.6', 'ge' ) ? get_term_meta( $term->term_id, 'group_of_quantity_max', true ) : get_woocommerce_term_meta($term->term_id, 'group_of_quantity_max', true);
        $group_of_min = version_compare( WC_VERSION, '3.6', 'ge' ) ? get_term_meta( $term->term_id, 'group_of_quantity_min', true ) : get_woocommerce_term_meta($term->term_id, 'group_of_quantity_min', true);
        ?>        
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e('Minimum quantity', 'woo_min_max_quantities'); ?></label></th>
            <td>
                <input type="number" size="5" name="group_of_quantity_min" value="<?php echo $group_of_min; ?>" />
                <p class="description"><?php _e('Enter a minimum allowable quantity for products belongs to this category to be purchased.', 'woo_min_max_quantities'); ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e('Maximum quantity', 'woo_min_max_quantities'); ?></label></th>
            <td>
                <input type="number" size="5" name="group_of_quantity_max" value="<?php echo $group_of_max; ?>" />
                <p class="description"><?php _e('Enter a maximum allowable quantity for products belongs to this category to be purchased.', 'woo_min_max_quantities'); ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e('Group of...', 'woo_min_max_quantities'); ?></label></th>
            <td>
                <input type="number" size="5" name="group_of_quantity" value="<?php echo $display_type; ?>" />
                <p class="description"><?php _e('Products belongs to this category can be purchased only in groups of X.', 'woo_min_max_quantities'); ?></p>
            </td>
        </tr>
        <?php
    }

    /**
     * woocommerce_category_fields_save function.
     *
     * @access public
     * @param mixed $term_id Term ID being saved
     * @param mixed $tt_id
     * @param mixed $taxonomy Taxonomy of the term being saved
     * @return void
     *
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.0
     */
    function woo_min_max_quantites_category_fields_save($term_id, $tt_id, $taxonomy) {

        // minimum group of products
        if (isset($_POST['group_of_quantity'])) {
            update_woocommerce_term_meta($term_id, 'group_of_quantity', esc_attr($_POST['group_of_quantity']));
        }

        // maximum group of products
        if (isset($_POST['group_of_quantity_max'])) {
            update_woocommerce_term_meta($term_id, 'group_of_quantity_max', esc_attr($_POST['group_of_quantity_max']));
        }

        if (isset($_POST['group_of_quantity_min'])) {
            update_woocommerce_term_meta($term_id, 'group_of_quantity_min', esc_attr($_POST['group_of_quantity_min']));
        }        
    }

    /**
     * product_cat_columns function.
     *
     * @access public
     * @param mixed $columns
     * @return void
     *
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.0
     */
    function woo_min_max_quantites_product_cat_columns($columns) {

        $columns['groupof'] = __('Purchasable in...', 'woo_min_max_quantities');

        return $columns;
    }

    /**
     * product_cat_column function.
     *
     * @access public
     * @param mixed $columns
     * @param mixed $column
     * @param mixed $id
     * @return void
     *
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.0
     */
    function woo_min_max_quantites_product_cat_column($columns, $column, $id) {

        global $woocommerce;

        if ($column == 'groupof') {
            
            $groupof = version_compare( WC_VERSION, '3.6', 'ge' ) ? get_term_meta( $id, 'group_of_quantity', true ) : get_woocommerce_term_meta($id, 'group_of_quantity', true);
            $groupof_max = version_compare( WC_VERSION, '3.6', 'ge' ) ? get_term_meta( $id, 'group_of_quantity_max', true ) : get_woocommerce_term_meta($id, 'group_of_quantity_max', true);
            $groupof_min = version_compare( WC_VERSION, '3.6', 'ge' ) ? get_term_meta( $id, 'group_of_quantity_min', true ) : get_woocommerce_term_meta($id, 'group_of_quantity_min', true);

            if (!empty($groupof) || !empty($groupof_max) || !empty($groupof_min)) {
                if (!empty($groupof))
                    $columns .= __('Groups of:', 'woo_min_max_quantities') . ' ' . absint($groupof);
                if(!empty($groupof_min))
                    $columns .= sprintf(__('%sGroups of min:', 'woo_min_max_quantities'), '<br />') . ' ' . absint($groupof_min);
                if (!empty($groupof_max))
                    $columns .= sprintf(__('%sGroups of max:', 'woo_min_max_quantities'), '<br />') . ' ' . absint($groupof_max);
            } else {
                $columns .= '&ndash;';
            }
        }

        return $columns;
    }

    /**
     * Add hooks ( action and filters). 
     * 
     * contains all action and filter related to admin site
     *
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.0
     */
    public function add_hooks() {

        /*** Min/Max Quantities tab ***/

        // add action to create custom tab
        if ( version_compare( WC_VERSION, '2.6', '<' ) ) {
            add_action( 'woocommerce_product_write_panel_tabs', array( $this, 'woo_min_max_qty_tab' ), 5 );
            add_action( 'woocommerce_product_write_panels', array( $this, 'woo_min_max_qty_panel' ) );
        } else {
            add_filter( 'woocommerce_product_data_tabs', array( $this, 'woo_min_max_qty_register_tab' ) );
            add_action( 'woocommerce_product_data_panels', array( $this, 'woo_min_max_qty_panel' ) );
        }                
        
        // Add Action to Save min/max quantities settings on product edit page
        add_action('woocommerce_process_product_meta', array($this, 'woo_min_max_quantites_write_panel_save'));

        /*** Variations ***/

        // Add aciton to save min/max quantities settings on variation level
        if (defined('WC_VERSION') && version_compare(WC_VERSION, '2.3.0', '>=')) {
            add_action('woocommerce_save_product_variation', array($this, 'woo_min_max_quantites_save_variation_settings'), 10, 2);
        }
        // Add action to dispaly enable min/max quantites rules on variation level
        add_action('woocommerce_variation_options', array($this, 'woo_min_max_quantites_variation_options'), 10, 3);

        // Add action to display min/max quantities settings on variations level
        add_action('woocommerce_product_after_variable_attributes', array($this, 'woo_min_max_quantites_variation_panel'), 10, 3);

        /*** Category ***/

        // Add action to save group of settings on category		
        add_action('created_term', array($this, 'woo_min_max_quantites_category_fields_save'), 10, 3);
        add_action('edit_term', array($this, 'woo_min_max_quantites_category_fields_save'), 10, 3);

        // Add action to edit group of settings on category
        add_action('product_cat_edit_form_fields', array($this, 'woo_min_max_quantites_edit_category_fields'), 10, 2);

        // Add action to add group of settings on category
        add_action('product_cat_add_form_fields', array($this, 'woo_min_max_quantites_add_category_fields'));

        // Add filter to add purchasable in column settings on category
        add_filter('manage_edit-product_cat_columns', array($this, 'woo_min_max_quantites_product_cat_columns'));

        // Add filter to add group of column settings on category
        add_filter('manage_product_cat_custom_column', array($this, 'woo_min_max_quantites_product_cat_column'), 10, 3);
    }

}