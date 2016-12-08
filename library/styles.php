<?php

function project_scripts_styles() {
  wp_enqueue_style( 'styles', get_template_directory_uri() . '/style.css',false,'1.0','all');
  wp_enqueue_script( 'jquery' );
  wp_enqueue_script( 'scripts', get_stylesheet_directory_uri() . '/js/compiled/scripts.min.js',false,'1.0','all' );
}
add_action( 'wp_enqueue_scripts', 'project_scripts_styles', 1);

?>