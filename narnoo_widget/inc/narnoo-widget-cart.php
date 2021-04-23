<?php
/**
 * Extract the shortcode data
 */
extract( shortcode_atts( array(
    'div'               => '',
    'size'              => '',
    'label'             => '',
    'variant'           => '',
    'expand'            => '',
    'fill'              => '',
    'css_class'         => '',
    'max_width'         => '',
    'custom_element'    => '',
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

    //Manage any custome CSS Classes
    if( !empty($css_class)){
        $css = $css_class;
    }

    //Manage max width
    if(!empty('max_width')){
        $maxWidth = $max_width;
    }

    //If custom element
    $customButton = false;
    if(!empty($custom_element)){
        $customButton = true;
        $div = $custom_element;
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
        element: \"".$div."\",";

        if(!empty($customButton)){
            $script .= "customCartButton: true,";
        }else{
            $script .= "customCartButton: false,";
            $script .= "access_key: \"".$access_key."\",
            size: \"".$size."\",
            variant: \"".$variant."\",
            label: \"".$label."\",
            expand: \"".$expand."\",";
            if( !empty($fill) ){
                $script .= "fill: \"".$fill."\",";
            }
            if( !empty($css) ){
                $script .= "cssClass: \"".$css."\" ";
            }
            if(!empty($maxWidth)){
                $script .= "maxWidth: \"".$maxWidth."\"";
            }

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
