<?php
/**
 * VedaWays WordPress Theme — header.php
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<nav class="navbar" id="navbar">
  <div class="nav-container">
    <a href="<?php echo home_url('/'); ?>" class="nav-logo">
      <span class="logo-veda">Veda</span><span class="logo-ways">Ways</span>
    </a>
    <?php wp_nav_menu([
      'theme_location' => 'primary',
      'menu_class'     => 'nav-links',
      'container'      => false,
      'fallback_cb'    => function() {
        echo '<ul class="nav-links">
          <li><a href="#journeys">Journeys</a></li>
          <li><a href="#destinations">Destinations</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>';
      },
    ]); ?>
    <a href="#plan" class="btn-plan-nav">Plan My Trip</a>
    <button class="hamburger" id="hamburger"><span></span><span></span><span></span></button>
  </div>
</nav>
