<?php
/**
 * Plugin Name: MOQ Limits for WooCommerce
 * Description: Simple plugin to set minimum and maximum order limits and minimum order surcharge
 * Version: 1.2.2
 * Author: Investracker
 * Text Domain: moq-limits
 * Requires at least: 5.0
 * Requires PHP: 7.2
 * WC requires at least: 3.0
 * WC tested up to: 10.5.1
 * Requires Plugins: woocommerce
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('before_woocommerce_init', function() {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});

class MOQ_Limits_Plugin {
    
    private $translations = array(
        'pl' => array(
            'settings_title' => 'MOQ Limits - Ustawienia',
            'language_label' => 'Język komunikatów',
            'language_desc' => 'Wybierz język dla komunikatów błędów w koszyku',
            'license_label' => 'Klucz licencyjny',
            'license_desc' => 'Wprowadź klucz licencyjny aby odblokować funkcje premium',
            'license_placeholder' => 'Wprowadź klucz licencyjny...',
            'license_activate' => 'Aktywuj licencję',
            'license_active' => 'Licencja aktywna',
            'license_invalid' => 'Nieprawidłowy klucz licencyjny',
            'premium_feature' => 'Funkcja Premium',
            'premium_required' => 'Ta funkcja wymaga aktywnej licencji premium',
            'min_order_label' => 'Minimalna kwota zamówienia',
            'max_order_label' => 'Maksymalna kwota zamówienia',
            'min_order_surcharge_label' => 'Dopłata do małego zamówienia',
            'min_order_surcharge_desc' => 'Nalicz jedną lub wiele dopłat, jeśli wartość koszyka jest niższa niż wskazane progi. Działa równolegle z limitami minimalnej i maksymalnej kwoty zamówienia.',
            'surcharge_threshold_label' => 'Próg dopłaty',
            'surcharge_type_label' => 'Typ dopłaty',
            'surcharge_value_label' => 'Wartość dopłaty',
            'surcharge_type_fixed' => 'Kwotowa',
            'surcharge_type_percentage' => 'Procentowa',
            'add_surcharge_rule' => 'Dodaj próg dopłaty',
            'small_order_surcharge_fee' => 'Dopłata do małego zamówienia',
            'leave_empty' => 'Pozostaw puste aby wyłączyć',
            'product_limits_label' => 'Limity dla produktów (Premium)',
            'product_limits_desc' => 'Ustaw minimalne i maksymalne ilości dla konkretnych produktów',
            'category_limits_label' => 'Limity dla kategorii (Premium)',
            'category_limits_desc' => 'Ustaw minimalne i maksymalne ilości dla całych kategorii produktów',
            'product_surcharges_label' => 'Dopłaty dla produktów (Premium)',
            'product_surcharges_desc' => 'Nalicz dopłatę do małego zamówienia, jeśli w koszyku znajdują się wskazane produkty',
            'category_surcharges_label' => 'Dopłaty dla kategorii (Premium)',
            'category_surcharges_desc' => 'Nalicz dopłatę do małego zamówienia, jeśli w koszyku znajdują się produkty z wybranych kategorii',
            'add_product_limit' => 'Dodaj limit produktu',
            'add_category_limit' => 'Dodaj limit kategorii',
            'add_product_surcharge' => 'Dodaj dopłatę produktu',
            'add_category_surcharge' => 'Dodaj dopłatę kategorii',
            'select_product' => 'Wybierz produkt',
            'select_category' => 'Wybierz kategorię',
            'min_quantity' => 'Min. ilość',
            'max_quantity' => 'Max. ilość',
            'remove' => 'Usuń',
            'regional_limits_label' => 'Limity regionalne (Premium)',
            'regional_limits_desc' => 'Ustaw różne limity kwotowe dla konkretnych krajów, stanów lub kodów pocztowych',
            'regional_surcharges_label' => 'Dopłaty regionalne (Premium)',
            'regional_surcharges_desc' => 'Nalicz dopłatę do małego zamówienia dla wskazanych krajów, stanów lub kodów pocztowych',
            'add_regional_limit' => 'Dodaj limit regionalny',
            'add_regional_surcharge' => 'Dodaj dopłatę regionalną',
            'select_country' => 'Wybierz kraj',
            'select_state' => 'Wybierz stan (opcjonalnie)',
            'postal_code' => 'Kod pocztowy (opcjonalnie)',
            'postal_code_placeholder' => 'np. 12345 lub 12-345',
            'min_amount' => 'Min. kwota',
            'max_amount' => 'Max. kwota',
            'threshold_amount' => 'Próg zamówienia',
            'any_country' => 'Dowolny kraj',
            'any_state' => 'Dowolny stan',
            'regional_min_error' => 'Minimalna wartość zamówienia dla %s: %s (Twój koszyk: %s)',
            'regional_max_error' => 'Maksymalna wartość zamówienia dla %s: %s (Twój koszyk: %s)',
            'min_order_error' => 'Kwota zamówienia jest za niska. Minimalna wartość zamówienia: %s (Twój koszyk: %s)',
            'max_order_error' => 'Maksymalna kwota zamówienia to %s. Twój koszyk: %s',
            'product_min_error' => 'Produkt "%s" wymaga minimalnej ilości %d sztuk (masz: %d)',
            'product_max_error' => 'Produkt "%s" ma maksymalną ilość %d sztuk (masz: %d)',
            'category_min_error' => 'Produkty z kategorii "%s" wymagają minimalnej ilości %d sztuk (masz: %d)',
            'category_max_error' => 'Produkty z kategorii "%s" mają maksymalną ilość %d sztuk (masz: %d)',
            'woocommerce_required' => 'MOQ Limits wymaga aktywnego WooCommerce!'
        ),
        'en' => array(
            'settings_title' => 'MOQ Limits - Settings',
            'language_label' => 'Message Language',
            'language_desc' => 'Select language for cart error messages',
            'license_label' => 'License Key',
            'license_desc' => 'Enter your license key to unlock premium features',
            'license_placeholder' => 'Enter license key...',
            'license_activate' => 'Activate License',
            'license_active' => 'License Active',
            'license_invalid' => 'Invalid license key',
            'premium_feature' => 'Premium Feature',
            'premium_required' => 'This feature requires an active premium license',
            'min_order_label' => 'Minimum Order Amount',
            'max_order_label' => 'Maximum Order Amount',
            'min_order_surcharge_label' => 'Small Order Surcharge',
            'min_order_surcharge_desc' => 'Add one or more surcharges when the cart subtotal is below configured thresholds. Works alongside the minimum and maximum order amount limits.',
            'surcharge_threshold_label' => 'Surcharge Threshold',
            'surcharge_type_label' => 'Surcharge Type',
            'surcharge_value_label' => 'Surcharge Value',
            'surcharge_type_fixed' => 'Fixed',
            'surcharge_type_percentage' => 'Percentage',
            'add_surcharge_rule' => 'Add Surcharge Threshold',
            'small_order_surcharge_fee' => 'Small order surcharge',
            'leave_empty' => 'Leave empty to disable',
            'product_limits_label' => 'Product Limits (Premium)',
            'product_limits_desc' => 'Set minimum and maximum quantities for specific products',
            'category_limits_label' => 'Category Limits (Premium)',
            'category_limits_desc' => 'Set minimum and maximum quantities for entire product categories',
            'product_surcharges_label' => 'Product Surcharges (Premium)',
            'product_surcharges_desc' => 'Apply a small order surcharge when the cart contains selected products',
            'category_surcharges_label' => 'Category Surcharges (Premium)',
            'category_surcharges_desc' => 'Apply a small order surcharge when the cart contains products from selected categories',
            'add_product_limit' => 'Add Product Limit',
            'add_category_limit' => 'Add Category Limit',
            'add_product_surcharge' => 'Add Product Surcharge',
            'add_category_surcharge' => 'Add Category Surcharge',
            'select_product' => 'Select Product',
            'select_category' => 'Select Category',
            'min_quantity' => 'Min. Qty',
            'max_quantity' => 'Max. Qty',
            'remove' => 'Remove',
            'regional_limits_label' => 'Regional Limits (Premium)',
            'regional_limits_desc' => 'Set different order limits for specific countries, states, or postal codes',
            'regional_surcharges_label' => 'Regional Surcharges (Premium)',
            'regional_surcharges_desc' => 'Apply a small order surcharge for selected countries, states, or postal codes',
            'add_regional_limit' => 'Add Regional Limit',
            'add_regional_surcharge' => 'Add Regional Surcharge',
            'select_country' => 'Select Country',
            'select_state' => 'Select State (Optional)',
            'postal_code' => 'Postal Code (Optional)',
            'postal_code_placeholder' => 'e.g. 12345 or 12-345',
            'min_amount' => 'Min. Amount',
            'max_amount' => 'Max. Amount',
            'threshold_amount' => 'Order Threshold',
            'any_country' => 'Any Country',
            'any_state' => 'Any State',
            'regional_min_error' => 'Minimum order value for %s: %s (Your cart: %s)',
            'regional_max_error' => 'Maximum order value for %s: %s (Your cart: %s)',
            'min_order_error' => 'Order amount is too low. Minimum order value: %s (Your cart: %s)',
            'max_order_error' => 'Maximum order amount is %s. Your cart: %s',
            'product_min_error' => 'Product "%s" requires minimum quantity of %d pieces (you have: %d)',
            'product_max_error' => 'Product "%s" has maximum quantity of %d pieces (you have: %d)',
            'category_min_error' => 'Products from category "%s" require minimum quantity of %d pieces (you have: %d)',
            'category_max_error' => 'Products from category "%s" have maximum quantity of %d pieces (you have: %d)',
            'woocommerce_required' => 'MOQ Limits requires active WooCommerce!'
        )
    );
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('woocommerce_cart_calculate_fees', array($this, 'apply_minimum_order_surcharge'));
        add_action('woocommerce_check_cart_items', array($this, 'validate_cart'));
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_settings_link'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'woocommerce_page_moq-limits') {
            return;
        }
        
        wp_enqueue_script('moq-limits-admin', plugin_dir_url(__FILE__) . 'assets/admin.js', array('jquery'), '1.0.4', true);
        wp_enqueue_style('moq-limits-admin', plugin_dir_url(__FILE__) . 'assets/admin.css', array(), '1.0.4');
        
        wp_localize_script('moq-limits-admin', 'moqLimits', array(
            'selectProduct' => $this->get_text('select_product'),
            'selectCategory' => $this->get_text('select_category'),
            'minQuantity' => $this->get_text('min_quantity'),
            'maxQuantity' => $this->get_text('max_quantity'),
            'remove' => $this->get_text('remove'),
        ));
    }
    
    public function add_settings_link($links) {
        $settings_link = '<a href="' . admin_url('admin.php?page=moq-limits') . '">Settings</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
    
    private function get_text($key) {
        $lang = get_option('moq_language', 'pl');
        return isset($this->translations[$lang][$key]) ? $this->translations[$lang][$key] : $key;
    }
    
    private function is_premium_active() {
        $license_key = get_option('moq_license_key', '');
        if (empty($license_key)) {
            return false;
        }
        
        // Proste sprawdzenie - w prawdziwej aplikacji to by łączyło się z API
        // Na teraz akceptujemy każdy klucz który ma format: MOQ-XXXX-XXXX-XXXX
        return preg_match('/^MOQ-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/', $license_key);
    }
    
    public function add_admin_menu() {
        add_submenu_page(
            'woocommerce',
            'MOQ Limits',
            'MOQ Limits',
            'manage_woocommerce',
            'moq-limits',
            array($this, 'settings_page')
        );
    }
    
    public function register_settings() {
        register_setting('moq_limits_settings', 'moq_license_key');
        register_setting('moq_limits_settings', 'moq_language');
        register_setting('moq_limits_settings', 'moq_min_order_amount');
        register_setting('moq_limits_settings', 'moq_max_order_amount');
        register_setting('moq_limits_settings', 'moq_min_order_surcharge_threshold');
        register_setting('moq_limits_settings', 'moq_min_order_surcharge_type');
        register_setting('moq_limits_settings', 'moq_min_order_surcharge_value');
        register_setting('moq_limits_settings', 'moq_min_order_surcharges');
        register_setting('moq_limits_settings', 'moq_product_limits');
        register_setting('moq_limits_settings', 'moq_category_limits');
        register_setting('moq_limits_settings', 'moq_regional_limits');
        register_setting('moq_limits_settings', 'moq_product_surcharges');
        register_setting('moq_limits_settings', 'moq_category_surcharges');
        register_setting('moq_limits_settings', 'moq_regional_surcharges');
    }
    
    public function settings_page() {
        $is_premium = $this->is_premium_active();
        $products = wc_get_products(array('limit' => -1, 'status' => 'publish'));
        $categories = get_terms(array('taxonomy' => 'product_cat', 'hide_empty' => false));
        $product_limits = json_decode(get_option('moq_product_limits', '[]'), true);
        $category_limits = json_decode(get_option('moq_category_limits', '[]'), true);
        $regional_limits = json_decode(get_option('moq_regional_limits', '[]'), true);
        $min_order_surcharges = $this->get_global_surcharge_rules();
        $product_surcharges = json_decode(get_option('moq_product_surcharges', '[]'), true);
        $category_surcharges = json_decode(get_option('moq_category_surcharges', '[]'), true);
        $regional_surcharges = json_decode(get_option('moq_regional_surcharges', '[]'), true);
        
        if (!is_array($product_limits)) $product_limits = array();
        if (!is_array($category_limits)) $category_limits = array();
        if (!is_array($regional_limits)) $regional_limits = array();
        if (!is_array($min_order_surcharges)) $min_order_surcharges = array();
        if (!is_array($product_surcharges)) $product_surcharges = array();
        if (!is_array($category_surcharges)) $category_surcharges = array();
        if (!is_array($regional_surcharges)) $regional_surcharges = array();

        if (empty($min_order_surcharges)) {
            $min_order_surcharges = array(
                array(
                    'threshold' => '',
                    'type' => 'fixed',
                    'value' => '',
                ),
            );
        }
        
        // Pobierz kraje i stany z WooCommerce
        $countries_obj = new WC_Countries();
        $countries = $countries_obj->get_countries();
        $states = $countries_obj->get_states();
        ?>
        <div class="wrap">
            <h1><?php echo esc_html($this->get_text('settings_title')); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('moq_limits_settings');
                do_settings_sections('moq_limits_settings');
                ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="moq_license_key"><?php echo esc_html($this->get_text('license_label')); ?></label>
                        </th>
                        <td>
                            <input type="text" name="moq_license_key" id="moq_license_key" 
                                   value="<?php echo esc_attr(get_option('moq_license_key', '')); ?>" 
                                   class="regular-text" 
                                   placeholder="<?php echo esc_attr($this->get_text('license_placeholder')); ?>">
                            <?php if ($is_premium): ?>
                                <span style="color: #46b450; font-weight: bold;">✓ <?php echo esc_html($this->get_text('license_active')); ?></span>
                            <?php endif; ?>
                            <p class="description"><?php echo esc_html($this->get_text('license_desc')); ?></p>
                            <p class="description"><em>Format: MOQ-XXXX-XXXX-XXXX (np. MOQ-A1B2-C3D4-E5F6)</em></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="moq_language"><?php echo esc_html($this->get_text('language_label')); ?></label>
                        </th>
                        <td>
                            <select name="moq_language" id="moq_language" class="regular-text">
                                <option value="pl" <?php selected(get_option('moq_language', 'pl'), 'pl'); ?>>Polski</option>
                                <option value="en" <?php selected(get_option('moq_language', 'pl'), 'en'); ?>>English</option>
                            </select>
                            <p class="description"><?php echo esc_html($this->get_text('language_desc')); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="moq_min_order_amount"><?php echo esc_html($this->get_text('min_order_label')); ?></label>
                        </th>
                        <td>
                            <input type="number" step="0.01" name="moq_min_order_amount" 
                                   id="moq_min_order_amount" 
                                   value="<?php echo esc_attr(get_option('moq_min_order_amount', '')); ?>" 
                                   class="regular-text"> 
                            <strong><?php echo get_woocommerce_currency_symbol(); ?></strong>
                            <p class="description"><?php echo esc_html($this->get_text('leave_empty')); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="moq_max_order_amount"><?php echo esc_html($this->get_text('max_order_label')); ?></label>
                        </th>
                        <td>
                            <input type="number" step="0.01" name="moq_max_order_amount" 
                                   id="moq_max_order_amount" 
                                   value="<?php echo esc_attr(get_option('moq_max_order_amount', '')); ?>" 
                                   class="regular-text"> 
                            <strong><?php echo get_woocommerce_currency_symbol(); ?></strong>
                            <p class="description"><?php echo esc_html($this->get_text('leave_empty')); ?></p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label><?php echo esc_html($this->get_text('min_order_surcharge_label')); ?></label>
                        </th>
                        <td>
                            <div id="global-surcharges-container">
                                <?php foreach ($min_order_surcharges as $index => $surcharge): ?>
                                <div class="moq-limit-row" data-index="<?php echo $index; ?>">
                                    <div>
                                        <label style="display: block; margin-bottom: 4px;"><?php echo esc_html($this->get_text('surcharge_threshold_label')); ?></label>
                                        <input type="number" step="0.01" name="moq_min_order_surcharges_json[<?php echo $index; ?>][threshold]"
                                               value="<?php echo esc_attr($surcharge['threshold'] ?? ''); ?>"
                                               class="moq-surcharge-threshold"
                                               style="width: 120px;">
                                        <strong><?php echo get_woocommerce_currency_symbol(); ?></strong>
                                    </div>
                                    <div>
                                        <label style="display: block; margin-bottom: 4px;"><?php echo esc_html($this->get_text('surcharge_type_label')); ?></label>
                                        <select name="moq_min_order_surcharges_json[<?php echo $index; ?>][type]" class="moq-surcharge-type" style="width: 140px;">
                                            <option value="fixed" <?php selected($surcharge['type'] ?? 'fixed', 'fixed'); ?>><?php echo esc_html($this->get_text('surcharge_type_fixed')); ?></option>
                                            <option value="percentage" <?php selected($surcharge['type'] ?? 'fixed', 'percentage'); ?>><?php echo esc_html($this->get_text('surcharge_type_percentage')); ?></option>
                                        </select>
                                    </div>
                                    <div>
                                        <label style="display: block; margin-bottom: 4px;"><?php echo esc_html($this->get_text('surcharge_value_label')); ?></label>
                                        <input type="number" step="0.01" name="moq_min_order_surcharges_json[<?php echo $index; ?>][value]"
                                               value="<?php echo esc_attr($surcharge['value'] ?? ''); ?>"
                                               class="moq-surcharge-value"
                                               style="width: 120px;">
                                    </div>
                                    <div style="padding-top: 22px;">
                                        <button type="button" class="button moq-remove-limit"><?php echo esc_html($this->get_text('remove')); ?></button>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" id="add-global-surcharge" class="button" style="margin-top: 10px;">
                                <?php echo esc_html($this->get_text('add_surcharge_rule')); ?>
                            </button>
                            <p class="description"><?php echo esc_html($this->get_text('min_order_surcharge_desc')); ?></p>
                            <input type="hidden" name="moq_min_order_surcharges" id="moq_min_order_surcharges_hidden" value="">
                        </td>
                    </tr>
                    
                    <!-- Premium Features Section -->
                    <tr>
                        <th colspan="2">
                            <h2 style="margin-top: 30px; margin-bottom: 10px;">
                                <?php echo esc_html($this->get_text('premium_feature')); ?>
                                <?php if (!$is_premium): ?>
                                    <span style="background: #ff9800; color: white; padding: 4px 12px; border-radius: 3px; font-size: 12px; font-weight: normal; margin-left: 10px;">PREMIUM</span>
                                <?php endif; ?>
                            </h2>
                        </th>
                    </tr>
                    
                    <?php if (!$is_premium): ?>
                    <tr>
                        <td colspan="2">
                            <div style="background: #fff3cd; border-left: 4px solid #ff9800; padding: 15px; margin: 10px 0;">
                                <strong><?php echo esc_html($this->get_text('premium_required')); ?></strong>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                    
                    <tr>
                        <th scope="row">
                            <label><?php echo esc_html($this->get_text('product_limits_label')); ?></label>
                        </th>
                        <td>
                            <div id="product-limits-container" <?php echo !$is_premium ? 'style="opacity: 0.5; pointer-events: none;"' : ''; ?>>
                                <?php foreach ($product_limits as $index => $limit): ?>
                                <div class="moq-limit-row" data-index="<?php echo $index; ?>">
                                    <select name="moq_product_limits_json[<?php echo $index; ?>][product_id]" class="moq-product-select">
                                        <option value=""><?php echo esc_html($this->get_text('select_product')); ?></option>
                                        <?php foreach ($products as $product): ?>
                                            <option value="<?php echo $product->get_id(); ?>" <?php selected($limit['product_id'], $product->get_id()); ?>>
                                                <?php echo esc_html($product->get_name()); ?> (ID: <?php echo $product->get_id(); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="number" name="moq_product_limits_json[<?php echo $index; ?>][min]" 
                                           placeholder="<?php echo esc_attr($this->get_text('min_quantity')); ?>" 
                                           value="<?php echo esc_attr($limit['min']); ?>" 
                                           style="width: 100px;">
                                    <input type="number" name="moq_product_limits_json[<?php echo $index; ?>][max]" 
                                           placeholder="<?php echo esc_attr($this->get_text('max_quantity')); ?>" 
                                           value="<?php echo esc_attr($limit['max']); ?>" 
                                           style="width: 100px;">
                                    <button type="button" class="button moq-remove-limit"><?php echo esc_html($this->get_text('remove')); ?></button>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if ($is_premium): ?>
                            <button type="button" id="add-product-limit" class="button" style="margin-top: 10px;">
                                <?php echo esc_html($this->get_text('add_product_limit')); ?>
                            </button>
                            <?php endif; ?>
                            <p class="description"><?php echo esc_html($this->get_text('product_limits_desc')); ?></p>
                            
                            <!-- Hidden field to store JSON -->
                            <input type="hidden" name="moq_product_limits" id="moq_product_limits_hidden" value="">
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label><?php echo esc_html($this->get_text('category_limits_label')); ?></label>
                        </th>
                        <td>
                            <div id="category-limits-container" <?php echo !$is_premium ? 'style="opacity: 0.5; pointer-events: none;"' : ''; ?>>
                                <?php foreach ($category_limits as $index => $limit): ?>
                                <div class="moq-limit-row" data-index="<?php echo $index; ?>">
                                    <select name="moq_category_limits_json[<?php echo $index; ?>][category_id]" class="moq-category-select">
                                        <option value=""><?php echo esc_html($this->get_text('select_category')); ?></option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category->term_id; ?>" <?php selected($limit['category_id'], $category->term_id); ?>>
                                                <?php echo esc_html($category->name); ?> (ID: <?php echo $category->term_id; ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="number" name="moq_category_limits_json[<?php echo $index; ?>][min]" 
                                           placeholder="<?php echo esc_attr($this->get_text('min_quantity')); ?>" 
                                           value="<?php echo esc_attr($limit['min']); ?>" 
                                           style="width: 100px;">
                                    <input type="number" name="moq_category_limits_json[<?php echo $index; ?>][max]" 
                                           placeholder="<?php echo esc_attr($this->get_text('max_quantity')); ?>" 
                                           value="<?php echo esc_attr($limit['max']); ?>" 
                                           style="width: 100px;">
                                    <button type="button" class="button moq-remove-limit"><?php echo esc_html($this->get_text('remove')); ?></button>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if ($is_premium): ?>
                            <button type="button" id="add-category-limit" class="button" style="margin-top: 10px;">
                                <?php echo esc_html($this->get_text('add_category_limit')); ?>
                            </button>
                            <?php endif; ?>
                            <p class="description"><?php echo esc_html($this->get_text('category_limits_desc')); ?></p>
                            
                            <!-- Hidden field to store JSON -->
                            <input type="hidden" name="moq_category_limits" id="moq_category_limits_hidden" value="">
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label><?php echo esc_html($this->get_text('regional_limits_label')); ?></label>
                        </th>
                        <td>
                            <div id="regional-limits-container" <?php echo !$is_premium ? 'style="opacity: 0.5; pointer-events: none;"' : ''; ?>>
                                <?php foreach ($regional_limits as $index => $limit): ?>
                                <div class="moq-limit-row moq-regional-row" data-index="<?php echo $index; ?>">
                                    <select name="moq_regional_limits_json[<?php echo $index; ?>][country]" class="moq-country-select" data-index="<?php echo $index; ?>">
                                        <option value=""><?php echo esc_html($this->get_text('any_country')); ?></option>
                                        <?php foreach ($countries as $code => $name): ?>
                                            <option value="<?php echo esc_attr($code); ?>" <?php selected($limit['country'], $code); ?>>
                                                <?php echo esc_html($name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <select name="moq_regional_limits_json[<?php echo $index; ?>][state]" class="moq-state-select" data-index="<?php echo $index; ?>">
                                        <option value=""><?php echo esc_html($this->get_text('any_state')); ?></option>
                                        <?php 
                                        if (!empty($limit['country']) && isset($states[$limit['country']])) {
                                            foreach ($states[$limit['country']] as $state_code => $state_name) {
                                                echo '<option value="' . esc_attr($state_code) . '" ' . selected($limit['state'], $state_code, false) . '>' . esc_html($state_name) . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <input type="text" name="moq_regional_limits_json[<?php echo $index; ?>][postal_code]" 
                                           placeholder="<?php echo esc_attr($this->get_text('postal_code_placeholder')); ?>" 
                                           value="<?php echo esc_attr($limit['postal_code'] ?? ''); ?>" 
                                           style="width: 120px;">
                                    <input type="number" step="0.01" name="moq_regional_limits_json[<?php echo $index; ?>][min]" 
                                           placeholder="<?php echo esc_attr($this->get_text('min_amount')); ?>" 
                                           value="<?php echo esc_attr($limit['min']); ?>" 
                                           style="width: 100px;">
                                    <strong><?php echo get_woocommerce_currency_symbol(); ?></strong>
                                    <input type="number" step="0.01" name="moq_regional_limits_json[<?php echo $index; ?>][max]" 
                                           placeholder="<?php echo esc_attr($this->get_text('max_amount')); ?>" 
                                           value="<?php echo esc_attr($limit['max']); ?>" 
                                           style="width: 100px;">
                                    <strong><?php echo get_woocommerce_currency_symbol(); ?></strong>
                                    <button type="button" class="button moq-remove-limit"><?php echo esc_html($this->get_text('remove')); ?></button>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if ($is_premium): ?>
                            <button type="button" id="add-regional-limit" class="button" style="margin-top: 10px;">
                                <?php echo esc_html($this->get_text('add_regional_limit')); ?>
                            </button>
                            <?php endif; ?>
                            <p class="description"><?php echo esc_html($this->get_text('regional_limits_desc')); ?></p>
                            
                            <!-- Hidden field to store JSON -->
                            <input type="hidden" name="moq_regional_limits" id="moq_regional_limits_hidden" value="">
                            
                            <!-- Hidden data for states -->
                            <script type="text/javascript">
                            var moqStatesData = <?php echo json_encode($states); ?>;
                            </script>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label><?php echo esc_html($this->get_text('product_surcharges_label')); ?></label>
                        </th>
                        <td>
                            <div id="product-surcharges-container" <?php echo !$is_premium ? 'style="opacity: 0.5; pointer-events: none;"' : ''; ?>>
                                <?php foreach ($product_surcharges as $index => $surcharge): ?>
                                <div class="moq-limit-row" data-index="<?php echo $index; ?>">
                                    <select name="moq_product_surcharges_json[<?php echo $index; ?>][product_id]" class="moq-product-select">
                                        <option value=""><?php echo esc_html($this->get_text('select_product')); ?></option>
                                        <?php foreach ($products as $product): ?>
                                            <option value="<?php echo $product->get_id(); ?>" <?php selected($surcharge['product_id'], $product->get_id()); ?>>
                                                <?php echo esc_html($product->get_name()); ?> (ID: <?php echo $product->get_id(); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="number" step="0.01" name="moq_product_surcharges_json[<?php echo $index; ?>][threshold]" 
                                           placeholder="<?php echo esc_attr($this->get_text('threshold_amount')); ?>" 
                                           value="<?php echo esc_attr($surcharge['threshold'] ?? ''); ?>" 
                                           class="moq-surcharge-threshold"
                                           style="width: 110px;">
                                    <strong><?php echo get_woocommerce_currency_symbol(); ?></strong>
                                    <select name="moq_product_surcharges_json[<?php echo $index; ?>][type]" class="moq-surcharge-type" style="width: 130px;">
                                        <option value="fixed" <?php selected($surcharge['type'] ?? 'fixed', 'fixed'); ?>><?php echo esc_html($this->get_text('surcharge_type_fixed')); ?></option>
                                        <option value="percentage" <?php selected($surcharge['type'] ?? 'fixed', 'percentage'); ?>><?php echo esc_html($this->get_text('surcharge_type_percentage')); ?></option>
                                    </select>
                                    <input type="number" step="0.01" name="moq_product_surcharges_json[<?php echo $index; ?>][value]" 
                                           placeholder="<?php echo esc_attr($this->get_text('surcharge_value_label')); ?>" 
                                           value="<?php echo esc_attr($surcharge['value'] ?? ''); ?>" 
                                           class="moq-surcharge-value"
                                           style="width: 110px;">
                                    <button type="button" class="button moq-remove-limit"><?php echo esc_html($this->get_text('remove')); ?></button>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if ($is_premium): ?>
                            <button type="button" id="add-product-surcharge" class="button" style="margin-top: 10px;">
                                <?php echo esc_html($this->get_text('add_product_surcharge')); ?>
                            </button>
                            <?php endif; ?>
                            <p class="description"><?php echo esc_html($this->get_text('product_surcharges_desc')); ?></p>
                            <input type="hidden" name="moq_product_surcharges" id="moq_product_surcharges_hidden" value="">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label><?php echo esc_html($this->get_text('category_surcharges_label')); ?></label>
                        </th>
                        <td>
                            <div id="category-surcharges-container" <?php echo !$is_premium ? 'style="opacity: 0.5; pointer-events: none;"' : ''; ?>>
                                <?php foreach ($category_surcharges as $index => $surcharge): ?>
                                <div class="moq-limit-row" data-index="<?php echo $index; ?>">
                                    <select name="moq_category_surcharges_json[<?php echo $index; ?>][category_id]" class="moq-category-select">
                                        <option value=""><?php echo esc_html($this->get_text('select_category')); ?></option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category->term_id; ?>" <?php selected($surcharge['category_id'], $category->term_id); ?>>
                                                <?php echo esc_html($category->name); ?> (ID: <?php echo $category->term_id; ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="number" step="0.01" name="moq_category_surcharges_json[<?php echo $index; ?>][threshold]" 
                                           placeholder="<?php echo esc_attr($this->get_text('threshold_amount')); ?>" 
                                           value="<?php echo esc_attr($surcharge['threshold'] ?? ''); ?>" 
                                           class="moq-surcharge-threshold"
                                           style="width: 110px;">
                                    <strong><?php echo get_woocommerce_currency_symbol(); ?></strong>
                                    <select name="moq_category_surcharges_json[<?php echo $index; ?>][type]" class="moq-surcharge-type" style="width: 130px;">
                                        <option value="fixed" <?php selected($surcharge['type'] ?? 'fixed', 'fixed'); ?>><?php echo esc_html($this->get_text('surcharge_type_fixed')); ?></option>
                                        <option value="percentage" <?php selected($surcharge['type'] ?? 'fixed', 'percentage'); ?>><?php echo esc_html($this->get_text('surcharge_type_percentage')); ?></option>
                                    </select>
                                    <input type="number" step="0.01" name="moq_category_surcharges_json[<?php echo $index; ?>][value]" 
                                           placeholder="<?php echo esc_attr($this->get_text('surcharge_value_label')); ?>" 
                                           value="<?php echo esc_attr($surcharge['value'] ?? ''); ?>" 
                                           class="moq-surcharge-value"
                                           style="width: 110px;">
                                    <button type="button" class="button moq-remove-limit"><?php echo esc_html($this->get_text('remove')); ?></button>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if ($is_premium): ?>
                            <button type="button" id="add-category-surcharge" class="button" style="margin-top: 10px;">
                                <?php echo esc_html($this->get_text('add_category_surcharge')); ?>
                            </button>
                            <?php endif; ?>
                            <p class="description"><?php echo esc_html($this->get_text('category_surcharges_desc')); ?></p>
                            <input type="hidden" name="moq_category_surcharges" id="moq_category_surcharges_hidden" value="">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label><?php echo esc_html($this->get_text('regional_surcharges_label')); ?></label>
                        </th>
                        <td>
                            <div id="regional-surcharges-container" <?php echo !$is_premium ? 'style="opacity: 0.5; pointer-events: none;"' : ''; ?>>
                                <?php foreach ($regional_surcharges as $index => $surcharge): ?>
                                <div class="moq-limit-row moq-regional-row" data-index="<?php echo $index; ?>">
                                    <select name="moq_regional_surcharges_json[<?php echo $index; ?>][country]" class="moq-country-select" data-index="surcharge-<?php echo $index; ?>">
                                        <option value=""><?php echo esc_html($this->get_text('any_country')); ?></option>
                                        <?php foreach ($countries as $code => $name): ?>
                                            <option value="<?php echo esc_attr($code); ?>" <?php selected($surcharge['country'] ?? '', $code); ?>>
                                                <?php echo esc_html($name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <select name="moq_regional_surcharges_json[<?php echo $index; ?>][state]" class="moq-state-select" data-index="surcharge-<?php echo $index; ?>">
                                        <option value=""><?php echo esc_html($this->get_text('any_state')); ?></option>
                                        <?php 
                                        if (!empty($surcharge['country']) && isset($states[$surcharge['country']])) {
                                            foreach ($states[$surcharge['country']] as $state_code => $state_name) {
                                                echo '<option value="' . esc_attr($state_code) . '" ' . selected($surcharge['state'] ?? '', $state_code, false) . '>' . esc_html($state_name) . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <input type="text" name="moq_regional_surcharges_json[<?php echo $index; ?>][postal_code]" 
                                           placeholder="<?php echo esc_attr($this->get_text('postal_code_placeholder')); ?>" 
                                           value="<?php echo esc_attr($surcharge['postal_code'] ?? ''); ?>" 
                                           style="width: 120px;">
                                    <input type="number" step="0.01" name="moq_regional_surcharges_json[<?php echo $index; ?>][threshold]" 
                                           placeholder="<?php echo esc_attr($this->get_text('threshold_amount')); ?>" 
                                           value="<?php echo esc_attr($surcharge['threshold'] ?? ''); ?>" 
                                           class="moq-surcharge-threshold"
                                           style="width: 110px;">
                                    <strong><?php echo get_woocommerce_currency_symbol(); ?></strong>
                                    <select name="moq_regional_surcharges_json[<?php echo $index; ?>][type]" class="moq-surcharge-type" style="width: 130px;">
                                        <option value="fixed" <?php selected($surcharge['type'] ?? 'fixed', 'fixed'); ?>><?php echo esc_html($this->get_text('surcharge_type_fixed')); ?></option>
                                        <option value="percentage" <?php selected($surcharge['type'] ?? 'fixed', 'percentage'); ?>><?php echo esc_html($this->get_text('surcharge_type_percentage')); ?></option>
                                    </select>
                                    <input type="number" step="0.01" name="moq_regional_surcharges_json[<?php echo $index; ?>][value]" 
                                           placeholder="<?php echo esc_attr($this->get_text('surcharge_value_label')); ?>" 
                                           value="<?php echo esc_attr($surcharge['value'] ?? ''); ?>" 
                                           class="moq-surcharge-value"
                                           style="width: 110px;">
                                    <button type="button" class="button moq-remove-limit"><?php echo esc_html($this->get_text('remove')); ?></button>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if ($is_premium): ?>
                            <button type="button" id="add-regional-surcharge" class="button" style="margin-top: 10px;">
                                <?php echo esc_html($this->get_text('add_regional_surcharge')); ?>
                            </button>
                            <?php endif; ?>
                            <p class="description"><?php echo esc_html($this->get_text('regional_surcharges_desc')); ?></p>
                            <input type="hidden" name="moq_regional_surcharges" id="moq_regional_surcharges_hidden" value="">
                        </td>
                    </tr>
                </table>
                
                <script type="text/javascript">
                jQuery(document).ready(function($) {
                    var globalSurchargeIndex = <?php echo count($min_order_surcharges); ?>;
                    var globalSurchargeTemplate = `
                        <div class="moq-limit-row" data-index="${globalSurchargeIndex}">
                            <div>
                                <label style="display: block; margin-bottom: 4px;"><?php echo esc_js($this->get_text('surcharge_threshold_label')); ?></label>
                                <input type="number" step="0.01" name="moq_min_order_surcharges_json[${globalSurchargeIndex}][threshold]"
                                       class="moq-surcharge-threshold"
                                       style="width: 120px;">
                                <strong><?php echo get_woocommerce_currency_symbol(); ?></strong>
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 4px;"><?php echo esc_js($this->get_text('surcharge_type_label')); ?></label>
                                <select name="moq_min_order_surcharges_json[${globalSurchargeIndex}][type]" class="moq-surcharge-type" style="width: 140px;">
                                    <option value="fixed"><?php echo esc_js($this->get_text('surcharge_type_fixed')); ?></option>
                                    <option value="percentage"><?php echo esc_js($this->get_text('surcharge_type_percentage')); ?></option>
                                </select>
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 4px;"><?php echo esc_js($this->get_text('surcharge_value_label')); ?></label>
                                <input type="number" step="0.01" name="moq_min_order_surcharges_json[${globalSurchargeIndex}][value]"
                                       class="moq-surcharge-value"
                                       style="width: 120px;">
                            </div>
                            <div style="padding-top: 22px;">
                                <button type="button" class="button moq-remove-limit"><?php echo esc_js($this->get_text('remove')); ?></button>
                            </div>
                        </div>
                    `;

                    // Template for products
                    var productIndex = <?php echo count($product_limits); ?>;
                    var productTemplate = `
                        <div class="moq-limit-row" data-index="${productIndex}">
                            <select name="moq_product_limits_json[${productIndex}][product_id]" class="moq-product-select">
                                <option value=""><?php echo esc_js($this->get_text('select_product')); ?></option>
                                <?php foreach ($products as $product): ?>
                                    <option value="<?php echo $product->get_id(); ?>">
                                        <?php echo esc_js($product->get_name()); ?> (ID: <?php echo $product->get_id(); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="number" name="moq_product_limits_json[${productIndex}][min]" 
                                   placeholder="<?php echo esc_attr($this->get_text('min_quantity')); ?>" 
                                   style="width: 100px;">
                            <input type="number" name="moq_product_limits_json[${productIndex}][max]" 
                                   placeholder="<?php echo esc_attr($this->get_text('max_quantity')); ?>" 
                                   style="width: 100px;">
                            <button type="button" class="button moq-remove-limit"><?php echo esc_js($this->get_text('remove')); ?></button>
                        </div>
                    `;
                    
                    // Template for categories
                    var categoryIndex = <?php echo count($category_limits); ?>;
                    var categoryTemplate = `
                        <div class="moq-limit-row" data-index="${categoryIndex}">
                            <select name="moq_category_limits_json[${categoryIndex}][category_id]" class="moq-category-select">
                                <option value=""><?php echo esc_js($this->get_text('select_category')); ?></option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category->term_id; ?>">
                                        <?php echo esc_js($category->name); ?> (ID: <?php echo $category->term_id; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="number" name="moq_category_limits_json[${categoryIndex}][min]" 
                                   placeholder="<?php echo esc_attr($this->get_text('min_quantity')); ?>" 
                                   style="width: 100px;">
                            <input type="number" name="moq_category_limits_json[${categoryIndex}][max]" 
                                   placeholder="<?php echo esc_attr($this->get_text('max_quantity')); ?>" 
                                   style="width: 100px;">
                            <button type="button" class="button moq-remove-limit"><?php echo esc_js($this->get_text('remove')); ?></button>
                        </div>
                    `;
                    
                    // Template for regional limits
                    var regionalIndex = <?php echo count($regional_limits); ?>;
                    var regionalTemplate = `
                        <div class="moq-limit-row moq-regional-row" data-index="${regionalIndex}">
                            <select name="moq_regional_limits_json[${regionalIndex}][country]" class="moq-country-select" data-index="${regionalIndex}">
                                <option value=""><?php echo esc_js($this->get_text('any_country')); ?></option>
                                <?php foreach ($countries as $code => $name): ?>
                                    <option value="<?php echo esc_js($code); ?>"><?php echo esc_js($name); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select name="moq_regional_limits_json[${regionalIndex}][state]" class="moq-state-select" data-index="${regionalIndex}">
                                <option value=""><?php echo esc_js($this->get_text('any_state')); ?></option>
                            </select>
                            <input type="text" name="moq_regional_limits_json[${regionalIndex}][postal_code]" 
                                   placeholder="<?php echo esc_attr($this->get_text('postal_code_placeholder')); ?>" 
                                   style="width: 120px;">
                            <input type="number" step="0.01" name="moq_regional_limits_json[${regionalIndex}][min]" 
                                   placeholder="<?php echo esc_attr($this->get_text('min_amount')); ?>" 
                                   style="width: 100px;">
                            <strong><?php echo get_woocommerce_currency_symbol(); ?></strong>
                            <input type="number" step="0.01" name="moq_regional_limits_json[${regionalIndex}][max]" 
                                   placeholder="<?php echo esc_attr($this->get_text('max_amount')); ?>" 
                                   style="width: 100px;">
                            <strong><?php echo get_woocommerce_currency_symbol(); ?></strong>
                            <button type="button" class="button moq-remove-limit"><?php echo esc_js($this->get_text('remove')); ?></button>
                        </div>
                    `;

                    // Template for product surcharges
                    var productSurchargeIndex = <?php echo count($product_surcharges); ?>;
                    var productSurchargeTemplate = `
                        <div class="moq-limit-row" data-index="${productSurchargeIndex}">
                            <select name="moq_product_surcharges_json[${productSurchargeIndex}][product_id]" class="moq-product-select">
                                <option value=""><?php echo esc_js($this->get_text('select_product')); ?></option>
                                <?php foreach ($products as $product): ?>
                                    <option value="<?php echo $product->get_id(); ?>">
                                        <?php echo esc_js($product->get_name()); ?> (ID: <?php echo $product->get_id(); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="number" step="0.01" name="moq_product_surcharges_json[${productSurchargeIndex}][threshold]" 
                                   placeholder="<?php echo esc_attr($this->get_text('threshold_amount')); ?>" 
                                   class="moq-surcharge-threshold"
                                   style="width: 110px;">
                            <strong><?php echo get_woocommerce_currency_symbol(); ?></strong>
                            <select name="moq_product_surcharges_json[${productSurchargeIndex}][type]" class="moq-surcharge-type" style="width: 130px;">
                                <option value="fixed"><?php echo esc_js($this->get_text('surcharge_type_fixed')); ?></option>
                                <option value="percentage"><?php echo esc_js($this->get_text('surcharge_type_percentage')); ?></option>
                            </select>
                            <input type="number" step="0.01" name="moq_product_surcharges_json[${productSurchargeIndex}][value]" 
                                   placeholder="<?php echo esc_attr($this->get_text('surcharge_value_label')); ?>" 
                                   class="moq-surcharge-value"
                                   style="width: 110px;">
                            <button type="button" class="button moq-remove-limit"><?php echo esc_js($this->get_text('remove')); ?></button>
                        </div>
                    `;

                    // Template for category surcharges
                    var categorySurchargeIndex = <?php echo count($category_surcharges); ?>;
                    var categorySurchargeTemplate = `
                        <div class="moq-limit-row" data-index="${categorySurchargeIndex}">
                            <select name="moq_category_surcharges_json[${categorySurchargeIndex}][category_id]" class="moq-category-select">
                                <option value=""><?php echo esc_js($this->get_text('select_category')); ?></option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category->term_id; ?>">
                                        <?php echo esc_js($category->name); ?> (ID: <?php echo $category->term_id; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="number" step="0.01" name="moq_category_surcharges_json[${categorySurchargeIndex}][threshold]" 
                                   placeholder="<?php echo esc_attr($this->get_text('threshold_amount')); ?>" 
                                   class="moq-surcharge-threshold"
                                   style="width: 110px;">
                            <strong><?php echo get_woocommerce_currency_symbol(); ?></strong>
                            <select name="moq_category_surcharges_json[${categorySurchargeIndex}][type]" class="moq-surcharge-type" style="width: 130px;">
                                <option value="fixed"><?php echo esc_js($this->get_text('surcharge_type_fixed')); ?></option>
                                <option value="percentage"><?php echo esc_js($this->get_text('surcharge_type_percentage')); ?></option>
                            </select>
                            <input type="number" step="0.01" name="moq_category_surcharges_json[${categorySurchargeIndex}][value]" 
                                   placeholder="<?php echo esc_attr($this->get_text('surcharge_value_label')); ?>" 
                                   class="moq-surcharge-value"
                                   style="width: 110px;">
                            <button type="button" class="button moq-remove-limit"><?php echo esc_js($this->get_text('remove')); ?></button>
                        </div>
                    `;

                    // Template for regional surcharges
                    var regionalSurchargeIndex = <?php echo count($regional_surcharges); ?>;
                    var regionalSurchargeTemplate = `
                        <div class="moq-limit-row moq-regional-row" data-index="${regionalSurchargeIndex}">
                            <select name="moq_regional_surcharges_json[${regionalSurchargeIndex}][country]" class="moq-country-select" data-index="surcharge-${regionalSurchargeIndex}">
                                <option value=""><?php echo esc_js($this->get_text('any_country')); ?></option>
                                <?php foreach ($countries as $code => $name): ?>
                                    <option value="<?php echo esc_js($code); ?>"><?php echo esc_js($name); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select name="moq_regional_surcharges_json[${regionalSurchargeIndex}][state]" class="moq-state-select" data-index="surcharge-${regionalSurchargeIndex}">
                                <option value=""><?php echo esc_js($this->get_text('any_state')); ?></option>
                            </select>
                            <input type="text" name="moq_regional_surcharges_json[${regionalSurchargeIndex}][postal_code]" 
                                   placeholder="<?php echo esc_attr($this->get_text('postal_code_placeholder')); ?>" 
                                   style="width: 120px;">
                            <input type="number" step="0.01" name="moq_regional_surcharges_json[${regionalSurchargeIndex}][threshold]" 
                                   placeholder="<?php echo esc_attr($this->get_text('threshold_amount')); ?>" 
                                   class="moq-surcharge-threshold"
                                   style="width: 110px;">
                            <strong><?php echo get_woocommerce_currency_symbol(); ?></strong>
                            <select name="moq_regional_surcharges_json[${regionalSurchargeIndex}][type]" class="moq-surcharge-type" style="width: 130px;">
                                <option value="fixed"><?php echo esc_js($this->get_text('surcharge_type_fixed')); ?></option>
                                <option value="percentage"><?php echo esc_js($this->get_text('surcharge_type_percentage')); ?></option>
                            </select>
                            <input type="number" step="0.01" name="moq_regional_surcharges_json[${regionalSurchargeIndex}][value]" 
                                   placeholder="<?php echo esc_attr($this->get_text('surcharge_value_label')); ?>" 
                                   class="moq-surcharge-value"
                                   style="width: 110px;">
                            <button type="button" class="button moq-remove-limit"><?php echo esc_js($this->get_text('remove')); ?></button>
                        </div>
                    `;
                    
                    $('#add-global-surcharge').on('click', function() {
                        var newRow = globalSurchargeTemplate.replace(/\$\{globalSurchargeIndex\}/g, globalSurchargeIndex);
                        $('#global-surcharges-container').append(newRow);
                        globalSurchargeIndex++;
                    });

                    // Add product limit
                    $('#add-product-limit').on('click', function() {
                        var newRow = productTemplate.replace(/\$\{productIndex\}/g, productIndex);
                        $('#product-limits-container').append(newRow);
                        productIndex++;
                    });
                    
                    // Add category limit
                    $('#add-category-limit').on('click', function() {
                        var newRow = categoryTemplate.replace(/\$\{categoryIndex\}/g, categoryIndex);
                        $('#category-limits-container').append(newRow);
                        categoryIndex++;
                    });
                    
                    // Add regional limit
                    $('#add-regional-limit').on('click', function() {
                        var newRow = regionalTemplate.replace(/\$\{regionalIndex\}/g, regionalIndex);
                        $('#regional-limits-container').append(newRow);
                        regionalIndex++;
                    });

                    // Add product surcharge
                    $('#add-product-surcharge').on('click', function() {
                        var newRow = productSurchargeTemplate.replace(/\$\{productSurchargeIndex\}/g, productSurchargeIndex);
                        $('#product-surcharges-container').append(newRow);
                        productSurchargeIndex++;
                    });

                    // Add category surcharge
                    $('#add-category-surcharge').on('click', function() {
                        var newRow = categorySurchargeTemplate.replace(/\$\{categorySurchargeIndex\}/g, categorySurchargeIndex);
                        $('#category-surcharges-container').append(newRow);
                        categorySurchargeIndex++;
                    });

                    // Add regional surcharge
                    $('#add-regional-surcharge').on('click', function() {
                        var newRow = regionalSurchargeTemplate.replace(/\$\{regionalSurchargeIndex\}/g, regionalSurchargeIndex);
                        $('#regional-surcharges-container').append(newRow);
                        regionalSurchargeIndex++;
                    });
                    
                    // Handle country change - update states dropdown
                    $(document).on('change', '.moq-country-select', function() {
                        var country = $(this).val();
                        var stateSelect = $(this).closest('.moq-limit-row').find('.moq-state-select').first();
                        
                        stateSelect.empty();
                        stateSelect.append('<option value=""><?php echo esc_js($this->get_text('any_state')); ?></option>');
                        
                        if (country && moqStatesData[country]) {
                            $.each(moqStatesData[country], function(code, name) {
                                stateSelect.append('<option value="' + code + '">' + name + '</option>');
                            });
                        }
                    });
                    
                    // Remove limit
                    $(document).on('click', '.moq-remove-limit', function() {
                        $(this).closest('.moq-limit-row').remove();
                    });
                    
                    // Before submit, collect data to JSON
                    $('form').on('submit', function() {
                        var globalSurcharges = [];
                        $('#global-surcharges-container .moq-limit-row').each(function() {
                            var threshold = $(this).find('.moq-surcharge-threshold').val();
                            var type = $(this).find('.moq-surcharge-type').val();
                            var value = $(this).find('.moq-surcharge-value').val();

                            if (threshold && value) {
                                globalSurcharges.push({
                                    threshold: threshold || '',
                                    type: type || 'fixed',
                                    value: value || ''
                                });
                            }
                        });
                        $('#moq_min_order_surcharges_hidden').val(JSON.stringify(globalSurcharges));

                        var productLimits = [];
                        $('#product-limits-container .moq-limit-row').each(function() {
                            var productId = $(this).find('.moq-product-select').val();
                            var min = $(this).find('input[placeholder*="Min"]').val();
                            var max = $(this).find('input[placeholder*="Max"]').val();
                            
                            if (productId) {
                                productLimits.push({
                                    product_id: productId,
                                    min: min || '',
                                    max: max || ''
                                });
                            }
                        });
                        $('#moq_product_limits_hidden').val(JSON.stringify(productLimits));
                        
                        var categoryLimits = [];
                        $('#category-limits-container .moq-limit-row').each(function() {
                            var categoryId = $(this).find('.moq-category-select').val();
                            var min = $(this).find('input[placeholder*="Min"]').val();
                            var max = $(this).find('input[placeholder*="Max"]').val();
                            
                            if (categoryId) {
                                categoryLimits.push({
                                    category_id: categoryId,
                                    min: min || '',
                                    max: max || ''
                                });
                            }
                        });
                        $('#moq_category_limits_hidden').val(JSON.stringify(categoryLimits));
                        
                        var regionalLimits = [];
                        $('#regional-limits-container .moq-limit-row').each(function() {
                            var country = $(this).find('.moq-country-select').val();
                            var state = $(this).find('.moq-state-select').val();
                            var postalCode = $(this).find('input[name*="postal_code"]').val();
                            var min = $(this).find('input[name*="[min]"]').val();
                            var max = $(this).find('input[name*="[max]"]').val();
                            
                            if (country || state || postalCode) {
                                regionalLimits.push({
                                    country: country || '',
                                    state: state || '',
                                    postal_code: postalCode || '',
                                    min: min || '',
                                    max: max || ''
                                });
                            }
                        });
                        $('#moq_regional_limits_hidden').val(JSON.stringify(regionalLimits));

                        var productSurcharges = [];
                        $('#product-surcharges-container .moq-limit-row').each(function() {
                            var productId = $(this).find('.moq-product-select').val();
                            var threshold = $(this).find('.moq-surcharge-threshold').val();
                            var type = $(this).find('.moq-surcharge-type').val();
                            var value = $(this).find('.moq-surcharge-value').val();

                            if (productId) {
                                productSurcharges.push({
                                    product_id: productId,
                                    threshold: threshold || '',
                                    type: type || 'fixed',
                                    value: value || ''
                                });
                            }
                        });
                        $('#moq_product_surcharges_hidden').val(JSON.stringify(productSurcharges));

                        var categorySurcharges = [];
                        $('#category-surcharges-container .moq-limit-row').each(function() {
                            var categoryId = $(this).find('.moq-category-select').val();
                            var threshold = $(this).find('.moq-surcharge-threshold').val();
                            var type = $(this).find('.moq-surcharge-type').val();
                            var value = $(this).find('.moq-surcharge-value').val();

                            if (categoryId) {
                                categorySurcharges.push({
                                    category_id: categoryId,
                                    threshold: threshold || '',
                                    type: type || 'fixed',
                                    value: value || ''
                                });
                            }
                        });
                        $('#moq_category_surcharges_hidden').val(JSON.stringify(categorySurcharges));

                        var regionalSurcharges = [];
                        $('#regional-surcharges-container .moq-limit-row').each(function() {
                            var country = $(this).find('.moq-country-select').val();
                            var state = $(this).find('.moq-state-select').val();
                            var postalCode = $(this).find('input[name*="postal_code"]').val();
                            var threshold = $(this).find('.moq-surcharge-threshold').val();
                            var type = $(this).find('.moq-surcharge-type').val();
                            var value = $(this).find('.moq-surcharge-value').val();

                            if (country || state || postalCode) {
                                regionalSurcharges.push({
                                    country: country || '',
                                    state: state || '',
                                    postal_code: postalCode || '',
                                    threshold: threshold || '',
                                    type: type || 'fixed',
                                    value: value || ''
                                });
                            }
                        });
                        $('#moq_regional_surcharges_hidden').val(JSON.stringify(regionalSurcharges));
                    });
                });
                </script>
                
                <style>
                .moq-limit-row {
                    margin-bottom: 10px;
                    display: flex;
                    gap: 10px;
                    align-items: center;
                }
                .moq-limit-row select {
                    min-width: 300px;
                }
                .moq-regional-row select {
                    min-width: 200px;
                }
                .moq-regional-row .moq-state-select {
                    min-width: 180px;
                }
                </style>
                
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
    
    public function validate_cart() {
        if (!WC()->cart) {
            return;
        }
        
        $cart_total = WC()->cart->get_subtotal();
        $customer = WC()->customer;
        
        // Premium features - sprawdź limity regionalne najpierw
        if ($this->is_premium_active()) {
            $regional_limits_json = get_option('moq_regional_limits', '[]');
            $regional_limits = json_decode($regional_limits_json, true);
            
            if (is_array($regional_limits) && !empty($regional_limits)) {
                $customer_country = $customer->get_shipping_country() ?: $customer->get_billing_country();
                $customer_state = $customer->get_shipping_state() ?: $customer->get_billing_state();
                $customer_postcode = $customer->get_shipping_postcode() ?: $customer->get_billing_postcode();
                
                // Znajdź najbardziej specyficzny limit (najpierw kod pocztowy, potem stan, potem kraj)
                $matched_limit = null;
                $match_priority = 0; // 1 = kraj, 2 = stan, 3 = kod pocztowy
                
                foreach ($regional_limits as $limit) {
                    $matches = true;
                    $current_priority = 0;
                    
                    // Sprawdź kraj
                    if (!empty($limit['country'])) {
                        if ($limit['country'] !== $customer_country) {
                            continue;
                        }
                        $current_priority = 1;
                    }
                    
                    // Sprawdź stan
                    if (!empty($limit['state'])) {
                        if ($limit['state'] !== $customer_state) {
                            continue;
                        }
                        $current_priority = 2;
                    }
                    
                    // Sprawdź kod pocztowy
                    if (!empty($limit['postal_code'])) {
                        if (strpos($customer_postcode, $limit['postal_code']) !== 0) {
                            continue;
                        }
                        $current_priority = 3;
                    }
                    
                    // Jeśli znaleźliśmy bardziej specyficzny limit, użyj go
                    if ($current_priority > $match_priority) {
                        $matched_limit = $limit;
                        $match_priority = $current_priority;
                    }
                }
                
                // Zastosuj znaleziony regionalny limit
                if ($matched_limit) {
                    $region_name = '';
                    if (!empty($matched_limit['postal_code'])) {
                        $region_name = $matched_limit['postal_code'];
                    } elseif (!empty($matched_limit['state'])) {
                        $states = WC()->countries->get_states($customer_country);
                        $region_name = isset($states[$customer_state]) ? $states[$customer_state] : $customer_state;
                    } elseif (!empty($matched_limit['country'])) {
                        $countries = WC()->countries->get_countries();
                        $region_name = isset($countries[$customer_country]) ? $countries[$customer_country] : $customer_country;
                    }
                    
                    if (!empty($matched_limit['min']) && $cart_total < floatval($matched_limit['min'])) {
                        wc_add_notice(
                            sprintf(
                                $this->get_text('regional_min_error'),
                                $region_name,
                                wc_price($matched_limit['min']),
                                wc_price($cart_total)
                            ),
                            'error'
                        );
                        return; // Nie sprawdzaj globalnych limitów jeśli regionalny zadziałał
                    }
                    
                    if (!empty($matched_limit['max']) && $cart_total > floatval($matched_limit['max'])) {
                        wc_add_notice(
                            sprintf(
                                $this->get_text('regional_max_error'),
                                $region_name,
                                wc_price($matched_limit['max']),
                                wc_price($cart_total)
                            ),
                            'error'
                        );
                        return; // Nie sprawdzaj globalnych limitów jeśli regionalny zadziałał
                    }
                }
            }
        }
        
        // Sprawdź globalną minimalną kwotę zamówienia (tylko jeśli nie było regionalnego limitu)
        $min_amount = get_option('moq_min_order_amount', '');
        if (!empty($min_amount) && $cart_total < floatval($min_amount)) {
            wc_add_notice(
                sprintf(
                    $this->get_text('min_order_error'),
                    wc_price($min_amount),
                    wc_price($cart_total)
                ),
                'error'
            );
        }
        
        // Sprawdź globalną maksymalną kwotę zamówienia (tylko jeśli nie było regionalnego limitu)
        $max_amount = get_option('moq_max_order_amount', '');
        if (!empty($max_amount) && $cart_total > floatval($max_amount)) {
            wc_add_notice(
                sprintf(
                    $this->get_text('max_order_error'),
                    wc_price($max_amount),
                    wc_price($cart_total)
                ),
                'error'
            );
        }
        
        // Premium features - tylko jeśli licencja jest aktywna
        if (!$this->is_premium_active()) {
            return;
        }
        
        // Pobierz limity produktów i kategorii z JSON
        $product_limits_json = get_option('moq_product_limits', '[]');
        $category_limits_json = get_option('moq_category_limits', '[]');
        
        $product_limits = json_decode($product_limits_json, true);
        $category_limits = json_decode($category_limits_json, true);
        
        if (!is_array($product_limits)) $product_limits = array();
        if (!is_array($category_limits)) $category_limits = array();
        
        // Konwertuj do stareg formatu dla kompatybilności
        $product_limits_converted = array();
        foreach ($product_limits as $limit) {
            if (isset($limit['product_id'])) {
                $product_limits_converted[$limit['product_id']] = array(
                    'min' => $limit['min'] ?? '',
                    'max' => $limit['max'] ?? ''
                );
            }
        }
        
        $category_limits_converted = array();
        foreach ($category_limits as $limit) {
            if (isset($limit['category_id'])) {
                $category_limits_converted[$limit['category_id']] = array(
                    'min' => $limit['min'] ?? '',
                    'max' => $limit['max'] ?? ''
                );
            }
        }
        
        // Sprawdź ilości produktów i kategorii
        $category_quantities = array();
        
        foreach (WC()->cart->get_cart() as $cart_item) {
            $product_id = $cart_item['product_id'];
            $quantity = $cart_item['quantity'];
            $product = $cart_item['data'];
            
            // Sprawdź limity dla konkretnego produktu
            if (isset($product_limits_converted[$product_id])) {
                $limits = $product_limits_converted[$product_id];
                
                if (!empty($limits['min']) && $quantity < intval($limits['min'])) {
                    wc_add_notice(
                        sprintf(
                            $this->get_text('product_min_error'),
                            $product->get_name(),
                            $limits['min'],
                            $quantity
                        ),
                        'error'
                    );
                }
                
                if (!empty($limits['max']) && $quantity > intval($limits['max'])) {
                    wc_add_notice(
                        sprintf(
                            $this->get_text('product_max_error'),
                            $product->get_name(),
                            $limits['max'],
                            $quantity
                        ),
                        'error'
                    );
                }
            }
            
            // Zlicz ilości dla kategorii
            $categories = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'ids'));
            foreach ($categories as $cat_id) {
                if (!isset($category_quantities[$cat_id])) {
                    $category_quantities[$cat_id] = 0;
                }
                $category_quantities[$cat_id] += $quantity;
            }
        }
        
        // Sprawdź limity dla kategorii
        foreach ($category_quantities as $cat_id => $total_quantity) {
            if (isset($category_limits_converted[$cat_id])) {
                $limits = $category_limits_converted[$cat_id];
                $category = get_term($cat_id, 'product_cat');
                $cat_name = $category ? $category->name : "ID: $cat_id";
                
                if (!empty($limits['min']) && $total_quantity < intval($limits['min'])) {
                    wc_add_notice(
                        sprintf(
                            $this->get_text('category_min_error'),
                            $cat_name,
                            $limits['min'],
                            $total_quantity
                        ),
                        'error'
                    );
                }
                
                if (!empty($limits['max']) && $total_quantity > intval($limits['max'])) {
                    wc_add_notice(
                        sprintf(
                            $this->get_text('category_max_error'),
                            $cat_name,
                            $limits['max'],
                            $total_quantity
                        ),
                        'error'
                    );
                }
            }
        }
    }

    public function apply_minimum_order_surcharge($cart) {
        if ((is_admin() && !defined('DOING_AJAX')) || !$cart || $cart->is_empty()) {
            return;
        }

        $cart_total = (float) $cart->get_subtotal();
        if ($cart_total <= 0) {
            return;
        }

        $candidate = $this->get_applicable_surcharge_candidate($cart, $cart_total);
        if (!$candidate || empty($candidate['amount'])) {
            return;
        }

        $cart->add_fee($this->get_text('small_order_surcharge_fee'), $candidate['amount'], false);
    }

    private function get_applicable_surcharge_candidate($cart, $cart_total) {
        $best_candidate = $this->get_best_surcharge_candidate_from_rules(
            $this->get_global_surcharge_rules(),
            $cart_total,
            10
        );

        if (!$this->is_premium_active()) {
            return $best_candidate;
        }

        $product_ids = array();
        $category_ids = array();
        foreach ($cart->get_cart() as $cart_item) {
            $product_id = (int) $cart_item['product_id'];
            $product_ids[] = $product_id;

            $product_categories = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'ids'));
            if (!is_wp_error($product_categories)) {
                $category_ids = array_merge($category_ids, array_map('intval', $product_categories));
            }
        }

        $product_ids = array_values(array_unique($product_ids));
        $category_ids = array_values(array_unique($category_ids));

        $best_candidate = $this->pick_better_surcharge_candidate(
            $best_candidate,
            $this->get_best_targeted_surcharge_candidate(
                get_option('moq_product_surcharges', '[]'),
                'product_id',
                $product_ids,
                $cart_total,
                30
            )
        );

        $best_candidate = $this->pick_better_surcharge_candidate(
            $best_candidate,
            $this->get_best_targeted_surcharge_candidate(
                get_option('moq_category_surcharges', '[]'),
                'category_id',
                $category_ids,
                $cart_total,
                20
            )
        );

        $customer_country = WC()->customer ? (WC()->customer->get_shipping_country() ?: WC()->customer->get_billing_country()) : '';
        $customer_state = WC()->customer ? (WC()->customer->get_shipping_state() ?: WC()->customer->get_billing_state()) : '';
        $customer_postcode = WC()->customer ? (WC()->customer->get_shipping_postcode() ?: WC()->customer->get_billing_postcode()) : '';

        $best_candidate = $this->pick_better_surcharge_candidate(
            $best_candidate,
            $this->get_best_regional_surcharge_candidate(
                get_option('moq_regional_surcharges', '[]'),
                $customer_country,
                $customer_state,
                $customer_postcode,
                $cart_total
            )
        );

        return $best_candidate;
    }

    private function get_global_surcharge_rules() {
        $rules = json_decode(get_option('moq_min_order_surcharges', '[]'), true);
        if (is_array($rules) && !empty($rules)) {
            return $rules;
        }

        $legacy_threshold = get_option('moq_min_order_surcharge_threshold', '');
        $legacy_value = get_option('moq_min_order_surcharge_value', '');
        if ($legacy_threshold !== '' && $legacy_value !== '') {
            return array(
                array(
                    'threshold' => $legacy_threshold,
                    'type' => get_option('moq_min_order_surcharge_type', 'fixed'),
                    'value' => $legacy_value,
                ),
            );
        }

        return array();
    }

    private function get_best_surcharge_candidate_from_rules($rules, $cart_total, $priority) {
        if (!is_array($rules) || empty($rules)) {
            return null;
        }

        $best_candidate = null;
        foreach ($rules as $rule) {
            $candidate = $this->build_surcharge_candidate(
                $rule['threshold'] ?? '',
                $rule['type'] ?? 'fixed',
                $rule['value'] ?? '',
                $cart_total,
                $priority
            );

            $best_candidate = $this->pick_better_surcharge_candidate($best_candidate, $candidate);
        }

        return $best_candidate;
    }

    private function get_best_targeted_surcharge_candidate($rules_json, $key, $matched_ids, $cart_total, $priority) {
        if (empty($matched_ids)) {
            return null;
        }

        $rules = json_decode($rules_json, true);
        if (!is_array($rules)) {
            return null;
        }

        $best_candidate = null;
        foreach ($rules as $rule) {
            if (empty($rule[$key]) || !in_array((int) $rule[$key], $matched_ids, true)) {
                continue;
            }

            $candidate = $this->build_surcharge_candidate(
                $rule['threshold'] ?? '',
                $rule['type'] ?? 'fixed',
                $rule['value'] ?? '',
                $cart_total,
                $priority
            );

            $best_candidate = $this->pick_better_surcharge_candidate($best_candidate, $candidate);
        }

        return $best_candidate;
    }

    private function get_best_regional_surcharge_candidate($rules_json, $country, $state, $postcode, $cart_total) {
        $rules = json_decode($rules_json, true);
        if (!is_array($rules)) {
            return null;
        }

        $best_candidate = null;
        foreach ($rules as $rule) {
            if (empty($rule['country']) && empty($rule['state']) && empty($rule['postal_code'])) {
                continue;
            }

            $specificity = 0;

            if (!empty($rule['country'])) {
                if ($rule['country'] !== $country) {
                    continue;
                }
                $specificity = 1;
            }

            if (!empty($rule['state'])) {
                if ($rule['state'] !== $state) {
                    continue;
                }
                $specificity = 2;
            }

            if (!empty($rule['postal_code'])) {
                if (strpos((string) $postcode, (string) $rule['postal_code']) !== 0) {
                    continue;
                }
                $specificity = 3;
            }

            $candidate = $this->build_surcharge_candidate(
                $rule['threshold'] ?? '',
                $rule['type'] ?? 'fixed',
                $rule['value'] ?? '',
                $cart_total,
                40 + $specificity
            );

            $best_candidate = $this->pick_better_surcharge_candidate($best_candidate, $candidate);
        }

        return $best_candidate;
    }

    private function build_surcharge_candidate($threshold, $type, $value, $cart_total, $priority) {
        $threshold = (float) $threshold;
        $value = (float) $value;
        $normalized_type = $type === 'percentage' ? 'percentage' : 'fixed';

        if ($threshold <= 0 || $value <= 0 || $cart_total >= $threshold) {
            return null;
        }

        $amount = $normalized_type === 'percentage' ? ($cart_total * ($value / 100)) : $value;
        $amount = round((float) $amount, wc_get_price_decimals());

        if ($amount <= 0) {
            return null;
        }

        return array(
            'priority' => (int) $priority,
            'threshold' => $threshold,
            'amount' => $amount,
        );
    }

    private function pick_better_surcharge_candidate($current, $candidate) {
        if (!$candidate) {
            return $current;
        }

        if (!$current) {
            return $candidate;
        }

        if ($candidate['priority'] > $current['priority']) {
            return $candidate;
        }

        if ($candidate['priority'] === $current['priority']) {
            if ($candidate['amount'] > $current['amount']) {
                return $candidate;
            }

            if ($candidate['amount'] === $current['amount'] && $candidate['threshold'] > $current['threshold']) {
                return $candidate;
            }
        }

        return $current;
    }
    
    private function parse_limits($text) {
        $limits = array();
        $lines = explode("\n", $text);
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }
            
            $parts = array_map('trim', explode(',', $line));
            if (count($parts) >= 2) {
                $id = $parts[0];
                $min = isset($parts[1]) ? $parts[1] : '';
                $max = isset($parts[2]) ? $parts[2] : '';
                
                $limits[$id] = array(
                    'min' => $min,
                    'max' => $max
                );
            }
        }
        
        return $limits;
    }
}

// Uruchom plugin
function moq_limits_init() {
    if (class_exists('WooCommerce')) {
        new MOQ_Limits_Plugin();
    } else {
        add_action('admin_notices', function() {
            $plugin = new MOQ_Limits_Plugin();
            echo '<div class="error"><p>' . esc_html($plugin->get_text('woocommerce_required')) . '</p></div>';
        });
    }
}
add_action('plugins_loaded', 'moq_limits_init');
