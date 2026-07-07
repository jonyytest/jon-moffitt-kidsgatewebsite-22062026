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

				<!-- Living learning path: the dot is the child moving through
				     lesson nodes. When a topic is missed, the AI scans the miss
				     and grows a re-teach detour (story, game, quiz) beneath the
				     path; the child masters the topic, then the journey carries
				     on. The path reshaping itself around the mistake IS the
				     demo of adaptive reinforcement. -->
				<div class="kg-aipath" data-kg-aipath aria-hidden="true">
					<span class="kg-aipath__ai"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 2l2.4 7.6L22 12l-7.6 2.4L12 22l-2.4-7.6L2 12l7.6-2.4L12 2z" fill="currentColor"/></svg><?php echo esc_html( $av['core'] ); ?></span>
					<svg class="kg-aipath__svg" viewBox="0 0 460 258" xmlns="http://www.w3.org/2000/svg" focusable="false">
						<defs>
							<linearGradient id="kg-apgrad" x1="0" y1="0" x2="1" y2="0">
								<stop offset="0" stop-color="#2ABFBF"/><stop offset="1" stop-color="#3BA55C"/>
							</linearGradient>
							<linearGradient id="kg-apgrad-det" x1="0" y1="0" x2="1" y2="0">
								<stop offset="0" stop-color="#F5A623"/><stop offset="1" stop-color="#2ABFBF"/>
							</linearGradient>
						</defs>
						<path class="kg-aipath__rail" data-kg-path-main d="M32 140 C82 140, 92 92, 138 92 C184 92, 192 128, 230 128 C268 128, 276 92, 322 92 C368 92, 378 128, 428 128"/>
						<path class="kg-aipath__prog" data-kg-prog-main d="M32 140 C82 140, 92 92, 138 92 C184 92, 192 128, 230 128 C268 128, 276 92, 322 92 C368 92, 378 128, 428 128"/>
						<path class="kg-aipath__rail kg-aipath__rail--detour" data-kg-path-detour d=""/>
						<path class="kg-aipath__prog kg-aipath__prog--detour" data-kg-prog-detour d=""/>
						<line class="kg-aipath__beam" data-kg-beam x1="424" y1="14" x2="230" y2="108"/>
						<g data-kg-fx></g>
						<g data-kg-nodes></g>
						<g class="kg-aipath__card" data-kg-card>
							<rect x="-56" y="-16" width="112" height="32" rx="16"/>
							<text dy=".35em"><tspan data-kg-card-glyph></tspan><tspan data-kg-card-word></tspan></text>
						</g>
						<circle class="kg-aipath__dot" data-kg-dot cx="32" cy="140" r="7"/>
					</svg>
				</div>

				<p class="kg-aiviz__status" data-kg-aiviz-status role="status">&nbsp;</p>
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

<!-- Spotlight: parent dashboard — a centred showcase moment rather than
     another image-plus-bullets row: the real dashboard floats in a laptop
     frame over a soft glow, with the three capabilities as chips below. -->
<section class="kg-section kg-section--white kg-dashspot">
	<div class="kg-container">
		<div class="kg-dashspot__head">
			<span class="kg-kicker" data-kg-reveal><?php kg_e( 'features.spotlight_dash.kicker' ); ?></span>
			<h2 class="kg-h2" data-kg-reveal style="--kg-delay:80ms"><?php kg_e( 'features.spotlight_dash.title' ); ?></h2>
			<p class="kg-lede" data-kg-reveal style="--kg-delay:160ms"><?php kg_e( 'features.spotlight_dash.text' ); ?></p>
		</div>

		<div class="kg-dashspot__stage" data-kg-reveal="pop" style="--kg-delay:120ms">
			<span class="kg-dashspot__glow" aria-hidden="true"></span>
			<div class="kg-dashspot__laptop">
				<button type="button" class="kg-dashspot__screen kg-lightbox-trigger"
					data-kg-lightbox
					data-kg-lightbox-zoom="<?php echo esc_attr( kg_t( 'common.zoom_hint' ) ); ?>"
					data-kg-lightbox-zoomout="<?php echo esc_attr( kg_t( 'common.zoom_out_hint' ) ); ?>"
					aria-label="<?php echo esc_attr( kg_t( 'common.view_larger' ) ); ?>">
					<img src="<?php echo esc_url( kg_asset( 'img/dashboard-activity.png' ) ); ?>" alt="<?php echo esc_attr( kg_t( 'features.spotlight_dash.img_alt' ) ); ?>" loading="lazy" decoding="async" width="1400" height="884">
					<span class="kg-dashspot__glare" aria-hidden="true"></span>
					<span class="kg-lightbox-trigger__badge" aria-hidden="true">
						<svg width="17" height="17" viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2.2"/><path d="m20 20-3.2-3.2M11 8.4v5.2M8.4 11h5.2" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>
						<?php kg_e( 'common.view_larger' ); ?>
					</span>
				</button>
				<span class="kg-dashspot__base" aria-hidden="true"></span>
			</div>
		</div>

		<ul class="kg-dashspot__chips">
			<?php foreach ( kg_list( 'features.spotlight_dash.points' ) as $i => $point ) : ?>
				<li data-kg-reveal="pop" style="--kg-delay:<?php echo (int) ( 240 + $i * 130 ); ?>ms">
					<svg width="17" height="17" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m5 13 4 4L19 7" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
					<?php echo $point; // phpcs:ignore ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<!-- Feature orbit: the KG logo is the sun, the eight features orbit it as
     planets on two slowly-turning rings. Falls back to a plain grid with the
     sun on top below 1100px. -->
<section class="kg-section kg-section--cream">
	<div class="kg-container">
		<?php kg_section_head( 'features.grid' ); ?>
		<div class="kg-orbit">
			<div class="kg-orbit__rings" aria-hidden="true">
				<span class="kg-orbit__ring kg-orbit__ring--1"></span>
				<span class="kg-orbit__ring kg-orbit__ring--2"></span>
			</div>

			<div class="kg-orbit__sun" data-kg-reveal="pop">
				<span class="kg-orbit__core">
					<span class="kg-orbit__rays" aria-hidden="true"></span>
					<span class="kg-orbit__glow" aria-hidden="true"></span>
					<img src="<?php echo esc_url( kg_asset( 'img/kg-logo-fancy.png' ) ); ?>" alt="The Kids Gate" width="200" height="207" loading="lazy">
				</span>
				<div class="kg-orbit__stats">
					<?php $s = kg_list( 'home.stats.items' ); ?>
					<span><strong>1,800+</strong><small><?php echo esc_html( $s[0]['label'] ?? 'lessons' ); ?></small></span>
					<span><strong>Gr.&nbsp;1–6</strong><small><?php echo esc_html( $s[1]['label'] ?? 'grades' ); ?></small></span>
				</div>
			</div>

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
			foreach ( kg_list( 'features.grid.items' ) as $i => $item ) :
				?>
				<div class="kg-orbit__item kg-orbit__item--<?php echo (int) ( $i + 1 ); ?>" data-kg-reveal="pop" style="--kg-delay:<?php echo (int) ( 160 + $i * 80 ); ?>ms">
					<div class="kg-orbit__card">
						<span class="kg-bubble <?php echo esc_attr( $bubbles[ $i % 4 ] ); ?>"><?php echo $icons[ $i % 8 ]; // phpcs:ignore ?></span>
						<h3 class="kg-h3"><?php echo $item['title']; // phpcs:ignore ?></h3>
						<p><?php echo $item['text']; // phpcs:ignore ?></p>
					</div>
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
