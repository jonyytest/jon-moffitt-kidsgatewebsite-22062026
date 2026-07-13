<?php
/**
 * Sponsors & Partners — B2B partnership pitch: impact stats, ways to
 * partner, sponsorship tiers, partner logo wall, the partnering journey,
 * a testimonial placeholder and the sponsor enquiry form (backend
 * integration documented as pending, reuses the shared support-form JS).
 */
get_header();
?>

<section class="kg-page-hero kg-section--teal-wash">
	<div class="kg-container">
		<span class="kg-kicker" data-kg-reveal><?php kg_e( 'sponsors.hero.kicker' ); ?></span>
		<h1 class="kg-h1" data-kg-reveal style="--kg-delay:80ms"><?php kg_e( 'sponsors.hero.title' ); ?></h1>
		<p class="kg-lede" data-kg-reveal style="--kg-delay:160ms"><?php kg_e( 'sponsors.hero.lede' ); ?></p>
		<div class="kg-hero__ctas" data-kg-reveal style="margin-top:26px; --kg-delay:240ms">
			<a class="kg-btn kg-btn--primary kg-btn--lg" href="#kg-sponsor-form" data-kg-event="sponsor_enquiry_start"><span><?php kg_e( 'sponsors.hero.cta' ); ?></span><svg class="kg-btn__arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
			<a class="kg-btn kg-btn--secondary kg-btn--lg" href="#kg-sponsor-tiers"><span><?php kg_e( 'sponsors.hero.cta_secondary' ); ?></span><svg class="kg-btn__arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
		</div>
		<div style="margin-top:20px;">
			<?php kg_trust_chips( 'sponsors.hero.chips' ); ?>
		</div>
	</div>
</section>

<!-- Impact / reach: animated counters — hidden for now, revisit when unique sponsor-specific stats are available -->
<?php /* <section class="kg-section kg-section--white">
	<div class="kg-container">
		<?php kg_section_head( 'sponsors.impact' ); ?>
		<div class="kg-stats">
			<?php foreach ( kg_list( 'sponsors.impact.items' ) as $i => $stat ) : ?>
				<div class="kg-stat kg-card" data-kg-reveal="pop" style="--kg-delay:<?php echo (int) ( $i * 90 ); ?>ms">
					<span class="kg-stat__num kg-counter" data-kg-count="<?php echo esc_attr( $stat['num'] ); ?>" data-kg-suffix="<?php echo esc_attr( $stat['suffix'] ); ?>">0</span>
					<span class="kg-stat__label"><?php echo $stat['label']; ?></span>
				</div>
			<?php endforeach; ?>
		</div>
		<p style="text-align:center; color:var(--kg-text-soft); font-size:.9rem; margin-top:18px;" data-kg-reveal><?php kg_e( 'sponsors.impact.note' ); ?></p>
	</div>
</section> */ ?>

<!-- Ways to partner -->
<section class="kg-section kg-section--cream-deep">
	<div class="kg-container">
		<?php kg_section_head( 'sponsors.ways' ); ?>
		<!-- Impact options: each partnership angle is a pickable card — sponsor
		     seal, an option number, and a selection bar that fills on hover —
		     leading into the sponsorship tiers below. -->
		<div class="kg-impact">
			<?php
			$icons = array(
				// Gift — prize draws
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 12v8a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-8M2 7h20v5H2zM12 21V7M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7zM12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
				// Coins — The Kids Gate Store
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><ellipse cx="12" cy="6" rx="8" ry="3" stroke="currentColor" stroke-width="2"/><path d="M4 6v6c0 1.7 3.6 3 8 3s8-1.3 8-3V6M4 12v6c0 1.7 3.6 3 8 3s8-1.3 8-3v-6" stroke="currentColor" stroke-width="2"/></svg>',
				// Heart — learning access
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>',
				// Globe — brand presence
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><path d="M2 12h20M12 2c2.5 2.7 4 6.3 4 10s-1.5 7.3-4 10c-2.5-2.7-4-6.3-4-10s1.5-7.3 4-10z" stroke="currentColor" stroke-width="2"/></svg>',
			);
			$tones = array( 'red', 'amber', 'teal', 'navy' );
			foreach ( kg_list( 'sponsors.ways.items' ) as $i => $item ) :
				$tone = $tones[ $i % 4 ];
				?>
				<a class="kg-card kg-card--arch kg-impact__card kg-impact--<?php echo esc_attr( $tone ); ?>" href="#kg-sponsor-tiers" data-kg-partner="<?php echo (int) $i; ?>" data-kg-reveal style="--kg-delay:<?php echo (int) ( $i * 100 ); ?>ms">
					<span class="kg-impact__seal">
						<span class="kg-bubble kg-bubble--<?php echo esc_attr( $tone ); ?>"><?php echo $icons[ $i % 4 ]; // phpcs:ignore ?></span>
						<svg class="kg-impact__star" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l2.4 7.2H22l-6 4.6 2.3 7.2-6.3-4.5-6.3 4.5L8 13.8 2 9.2h7.6z"/></svg>
					</span>
					<h3 class="kg-h3"><?php echo $item['title']; // phpcs:ignore ?></h3>
					<p class="kg-impact__text"><?php echo $item['text']; // phpcs:ignore ?></p>
					<span class="kg-impact__go" aria-hidden="true">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M12 5v14m-6-6 6 6 6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Sponsorship tiers: a podium of four levels, each pitched at a different
     kind of sponsor (local business → product brand → CSR company → strategic
     partner). Cards step upward toward the highest tier. -->
