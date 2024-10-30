<?php

	//Create Custom Post type for invoice
	function pravel_create_invoice_post_type() {		
		$supports = array(
			'title',
			'editor',
			'author', 
			'thumbnail',
			'excerpt',		
		);
		
		$labels = array(
			'name' => _x('Pravel Invoice', 'plural'),
			'singular_name' => _x('Pravel Invoice', 'singular'),
			'menu_name' => _x('Pravel Invoice', 'admin menu'),
			'name_admin_bar' => _x('pravel_invoice', 'admin bar'),
			'add_new' => _x('Add New', 'add new'),
			'add_new_item' => __('Add New Invoice'),
			'new_item' => __('New Invoice'),
			'edit_item' => __('Edit Invoice'),
			'view_item' => __('View Invoice'),
			'all_items' => __('All Invoice'),
			'search_items' => __('Search Invoice'),
			'not_found' => __('No Invoices found.'),
		);
		
		$args = array(
			'supports' => $supports,
			'labels' => $labels,
			'public' => true,
			'query_var' => true,
			'menu_icon' => 'dashicons-admin-page',
			'rewrite' => false,
			'has_archive' => false,
			'hierarchical' => false,
			
		);
		register_post_type( 'pravel_invoice', $args );
	}
	
	add_action( 'init', 'pravel_create_invoice_post_type' );

	function pravel_create_product_post_type() {		
		$supports = array(
			'title',			
			'author', 			
			'custom-fields'
		);
		
		$labels = array(
			'name' => _x('Shop Product', 'plural'),
			'singular_name' => _x('Shop Product', 'singular'),
			'menu_name' => _x('Shop Product', 'admin menu'),
			'name_admin_bar' => _x('pravel_shop_product', 'admin bar'),
			'add_new' => _x('Add New', 'add new'),
			'add_new_item' => __('Add New Product'),
			'new_item' => __('New Product'),
			'edit_item' => __('Edit Product'),
			'view_item' => __('View Product'),
			'all_items' => __('All Product'),
			'search_items' => __('Search Product'),
			'not_found' => __('No Product found.'),
		);
		
		$args = array(
			'supports' => $supports,
			'labels' => $labels,
			'public' => true,
			'query_var' => true,
			'menu_icon' => 'dashicons-cart',			
			'rewrite' => array('pravel_shop_product' => 'news'),
			'has_archive' => true,
			'hierarchical' => false,
		);
		register_post_type( 'pravel_shop_product', $args );
	}
	
	add_action( 'init', 'pravel_create_product_post_type' );
