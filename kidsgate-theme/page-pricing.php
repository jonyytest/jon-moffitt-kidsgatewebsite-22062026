<?php
/**
 * Pricing — billing toggle, plan cards, the per-child family plan builder
 * (up to 6 children, mixed subject combinations, live total), billing
 * disclaimer and pricing FAQ. Plan selection routes to Support for now.
 */
get_header();
$p = kg_pricing_for_lang();
?>

<section class="kg-page-hero kg-section--cream">
	<div class="kg-container">
		<span class="kg-kicker" data-kg-reveal><?php kg_e( 'pricing.hero.kicker' ); ?></span>
		<h1 class="kg-h1" data-kg-reveal style="--kg-delay:80ms"><?php kg_e( 'pricing.hero.title' ); ?></h1>
		<p class="kg-lede" data-kg-reveal style="--kg-delay:160ms"><?php kg_e( 'pricing.hero.lede' ); ?></p>
	</div>
</section>

<?php kg_region_banner(); ?>

<!-- Plans + toggle -->
<section class="kg-section kg-section--white" style="padding-top: clamp(32px, 4vw, 48px);">
	<div class="kg-container">
		<div style="text-align:center;" data-kg-reveal>
			<div class="kg-price-toggle" data-kg-billing-toggle role="group" aria-label="<?php echo esc_attr( kg_t( 'pricing.toggle.monthly' ) . ' / ' . kg_t( 'pricing.toggle.yearly' ) ); ?>">
				<button type="button" data-kg-billing="m" aria-pressed="false"><?php kg_e( 'pricing.toggle.monthly' ); ?></button>
				<button type="button" data-kg-billing="y" aria-pressed="true"><?php kg_e( 'pricing.toggle.yearly' ); ?><span class="kg-save-pill" data-kg-save-annual aria-hidden="true"><?php echo esc_html( kg_save_label( $p['first'][1]['m'], $p['first'][1]['y'] ) ); ?></span></button>
			</div>
		</div>

		<div data-kg-rates
			data-m1="<?php echo esc_attr( $p['first'][1]['m'] ); ?>"
			data-y1="<?php echo esc_attr( $p['first'][1]['y'] ); ?>"
			data-m2="<?php echo esc_attr( $p['first'][2]['m'] ); ?>"
			data-y2="<?php echo esc_attr( $p['first'][2]['y'] ); ?>"
			data-label="<?php echo esc_attr( kg_t( 'pricing.save_label' ) ); ?>"
			hidden></div>
		<div class="kg-plans">
			<div class="kg-card kg-plan" data-kg-reveal>
				<h2 class="kg-h3"><?php kg_e( 'pricing.plans.one.name' ); ?></h2>
				<p style="color:var(--kg-text-soft); margin:0;"><?php kg_e( 'pricing.plans.one.desc' ); ?></p>
				<div class="kg-plan__price">
					<p class="kg-plan__was kg-plan__was--ghost" aria-hidden="true">&nbsp;</p>
					<span class="kg-plan__amount" data-kg-price-m="<?php echo esc_attr( kg_price( $p['first'][1]['m'] ) ); ?>" data-kg-price-y="<?php echo esc_attr( kg_price( $p['first'][1]['y'] ) ); ?>"><?php echo esc_html( kg_price( $p['first'][1]['m'] ) ); ?></span>
					<span class="kg-plan__per"><?php kg_e( 'pricing.plans.per_month' ); ?></span>
				</div>
				<p class="kg-plan__per" data-kg-billing-note="y" hidden><?php kg_e( 'pricing.plans.billed_yearly' ); ?></p>
				<ul class="kg-plan__list">
					<?php foreach ( kg_list( 'pricing.plans.one.features' ) as $f ) : ?>
						<li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m5 13 4 4L19 7" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg><?php echo $f; // phpcs:ignore ?></li>
					<?php endforeach; ?>
				</ul>
				<?php if ( kg_country() ) : ?>
				<a class="kg-btn kg-btn--secondary" href="#kg-builder"><span><?php kg_e( 'pricing.builder.kicker' ); ?></span></a>
				<?php else : ?>
				<button class="kg-btn kg-btn--secondary" type="button" data-kg-choose-region><span><?php kg_e( 'common.choose_region' ); ?></span></button>
				<?php endif; ?>
			</div>
			<div class="kg-card kg-plan kg-plan--featured" data-kg-reveal style="--kg-delay:110ms">
				<span class="kg-plan__flag"><?php kg_e( 'pricing.plans.two.flag' ); ?></span>
				<h2 class="kg-h3"><?php kg_e( 'pricing.plans.two.name' ); ?></h2>
				<p style="color:var(--kg-text-on-dark-soft); margin:0;"><?php kg_e( 'pricing.plans.two.desc' ); ?></p>
				<div class="kg-plan__price">
					<span class="kg-plan__amount" data-kg-price-m="<?php echo esc_attr( kg_price( $p['first'][2]['m'] ) ); ?>" data-kg-price-y="<?php echo esc_attr( kg_price( $p['first'][2]['y'] ) ); ?>"><?php echo esc_html( kg_price( $p['first'][2]['m'] ) ); ?></span>
					<span class="kg-plan__per"><?php kg_e( 'pricing.plans.per_month' ); ?></span>
					<span class="kg-plan__save" data-kg-save-card="two"><?php echo esc_html( kg_save_label( 2 * $p['first'][1]['y'], $p['first'][2]['y'] ) ); ?></span>
					<p class="kg-plan__was"><s><span data-kg-price-m="<?php echo esc_attr( kg_price( 2 * $p['first'][1]['m'] ) ); ?>" data-kg-price-y="<?php echo esc_attr( kg_price( 2 * $p['first'][1]['y'] ) ); ?>"><?php echo esc_html( kg_price( 2 * $p['first'][1]['y'] ) ); ?></span> <?php kg_e( 'pricing.plans.per_month' ); ?></s> <?php kg_e( 'pricing.plans.two.vs_singles' ); ?></p>
				</div>
				<p class="kg-plan__per" data-kg-billing-note="y" hidden><?php kg_e( 'pricing.plans.billed_yearly' ); ?></p>
				<ul class="kg-plan__list">
					<?php foreach ( kg_list( 'pricing.plans.two.features' ) as $f ) : ?>
						<li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m5 13 4 4L19 7" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg><?php echo $f; // phpcs:ignore ?></li>
					<?php endforeach; ?>
				</ul>
				<?php if ( kg_country() ) : ?>
				<a class="kg-btn kg-btn--primary" href="#kg-builder"><span><?php kg_e( 'pricing.builder.kicker' ); ?></span></a>
				<?php else : ?>
				<button class="kg-btn kg-btn--primary" type="button" data-kg-choose-region><span><?php kg_e( 'common.choose_region' ); ?></span></button>
				<?php endif; ?>
			</div>
		</div>
		<p style="text-align:center; margin-top:22px; color:var(--kg-text-soft);" data-kg-reveal>
			<?php
			$addl_note_m = str_replace( '{price}', kg_price( $p['addl'][1]['m'] ), kg_t( 'pricing.plans.addl_note' ) );
			$addl_note_y = str_replace( '{price}', kg_price( $p['addl'][1]['y'] ), kg_t( 'pricing.plans.addl_note' ) );
			?>
			<span data-kg-price-m="<?php echo esc_attr( $addl_note_m ); ?>" data-kg-price-y="<?php echo esc_attr( $addl_note_y ); ?>"><?php echo $addl_note_y; // phpcs:ignore ?></span>
		</p>
	</div>
