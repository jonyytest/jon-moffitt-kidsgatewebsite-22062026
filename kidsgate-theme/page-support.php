<?php
/**
 * Support — searchable/categorised FAQ, contact form (UI only; backend
 * integration documented as pending), prominent support email, quick
 * links and the rule-based guided helper widget.
 */
get_header();

$support_email = kg_support_email();

// FAQ answers may carry {placeholders} for URLs/email — resolve them here.
$replacements = array(
	'{schools_url}'   => esc_url( kg_url( 'schools' ) ),
	'{pricing_url}'   => esc_url( kg_url( 'pricing' ) ),
	'{parents_url}'   => esc_url( kg_url( 'parents' ) ),
	'{support_email}' => esc_html( $support_email ),
);

$faq_items = array_map(
	function ( $item ) use ( $replacements ) {
		$item['a'] = strtr( $item['a'], $replacements );
		return $item;
	},
	kg_list( 'support.faq_items' )
);

// Shared activation-fee explainer (also shown as a tooltip and in the pricing FAQ).
$faq_items[] = array(
	'q'   => kg_t( 'pricing.activation_faq_q' ),
	'a'   => kg_t( 'pricing.activation_info' ),
	'cat' => 'plans',
);
?>

<section class="kg-page-hero kg-section--teal-wash">
	<div class="kg-container">
		<span class="kg-kicker" data-kg-reveal><?php kg_e( 'support.hero.kicker' ); ?></span>
		<h1 class="kg-h1" data-kg-reveal style="--kg-delay:80ms"><?php kg_e( 'support.hero.title' ); ?></h1>
		<p class="kg-lede" data-kg-reveal style="--kg-delay:160ms"><?php kg_e( 'support.hero.lede' ); ?></p>
	</div>
</section>

<section class="kg-section kg-section--white" style="padding-top: clamp(32px, 4vw, 48px);">
	<div class="kg-container">
		<!-- Search -->
		<div class="kg-support-search" data-kg-reveal>
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2.2"/><path d="m20 20-3.5-3.5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>
			<label class="kg-visually-hidden" for="kg-support-search-input"><?php kg_e( 'support.search_placeholder' ); ?></label>
			<input type="search" id="kg-support-search-input" data-kg-support-search placeholder="<?php echo esc_attr( kg_t( 'support.search_placeholder' ) ); ?>">
		</div>

		<!-- Category filters -->
		<div class="kg-support-cats" data-kg-reveal style="--kg-delay:100ms">
			<?php
			$cat_icons = array(
				'plans'   => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
				'product' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M4 19.5A2.5 2.5 0 0 0 6.5 22H20V2H6.5A2.5 2.5 0 0 0 4 4.5v15z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
				'account' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 6.5a3.5 3.5 0 1 1 0 7 3.5 3.5 0 0 1 0-7zM5 20c.8-3 3.6-5 7-5s6.2 2 7 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
			);
			$cat_bubbles = array( 'plans' => 'kg-bubble--amber', 'product' => 'kg-bubble--teal', 'account' => 'kg-bubble--red' );
			foreach ( kg_list( 'support.cats' ) as $cat_key => $cat_label ) :
				?>
				<button class="kg-card kg-support-cat" type="button" data-kg-support-cat="<?php echo esc_attr( $cat_key ); ?>" aria-pressed="false">
					<span class="kg-bubble <?php echo esc_attr( $cat_bubbles[ $cat_key ] ); ?>"><?php echo $cat_icons[ $cat_key ]; // phpcs:ignore ?></span>
					<span style="display:block;"><?php echo $cat_label; // phpcs:ignore ?></span>
				</button>
			<?php endforeach; ?>
		</div>

		<!-- FAQ -->
		<div class="kg-faq" data-kg-faq data-kg-faq-context="support">
			<?php foreach ( $faq_items as $i => $item ) : ?>
				<div class="kg-faq__item" data-kg-reveal style="--kg-delay:<?php echo (int) ( min( $i, 6 ) * 50 ); ?>ms" data-kg-faq-cat="<?php echo esc_attr( $item['cat'] ); ?>" data-kg-faq-text="<?php echo esc_attr( strtolower( wp_strip_all_tags( $item['q'] . ' ' . $item['a'] ) ) ); ?>">
				<h3 class="kg-faq__q">
					<button type="button" aria-expanded="false" aria-controls="kg-faq-panel-support-<?php echo (int) $i; ?>" id="kg-faq-btn-support-<?php echo (int) $i; ?>">
						<span><?php echo $item['q']; // phpcs:ignore ?></span>
						<svg class="kg-faq__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"/></svg>
					</button>
				</h3>
				<div class="kg-faq__a" id="kg-faq-panel-support-<?php echo (int) $i; ?>" role="region" aria-labelledby="kg-faq-btn-support-<?php echo (int) $i; ?>" hidden>
					<div class="kg-faq__a-inner"><?php echo wpautop( $item['a'] ); // phpcs:ignore ?></div>
				</div>
			</div>
			<?php endforeach; ?>
			<p class="kg-faq__empty"><?php kg_e( 'support.no_results' ); ?></p>
		</div>
	</div>
