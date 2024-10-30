<?php 
	function pravel_invoice_ajax_user_init(){
	
		wp_register_script('ajax-user-script', PRAVEL_INVOICE_PLUGIN_URL.'assets/js/ajax-login-script.js', array('jquery') ); 
		wp_enqueue_script('ajax-user-script');
		wp_localize_script( 'ajax-user-script', 'ajax_user_object', array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'redirecturl' => home_url(),
			'ajax_nonce' => wp_create_nonce('security'),
			'loadingmessage' => __('Sending user info, please wait...')
		));
		add_action( 'wp_ajax_pravel_invoice_create', 'pravel_invoice_create' );	
		add_action( 'wp_ajax_nopriv_pravel_invoice_create', 'pravel_invoice_create' );	
		add_action( 'wp_ajax_pravel_invoice_product_add', 'pravel_invoice_product_add' );
		add_action( 'wp_ajax_nopriv_pravel_invoice_product_add', 'pravel_invoice_product_add' );	
		add_action( 'wp_ajax_pravel_invoice_product_edit', 'pravel_invoice_product_edit' );	
		add_action( 'wp_ajax_nopriv_pravel_invoice_product_edit', 'pravel_invoice_product_edit' );	
		add_action( 'wp_ajax_pravel_invoice_product_delete', 'pravel_invoice_product_delete' );		
	    add_action( 'wp_ajax_nopriv_pravel_invoice_product_delete', 'pravel_invoice_product_delete' );	
		add_action( 'wp_ajax_pravel_invoice_delete', 'pravel_invoice_delete' );	
		add_action( 'wp_ajax_nopriv_pravel_invoice_delete', 'pravel_invoice_delete' );	
		add_action( 'wp_ajax_pravel_invoice_mail_send', 'pravel_invoice_mail_send' );
		add_action( 'wp_ajax_nopriv_pravel_invoice_mail_send', 'pravel_invoice_mail_send' );	
	}
	add_action('init', 'pravel_invoice_ajax_user_init');
	
	

	function pravel_invoice_create(){
		
		$customer_first_name = sanitize_text_field($_POST['customer_first_name']);
		$customer_last_name = sanitize_text_field($_POST['customer_last_name']);
		$custor_full_name_invoice = $customer_first_name.' '.$customer_last_name;
		$customer_email = sanitize_text_field($_POST['customer_email']);
		if($customer_email == ''){
			$customer_email = 'Not Mention';
		}
		$customer_mobile_number = sanitize_text_field($_POST['customer_mobile_number']);
		$invoice_total = sanitize_text_field($_POST['invoice_total']);
		$user_id = sanitize_text_field($_POST['user_id']);
		$invoice_data_content = 'Customer Name : '.$custor_full_name_invoice.'<br />		
		Customer Email : '.$customer_email.'<br />		
		Customer Phone Number : '.$customer_mobile_number.'
		<table>
		<thead>
		<tr>
		<th>No</th>
		<th>Product Name</th>
		<th>Product Price</th>
		<th>Quantity</th>
		<th>Total</th>
		</tr>
		</thead>
		<tbody>';
		
		
		$items = $_POST['items'];		
		$add_item_post_meta = array();
		$i = 0;
		$no = 1;
		foreach($items as $val){
			$add_item_post_meta[$i]['selected_item_name'] = sanitize_text_field($val['selected_item_name']);
			$add_item_post_meta[$i]['selected_item_price'] = sanitize_text_field($val['selected_item_price']);
			$add_item_post_meta[$i]['pravel_qty'] = sanitize_text_field($val['pravel_qty']);
			$add_item_post_meta[$i]['total_row_price'] = sanitize_text_field($val['total_row_price']);
			$invoice_data_content .= '
				<tr>
				<td>'. $no .'</td>
				<td>'.sanitize_text_field($val['selected_item_name']).'</td>
				<td>'.sanitize_text_field($val['selected_item_price']).'</td>
				<td>'.sanitize_text_field($val['pravel_qty']).'</td>
				<td>'.sanitize_text_field($val['total_row_price']).'</td>
				</tr>
			';
			$i++;
			$no++;
		}	
		$invoice_data_content .= '</tbody>
		</table>
		<strong>Final Total : '.$invoice_total.'</strong>';
		$random_invoice_number = date("Ymd").rand(1000,9999);		
		$new_invoice = array(
			'post_title'    => '#'.$random_invoice_number,			
			'post_content'  => $invoice_data_content,
			'post_status'   => 'publish',          
			'post_type'     => 'pravel_invoice',
			'post_author'   => $user_id
		);
		
		$new_invoice_id = wp_insert_post($new_invoice);
		
		add_post_meta($new_invoice_id, 'pravel_invoice_customer_first_name', $customer_first_name, true);
		add_post_meta($new_invoice_id, 'pravel_invoice_customer_last_name', $customer_last_name, true);
		add_post_meta($new_invoice_id, 'pravel_invoice_customer_email', $customer_email, true);
		add_post_meta($new_invoice_id, 'pravel_invoice_customer_mobile_number', $customer_mobile_number, true);
		add_post_meta($new_invoice_id, 'pravel_invoice_items', $add_item_post_meta, true);
		add_post_meta($new_invoice_id, 'pravel_invoice_random_invoice_number', $random_invoice_number, true);		
		add_post_meta($new_invoice_id, 'pravel_invoice_total', $invoice_total, true);		
		echo json_encode(array('success'=>true, 'message'=>__('Successfully create new invoice')));
		die();
		
	}
	
	function pravel_invoice_delete(){
		$product_id = sanitize_text_field($_POST['product_id']);		
		$user_id = sanitize_text_field($_POST['user_id']);
		$post_author_id = get_post_field( 'post_author', $product_id );
		if($post_author_id == $user_id){
			wp_delete_post( $product_id, true);
			echo json_encode(array('success'=>true, 'message'=>__('Successfully delete Invoice in your list')));
		}
		else
		{
			echo json_encode(array('success'=>false, 'message'=>__('Something is wrong')));
		}
		
		die();
	}
	function pravel_invoice_product_add(){
		$product_name = sanitize_text_field($_POST['product_name']);
		$product_price = sanitize_text_field($_POST['product_price']);
		$user_id = sanitize_text_field($_POST['user_id']);
		$new_product = array(
			'post_title'    => $product_name,			
			'post_status'   => 'publish',          
			'post_type'     => 'pravel_shop_product',
			'post_author'   => $user_id
		);
		
		$new_product_id = wp_insert_post($new_product);
		add_post_meta($new_product_id, 'pravel_invoice_product_price', $product_price, true);
		
		echo json_encode(array('success'=>true, 'message'=>__('Successfully add product in your list')));
		die();
	}
	
	
	function pravel_invoice_product_edit(){
		$product_name = sanitize_text_field($_POST['product_name']);
		$product_price = sanitize_text_field($_POST['product_price']);
		$product_id = sanitize_text_field($_POST['product_id']);
		$product_arr = array(
			  'ID'           => $product_id,
			  'post_title'   => $product_name,			 
		 );		 	
		wp_update_post( $product_arr );
		update_post_meta($product_id, 'pravel_invoice_product_price', $product_price);
		
		echo json_encode(array('success'=>true, 'message'=>__('Successfully add product in your list')));
		die();
	}
	
	
	
	function pravel_invoice_product_delete(){
		$product_id = sanitize_text_field($_POST['product_id']);		
		$user_id = sanitize_text_field($_POST['user_id']);
		$post_author_id = get_post_field( 'post_author', $product_id );
		if($post_author_id == $user_id){
			wp_delete_post( $product_id, true);
			echo json_encode(array('success'=>true, 'message'=>__('Successfully delete product in your list')));
		}
		else
		{
			echo json_encode(array('success'=>false, 'message'=>__('Something is wrong')));
		}
		
		die();
	}
	
	function pravel_invoice_mail_send(){
		
		$invoice_id = sanitize_text_field($_POST['invoice_id']);
		$customer_email = sanitize_text_field($_POST['customer_email']);
		$customer_first_name = get_post_meta($invoice_id, 'pravel_invoice_customer_first_name', true);
		$customer_last_name = get_post_meta($invoice_id, 'pravel_invoice_customer_last_name', true);		
		$customer_phone_number = get_post_meta($invoice_id, 'pravel_invoice_customer_mobile_number', true);
		$invoice_items = get_post_meta($invoice_id, 'pravel_invoice_items', true);
		$invoice_number = get_post_meta($invoice_id, 'pravel_invoice_random_invoice_number', true);		
		$invoice_final_total = get_post_meta($invoice_id, 'pravel_invoice_total', true);	
		$user_id = get_current_user_id();
		$company_name = get_user_meta($user_id, 'company_name', true);
		$company_address_1 = get_user_meta($user_id, 'company_address_1', true);
		$company_address_2 = get_user_meta($user_id, 'company_address_2', true);
		$company_city = get_user_meta($user_id, 'company_city', true);
		$company_pincode = get_user_meta($user_id, 'company_pincode', true);
		$company_logo_url = get_user_meta($user_id, 'company_logo', true);
		$company_mobile = get_user_meta($user_id, 'customer_mobile', true);
		
		$message = '
			<!DOCTYPE html>
			<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">

			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=us-ascii">
			<meta name="viewport" content="width=device-width">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="x-apple-disable-message-reformatting">			
			<link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&amp;display=swap" rel="stylesheet" />
			<title></title>
			<style type="text/css">
			html,
			body {
			margin: 0 auto !important;
			padding: 0 !important;
			height: 100% !important;
			width: 100% !important;
			background: #f1f1f1;
			font-weight: lighter;
			
			font-weight: 300;
			}

			* {
			-ms-text-size-adjust: 100%;
			-webkit-text-size-adjust: 100%;
			}

			div[style*="margin: 16px 0"] {
			margin: 0 !important;
			}

			table,
			td {
			mso-table-lspace: 0pt !important;
			mso-table-rspace: 0pt !important;
			}

			table {
			border-spacing: 0 !important;
			border-collapse: collapse !important;
			table-layout: fixed !important;
			margin: 0 auto !important;
			}

			img {
			-ms-interpolation-mode: bicubic;
			}

			a {
			text-decoration: none;
			}

			@media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
			u ~ div .email-container {
			min-width: 320px !important;
			}
			}

			@media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
			u ~ div .email-container {
			min-width: 375px !important;
			}
			}

			@media only screen and (min-device-width: 414px) {
			u ~ div .email-container {
			min-width: 414px !important;
			}
			}
			</style>
			<style type="text/css">
			h1,
			h2,
			h3,
			h4,
			h5,
			h6 {
			color: #000000;
			margin-top: 0;
			
			font-weight: 500;
			}

			body {
			font-weight: 300;
			font-size: 15px;
			line-height: 1.8;
			color: #354052;
			font-weight: lighter;
			}

			a {
			text-decoration: none !important;
			display: block;
			font-weight: 300;
			}

			p {
			font-weight: 300;
			}

			.invoice-box {
			max-width: 800px;
			margin-top: 20px;
			margin: auto;
			border: 1px solid #eee;
			font-size: 16px;
			line-height: 24px;
			color: #555;
			}

			.invoice-box table {
			width: 100%;
			line-height: inherit;
			text-align: left;
			}

			.invoice-box table td {
			padding: 5px;
			vertical-align: top;
			font-weight: 400;
			font-size: 14px;
			}

			.invoice-box table tr td:nth-child(2) {
			text-align: left;
			}

			.invoice-box table tr.top table td {
			padding-bottom: 20px;
			}

			.invoice-box table tr.top table td.title {
			font-size: 45px;
			line-height: 45px;
			color: #333;
			}

			.invoice-box table tr.information table td {
			padding-bottom: 40px;
			}

			.invoice-box table tr.heading td {
			background: #eee !important;
			border-bottom: 1px solid #ddd !important;
			font-weight: 600 !important;
			}

			.invoice-box table tr.details td {
			padding-bottom: 20px !important;
			}

			.invoice-box table tr.item td {
			border-bottom: 1px solid #eee;
			}

			.invoice-box table tr.item.last td {
			border-bottom: none;
			}

			.invoice-box table tr.total td:nth-child(5) {
			border-top: 2px solid #eee;
			font-weight: bold;
			}

			@media screen and (max-width: 750px) {
			.email-container {
			width: 100% !important;
			}
			}

			@media screen and (max-width: 500px) {
			</style>
			</head>

			<body style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #222222;" width="100%">
			<center style="width: 100%; background-color: #f1f1f1;">
			<div style="display: none; font-size: 1px;max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all;">&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;</div>

			<div class="email-container" style="max-width: 600px; margin: 0 auto; background-color: #20a7db12;">
			<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="margin: auto;" width="100%">
			<tbody>
			<tr>
			<td style="padding: 25px;">
			<table class="top-section" style="background: #fff; border-radius: 6px; width: 100%;">
			<tbody>
			<tr>
			<td class="p-side">
			<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="margin: auto;" width="100%">
			<tbody>
			<tr>
			<td class="logo" style="padding-bottom: 50px; text-align: center; font-weight: 600;font-size: 24px;color: #fff; background: #20a7db; width: 100%;padding: 10px 0px;text-decoration: none; display: block; text-transform: uppercase;">invoice</td>
			</tr>
			</tbody>
			</table>
			<div class="invoice-box" style="padding: 20px;">
			<table>
				<tr>
					<td>';
						if($company_logo_url != '')
						{
							$message .='<img src="'.$company_logo_url.'" class="pravel_brand_logo_img" />';
						}
					$message .= '</td>
					
					<td style="float:right;">
						<b>Invoice No : #'.$invoice_number.'</b><br>
						Send Date: '.date("d-m-Y").'												
					</td>
				</tr>
			</table>
			<table style="margin-bottom: 10px !important;">	
				<td style="border-top:2px solid #f2f2f2;"></td>
			</table>
			
			<table>
				<tr>
					<td>
						<b>Bill To </b></br>
						'.$customer_first_name.' '.$customer_last_name.'<br>
						Email : '.$customer_email.'<br>
						Phone No. : '.$customer_phone_number.'<br>
					</td>
					
					<td style="float:right;">';
					if($company_name != '' || $company_address_1 != '' || $company_address_2 != '' || $company_city != '' || $company_pincode != ''){
						$message .= '<b>Bill From </b></br>
						'.$company_name.'<br/>
						'.$company_address_1.'<br/>
						'.$company_address_2.'<br/>
						'.$company_city.' '.$company_pincode.'';
					}
					$message .= '</td>
				</tr>
			</table>
			<table >	
				<td style="border-top:2px solid #f2f2f2;"></td>
			</table>
			
			<table style="margin-top: 30px !important; width: 100%;">
			<tbody>
			<tr class="heading" >
			<td style="background: #eee; border-bottom: 1px solid #ddd;font-weight: 600;">No</td>
			<td style="background: #eee; border-bottom: 1px solid #ddd;font-weight: 600;">Product Name</td>
			<td style="background: #eee; border-bottom: 1px solid #ddd;font-weight: 600;">Product Price</td>
			<td style="background: #eee; border-bottom: 1px solid #ddd;font-weight: 600;">Quantity</td>
			<td style="background: #eee; border-bottom: 1px solid #ddd;font-weight: 600;">Total</td>
			</tr>';
			$no = 1;
			foreach($invoice_items as $val){				
				$message .= '<tr class="item">
				<td style="border-bottom: 1px solid #eee;">'.$no.'</td>
				<td style="border-bottom: 1px solid #eee;">'.$val['selected_item_name'].'</td>
				<td style="border-bottom: 1px solid #eee;">'.$val['selected_item_price'].'</td>
				<td style="border-bottom: 1px solid #eee;">'.$val['pravel_qty'].'</td>
				<td style="border-bottom: 1px solid #eee;">'.$val['total_row_price'].'</td>
				</tr>';
				$no++;
			}
			
			$message .= '<tr class="total">
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td style="font-weight: bold;">
			Total: '.$invoice_final_total.'
			</td>
			</tr>
			</tbody>
			</table>
			</div>

			</td>
			</tr>
			</tbody>
			</table>
			</td>
			</tr>
			</tbody>
			</table>
			<table style="margin-bottom: 10px !important;">	
				<td style="border-top:2px solid #f2f2f2;"></td>
			</table>
			<table>	
				<tr>
					<td style="text-align:center;">';
					if($company_mobile != ''){
						$message .='If you have any questions concerning this invoice, please contact <br/>
					Mobile Number : '.$company_mobile.'<br/>';
					}
					$message .='<b>THANK YOU FOR YOUR BUSINESS</b></td>
				</tr>
			</table>
			</div>
			</center>
			</body>

			</html>
		
		';
		$headers = array('Content-Type: text/html; charset=UTF-8');	
		$subject = 'Invoice #'.$invoice_number;
		wp_mail( $customer_email, $subject, $message, $headers );		
		echo json_encode(array('success'=>true, 'message'=>__('Successfully mail sent')));
		die();
	}
	
	
	
	