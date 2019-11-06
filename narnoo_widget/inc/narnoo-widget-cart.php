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
    'div'       => '',
    'size'      => '',
    'label'     => '',
    'variant'   => '',
    'expand'    => '',
    'fill'      => '',
    'cssClass'  => ''
), $atts ) );

    //We need to get the widget settings from the database	
    $option = get_option( 'narnoo_widget_settings' );
    //If the access keys don't exist we have to return false
    if(empty($option['widget_access_key'])){
        return false;
    }

    $access_key = $option['widget_access_key'];
    //Manage the div to hold the cart
    if( empty($div) ){
        $div = 'narnoo-cart-widget';
    }else{
        $div = $div;
    }
    //Manage the size of the button
    if( empty($size) ){
        $type = 'default';
    }else{
        $type = $type;
    }
    //Manage the label for the button
    if( empty($label) ){
        $label = 'View Cart Items';
    }else{
        $label = $label;
    }
    //Manage the theme colour
    if( empty($variant) ){
        $variant = 'green';
    }else{
        $variant = $variant;
    }
    //Manage the theme colour
    if( empty($expand) ){
        $expand = 'block';
    }else{
        $expand = $expand;
    }
    

    $script = "<script>
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
    }(window, document, 'script', 'narnooButton', 'https://booking-widget.narnoo.com/button-widget.min.js'));

    narnooButton('init', {
        element: \"".$div."\",
        access_key: \"".$access_key."\",
        size: \"".$size."\",
        variant: \"".$variant."\",
        label: \"".$label."\",
        expand: \"".$expand."\",";
        if( !empty($fill) ){
            $script .= "fill: \"".$fill."\",";
        }
        if( !empty($cssClass) ){
            $script .= "cssClass: \"".$cssClass."\" ";
        }
    $script .= "});
    </script>";
    
    add_action( 'wp_footer', function() use( $script ){
        echo $script;
    });

    //Output the DIV
    echo '<div id="'.$div.'"></div>';
?>
