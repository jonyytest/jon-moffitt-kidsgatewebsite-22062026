<?php
/**
 * Features — AI personalisation and the parent dashboard get spotlight
 * rows with extra visual weight; the remaining features form a card grid.
 */
get_header();
?>

<section class="kg-page-hero kg-section--cream">
	<div class="kg-container">
		<span class="kg-kicker" data-kg-reveal><?php kg_e( 'features.hero.kicker' ); ?></span>
		<h1 class="kg-h1" data-kg-reveal style="--kg-delay:80ms"><?php kg_e( 'features.hero.title' ); ?></h1>
		<p class="kg-lede" data-kg-reveal style="--kg-delay:160ms"><?php kg_e( 'features.hero.lede' ); ?></p>
	</div>
</section>

<!-- Spotlight: adaptive AI -->
<section class="kg-section kg-section--navy">
	<div class="kg-container">
		<div class="kg-spot">
			<?php $av = kg_t( 'features.spotlight_ai.aiviz' ); ?>
			<div class="kg-aiviz" data-kg-reveal="left" data-kg-aiviz>
				<script type="application/json" data-kg-aiviz-data><?php echo wp_json_encode( $av ); ?></script>
				<div class="kg-aiviz__head">
					<span class="kg-aiviz__title"><span class="kg-aiviz__live-dot" aria-hidden="true"></span><?php echo esc_html( $av['label'] ); ?></span>
					<span class="kg-aiviz__badge"><?php echo esc_html( $av['live'] ); ?></span>
				</div>

				<div class="kg-aiviz__flow" aria-hidden="true">
					<div class="kg-aiviz__card kg-aiviz__card--answer" data-kg-answer-card>
						<small><?php echo esc_html( $av['answer'] ); ?></small>
						<span class="kg-aiviz__skill" data-kg-answer-skill></span>
						<span class="kg-aiviz__result" data-kg-answer-result></span>
					</div>
					<div class="kg-aiviz__core" data-kg-core>
						<span class="kg-aiviz__core-ring"></span>
						<span class="kg-aiviz__core-ring"></span>
						<span class="kg-aiviz__core-label"><?php echo esc_html( $av['core'] ); ?></span>
					</div>
					<div class="kg-aiviz__card kg-aiviz__card--action" data-kg-action-card>
						<small><?php echo esc_html( $av['action'] ); ?></small>
						<span class="kg-aiviz__action-text" data-kg-action-text></span>
					</div>
				</div>

				<p class="kg-aiviz__status" data-kg-aiviz-status role="status">&nbsp;</p>

				<div class="kg-aiviz__map">
					<span class="kg-aiviz__map-label"><?php echo esc_html( $av['map'] ); ?></span>
					<?php foreach ( $av['skills'] as $i => $skill ) : ?>
						<div class="kg-aiviz__row" data-kg-row="<?php echo (int) $i; ?>">
							<span class="kg-aiviz__row-name"><?php echo esc_html( $skill ); ?></span>
							<span class="kg-aiviz__bar"><span class="kg-aiviz__bar-fill" data-kg-fill></span></span>
							<span class="kg-aiviz__pct" data-kg-pct></span>
						</div>
					<?php endforeach; ?>
				</div>
				<p class="kg-aiviz__disclaimer">For illustrative purposes only. This visualisation is a simplified conceptual representation of the adaptive learning process and does not depict the actual underlying system architecture or algorithms.</p>
			</div>
			<div data-kg-reveal="right">
				<span class="kg-kicker"><?php kg_e( 'features.spotlight_ai.kicker' ); ?></span>
				<h2 class="kg-h2"><?php kg_e( 'features.spotlight_ai.title' ); ?></h2>
				<p class="kg-lede"><?php kg_e( 'features.spotlight_ai.text' ); ?></p>
				<ul class="kg-exp__points">
					<?php foreach ( kg_list( 'features.spotlight_ai.points' ) as $point ) : ?>
						<li><svg width="17" height="17" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m5 13 4 4L19 7" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg><?php echo $point; // phpcs:ignore ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</section>

<!-- Spotlight: parent dashboard -->
<section class="kg-section kg-section--white">
	<div class="kg-container">
		<div class="kg-spot kg-spot--flip">
			<div class="kg-spot__visual" data-kg-reveal="right">
				<img src="<?php echo esc_url( kg_asset( 'img/dashboard-activity.png' ) ); ?>" alt="<?php echo esc_attr( kg_t( 'features.spotlight_dash.img_alt' ) ); ?>" loading="lazy" width="1400" height="884">
			</div>
			<div data-kg-reveal="left">
				<span class="kg-kicker"><?php kg_e( 'features.spotlight_dash.kicker' ); ?></span>
				<h2 class="kg-h2"><?php kg_e( 'features.spotlight_dash.title' ); ?></h2>
				<p class="kg-lede"><?php kg_e( 'features.spotlight_dash.text' ); ?></p>
				<ul class="kg-exp__points">
					<?php foreach ( kg_list( 'features.spotlight_dash.points' ) as $point ) : ?>
						<li><svg width="17" height="17" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m5 13 4 4L19 7" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg><?php echo $point; // phpcs:ignore ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</section>

