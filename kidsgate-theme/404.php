<?php
/**
 * 404 — a gate that leads nowhere. Playful, lightweight, with clear
 * routes back: Return Home, Visit Support and common destinations.
 */
get_header();
?>

<section class="kg-404">
	<span class="kg-float kg-float--bob kg-token" style="top:14%; left:10%; --kg-tilt:-9deg;" aria-hidden="true">?</span>
	<span class="kg-float kg-float--bob kg-float--slow kg-token kg-token--teal" style="top:22%; right:12%; --kg-tilt:8deg;" aria-hidden="true">!</span>
	<span class="kg-float kg-float--bob kg-token kg-token--red" style="bottom:18%; left:16%; --kg-tilt:5deg;" aria-hidden="true">×</span>

	<div class="kg-404__gate" aria-hidden="true">
		<span>4</span><span class="kg-404__zero"></span><span>4</span>
	</div>

	<h1 class="kg-h2" data-kg-reveal><?php kg_e( 'e404.title' ); ?></h1>
	<p class="kg-lede" data-kg-reveal style="max-width:34em; --kg-delay:100ms"><?php kg_e( 'e404.text' ); ?></p>

	<div class="kg-404__ctas" data-kg-reveal style="--kg-delay:180ms">
		<?php kg_cta( 'e404.home_cta', kg_url(), '', 'kg-btn kg-btn--primary kg-btn--lg' ); ?>
		<?php kg_cta( 'e404.support_cta', kg_url( 'support' ), '404_support_click', 'kg-btn kg-btn--secondary kg-btn--lg' ); ?>
	</div>

	<p style="color:var(--kg-text-soft); font-weight:700; margin-bottom:12px;" data-kg-reveal><?php kg_e( 'e404.links_label' ); ?></p>
	<div class="kg-404__links" data-kg-reveal>
		<a class="kg-chip" href="<?php echo esc_url( kg_url( 'pricing' ) ); ?>"><?php kg_e( 'nav.pricing' ); ?></a>
		<a class="kg-chip" href="<?php echo esc_url( kg_url( 'how-it-works' ) ); ?>"><?php kg_e( 'nav.how_it_works' ); ?></a>
		<a class="kg-chip" href="<?php echo esc_url( kg_url( 'sponsors' ) ); ?>"><?php kg_e( 'nav.sponsors' ); ?></a>
	</div>
</section>

<?php
get_footer();
