<?php


// Create Post Types: Articles, Features
function baptistleader_custom_post_types() {

  register_post_type( 'article',
      $labels = array(
        'labels' => array(
          'name' => __( 'Articles' ),
          'singular_name' => __( 'Article' ),
          'add_new_item' => __( 'Add New Article' ),
          'edit_item' => __( 'Edit Article' ),
          'new_item' => __( 'New Article' ),
          'view_item' => __( 'View Article' ),
          'search_items' => __( 'Search Article' ),
          'all_items' => __( 'All Articles' )
        ),
        'supports' => array (
           'title',
           'post-thumbnails',
           'editor'
         ),
        'taxonomies' => array('post_tag','category'),
        'menu_icon'  => __( 'dashicons-format-aside'),
        'public' => true,
        'has_archive' => true,
        'menu_position' => 5,
        'publicly_queryable'  => true
      )
    );

    register_post_type( 'feature',
      $labels = array(
        'labels' => array(
          'name' => __( 'Features' ),
          'singular_name' => __( 'Feature' ),
          'add_new_item' => __( 'Add New Feature' ),
          'edit_item' => __( 'Edit Feature' ),
          'new_item' => __( 'New Feature' ),
          'view_item' => __( 'View Feature' ),
          'search_items' => __( 'Search Feature' ),
          'all_items' => __( 'All Features' )
        ),
        'supports' => array (
            'title',
            'thumbnail',
            'post-thumbnails',
            'editor'
          ),
        'taxonomies' => array('post_tag','category'),
        'menu_icon'  => __( 'dashicons-text'),
        'public' => true,
        'has_archive' => true,
        'menu_position' => 6,
        'publicly_queryable'  => true
      )
    );

}
add_action( 'init', 'baptistleader_custom_post_types', 1);


// Custom Taxonomy for Articles and Features

function locations_taxonomy() {  
    register_taxonomy(  
        'location',   // The name of the taxonomy
        'article',    // Post type name

        array(  
            'hierarchical' => true,  
            'labels' => array(
                'name' => 'Locations',
                'singular_name' => 'location',
                'search_items' => 'Search Locations',
                'popular_items' => 'Popular Locations',
                'all_items' => 'All Locations',
                'parent_item' => null,
                'parent_item_colon' => null,
                'edit_item' => 'Edit Locations',
                'update_item' => 'Update Locations',
                'add_new_item' => 'Add New Location',
                'new_item_name' => 'New Locations Name',
                'separate_items_with_commas' => 'Separate Locations with commas',
                'add_or_remove_items' => 'Add or remove locations',
                'choose_from_most_used' => 'Choose from the most used locations',
                'not_found' => 'No locations found.',
                'menu_name' => 'Locations'
              ),
            'query_var' => true,
            'show_admin_column' => true,
            'rewrite' => array(
                'slug' => 'location',
                'with_front' => false 
            ),
            'menu_position' => 0
        )  
    );  
}  
add_action( 'init', 'locations_taxonomy', 1);