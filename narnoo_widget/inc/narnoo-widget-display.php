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
    'div'               => '',
    'operator_id'       => '',
    'booking_id'        => '',
    'hide_datepicker'   => '',
    'show_gallery'      => '',
    'show_pricing'      => '',
    'maxWidth'          => '',
    'variant'           => '',
    'button_label'      => '',
    'button_size'       => '',
    'button_variant'    => '',
    'button_expand'     => '',
    'button_fill'       => ''
), $atts ) );
    
   //We need to get the widget settings from the database	
    $option = get_option( 'narnoo_widget_settings' );
    //If the access keys don't exist we have to return false
    if(empty($option['widget_access_key'])){
        //return false;
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
        $div = 'narnoo-booking-widget';
    }else{
        $div = $div;
    }
    //Deal with the datepicker
    if( empty( $hide_datepicker ) ){
        $datepicker = true;
    }else{
        $datepicker = false;
    }
        
    //Manage the theme colour
    if( empty($variant) ){
        $variant = 'green';
    }else{
        $variant = $variant;
    }

    //Manage the size of the button
    if( empty($button_size) ){
        $button_size = 'default';
    }else{
        $button_size = $button_size;
    }
    //Manage the label for the button
    if( empty($button_label) ){
        $button_label = 'Check Availability';
    }else{
        $button_label = $label;
    }
    //Manage the theme colour
    if( empty($button_variant) ){
        $button_variant = 'green';
    }else{
        $button_variant = $button_variant;
    }
    //Manage the theme colour
    if( empty($button_expand) ){
        $button_expand = 'block';
    }else{
        $button_expand = $button_expand;
    }
    

    $script = "<script>
    (function(w, d, s, o, f, js, fjs) {
      w['narnoo-widget'] = o;
      w[o] = w[o] || function () {
          (w[o].q = w[o].q || []).push(arguments)
      };
      js = d.createElement(s), fjs = d.getElementsByTagName(s)[0];
      js.id = o;
      js.src = f;
      js.async = 1;
      fjs.parentNode.insertBefore(js, fjs);
    }(window, document, 'script', 'narnoo', 'https://booking-widget.narnoo.com/narnoo-widget.min.js'));

    narnoo('init', {
        element: \"".$div."\",
        access_key: \"".$access_key."\",
        operator_id: \"".$operator_id."\",
        booking_id: \"".$booking_id."\",
        dropdownOption:{
            align: \"right\"
        },";
        
        if(!empty($maxWidth)){
            $script .= "maxWidth: \"".$maxWidth."\",";
        }

        if(!empty($show_gallery)){
            $script .= "gallery: true,";
        }else{
            $script .= "gallery: false,";
        }

        if(!empty($show_pricing)){
            $script .= "pricing: true,";
        }else{
            $script .= "pricing: false,";
        }
        
        $script .= "variant: \"".$variant."\",
        buttonOptions: {
                label: \"".$button_label."\",
                variant: \"".$button_variant."\",
                size: \"".$button_size."\",";
                if(!empty($button_fill)){
                    $script .= "fill: \"".$button_fill."\",";
                }
                $script .= "expand: \"".$button_expand."\"
        }";

    $script .= "});
    </script>";
    
    add_action( 'wp_footer', function() use( $script ){
        echo $script;
    });

    //Output the DIV
    echo '<div id="'.$div.'"></div>';
?>
