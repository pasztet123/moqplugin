<?php
/**
 * Plugin Name: MOQ Limits for WooCommerce
 * Description: Simple plugin to set minimum and maximum order limits
 * Version: 1.1.0
 * Author: Investracker
 * Text Domain: moq-limits
 * Requires at least: 5.0
 * Requires PHP: 7.2
 * WC requires at least: 3.0
 * WC tested up to: 9.0
 * Requires Plugins: woocommerce
 */

if (!defined('ABSPATH')) {
    exit;
}

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
            'leave_empty' => 'Pozostaw puste aby wyłączyć',
            'product_limits_label' => 'Limity dla produktów (Premium)',
            'product_limits_desc' => 'Ustaw minimalne i maksymalne ilości dla konkretnych produktów',
            'category_limits_label' => 'Limity dla kategorii (Premium)',
            'category_limits_desc' => 'Ustaw minimalne i maksymalne ilości dla całych kategorii produktów',
            'add_product_limit' => 'Dodaj limit produktu',
            'add_category_limit' => 'Dodaj limit kategorii',
            'select_product' => 'Wybierz produkt',
            'select_category' => 'Wybierz kategorię',
            'min_quantity' => 'Min. ilość',
            'max_quantity' => 'Max. ilość',
            'remove' => 'Usuń',
            'regional_limits_label' => 'Limity regionalne (Premium)',
            'regional_limits_desc' => 'Ustaw różne limity kwotowe dla konkretnych krajów, stanów lub kodów pocztowych',
            'add_regional_limit' => 'Dodaj limit regionalny',
            'select_country' => 'Wybierz kraj',
            'select_state' => 'Wybierz stan (opcjonalnie)',
            'postal_code' => 'Kod pocztowy (opcjonalnie)',
            'postal_code_placeholder' => 'np. 12345 lub 12-345',
            'min_amount' => 'Min. kwota',
            'max_amount' => 'Max. kwota',
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
            'leave_empty' => 'Leave empty to disable',
            'product_limits_label' => 'Product Limits (Premium)',
            'product_limits_desc' => 'Set minimum and maximum quantities for specific products',
            'category_limits_label' => 'Category Limits (Premium)',
            'category_limits_desc' => 'Set minimum and maximum quantities for entire product categories',
            'add_product_limit' => 'Add Product Limit',
            'add_category_limit' => 'Add Category Limit',
            'select_product' => 'Select Product',
            'select_category' => 'Select Category',
            'min_quantity' => 'Min. Qty',
            'max_quantity' => 'Max. Qty',
            'remove' => 'Remove',
            'regional_limits_label' => 'Regional Limits (Premium)',
            'regional_limits_desc' => 'Set different order limits for specific countries, states, or postal codes',
            'add_regional_limit' => 'Add Regional Limit',
            'select_country' => 'Select Country',
            'select_state' => 'Select State (Optional)',
            'postal_code' => 'Postal Code (Optional)',
            'postal_code_placeholder' => 'e.g. 12345 or 12-345',
            'min_amount' => 'Min. Amount',
            'max_amount' => 'Max. Amount',
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
        register_setting('moq_limits_settings', 'moq_product_limits');
        register_setting('moq_limits_settings', 'moq_category_limits');
        register_setting('moq_limits_settings', 'moq_regional_limits');
    }
    
    public function settings_page() {
        $is_premium = $this->is_premium_active();
        $products = wc_get_products(array('limit' => -1, 'status' => 'publish'));
        $categories = get_terms(array('taxonomy' => 'product_cat', 'hide_empty' => false));
        $product_limits = json_decode(get_option('moq_product_limits', '[]'), true);
        $category_limits = json_decode(get_option('moq_category_limits', '[]'), true);
        $regional_limits = json_decode(get_option('moq_regional_limits', '[]'), true);
        
        if (!is_array($product_limits)) $product_limits = array();
        if (!is_array($category_limits)) $category_limits = array();
        if (!is_array($regional_limits)) $regional_limits = array();
        
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
                </table>
                
                <script type="text/javascript">
                jQuery(document).ready(function($) {
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
                    
                    // Handle country change - update states dropdown
                    $(document).on('change', '.moq-country-select', function() {
                        var country = $(this).val();
                        var index = $(this).data('index');
                        var stateSelect = $('.moq-state-select[data-index="' + index + '"]');
                        
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
