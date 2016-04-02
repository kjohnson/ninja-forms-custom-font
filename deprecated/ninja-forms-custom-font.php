<?php

final class NF_CustomFont
{
    public function __construct()
    {
        add_action( 'ninja_forms_display_before_form', array( $this, 'display_before_form' ) );
        add_filter( 'ninja_forms_form_settings_basic', array( $this, 'from_display_settings' ) );
    }

    public function display_before_form()
    {
        global $ninja_forms_loading;

        if( ! isset( $ninja_forms_loading->data[ 'form' ][ 'custom_font' ] ) ) return;

        $font_family = $ninja_forms_loading->data[ 'form' ][ 'custom_font' ];

        ?>
        <style>
            .ninja-forms-form,
            .ninja-forms-form input {
                font-family: <?php echo $font_family; ?> !important;
            }
        </style>
        <?php
    }

    public function from_display_settings( $args )
    {
        $args[ 'settings' ][] = array(
            'name' 	=> 'custom_font',
            'type' 	=> 'text',
            'label'	=> __( 'Custom Font', 'ninja-forms' ),
        );
        return $args;
    }
}

new NF_CustomFont();