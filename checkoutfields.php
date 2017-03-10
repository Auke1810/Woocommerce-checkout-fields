<?php #######################################################################################
##
## CHECKOUT FIELDS WOOCOMMERCE
## Auke Jongbloed
## www.wordpressassist.nl
## auke@wordpressassist.nl
## https://github.com/Auke1810/Woocommerce-checkout-fields
##
## Voor de Nederlandse markt is deze woocommerce checkout fields snippet aangemaakt.
## Om te voorkomen dat er bestellingen door worden gegeven met een adres zonder huisnummer
## voegt deze snippets een huisnummer veld toe aan het checkout formulier van Woocommerce.
## Je kunt de snippet in een eigen file zetten en includen in je functions.php van je childtheme
## of direct in je functions.php kopieren en plakken.
##
#############################################################################################
 
add_filter( 'woocommerce_checkout_fields' , 'wpass_add_field_and_reorder_fields' );
 
function wpass_add_field_and_reorder_fields( $fields ) {
 
    // Add New huisnummer Fields
      
    $fields['billing']['billing_houseno'] = array(
    'label'     => __('Huisnummer', 'woocommerce'),
    'placeholder'   => _x('Huisnummer', 'placeholder', 'woocommerce'),
    'required'  => true,
    'class'     => array('form-row-last'),
    'clear'     => true
     );
 
    $fields['shipping']['shipping_houseno'] = array(
    'label'     => __('Huisnummer', 'woocommerce'),
    'placeholder'   => _x('Huisnummer', 'placeholder', 'woocommerce'),
    'required'  => true,
    'class'     => array('form-row-last'),
    'clear'     => true
     );
 
    // Remove Address_2 Fields
 
    unset($fields['billing']['billing_address_2']);
    unset($fields['shipping']['shipping_address_2']);
 
    // Make Address_1 Fields Half Width
 
    $fields['billing']['billing_address_1']['class'] = array('form-row-first');
    $fields['shipping']['shipping_address_1']['class'] = array('form-row-first');
 
    // Billing: Sort Fields
      
    $newfields['billing']['billing_first_name'] = $fields['billing']['billing_first_name'];
    $newfields['billing']['billing_last_name']  = $fields['billing']['billing_last_name'];
    $newfields['billing']['billing_company']    = $fields['billing']['billing_company'];
    $newfields['billing']['billing_email']      = $fields['billing']['billing_email'];
    $newfields['billing']['billing_phone']      = $fields['billing']['billing_phone'];
    $newfields['billing']['billing_country']    = $fields['billing']['billing_country'];
    $newfields['billing']['billing_address_1']  = $fields['billing']['billing_address_1'];  
    $newfields['billing']['billing_houseno']    = $fields['billing']['billing_houseno'];
    $newfields['billing']['billing_city']       = $fields['billing']['billing_city'];
    $newfields['billing']['billing_postcode']   = $fields['billing']['billing_postcode'];
    $newfields['billing']['billing_state']      = $fields['billing']['billing_state'];
   
    // Shipping: Sort Fields
      
    $newfields['shipping']['shipping_first_name'] = $fields['shipping']['shipping_first_name'];
    $newfields['shipping']['shipping_last_name']  = $fields['shipping']['shipping_last_name'];
    $newfields['shipping']['shipping_company']    = $fields['shipping']['shipping_company'];
    $newfields['shipping']['shipping_country']    = $fields['shipping']['shipping_country'];
    $newfields['shipping']['shipping_address_1']  = $fields['shipping']['shipping_address_1'];  
    $newfields['shipping']['shipping_houseno']    = $fields['shipping']['shipping_houseno'];
    $newfields['shipping']['shipping_city']       = $fields['shipping']['shipping_city'];
    $newfields['shipping']['shipping_state']      = $fields['shipping']['shipping_state'];
    $newfields['shipping']['shipping_postcode']   = $fields['shipping']['shipping_postcode'];
 
    $checkout_fields = array_merge( $fields, $newfields);
    return $checkout_fields;
} 
 
 
// ------------------------------------
// Add Billing House # to Address Fields
 
add_filter( 'woocommerce_order_formatted_billing_address' , 'wpass_default_billing_address_fields', 10, 2 );
 
function wpass_default_billing_address_fields( $fields, $order ) {
$fields['billing_houseno'] = get_post_meta( $order->id, '_billing_houseno', true );
return $fields;
}
 
 
// ------------------------------------
// Add Shipping House # to Address Fields
 
add_filter( 'woocommerce_order_formatted_shipping_address' , 'wpass_default_shipping_address_fields', 10, 2 );
 
function wpass_default_shipping_address_fields( $fields, $order ) {
$fields['shipping_houseno'] = get_post_meta( $order->id, '_shipping_houseno', true );
return $fields;
}
 
// ------------------------------------
// Create 'replacements' for new Address Fields
 
add_filter( 'woocommerce_formatted_address_replacements', 'add_new_replacement_fields',10,2 );
 
function add_new_replacement_fields( $replacements, $address ) {
$replacements['{billing_houseno}'] = isset($address['billing_houseno']) ? $address['billing_houseno'] : '';
$replacements['{shipping_houseno}'] = isset($address['shipping_houseno']) ? $address['shipping_houseno'] : '';
return $replacements;
}
 
// ------------------------------------
// Show Shipping & Billing House # for different countries
 
add_filter( 'woocommerce_localisation_address_formats', 'wpass_new_address_formats' );
 
function wpass_new_address_formats( $formats ) {
$formats['IE'] = "{name}\n{company}\n{address_1}\n{billing_houseno}\n{shipping_houseno}\n{city}\n{state}\n{postcode}\n{country}";
$formats['UK'] = "{name}\n{company}\n{address_1}\n{billing_houseno}\n{shipping_houseno}\n{city}\n{state}\n{postcode}\n{country}";
return $formats;
}
