<?php
/*
	Plugin Name: Genesis Simple Mobile Navigation
	Plugin URI: http://redblue.us
	Description: A plugin which handles mobile menus a bit differently than typical themes
	Version: 0.1
    Author: Jon Schroeder
    Author URI: http://redblue.us

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.
*/

/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
    die( "Sorry, you are not allowed to access this page directly." );
}

// Define the version number
define( 'REDBLUE_MOBILE_NAV_VERSION', 0.1 );

// Plugin directory
define( 'REDBLUE_MOBILE_NAV', dirname( __FILE__ ) );

add_action( 'genesis_init', 'rb_load_mobile_nav' );
function rb_load_mobile_nav() {
    add_action( 'wp_enqueue_scripts', 'rbmn_scripts_styles' );
    add_action( 'wp_enqueue_scripts', 'rbmn_remove_unused_genesis_scripts_styles', 99 );
    add_action( 'init', 'rbmn_add_widget_areas' );
    add_action( 'genesis_before', 'rbmn_output_menus', 0 );
    add_action( 'genesis_before', 'rbmn_add_mobile_nav_button', 5 );
    add_action( 'genesis_before', 'rbmn_add_after_header', 7 );
    add_action( 'genesis_after', 'rbmn_close_body_container_markup', 99 );
}

//* Enqueue Scripts and Styles (this should happen in the normal order)
function rbmn_scripts_styles() {

    //* Don't add these scripts and styles to the admin side of the site
    if ( is_admin() )
		return;

    wp_enqueue_style( 'dashicons' );

    //* Enqueue main style
    wp_enqueue_style( 'rbmn-style', plugin_dir_url( __FILE__ ) . 'css/rbmn-style.css', array(), REDBLUE_MOBILE_NAV_VERSION, 'screen' );

    //* Enqueue scripts
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'slideout', plugin_dir_url( __FILE__ ) . 'js/slide-menus.js', array( 'jquery' ), REDBLUE_MOBILE_NAV_VERSION, true );

}

//* Remove the default Genesis script for making menus mobile-friendly, since we're using our own
function rbmn_remove_unused_genesis_scripts_styles() {
    wp_dequeue_script( 'genesis-sample-responsive-menu' );
}

//* Register the widget areas
function rbmn_add_widget_areas() {
    genesis_register_sidebar( array(
        'id'			=> 'mobile-header',
        'name'		  => __( 'Mobile header', 'rbmn' ),
        'description'   => __( 'This area displays next to the hamburger. Keep this extremely short. If a logo is used, its height will be constrained. Recommended usage: a link with a class of "logo."', 'rbmn' ),
    ) );
    genesis_register_sidebar( array(
        'id'			=> 'mobile-after-header',
        'name'		  => __( 'Mobile after header', 'rbmn' ),
        'description'   => __( 'This area appears below the header. Completely optional, but this area could show a phone number or other basic information you\'d like to show below the nav menu.', 'rbmn' ),
    ) );
    genesis_register_sidebar( array(
        'id'			=> 'mobile-nav-left',
        'name'		  => __( 'Left mobile navigation area', 'rbmn' ),
        'description'   => __( 'This area displays after the left hamburger is clicked. You can add multiple menus, if you like, and they\'ll display as one menu. Text or social icons are fine too.', 'rbmn' ),
    ) );
    genesis_register_sidebar( array(
        'id'			=> 'mobile-nav-right',
        'name'		  => __( 'Right mobile navigation area', 'rbmn' ),
        'description'   => __( 'This area displays after the right hamburger is clicked. You can add multiple menus, if you like, and they\'ll display as one menu. Text or social icons are fine too.', 'rbmn' ),
    ) );
}


function rbmn_output_menus() {

    genesis_widget_area( 'mobile-nav-left', array(
        'before' => '<div class="slide-left slide-menu"><div class="mobile-nav-area">',
        'after' => '</div></div>',
    ) );

    genesis_widget_area( 'mobile-nav-right', array(
        'before' => '<div class="slide-right slide-menu"><div class="mobile-nav-area">',
        'after' => '</div></div>',
    ) );

}


function rbmn_add_mobile_nav_button() {

    //* We open 'main' here and will close it after everything
    echo '<div class="mobile-header-wrapper">';

        if ( is_active_sidebar( 'mobile-nav-left' ) )
            echo '<a href="#" class="open-left open-menu"><span></span><span></span><span></span></a>';

        if ( is_active_sidebar( 'mobile-nav-right' ) )
            echo '<a href="#" class="open-right open-menu"><span></span><span></span><span></span></a>';

        genesis_widget_area( 'mobile-header', array(
            'before' => '<div class="mobile-header-widget-area">',
            'after' => '</div>',
    	) );

        echo '<div class="clear"></div>';

    echo '</div>';
    echo '<div class="body-wrapper">';

}

function rbmn_add_after_header() {
    genesis_widget_area( 'mobile-after-header', array(
        'before' => '<div class="clear"></div><div class="mobile-after-header-widget-area">',
        'after' => '</div>',
    ) );
}

function rbmn_close_body_container_markup() {

        echo '<div class="slide-overlay"></div>';
    echo '</div>'; // .body-container
}