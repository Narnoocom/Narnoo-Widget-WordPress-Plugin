<?php
/**
 * Extract the shortcode data
 */
extract( shortcode_atts( array(
    'div'           => '',
    'operator_id'   => '',
    'booking_id'    => '',
    'size'          => '',
    'label'         => '',
    'variant'       => '',
    'expand'        => '',
    'fill'          => '',
    'css_class'     => '',
    'max_width'     => '',
    'on_click'      => '',
), $atts ) );
    /**
     * Set up all the options
     */
    //We need to get the widget settings from the database	
    $option = get_option( 'narnoo_widget_settings' );
    
    //If the access keys don't exist we have to return false
    if(empty($option['widget_access_key'])){
        return false;
    }
    //Operator ID
    if(empty($operator_id)){
        return false;
    }
    //Product ID
    if(empty($booking_id)){
        return false;
    }

    $access_key = $option['widget_access_key'];
    //Manage the div to hold the cart
    if( empty($div) ){
        $div = 'narnoo-button-widget';
    }else{
        $div = $div;
    }
    //Manage the size of the button
    if( empty($size) ){
        $size = 'default';
    }else{
        $size = $size;
    }
    //Manage the label for the button
    if( empty($label) ){
        $label = 'Check Availability';
    }else{
        $label = $label;
    }
    //Manage the theme colour
    if( empty($variant) ){
        $variant = 'green';
    }else{
        $variant = $variant;
    }
    //Manage the fill colour
    if( empty($fill) ){
        $fill = 'default';
    }else{
        $fill = $fill;
    }

    //Manage any custome CSS Classes
    if( !empty($css_class)){
        $css = $css_class;
    }

    //Manage max width
    if(!empty($max_width)){
        $maxWidth = $max_width;
    }

    //Manage onclick
    if(!empty($on_click)){
        $onClick = $on_click;
    }

    
    /**
     * Embed script
     */
    $script = "<script>
    (function (w, d, s, o, f, js, fjs) {
        w['narnoo-booking-button-widget'] = o;
        w[o] = w[o] || function () {
            (w[o].q = w[o].q || []).push(arguments)
        };
        js = d.createElement(s), fjs = d.getElementsByTagName(s)[0];
        js.id = o;
        js.src = f;
        js.async = 1;
        fjs.parentNode.insertBefore(js, fjs);
    }(window, document, 'script', 'narnooBooking', 'https://booking-widget.narnoo.com/booking-button-widget.min.js'));

    narnooBooking('init', {
        element: \"".$div."\",
        access_key: \"".$access_key."\",
        operator_id: \"".$operator_id."\",
        booking_id: \"".$booking_id."\",
        size: \"".$size."\",
        variant: \"".$variant."\",
        label: \"".$label."\",
        fill: \"".$fill."\",";
        if(!empty($expand)){
            $script .= "expand: \"".$expand."\",";
        }
        if(!empty($css)){
            $script .= "cssClass: \"".$css."\",";
        }
        if(!empty($maxWidth)){
            $script .= "maxWidth: \"".$maxWidth."\",";
        }
        if(!empty($onClick)){
            $script .= "onClickEvent: \"".$onClick."\",";
        }
    $script .= "});
    </script>";

    /**
     * Add the script to the footer of the page to increase page load time.
     */
    add_action( 'wp_footer', function() use( $script ){
        echo $script;
    });

    /**
     * Output the div holder in the correct place
     */
    echo '<div id="'.$div.'"></div>';
?>
