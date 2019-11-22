<?php
/*
(function (w, d, s, o, f, js, fjs) {
        w['narnoo-button-widget'] = o;
        w[o] = w[o] || function () {
            (w[o].q = w[o].q || []).push(arguments)
        };
        js = d.createElement(s), fjs = d.getElementsByTagName(s)[0];
        js.id = o;
        js.src = f;
        js.async = 1;
        fjs.parentNode.insertBefore(js, fjs);
    }(window, document, 'script', 'narnooButton', 'https://narnoo-widget.herokuapp.com/button-widget.min.js'));

    narnooButton('init', {
        element: "narnoo-button-cart-widget",
        label: "View Cart with label",
        size: "small", // small, default, large
        // variant: "", // green, blue, orange, navy, yellow, peach, red, beige, cyan, celadon, brown, cherry, purple, olive
        // size: "default", // default, small, large
        // expand: "", // block, full
        // fill: "", // clear, outline
        // cssClass: "" // custom CSS Class
    });
*/


extract( shortcode_atts( array(
    'div'           => '',
    'operator_id'   => '',
    'booking_id'    => '',
    'size'          => '',
    'label'         => '',
    'variant'       => '',
    'expand'        => '',
    'fill'          => ''
), $atts ) );

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
    if( empty($fill) ){
        $fill = 'default';
    }else{
        $fill = $fill;
    }

    

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
            $script .= "expand: \"".$expand."\"";
        }
    $script .= "});
    </script>";


   /* $script = "<script>
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
    }(window, document, 'script', 'narnooBooking','https://narnoo-booking-widget-dev.herokuapp.com/booking-button-widget.min.js'));

    narnooBooking('init', {
        element: \"narnoo-button-widget\",
        access_key: \"".$access_key."\",
        operator_id: \"".$operator_id."\",
        booking_id: \"".$booking_id."\",
        label: \"Check Booking\"
    });
</script>";*/

    
    add_action( 'wp_footer', function() use( $script ){
        echo $script;
    });

    //Output the DIV
    echo '<div id="'.$div.'"></div>';
?>
