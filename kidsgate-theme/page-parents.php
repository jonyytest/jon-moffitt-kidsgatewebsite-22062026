<?php
/**
 * For Parents — dashboard walkthrough (tappable annotated tour),
 * family profiles, safety, trial reassurance and parent FAQ.
 * Tone: warmer and calmer than the child-facing sections.
 */
get_header();
?>

<section class="kg-page-hero kg-section--cream">
	<div class="kg-container">
		<span class="kg-kicker" data-kg-reveal><?php kg_e( 'parents.hero.kicker' ); ?></span>
		<h1 class="kg-h1" data-kg-reveal style="--kg-delay:80ms"><?php kg_e( 'parents.hero.title' ); ?></h1>
		<p class="kg-lede" data-kg-reveal style="--kg-delay:160ms"><?php kg_e( 'parents.hero.lede' ); ?></p>
	</div>
</section>

<!-- Dashboard tour: live annotated mock + linked explanation cards -->
<section class="kg-section kg-section--white">
	<div class="kg-container">
		<?php kg_section_head( 'parents.tour', false ); ?>
		<?php
		$tour = kg_list( 'parents.tour.items' );
		// Five annotation zones over the screenshot. The two views (Activity /
		// Performance) are almost the same layout but the Performance art sits
		// ~1% further right, so each view carries its own measured positions.
		// Each entry is [ top, left, width, height ] as % of the 1100×1000 shot.
		$zone_pos = array(
			'a' => array(            // Activity view
				array( 15.7, 2.4,  95, 14 ), // stat row (blue / red / green boxes)
				array( 33.7, 3.7,  45.5, 22.7 ), // top-left card
				array( 33.7, 50.5, 45.5, 22.7 ), // top-right card
				array( 58, 3.7,  45.5, 22.7 ), // bottom-left card
				array( 58, 50.5, 45.5, 22.7 ), // bottom-right card
			),
			'b' => array(            // Performance view
				array( 15.7, 2.4,  95, 14 ),
				array( 33.7, 3.7,  45.5, 22.7 ),
				array( 33.7, 50.5, 45.5, 22.7 ),
				array( 58, 3.7,  45.5, 22.7 ),
				array( 58, 50.5, 45.5, 22.7 ),
			),
		);
		$zone_style = function ( $p ) {
			return sprintf( 'top:%s%%;left:%s%%;width:%s%%;height:%s%%;', $p[0], $p[1], $p[2], $p[3] );
		};
		?>
		<div class="kg-tour" data-kg-tour>

			<div class="kg-tour__stage" data-kg-tour-stage data-kg-reveal="left"
				data-img-a="<?php echo esc_url( kg_asset( 'img/parent-activity.png' ) ); ?>"
				data-img-b="<?php echo esc_url( kg_asset( 'img/parent-performance.png' ) ); ?>"
				data-alt-a="<?php echo esc_attr( kg_t( 'parents.tour.activity_alt' ) ); ?>"
				data-alt-b="<?php echo esc_attr( kg_t( 'parents.tour.performance_alt' ) ); ?>"
				data-label-a="<?php echo esc_attr( kg_t( 'parents.tour.activity_label' ) ); ?>"
				data-label-b="<?php echo esc_attr( kg_t( 'parents.tour.performance_label' ) ); ?>">
				<span class="kg-tour__blob kg-tour__blob--1" aria-hidden="true"></span>
				<span class="kg-tour__blob kg-tour__blob--2" aria-hidden="true"></span>
				<div class="kg-tour__frame">
					<div class="kg-tour__bar" aria-hidden="true">
						<span class="kg-tour__dot kg-tour__dot--r"></span>
						<span class="kg-tour__dot kg-tour__dot--a"></span>
						<span class="kg-tour__dot kg-tour__dot--t"></span>
						<span class="kg-tour__barlabel" data-kg-tour-barlabel><?php echo esc_html( kg_t( 'parents.tour.activity_label' ) ); ?></span>
					</div>
					<div class="kg-tour__shot">
						<img class="kg-tour__img" data-kg-tour-img
							src="<?php echo esc_url( kg_asset( 'img/parent-activity.png' ) ); ?>"
							alt="<?php echo esc_attr( kg_t( 'parents.tour.activity_alt' ) ); ?>"
							width="1087" height="984" loading="lazy" decoding="async">
						<?php foreach ( $zone_pos['a'] as $i => $pa ) : ?>
							<button type="button" class="kg-tour__zone" data-kg-tour-zone="<?php echo (int) $i; ?>"
								data-pos-a="<?php echo esc_attr( $zone_style( $pa ) ); ?>"
								data-pos-b="<?php echo esc_attr( $zone_style( $zone_pos['b'][ $i ] ) ); ?>"
								style="<?php echo esc_attr( $zone_style( $pa ) ); ?>"
								aria-label="<?php echo esc_attr( isset( $tour[ $i ]['title_a'] ) ? wp_strip_all_tags( $tour[ $i ]['title_a'] ) : '' ); ?>">
								<span class="kg-tour__pin" aria-hidden="true"><?php echo (int) ( $i + 1 ); ?></span>
							</button>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

			<div class="kg-tour__list" data-kg-reveal="right">
				<?php foreach ( $tour as $i => $item ) : ?>
					<div class="kg-tour__item" data-kg-tour-item
						data-title-a="<?php echo esc_attr( $item['title_a'] ); ?>"
						data-text-a="<?php echo esc_attr( $item['text_a'] ); ?>"
						data-title-b="<?php echo esc_attr( $item['title_b'] ); ?>"
						data-text-b="<?php echo esc_attr( $item['text_b'] ); ?>">
						<button type="button" class="kg-tour__head" data-kg-tour-head aria-expanded="false" aria-controls="kg-tour-panel-<?php echo (int) $i; ?>">
							<span class="kg-tour__index" aria-hidden="true"><?php echo (int) ( $i + 1 ); ?></span>
							<span class="kg-tour__title" data-kg-tour-title><?php echo esc_html( $item['title_a'] ); ?></span>
							<svg class="kg-tour__plus" width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"/></svg>
						</button>
						<div class="kg-tour__panel" id="kg-tour-panel-<?php echo (int) $i; ?>">
							<div><p data-kg-tour-text><?php echo esc_html( $item['text_a'] ); ?></p></div>
						</div>
					</div>
				<?php endforeach; ?>

				<button type="button" class="kg-tour__item kg-tour__toggle" data-kg-tour-item data-kg-tour-toggle
					data-title-a="<?php echo esc_attr( kg_t( 'parents.tour.toggle.title_a' ) ); ?>"
					data-text-a="<?php echo esc_attr( kg_t( 'parents.tour.toggle.text_a' ) ); ?>"
					data-title-b="<?php echo esc_attr( kg_t( 'parents.tour.toggle.title_b' ) ); ?>"
					data-text-b="<?php echo esc_attr( kg_t( 'parents.tour.toggle.text_b' ) ); ?>">
					<span class="kg-tour__index kg-tour__index--swap" aria-hidden="true"><?php echo (int) ( count( $tour ) + 1 ); ?></span>
					<span class="kg-tour__toggle-copy">
						<span class="kg-tour__title" data-kg-tour-title><?php echo esc_html( kg_t( 'parents.tour.toggle.title_a' ) ); ?></span>
						<span class="kg-tour__toggle-sub" data-kg-tour-text><?php echo esc_html( kg_t( 'parents.tour.toggle.text_a' ) ); ?></span>
					</span>
					<svg class="kg-tour__toggle-arrow" width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
				</button>
			</div>

		</div>
	</div>