</section>

<!-- Contact form + quick links -->
<section class="kg-section kg-section--cream" id="support-form">
	<div class="kg-container">
		<div class="kg-support-grid">
			<div>
				<?php kg_section_head( 'support.form', false ); ?>
				<form data-kg-support-form data-kg-form-subject="The Kids Gate: Support Request" novalidate>
					<div class="kg-form-grid">
						<div class="kg-field">
							<label for="kg-sup-name"><?php kg_e( 'support.form.name' ); ?></label>
							<input type="text" id="kg-sup-name" name="kg_name" required autocomplete="name">
						</div>
						<div class="kg-field">
							<label for="kg-sup-email"><?php kg_e( 'support.form.email' ); ?></label>
							<input type="email" id="kg-sup-email" name="kg_email" required autocomplete="email">
						</div>
						<div class="kg-field kg-field--full">
							<label for="kg-sup-topic"><?php kg_e( 'support.form.topic' ); ?></label>
							<select id="kg-sup-topic" name="kg_topic" required>
								<?php foreach ( kg_list( 'support.form.topics' ) as $topic ) : ?>
									<option><?php echo $topic; // phpcs:ignore ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="kg-field kg-field--full">
							<label for="kg-sup-account"><?php kg_e( 'support.form.account' ); ?></label>
							<input type="text" id="kg-sup-account" name="kg_account">
						</div>
						<div class="kg-field kg-field--full">
							<label for="kg-sup-message"><?php kg_e( 'support.form.message' ); ?></label>
							<textarea id="kg-sup-message" name="kg_message" required></textarea>
						</div>
					</div>
					<button class="kg-btn kg-btn--primary kg-btn--lg" type="submit"><span><?php kg_e( 'support.form.submit' ); ?></span></button>
				</form>
				<div class="kg-form-success" data-kg-support-form-success hidden tabindex="-1">
					<svg width="42" height="42" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m5 13 4 4L19 7" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
					<h3 class="kg-h3"><?php kg_e( 'support.form.success_title' ); ?></h3>
					<p style="margin:0;"><?php kg_e( 'support.form.success_text' ); ?></p>
				</div>

				<div class="kg-form-note" style="margin-top:22px;">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true" style="flex:none; margin-top:2px;"><path d="M4 6h16a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1z" stroke="currentColor" stroke-width="2"/><path d="m3 7 9 6 9-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
					<span>
						<strong><?php kg_e( 'support.form.email_label' ); ?></strong>
						<a href="mailto:<?php echo esc_attr( $support_email ); ?>" data-kg-event="support_email_click" style="font-weight:800;"><?php echo esc_html( $support_email ); ?></a>
						<?php if ( ! kg_support_email_is_live() ) : ?>
							<em style="display:block; font-size:.85rem;"><?php kg_e( 'footer.email_placeholder_note' ); ?></em>
						<?php endif; ?>
					</span>
				</div>
			</div>

			<aside>
				<?php kg_section_head( 'support.links', false ); ?>
				<div style="display:grid; gap:12px;">
					<?php foreach ( kg_list( 'support.links.items' ) as $link ) : ?>
						<a class="kg-card kg-card--hover" style="display:flex; align-items:center; justify-content:space-between; gap:12px; padding:18px 22px;" href="<?php echo esc_url( kg_url( $link['slug'] ) ); ?>">
							<strong style="font-family:var(--kg-font-display); color:var(--kg-navy);"><?php echo $link['label']; // phpcs:ignore ?></strong>
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true" style="color:var(--kg-amber-deep); flex:none;"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
						</a>
					<?php endforeach; ?>
				</div>
			</aside>
		</div>
	</div>
</section>

<?php
// The guided helper widget is rendered globally from footer.php via
// kg_render_helper(), so it appears on every page.
get_footer();
