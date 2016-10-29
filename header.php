<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title><?php bloginfo('name'); ?> | <?php is_front_page() ? bloginfo('description') : wp_title(''); ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <link rel="shortcut icon" href="<?php echo esc_url( get_template_directory_uri() )?>/favicon.ico" />

        <script src="<?php bloginfo('template_directory'); ?>/js/vendor/modernizr-2.8.3.min.js"></script>

    	<?php wp_head(); ?>
    </head>

	<body <?php body_class(); ?>>