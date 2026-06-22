<?php
/**
 * Site header: skip link, sticky pill navigation with KG logo,
 * language switcher and the persistent Start Free Trial CTA.
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> lang="<?php echo esc_attr( kg_lang() ); ?>">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="<?php echo esc_attr( kg_t( 'meta.description' ) ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'kg-lang-' . kg_lang() ); ?>>
<?php wp_body_open(); ?>

<a class="kg-skip-link" href="#kg-main"><?php kg_e( 'common.skip_to_content' ); ?></a>

<header class="kg-header" data-kg-header>
	<div class="kg-header__inner">
		<a class="kg-logo" href="<?php echo esc_url( kg_url() ); ?>" aria-label="<?php echo esc_attr( kg_t( 'common.home_aria' ) ); ?>">
			<img src="<?php echo esc_url( kg_asset( 'img/kg-logo.png' ) ); ?>" alt="Kids Gate" width="46" height="48">
			<span class="kg-logo__word">
				<span class="kg-logo__word-default">Kids <em>Gate</em></span>
				<span class="kg-logo__word-home" aria-hidden="true"><span><?php kg_e( 'nav.home_hover' ); ?></span></span>
			</span>
		</a>

		<nav class="kg-nav" id="kg-nav" aria-label="<?php echo esc_attr( kg_t( 'common.nav_aria' ) ); ?>">
			<ul class="kg-nav__list">
				<li class="kg-nav__mobile-home"><a href="<?php echo esc_url( kg_url() ); ?>"<?php kg_nav_active_attr( '' ); ?>><?php kg_e( 'nav.home' ); ?></a></li>
				<li><a href="<?php echo esc_url( kg_url( 'how-it-works' ) ); ?>"<?php kg_nav_active_attr( 'how-it-works' ); ?>><?php kg_e( 'nav.how_it_works' ); ?></a></li>
				<li><a href="<?php echo esc_url( kg_url( 'features' ) ); ?>"<?php kg_nav_active_attr( 'features' ); ?>><?php kg_e( 'nav.features' ); ?></a></li>
				<li><a href="<?php echo esc_url( kg_url( 'parents' ) ); ?>"<?php kg_nav_active_attr( 'parents' ); ?>><?php kg_e( 'nav.parents' ); ?></a></li>
				<li><a href="<?php echo esc_url( kg_url( 'pricing' ) ); ?>"<?php kg_nav_active_attr( 'pricing' ); ?>><?php kg_e( 'nav.pricing' ); ?></a></li>
				<li><a href="<?php echo esc_url( kg_url( 'schools' ) ); ?>"<?php kg_nav_active_attr( 'schools' ); ?>><?php kg_e( 'nav.schools' ); ?></a></li>
				<li><a href="<?php echo esc_url( kg_url( 'leaderboard' ) ); ?>"<?php kg_nav_active_attr( 'leaderboard' ); ?>><?php kg_e( 'nav.leaderboard' ); ?></a></li>
			</ul>
			<div class="kg-nav__mobile-foot">
				<?php kg_language_switcher( '-m' ); ?>
				<?php kg_cta( 'common.cta_primary', kg_url( 'pricing' ), 'free_trial_start', 'kg-btn kg-btn--primary kg-btn--block' ); ?>
			</div>
		</nav>

		<div class="kg-header__actions">
			<?php kg_language_switcher(); ?>
			<?php kg_cta( 'common.cta_primary', kg_url( 'pricing' ), 'free_trial_start', 'kg-btn kg-btn--primary kg-btn--sm kg-header__cta' ); ?>
			<button class="kg-burger" type="button" aria-expanded="false" aria-controls="kg-nav" data-kg-burger>
				<span class="kg-burger__line"></span>
				<span class="kg-burger__line"></span>
				<span class="kg-burger__line"></span>
				<span class="kg-visually-hidden"><?php kg_e( 'common.menu' ); ?></span>
			</button>
		</div>
	</div>
</header>

<main id="kg-main" class="kg-main">
