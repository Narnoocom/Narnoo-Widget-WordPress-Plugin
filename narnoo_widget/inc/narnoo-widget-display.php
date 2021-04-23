<?php
/**
 * Extract the shortcode data
 */
extract( shortcode_atts( array(
    'div'                   => '',
    'operator_id'           => '',
    'booking_id'            => '',
    'hide_datepicker'       => '',
    'show_gallery'          => '',
    'show_pricing'          => '',
    'maxWidth'              => '',
    'variant'               => '',
    'button_label'          => '',
    'button_size'           => '',
    'button_variant'        => '',
    'button_expand'         => '',
    'button_fill'           => '',
    'datepicker_type'       => 'range',
    'datepicker_label'      => '',
    'productoption_label'   => '',
    'guestoption_label'     => '',
    'timeoption_label'      => '',
    'datepicker_postion'    => 'left',
    'datepicker_type'       => 'range',
    'datepicker_drops'      => 'down'
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

    /**
     * Embed script
     */
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

        datepickerOption:{";
          if(!empty($datepicker_label)){
            $script .= "label: \"".$datepicker_label."\",";
          }
          $script .=  "type: \"".$datepicker_type."\",
            position: \"".$datepicker_postion."\",
            drops: \"".$datepicker_drops."\",
        },
        buttonOptions: {
                label: \"".$button_label."\",
                variant: \"".$button_variant."\",
                size: \"".$button_size."\",";
                if(!empty($button_fill)){
                    $script .= "fill: \"".$button_fill."\",";
                }
                $script .= "expand: \"".$button_expand."\"
        }";

        if(!empty($productoption_label)){
          $script .= "productOptions: {
                  label: \"".$productoption_label."\"
          },";
        }

        if(!empty($guestoption_label)){
          $script .= "guestOptions: {
                  label: \"".$guestoption_label."\"
          },";
        }

        if(!empty($timeoption_label)){
          $script .= "productTimeOptions: {
                  label: \"".$timeoption_label."\"
          },";
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
