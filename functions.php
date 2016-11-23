<?php
/**
 * Functions and Definitions
 */

// Enqueue CSS and Scripts
function project_scripts_styles() {
  wp_enqueue_style( 'styles', get_template_directory_uri() . '/style.css',false,'1.0','all');
  wp_enqueue_script( 'jquery' );
  wp_enqueue_script( 'scripts', get_stylesheet_directory_uri() . '/js/compiled/scripts.min.js',false,'1.0','all' );
}
add_action( 'wp_enqueue_scripts', 'project_scripts_styles', 1);



// Adds Theme Options Page (if you have ACF Pro installed)
if( function_exists('acf_add_options_page') ) {
 
  $option_page = acf_add_options_page(array(
    'page_title'  => 'Theme Settings',
    'menu_title'  => 'Theme Settings',
    'menu_slug'   => 'theme-settings',
    'capability'  => 'edit_posts',
    'redirect'  => false
  ));
 
}


// Nav
function register_my_menu() {
		register_nav_menu('primary',__( 'Main Menu', 'wpboilerplate'));
	}
add_action( 'init', 'register_my_menu' );



// Hide admin bar
show_admin_bar( false );



// Clone posts and pages
function rd_duplicate_post_as_draft(){
  global $wpdb;
  if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'rd_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
    wp_die('No post to duplicate has been supplied!');
  }
  $post_id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);
  $post = get_post( $post_id );
  $current_user = wp_get_current_user();
  $new_post_author = $current_user->ID;
  if (isset( $post ) && $post != null) {
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
    $new_post_id = wp_insert_post( $args );
    $taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
    foreach ($taxonomies as $taxonomy) {
      $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
      wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
    }
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
    wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
    exit;
  } else {
    wp_die('Post creation failed, could not find original post: ' . $post_id);
  }
}
add_action( 'admin_action_rd_duplicate_post_as_draft', 'rd_duplicate_post_as_draft' );
 

function rd_duplicate_post_link( $actions, $post ) {
  if (current_user_can('edit_posts')) {
    $actions['duplicate'] = '<a href="admin.php?action=rd_duplicate_post_as_draft&amp;post=' . $post->ID . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
  }
  return $actions;
}
add_filter('page_row_actions', 'rd_duplicate_post_link', 10, 2);
add_filter('post_row_actions', 'rd_duplicate_post_link', 10, 2);


?>