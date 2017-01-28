<!DOCTYPE html>
<!--[if IE 9 ]> <html <?php language_attributes(); ?> class="ie9 <?php flatsome_html_classes(); ?>"> <![endif]-->
<!--[if IE 8 ]> <html <?php language_attributes(); ?> class="ie8 <?php flatsome_html_classes(); ?>"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html <?php language_attributes(); ?> class="<?php flatsome_html_classes(); ?>"> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

	<meta name="author" content="Pro Gamer Store" />
	<meta name="contact" content="support@progamerstore.nl" />
	<meta name="copyright" content="Copyright (c)2017 Pro Gamer Store" />
	<meta name="description" content="Pro Gamer Store offers the best gaming gear to become a better gamer" />
	<meta name="keywords" content="pro, gamer, game, gaming, store, shop, headset, keyboard, mouse, controller, gamingheadset, gamingkeyboard, gamingmouse, gamingcontroller" />
<!--	<meta property="og:locale" content="en_US">-->
<!--	<meta property="og:type" content="website">-->
<!--	<meta property="og:title" content="Pro Gamer Store">-->
<!--	<meta property="og:description" content="Get the best gaming gear">-->
<!--	<meta property="og:url" content="https://www.progamerstore.com/">-->
<!--	<meta property="og:site_name" content="Pro Gamer Store">-->
<!--	<meta property="og:image" content="--><?php //echo get_stylesheet_directory_uri(); ?><!--/images/product_single.png">-->

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php wp_head(); ?>

	<link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/restyle.css'; ?>" />
	<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/pgs-favicon.ico" />
</head>

<body <?php body_class(); // Body classes is added from inc/helpers-frontend.php ?>>

<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'flatsome' ); ?></a>

<div id="wrapper">

<?php do_action('flatsome_before_header'); ?>

<header id="header" class="header <?php flatsome_header_classes();  ?>">
   <div class="header-wrapper">
	<?php
		get_template_part('template-parts/header/header', 'wrapper');
	?>
   </div><!-- header-wrapper-->
</header>

<?php do_action('flatsome_after_header'); ?>

<main id="main" class="<?php flatsome_main_classes();  ?>">