</section>

<!-- Family profiles: one-account family hub. Six child avatars orbit a single
     parent-account home (four smiling, two open "room to grow" slots) —
     literally "up to six children, one account" — with the three family
     features as warm rows beside it. Mirrors the safety section's
     visual-plus-list composition, in the light family palette. -->
<section class="kg-section kg-section--teal-wash kg-fam">
	<div class="kg-container">
		<?php kg_section_head( 'parents.profiles' ); ?>
		<div class="kg-fam__grid">
			<ul class="kg-fam__list">
				<?php
				$fam_icons = array(
					'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 3.1a4 4 0 1 1 0 7.8M22 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
					'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M4 19.5A2.5 2.5 0 0 0 6.5 22H20V2H6.5A2.5 2.5 0 0 0 4 4.5v15z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
					'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
				);
				$fam_tones = array( 'teal', 'amber', 'red' );
				foreach ( kg_list( 'parents.profiles.items' ) as $i => $item ) :
					?>
					<li class="kg-fam__item" data-kg-reveal="left" style="--kg-delay:<?php echo (int) ( $i * 140 ); ?>ms">
						<span class="kg-bubble kg-bubble--<?php echo esc_attr( $fam_tones[ $i % 3 ] ); ?>"><?php echo $fam_icons[ $i % 3 ]; // phpcs:ignore ?></span>
						<div class="kg-fam__copy">
							<h3 class="kg-h3"><?php echo $item['title']; // phpcs:ignore ?></h3>
							<p><?php echo $item['text']; // phpcs:ignore ?></p>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>

			<div class="kg-fam__viz" data-kg-reveal="pop" aria-hidden="true">
				<svg viewBox="0 0 320 320" fill="none" focusable="false">
					<!-- spokes: every profile connects to the one parent account -->
					<g class="kg-fam__spokes" stroke-width="2" stroke-linecap="round" stroke-dasharray="1 8">
						<path d="M160 160 275 160"/><path d="M160 160 217.5 259.6"/><path d="M160 160 102.5 259.6"/>
						<path d="M160 160 45 160"/><path d="M160 160 102.5 60.4"/><path d="M160 160 217.5 60.4"/>
					</g>

					<!-- parent-account home at the centre -->
					<g class="kg-fam__hub">
						<rect x="114" y="114" width="92" height="92" rx="26" class="kg-fam__hub-bg"/>
						<path d="M160 138l-19 16v22a3 3 0 0 0 3 3h32a3 3 0 0 0 3-3v-22l-19-16z" fill="#fff"/>
						<rect x="153" y="162" width="14" height="17" rx="2" class="kg-fam__hub-door"/>
						<g transform="translate(198 122)">
							<circle r="13" class="kg-fam__heart-bg"/>
							<path d="M0 5.4C-1.8 2.4 -5.6 1.2 -5.6 -1.9a3.2 3.2 0 0 1 5.6-2.1 3.2 3.2 0 0 1 5.6 2.1C5.6 1.2 1.8 2.4 0 5.4z" fill="#fff"/>
						</g>
					</g>

					<!-- six slots: four children home, two spots waiting -->
					<g transform="translate(275 160)"><g class="kg-fam__float" style="--kg-fd:0s"><g class="kg-fam__kid kg-fam__kid--amber" style="--kg-kd:120ms">
						<circle r="30" class="kg-fam__kid-bg"/>
						<circle cx="-10" cy="-5" r="2.8" class="kg-fam__kid-ink"/><circle cx="10" cy="-5" r="2.8" class="kg-fam__kid-ink"/>
						<path d="M-10 7q10 9 20 0" class="kg-fam__kid-smile"/>
					</g></g></g>
					<g transform="translate(217.5 259.6)"><g class="kg-fam__float" style="--kg-fd:.9s"><g class="kg-fam__kid kg-fam__kid--teal" style="--kg-kd:240ms">
						<circle r="30" class="kg-fam__kid-bg"/>
						<circle cx="-10" cy="-5" r="2.8" class="kg-fam__kid-ink"/><circle cx="10" cy="-5" r="2.8" class="kg-fam__kid-ink"/>
						<path d="M-10 7q10 9 20 0" class="kg-fam__kid-smile"/>
					</g></g></g>
					<g transform="translate(102.5 259.6)"><g class="kg-fam__float" style="--kg-fd:1.8s"><g class="kg-fam__kid kg-fam__kid--red" style="--kg-kd:360ms">
						<circle r="30" class="kg-fam__kid-bg"/>
						<circle cx="-10" cy="-5" r="2.8" class="kg-fam__kid-ink"/><circle cx="10" cy="-5" r="2.8" class="kg-fam__kid-ink"/>
						<path d="M-10 7q10 9 20 0" class="kg-fam__kid-smile"/>
					</g></g></g>
					<g transform="translate(45 160)"><g class="kg-fam__float" style="--kg-fd:2.7s"><g class="kg-fam__kid kg-fam__kid--green" style="--kg-kd:480ms">
						<circle r="30" class="kg-fam__kid-bg"/>
						<circle cx="-10" cy="-5" r="2.8" class="kg-fam__kid-ink"/><circle cx="10" cy="-5" r="2.8" class="kg-fam__kid-ink"/>
						<path d="M-10 7q10 9 20 0" class="kg-fam__kid-smile"/>
					</g></g></g>
					<g transform="translate(102.5 60.4)"><g class="kg-fam__float" style="--kg-fd:1.3s"><g class="kg-fam__kid kg-fam__kid--slot" style="--kg-kd:600ms">
						<circle r="30" class="kg-fam__slot-bg"/>
						<path d="M-9 0h18M0-9v18" class="kg-fam__slot-plus"/>
					</g></g></g>
					<g transform="translate(217.5 60.4)"><g class="kg-fam__float" style="--kg-fd:2.2s"><g class="kg-fam__kid kg-fam__kid--slot" style="--kg-kd:720ms">
						<circle r="30" class="kg-fam__slot-bg"/>
						<path d="M-9 0h18M0-9v18" class="kg-fam__slot-plus"/>
					</g></g></g>
				</svg>
			</div>
		</div>
	</div>