<section class="kg-section kg-section--white" id="kg-sponsor-tiers">
	<div class="kg-container">
		<?php kg_section_head( 'sponsors.tiers' ); ?>
		<div class="kg-tiers">
			<?php
			$tier_tones = array( 'teal', 'red', 'amber', 'navy' );
			$tier_icons = array(
				// Smile — community sponsor
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><path d="M8 14s1.5 2 4 2 4-2 4-2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M9 9h.01M15 9h.01" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"/></svg>',
				// Gift — prize partner
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 12v8a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-8M2 7h20v5H2zM12 21V7M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7zM12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
				// Graduation cap — learning partner
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M22 9 12 4 2 9l10 5 10-5z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M6 11.5V16c0 1.4 2.7 3 6 3s6-1.6 6-3v-4.5M22 9v5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
				// Crown — founding champion
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 8.5 7 12l5-7 5 7 4-3.5L19.4 18H4.6L3 8.5z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>',
			);
			$tiers_items = kg_list( 'sponsors.tiers.items' );
			$tier_count  = count( $tiers_items );
			foreach ( $tiers_items as $i => $tier ) :
				$tone     = $tier_tones[ $i % 4 ];
				$featured = ! empty( $tier['featured'] );
				$last     = ( $i === $tier_count - 1 );
				?>
				<div class="kg-tier kg-tier--<?php echo esc_attr( $tone ); ?><?php echo $featured ? ' kg-tier--featured' : ''; ?>" data-kg-reveal style="--kg-delay:<?php echo (int) ( $i * 110 ); ?>ms; --kg-tier-step:<?php echo (int) ( $tier_count - 1 - $i ); ?>;">
					<?php if ( $featured ) : ?>
						<span class="kg-tier__flag"><?php kg_e( 'sponsors.tiers.popular' ); ?></span>
					<?php elseif ( $last ) : ?>
						<span class="kg-tier__flag kg-tier__flag--top"><?php kg_e( 'sponsors.tiers.highest' ); ?></span>
					<?php endif; ?>
					<div class="kg-tier__head">
						<span class="kg-bubble kg-bubble--<?php echo esc_attr( $tone ); ?>"><?php echo $tier_icons[ $i % 4 ]; // phpcs:ignore ?></span>
						<span class="kg-tier__level" aria-hidden="true"><?php for ( $d = 0; $d < $tier_count; $d++ ) : ?><i<?php echo $d <= $i ? ' class="is-on"' : ''; ?>></i><?php endfor; ?></span>
					</div>
					<h3 class="kg-tier__name"><?php echo $tier['name']; // phpcs:ignore ?></h3>
					<p class="kg-tier__who"><?php echo $tier['who']; // phpcs:ignore ?></p>
					<p class="kg-tier__blurb"><?php echo $tier['blurb']; // phpcs:ignore ?></p>
					<?php if ( $i > 0 ) : ?>
						<p class="kg-tier__prev"><?php echo esc_html( str_replace( '{tier}', wp_strip_all_tags( $tiers_items[ $i - 1 ]['name'] ), kg_t( 'sponsors.tiers.everything_in' ) ) ); ?></p>
					<?php endif; ?>
					<ul class="kg-tier__points">
						<?php foreach ( $tier['points'] as $point ) : ?>
							<li><svg width="17" height="17" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m5 13 4 4L19 7" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg><span><?php echo $point; // phpcs:ignore ?></span></li>
						<?php endforeach; ?>
					</ul>
					<a class="kg-btn <?php echo $featured ? 'kg-btn--primary' : 'kg-btn--secondary'; ?> kg-btn--block" href="#kg-sponsor-form" data-kg-tier="<?php echo (int) $i; ?>" data-kg-event="sponsor_enquiry_start"><span><?php kg_e( 'sponsors.tiers.cta' ); ?></span></a>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="kg-tiers__adfree" data-kg-reveal>
			<svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 2 4 5.5V11c0 5 3.4 9.3 8 11 4.6-1.7 8-6 8-11V5.5L12 2z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="m8.6 12 2.3 2.3 4.5-4.6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
			<p style="margin:0;"><?php kg_e( 'sponsors.tiers.adfree_note' ); ?></p>
		</div>
		<p style="text-align:center; color:var(--kg-text-soft); font-size:.9rem; margin-top:18px;" data-kg-reveal><?php kg_e( 'sponsors.tiers.note' ); ?></p>
	</div>