<!-- Feature grid -->
<section class="kg-section kg-section--cream">
	<div class="kg-container">
		<?php kg_section_head( 'features.grid' ); ?>
		<div class="kg-feature-grid">
			<?php
			// Navy bubbles swapped to green; teal/amber/red kept as-is.
			$bubbles = array( 'kg-bubble--teal', 'kg-bubble--amber', 'kg-bubble--red', 'kg-bubble--green' );
			$icons   = array(
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M4 19.5A2.5 2.5 0 0 0 6.5 22H20V2H6.5A2.5 2.5 0 0 0 4 4.5v15z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M9 7h6m-6 4h6m-6 4h3M5 3h14a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 6.5a3.5 3.5 0 1 1 0 7 3.5 3.5 0 0 1 0-7zM5 20c.8-3 3.6-5 7-5s6.2 2 7 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><path d="M2 12h20M12 2a15 15 0 0 1 0 20 15 15 0 0 1 0-20z" stroke="currentColor" stroke-width="2"/></svg>',
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 8h12l-1 13H7L6 8zM9 8V6a3 3 0 0 1 6 0v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M8 21h8m-4-4v4m-6-9a6 6 0 0 0 12 0V4H6v8zM6 9H4a2 2 0 0 1-2-2V5h4m12 4h2a2 2 0 0 0 2-2V5h-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 3v18h18M9 17V9m4 8V5m4 12v-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 2 4 6v6c0 5 3.4 8.5 8 10 4.6-1.5 8-5 8-10V6l-8-4z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
			);
			$grid_items = kg_list( 'features.grid.items' );
			// Split: first 4 items fill rows 1–2 left half, insert hero in center,
			// then remaining 4 fill rows 2–3 right half.
			$split = 4;
			foreach ( $grid_items as $i => $item ) :
				// After item 3 (0-indexed), inject the KG hero card.
				if ( $i === $split ) : ?>
				<div class="kg-card kg-feature-grid__hero" data-kg-reveal="pop" style="--kg-delay:360ms">
					<div class="kg-feature-grid__hero-glow" aria-hidden="true"></div>
					<img class="kg-feature-grid__hero-logo" src="<?php echo esc_url( kg_asset( 'img/kg-logo-fancy.png' ) ); ?>" alt="Kids Gate" width="200" height="207" loading="lazy">
					<div class="kg-feature-grid__hero-stats">
						<?php $s = kg_list( 'home.stats.items' ); ?>
						<span><strong>1,800+</strong><small><?php echo esc_html( $s[0]['label'] ?? 'lessons' ); ?></small></span>
						<span><strong>Gr.&nbsp;1–6</strong><small><?php echo esc_html( $s[1]['label'] ?? 'grades' ); ?></small></span>
					</div>
				</div>
				<?php endif; ?>
				<div class="kg-card kg-card--hover" data-kg-reveal style="--kg-delay:<?php echo (int) ( ( $i % 4 ) * 90 ); ?>ms">
					<span class="kg-bubble <?php echo esc_attr( $bubbles[ $i % 4 ] ); ?>"><?php echo $icons[ $i % 8 ]; // phpcs:ignore ?></span>
					<h3 class="kg-h3"><?php echo $item['title']; // phpcs:ignore ?></h3>
					<p style="margin:0;"><?php echo $item['text']; // phpcs:ignore ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Closing CTA -->
<section class="kg-section">
	<div class="kg-container">
		<div class="kg-final-cta" data-kg-reveal="pop">
			<h2 class="kg-h2"><?php kg_e( 'features.cta.title' ); ?></h2>
			<p class="kg-lede"><?php kg_e( 'features.cta.lede' ); ?></p>
			<div class="kg-final-cta__ctas">
				<?php kg_cta( 'common.cta_primary', kg_url( 'pricing' ), 'free_trial_start', 'kg-btn kg-btn--primary kg-btn--lg' ); ?>
				<?php kg_cta( 'common.cta_secondary', kg_url( 'how-it-works' ), '', 'kg-btn kg-btn--ghost-dark kg-btn--lg' ); ?>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