</section>

<!-- Everyday extras: pinned-note strip. Deliberately breaks from the card
     grids above (profiles) and below (safety) — tilted colour-wash notes
     "taped" to the page, with the middle note hung lower. -->
<section class="kg-section kg-section--white">
	<div class="kg-container">
		<?php kg_section_head( 'parents.perks' ); ?>
		<ul class="kg-perks">
			<?php
			$perk_icons = array(
				// Cloud with down arrow — offline / cached lessons
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 18a4 4 0 0 1-.5-7.97A6 6 0 0 1 18 8.5a4.5 4.5 0 0 1 .5 8.97" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 11v6m0 0-2.5-2.5M12 17l2.5-2.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
				// Open padlock — no lock-in, cancel any time
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2" stroke="currentColor" stroke-width="2"/><path d="M7 11V7a5 5 0 0 1 9.9-1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
				// Flag — weakness alerts
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 22V4M4 4s1.5-1 4-1 4 2 6.5 2S19 4 19 4v10s-2 1-4.5 1S10 13 7.5 13 4 14 4 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
			);
			$perk_tones = array( 'teal', 'amber', 'red' );
			foreach ( kg_list( 'parents.perks.items' ) as $i => $item ) :
				?>
				<li class="kg-perks__slot" data-kg-reveal="pop" style="--kg-delay:<?php echo (int) ( $i * 120 ); ?>ms">
					<div class="kg-perk kg-perk--<?php echo esc_attr( $perk_tones[ $i % 3 ] ); ?>">
						<span class="kg-perk__tape" aria-hidden="true"></span>
						<span class="kg-perk__icon"><?php echo $perk_icons[ $i % 3 ]; // phpcs:ignore ?></span>
						<h3 class="kg-h3"><?php echo $item['title']; // phpcs:ignore ?></h3>
						<p><?php echo $item['text']; // phpcs:ignore ?></p>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<!-- Safety: shield-and-checklist security treatment. A guarding shield with
     slow radar rings on the left; the three protections as a connected
     "security checklist" rail with verified seals on the right. -->