</section>

<!-- Partner logo wall (placeholders) -->
<section class="kg-section kg-section--cream">
	<div class="kg-container">
		<?php kg_section_head( 'sponsors.partners' ); ?>
		<div class="kg-logos">
			<?php for ( $i = 0; $i < 6; $i++ ) : ?>
				<div class="kg-logo-tile" data-kg-reveal="pop" style="--kg-delay:<?php echo (int) ( $i * 70 ); ?>ms">
					<svg width="34" height="34" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="4" stroke="currentColor" stroke-width="1.6"/><path d="M3 16l5-5 4 4 3-3 6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><circle cx="9" cy="9" r="1.6" fill="currentColor"/></svg>
					<span><?php kg_e( 'sponsors.partners.placeholder' ); ?></span>
				</div>
			<?php endfor; ?>
		</div>
	</div>
</section>

<!-- How partnering works -->
<section class="kg-section kg-section--white">
	<div class="kg-container">
		<?php kg_section_head( 'sponsors.steps' ); ?>
		<div class="kg-steps-row">
			<?php foreach ( kg_list( 'sponsors.steps.items' ) as $i => $step ) : ?>
				<div class="kg-card kg-step-card" data-kg-reveal style="--kg-delay:<?php echo (int) ( $i * 110 ); ?>ms">
					<h3 class="kg-h3"><?php echo $step['title']; // phpcs:ignore ?></h3>
					<p style="margin:0;"><?php echo $step['text']; // phpcs:ignore ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Partner testimonial placeholder -->
<section class="kg-section kg-section--navy">
	<div class="kg-container">
		<div class="kg-card kg-card--navy kg-testimonial kg-testimonial--placeholder" style="max-width:680px; margin:0 auto;" data-kg-reveal>
			<span class="kg-testimonial__flag"><?php kg_e( 'sponsors.testimonial.flag' ); ?></span>
			<p class="kg-testimonial__quote">“<?php kg_e( 'sponsors.testimonial.quote' ); ?>”</p>
			<div class="kg-testimonial__who">
				<span class="kg-testimonial__avatar" style="background:var(--kg-amber);" aria-hidden="true">S</span>
				<span><strong style="color:#fff;"><?php kg_e( 'sponsors.testimonial.name' ); ?></strong><small style="color:var(--kg-text-on-dark-soft);"><?php kg_e( 'sponsors.testimonial.meta' ); ?></small></span>
			</div>
		</div>
	</div>
</section>