</section>

<!-- Family plan builder -->
<section class="kg-section kg-section--cream" id="kg-builder">
	<div class="kg-container">
		<?php kg_section_head( 'pricing.builder' ); ?>

		<div class="kg-builder" data-kg-builder data-kg-reveal>
			<div>
				<div class="kg-builder__children" data-kg-builder-children></div>
				<button class="kg-builder__add" type="button" data-kg-builder-add style="width:100%; margin-top:16px;">
					+ <?php kg_e( 'pricing.builder.add_child' ); ?>
				</button>
			</div>

			<aside class="kg-builder__summary">
				<h3 class="kg-h3"><?php kg_e( 'pricing.builder.summary_title' ); ?></h3>
				<div class="kg-price-toggle kg-price-toggle--compact" data-kg-billing-toggle role="group" aria-label="<?php echo esc_attr( kg_t( 'pricing.toggle.monthly' ) . ' / ' . kg_t( 'pricing.toggle.yearly' ) ); ?>">
					<button type="button" data-kg-billing="m" aria-pressed="false"><?php kg_e( 'pricing.toggle.monthly' ); ?></button>
					<button type="button" data-kg-billing="y" aria-pressed="true"><?php kg_e( 'pricing.toggle.yearly' ); ?><span class="kg-save-pill" data-kg-save-annual aria-hidden="true"><?php echo esc_html( kg_save_label( $p['first'][1]['m'], $p['first'][1]['y'] ) ); ?></span></button>
				</div>
				<div class="kg-builder__rows" data-kg-builder-rows></div>
				<div class="kg-builder__activation" data-kg-builder-activation>
					<span>
						<strong><?php kg_e( 'pricing.builder.activation_label' ); ?></strong><?php echo kg_tip( kg_t( 'pricing.activation_info' ), kg_t( 'pricing.activation_help' ) ); // phpcs:ignore ?>
						<small data-kg-activation-sub></small>
					</span>
					<span class="kg-row-price" data-kg-activation-amount></span>
				</div>
				<p class="kg-builder__trial-lead"><?php kg_e( 'pricing.builder.trial_lead' ); ?></p>
				<div class="kg-builder__total">
					<span><?php kg_e( 'pricing.builder.total_label' ); ?></span>
					<span><strong data-kg-builder-total></strong> <span data-kg-builder-period style="font-size:.9rem;"></span></span>
				</div>
				<div class="kg-builder__firstpay" data-kg-builder-firstpay>
					<span><?php kg_e( 'pricing.builder.first_payment' ); ?></span>
					<span><strong data-kg-firstpay-amount></strong></span>
				</div>
				<p class="kg-builder__note kg-builder__note--tax"><?php kg_e( 'pricing.builder.tax_note' ); ?></p>
				<?php if ( kg_country() ) : ?>
				<a class="kg-btn kg-btn--primary kg-btn--block" href="<?php echo esc_url( kg_url( 'payment' ) ); ?>" data-kg-builder-select><span><?php kg_e( 'pricing.builder.select_cta' ); ?></span></a>
				<?php else : ?>
				<button class="kg-btn kg-btn--primary kg-btn--block" type="button" data-kg-choose-region><span><?php kg_e( 'common.choose_region' ); ?></span></button>
				<?php endif; ?>
				<p class="kg-builder__note" style="margin-bottom:0; margin-top:12px;"><?php kg_e( 'pricing.builder.select_note' ); ?></p>
			</aside>
		</div>

		<!-- Template cloned by pricing.js for each child row -->
		<template id="kg-child-template">
			<div class="kg-builder__child">
				<div class="kg-builder__child-head">
					<strong data-kg-child-label></strong>
					<button class="kg-builder__remove" type="button" data-kg-child-remove aria-label="<?php echo esc_attr( kg_t( 'pricing.builder.remove_child' ) ); ?>">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"/></svg>
					</button>
				</div>
				<div class="kg-builder__subjects">
					<button class="kg-subject-pick" type="button" data-kg-subject="english" aria-pressed="false"><?php kg_e( 'pricing.builder.english' ); ?></button>
					<button class="kg-subject-pick" type="button" data-kg-subject="maths" aria-pressed="false"><?php kg_e( 'pricing.builder.maths' ); ?></button>
				</div>
				<p class="kg-builder__rate" data-kg-child-rate style="margin:0;"></p>
			</div>
		</template>

		<div class="kg-pricing-disclaimer" data-kg-reveal>
			<svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><path d="M12 8v5m0 3.5v.01" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></svg>
			<p style="margin:0;"><?php kg_e( 'pricing.disclaimer' ); ?></p>
		</div>
	</div>