<section class="kg-section kg-section--navy kg-safe">
	<div class="kg-container">
		<?php kg_section_head( 'parents.safety' ); ?>
		<div class="kg-safe__grid">
			<div class="kg-safe__shield" data-kg-reveal="pop" aria-hidden="true">
				<span class="kg-safe__ring kg-safe__ring--1"></span>
				<span class="kg-safe__ring kg-safe__ring--2"></span>
				<span class="kg-safe__ring kg-safe__ring--3"></span>
				<svg class="kg-safe__shield-svg" viewBox="0 0 120 132" fill="none" focusable="false">
					<path class="kg-safe__shield-body" d="M60 5 14 23v37c0 30.8 19.6 52.6 46 62 26.4-9.4 46-31.2 46-62V23L60 5z"/>
					<path class="kg-safe__shield-line" d="M60 15 23 29.5V60c0 25.4 15.9 43.8 37 52.4 21.1-8.6 37-27 37-52.4V29.5L60 15z"/>
					<path class="kg-safe__shield-tick" d="m41 64 14 14 26-28"/>
				</svg>
			</div>
			<ul class="kg-safe__list">
				<?php
				$safety_icons = array(
					'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 2 4 6v6c0 5 3.4 8.5 8 10 4.6-1.5 8-5 8-10V6l-8-4z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
					'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M18.4 18.4A9 9 0 0 1 5.6 5.6m2.2-1.5A9 9 0 0 1 19.9 16.2M2 2l20 20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
					'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2" stroke="currentColor" stroke-width="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
				);
				foreach ( kg_list( 'parents.safety.items' ) as $i => $item ) :
					?>
					<li class="kg-safe__item" data-kg-reveal="right" style="--kg-delay:<?php echo (int) ( $i * 140 ); ?>ms">
						<span class="kg-safe__badge" aria-hidden="true"><?php echo $safety_icons[ $i % 3 ]; // phpcs:ignore ?></span>
						<div class="kg-safe__copy">
							<h3 class="kg-h3"><?php echo $item['title']; // phpcs:ignore ?></h3>
							<p><?php echo $item['text']; // phpcs:ignore ?></p>
						</div>
						<svg class="kg-safe__seal" width="28" height="28" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="10" fill="currentColor" opacity=".14"/><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.6"/><path d="m8 12.2 2.6 2.6L16 9.6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>