<!-- Sponsor enquiry form -->
<section class="kg-section kg-section--cream" id="kg-sponsor-form">
	<div class="kg-container">
		<?php kg_section_head( 'sponsors.form' ); ?>
		<div class="kg-schools-form">
			<form data-kg-support-form data-kg-form-subject="The Kids Gate: Sponsor Enquiry" novalidate>
				<!-- Honeypot: hidden from humans, dropped server-side when filled. -->
				<input type="text" name="kg_website" tabindex="-1" autocomplete="off" aria-hidden="true" class="kg-visually-hidden">
				<div class="kg-form-grid">
					<div class="kg-field">
						<label for="kg-sponsor-name"><?php kg_e( 'sponsors.form.name' ); ?></label>
						<input type="text" id="kg-sponsor-name" name="kg_name" required autocomplete="name">
					</div>
					<div class="kg-field">
						<label for="kg-sponsor-email"><?php kg_e( 'sponsors.form.email' ); ?></label>
						<input type="email" id="kg-sponsor-email" name="kg_email" required autocomplete="email">
					</div>
					<!-- Auto-filled from the tier cards above (data-kg-tier); index-aligned
					     with the tiers so it works across every locale. -->
					<div class="kg-field">
						<label for="kg-sponsor-level"><?php kg_e( 'sponsors.form.level' ); ?></label>
						<select id="kg-sponsor-level" name="kg_level">
							<option value=""><?php kg_e( 'sponsors.form.level_default' ); ?></option>
							<?php foreach ( kg_list( 'sponsors.tiers.items' ) as $tier ) : ?>
								<option><?php echo esc_html( wp_strip_all_tags( $tier['name'] ) ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<!-- Auto-filled from the "Ways to partner" cards (data-kg-partner). -->
					<div class="kg-field">
						<label for="kg-sponsor-interest"><?php kg_e( 'sponsors.form.interest' ); ?></label>
						<select id="kg-sponsor-interest" name="kg_topic" required>
							<?php foreach ( kg_list( 'sponsors.form.interest_opts' ) as $opt ) : ?>
								<option><?php echo $opt; // phpcs:ignore ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="kg-field">
						<label for="kg-sponsor-org"><?php kg_e( 'sponsors.form.org' ); ?></label>
						<input type="text" id="kg-sponsor-org" name="kg_org" required autocomplete="organization">
					</div>
					<div class="kg-field">
						<label for="kg-sponsor-budget"><?php kg_e( 'sponsors.form.budget' ); ?></label>
						<input type="text" id="kg-sponsor-budget" name="kg_budget">
					</div>
					<div class="kg-field kg-field--full">
						<label for="kg-sponsor-message"><?php kg_e( 'sponsors.form.message' ); ?></label>
						<textarea id="kg-sponsor-message" name="kg_message"></textarea>
					</div>
				</div>
				<button class="kg-btn kg-btn--primary kg-btn--lg" type="submit" data-kg-event="sponsor_enquiry_submit"><span><?php kg_e( 'sponsors.form.submit' ); ?></span></button>
			</form>
			<div class="kg-form-success" data-kg-support-form-success hidden tabindex="-1">
				<svg width="42" height="42" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m5 13 4 4L19 7" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
				<h3 class="kg-h3"><?php kg_e( 'sponsors.form.success_title' ); ?></h3>
				<p style="margin:0;"><?php kg_e( 'sponsors.form.success_text' ); ?></p>
			</div>
		</div>
	</div>
</section>

<!-- Closing CTA -->
<section class="kg-section">
	<div class="kg-container">
		<div class="kg-final-cta" data-kg-reveal="pop">
			<h2 class="kg-h2"><?php kg_e( 'sponsors.cta.title' ); ?></h2>
			<p class="kg-lede"><?php kg_e( 'sponsors.cta.lede' ); ?></p>
			<div class="kg-final-cta__ctas">
				<a class="kg-btn kg-btn--primary kg-btn--lg" href="#kg-sponsor-form" data-kg-event="sponsor_enquiry_start"><span><?php kg_e( 'sponsors.hero.cta' ); ?></span></a>
			</div>
			<p style="margin-top:16px;">
				<a href="mailto:<?php echo esc_attr( kg_support_email() ); ?>" data-kg-event="support_email_click"><?php echo esc_html( kg_support_email() ); ?></a>
			</p>
		</div>
	</div>
</section>

<?php
get_footer();
