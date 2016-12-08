<?php

function register_my_menu() {
		register_nav_menu('primary',__( 'Main Menu', 'wpboilerplate'));
	}
add_action( 'init', 'register_my_menu' );

?>