<!-- How to join -->
<section class="kg-section kg-section--cream" id="kg-join">
	<div class="kg-container">
		<?php kg_section_head( 'parents.join' ); ?>
		<ol class="kg-join">
			<?php
			$join_icons = array(
				// Add person — create account
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M14 19v-1.5a3.5 3.5 0 0 0-3.5-3.5H6a3.5 3.5 0 0 0-3.5 3.5V19M8 11a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7zM18 8v6m3-3h-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
				// Clipboard check — assessment
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M9 3h6a1 1 0 0 1 1 1v1h2a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h2V4a1 1 0 0 1 1-1z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="m9 13 2 2 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
				// Open book — learning
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 6.5C10.5 5 8 4.5 4 4.5V18c4 0 6.5.5 8 2 1.5-1.5 4-2 8-2V4.5c-4 0-6.5.5-8 2zM12 6.5V20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
				// Target — stay informed
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="5" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="1.5" fill="currentColor"/></svg>',
			);
			$join_bubbles = array( 'kg-bubble--teal', 'kg-bubble--amber', 'kg-bubble--red', 'kg-bubble--navy' );
			$join_items   = kg_list( 'parents.join.items' );
			$last         = count( $join_items ) - 1;
			foreach ( $join_items as $i => $item ) :
				$is_last = ( $i === $last );
				?>
				<li class="kg-join__step<?php echo $is_last ? ' kg-join__step--cta' : ''; ?>" data-kg-reveal style="--kg-delay:<?php echo (int) ( $i * 110 ); ?>ms">
					<span class="kg-join__num"><?php echo (int) ( $i + 1 ); ?></span>
					<span class="kg-bubble <?php echo esc_attr( $join_bubbles[ $i % 4 ] ); ?> kg-join__icon"><?php echo $join_icons[ $i % 4 ]; // phpcs:ignore ?></span>
					<h3 class="kg-h3"><?php echo $item['title']; // phpcs:ignore ?></h3>
					<p class="kg-join__text"><?php echo $item['text']; // phpcs:ignore ?></p>
					<?php if ( $is_last ) : ?>
						<div class="kg-join__cta">
							<?php kg_cta( 'common.cta_primary', kg_url( 'pricing' ), 'free_trial_start', 'kg-btn kg-btn--primary kg-btn--lg' ); ?>
						</div>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>

<!-- Parent FAQ -->
<section class="kg-section kg-section--white">
	<div class="kg-container">
		<?php kg_section_head( 'parents.faq' ); ?>
		<?php kg_faq_accordion( kg_list( 'parents.faq.items' ), 'parents' ); ?>
	</div>
</section>

<?php
get_footer();
