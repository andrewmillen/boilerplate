<?php
/**
 * Functions and Definitions
 */

// Enqueue CSS
function project_scripts_styles() {
  wp_enqueue_style( 'style', get_template_directory_uri() . '/style.css',false,'1.0','all');
}
add_action( 'wp_enqueue_scripts', 'project_scripts_styles', 1);


// Adds featured image to posts
// add_theme_support( 'post-thumbnails' );


// Nav
// function register_my_menu() {
// 		register_nav_menu('primary',__( 'Main Menu', 'wpboilerplate'));
// 	}
// add_action( 'init', 'register_my_menu' );


// Hide admin bar
// show_admin_bar( false );


// // Register custom post type "Listing"
// add_action( 'init', 'create_post_type' );
// function create_post_type() {
//   register_post_type( 'listing',
//     $labels = array(
//       'labels' => array(
//         'name' => __( 'Listings' ),
//         'singular_name' => __( 'Listing' ),
//         'add_new_item' => __( 'Add New Listing' ),
//         'edit_item' => __( 'Edit Listing' ),
//         'new_item' => __( 'New Listing' ),
//         'view_item' => __( 'View Listing' ),
//         'search_items' => __( 'Search Listings' ),
//         'all_items' => __( 'All Listings' )
//       ),
//       'supports' => array (
//       		'title',
//       		'thumbnail',
//       		'post-thumbnails'
//       	),
//       'menu_icon'  => __( 'dashicons-building'),
//       'public' => true,
//       'has_archive' => true,
//       'menu_position' => 20,
//       'publicly_queryable'  => true
//     )
//   );
// }
// add_action( 'init', 'create_post_type', 1);

// // Custom Taxonomy for Listings

// function listings_taxonomy() {  
//     register_taxonomy(  
//         'category',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces). 
//         'listing',        //post type name
//         array(  
//             'hierarchical' => true,  
//             'label' => 'Categories',  //Display name
//             'query_var' => true,
//             'show_admin_column' => true,
//             'rewrite' => array(
//                 'slug' => 'listing', // This controls the base slug that will display before each term
//                 'with_front' => false // Don't display the category base before 
//             )
//         )  
//     );  
// }  
// add_action( 'init', 'listings_taxonomy');


// Clone posts and pages
function rd_duplicate_post_as_draft(){
  global $wpdb;
  if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'rd_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
    wp_die('No post to duplicate has been supplied!');
  }
 
  /*
   * get the original post id
   */
  $post_id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);
  /*
   * and all the original post data then
   */
  $post = get_post( $post_id );
 
  /*
   * if you don't want current user to be the new post author,
   * then change next couple of lines to this: $new_post_author = $post->post_author;
   */
  $current_user = wp_get_current_user();
  $new_post_author = $current_user->ID;
 
  /*
   * if post data exists, create the post duplicate
   */
  if (isset( $post ) && $post != null) {
 
    /*
     * new post data array
     */
    $args = array(
      'comment_status' => $post->comment_status,
      'ping_status'    => $post->ping_status,
      'post_author'    => $new_post_author,
      'post_content'   => $post->post_content,
      'post_excerpt'   => $post->post_excerpt,
      'post_name'      => $post->post_name,
      'post_parent'    => $post->post_parent,
      'post_password'  => $post->post_password,
      'post_status'    => 'draft',
      'post_title'     => $post->post_title,
      'post_type'      => $post->post_type,
      'to_ping'        => $post->to_ping,
      'menu_order'     => $post->menu_order
    );
 
    /*
     * insert the post by wp_insert_post() function
     */
    $new_post_id = wp_insert_post( $args );
 
    /*
     * get all current post terms ad set them to the new post draft
     */
    $taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
    foreach ($taxonomies as $taxonomy) {
      $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
      wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
    }
 
    /*
     * duplicate all post meta just in two SQL queries
     */
    $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
    if (count($post_meta_infos)!=0) {
      $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
      foreach ($post_meta_infos as $meta_info) {
        $meta_key = $meta_info->meta_key;
        $meta_value = addslashes($meta_info->meta_value);
        $sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
      }
      $sql_query.= implode(" UNION ALL ", $sql_query_sel);
      $wpdb->query($sql_query);
    }
 
 
    /*
     * finally, redirect to the edit post screen for the new draft
     */
    wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
    exit;
  } else {
    wp_die('Post creation failed, could not find original post: ' . $post_id);
  }
}
add_action( 'admin_action_rd_duplicate_post_as_draft', 'rd_duplicate_post_as_draft' );
 
/*
 * Add the duplicate link to action list for post_row_actions
 */
function rd_duplicate_post_link( $actions, $post ) {
  if (current_user_can('edit_posts')) {
    $actions['duplicate'] = '<a href="admin.php?action=rd_duplicate_post_as_draft&amp;post=' . $post->ID . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
  }
  return $actions;
}
 
add_filter('page_row_actions', 'rd_duplicate_post_link', 10, 2);
add_filter('post_row_actions', 'rd_duplicate_post_link', 10, 2);


?>