</section>

<!-- Pricing FAQ -->
<section class="kg-section kg-section--white">
	<div class="kg-container">
		<?php kg_section_head( 'pricing.faq' ); ?>
		<?php
		$pricing_faq   = kg_list( 'pricing.faq.items' );
		$pricing_faq[] = array(
			'q' => kg_t( 'pricing.activation_faq_q' ),
			'a' => kg_t( 'pricing.activation_info' ),
		);
		kg_faq_accordion( $pricing_faq, 'pricing' );
		?>
	</div>
</section>

<!-- Closing CTA -->
<section class="kg-section">
	<div class="kg-container">
		<div class="kg-final-cta" data-kg-reveal="pop">
			<h2 class="kg-h2"><?php kg_e( 'home.final.title' ); ?></h2>
			<div class="kg-final-cta__ctas">
				<?php if ( kg_country() ) : ?>
				<a class="kg-btn kg-btn--primary kg-btn--lg" href="#kg-builder" data-kg-event="free_trial_start"><span><?php kg_e( 'common.cta_primary' ); ?></span></a>
				<?php else : ?>
				<button class="kg-btn kg-btn--primary kg-btn--lg" type="button" data-kg-choose-region><span><?php kg_e( 'common.choose_region' ); ?></span></button>
				<?php endif; ?>
			</div>
			<?php kg_trust_chips( 'common.cancel_chips' ); ?>
		</div>
	</div>
</section>

<?php
get_footer();
