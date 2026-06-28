<?php
/**
 * Header template
 *
 * @package classic-starter
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php echo esc_attr(get_bloginfo('charset')); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="header">
  <div class="inner">
    <?php if (is_front_page() && is_home()) : ?>
      <h1 class="header-ttl">
          <a class="header-ttl-link" href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html(get_bloginfo('name')); ?></a>
      </h1>
    <?php else : ?>
      <p class="header-ttl">
          <a class="header-ttl-link" href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html(get_bloginfo('name')); ?></a>
      </p>
    <?php endif; ?>

    <?php $siteDescription = get_bloginfo('description'); ?>
    <?php if ($siteDescription) : ?>
      <p class="header-description"><?php echo esc_html($siteDescription); ?></p>
    <?php endif; ?>
  </div>
</header>

<main class="main">
