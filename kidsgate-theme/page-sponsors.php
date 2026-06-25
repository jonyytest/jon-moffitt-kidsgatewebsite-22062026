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
		<div class="kg-problems">
			<?php
			$icons = array(
				// Gift — prize draws
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 12v8a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-8M2 7h20v5H2zM12 21V7M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7zM12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
				// Coins — Kids Gate Store
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><ellipse cx="12" cy="6" rx="8" ry="3" stroke="currentColor" stroke-width="2"/><path d="M4 6v6c0 1.7 3.6 3 8 3s8-1.3 8-3V6M4 12v6c0 1.7 3.6 3 8 3s8-1.3 8-3v-6" stroke="currentColor" stroke-width="2"/></svg>',
				// Heart — learning access
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>',
				// Globe — brand presence
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><path d="M2 12h20M12 2c2.5 2.7 4 6.3 4 10s-1.5 7.3-4 10c-2.5-2.7-4-6.3-4-10s1.5-7.3 4-10z" stroke="currentColor" stroke-width="2"/></svg>',
			);
			$bubbles = array( 'kg-bubble--red', 'kg-bubble--amber', 'kg-bubble--teal', 'kg-bubble--navy' );
			foreach ( kg_list( 'sponsors.ways.items' ) as $i => $item ) :
				?>
				<div class="kg-card kg-card--arch" data-kg-reveal style="--kg-delay:<?php echo (int) ( $i * 100 ); ?>ms">
					<span class="kg-bubble <?php echo esc_attr( $bubbles[ $i % 4 ] ); ?>"><?php echo $icons[ $i % 4 ]; // phpcs:ignore ?></span>
					<h3 class="kg-h3"><?php echo $item['title']; // phpcs:ignore ?></h3>
					<p style="margin:0;"><?php echo $item['text']; // phpcs:ignore ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Sponsorship tiers -->
<section class="kg-section kg-section--white" id="kg-sponsor-tiers">
	<div class="kg-container">
		<?php kg_section_head( 'sponsors.tiers' ); ?>
		<div class="kg-tiers">
			<?php foreach ( kg_list( 'sponsors.tiers.items' ) as $i => $tier ) :
				$featured = ! empty( $tier['featured'] );
				?>
				<div class="kg-tier<?php echo $featured ? ' kg-tier--featured' : ''; ?>" data-kg-reveal style="--kg-delay:<?php echo (int) ( $i * 110 ); ?>ms">
					<?php if ( $featured ) : ?>
						<span class="kg-tier__flag"><?php kg_e( 'sponsors.tiers.popular' ); ?></span>
					<?php endif; ?>
					<h3 class="kg-tier__name"><?php echo $tier['name']; // phpcs:ignore ?></h3>
					<p class="kg-tier__price"><?php echo $tier['price']; // phpcs:ignore ?></p>
					<p class="kg-tier__blurb"><?php echo $tier['blurb']; // phpcs:ignore ?></p>
					<ul class="kg-tier__points">
						<?php foreach ( $tier['points'] as $point ) : ?>
							<li><svg width="17" height="17" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m5 13 4 4L19 7" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg><span><?php echo $point; // phpcs:ignore ?></span></li>
						<?php endforeach; ?>
					</ul>
					<a class="kg-btn <?php echo $featured ? 'kg-btn--primary' : 'kg-btn--secondary'; ?> kg-btn--block" href="#kg-sponsor-form" data-kg-event="sponsor_enquiry_start"><span><?php kg_e( 'sponsors.tiers.cta' ); ?></span></a>
				</div>
			<?php endforeach; ?>
		</div>
		<p style="text-align:center; color:var(--kg-text-soft); font-size:.9rem; margin-top:20px;" data-kg-reveal><?php kg_e( 'sponsors.tiers.note' ); ?></p>
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
			<form data-kg-support-form data-kg-form-subject="Kids Gate: Sponsor Enquiry" novalidate>
				<div class="kg-form-grid">
					<div class="kg-field">
						<label for="kg-sponsor-name"><?php kg_e( 'sponsors.form.name' ); ?></label>
						<input type="text" id="kg-sponsor-name" name="kg_name" required autocomplete="name">
					</div>
					<div class="kg-field">
						<label for="kg-sponsor-email"><?php kg_e( 'sponsors.form.email' ); ?></label>
						<input type="email" id="kg-sponsor-email" name="kg_email" required autocomplete="email">
					</div>
					<div class="kg-field">
						<label for="kg-sponsor-org"><?php kg_e( 'sponsors.form.org' ); ?></label>
						<input type="text" id="kg-sponsor-org" name="kg_org" required autocomplete="organization">
					</div>
					<div class="kg-field">
						<label for="kg-sponsor-interest"><?php kg_e( 'sponsors.form.interest' ); ?></label>
						<select id="kg-sponsor-interest" name="kg_topic" required>
							<?php foreach ( kg_list( 'sponsors.form.interest_opts' ) as $opt ) : ?>
								<option><?php echo $opt; // phpcs:ignore ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="kg-field kg-field--full">
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
