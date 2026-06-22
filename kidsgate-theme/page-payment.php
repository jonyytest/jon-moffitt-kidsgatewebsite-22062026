<?php
/**
 * Payment — placeholder shown after a visitor selects a plan in the pricing
 * builder. Secure checkout is not connected yet, so this page reassures the
 * visitor and routes them to Support (or email) and back Home.
 *
 * When the real checkout is built, replace this template with the payment flow.
 */
get_header();
?>

<section class="kg-404 kg-payment">
	<span class="kg-float kg-float--bob kg-token" style="top:15%; left:11%; --kg-tilt:-9deg;" aria-hidden="true">$</span>
	<span class="kg-float kg-float--bob kg-float--slow kg-token kg-token--teal" style="top:23%; right:13%; --kg-tilt:8deg;" aria-hidden="true">&#10003;</span>
	<span class="kg-float kg-float--bob kg-token kg-token--red" style="bottom:19%; left:17%; --kg-tilt:5deg;" aria-hidden="true">&#9733;</span>

	<div class="kg-payment__badge" data-kg-reveal aria-hidden="true">
		<svg width="46" height="46" viewBox="0 0 24 24" fill="none">
			<rect x="2.5" y="5" width="19" height="14" rx="3" stroke="currentColor" stroke-width="1.9"/>
			<path d="M2.5 9.5h19" stroke="currentColor" stroke-width="1.9"/>
			<path d="M6 15h4" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/>
		</svg>
	</div>

	<span class="kg-kicker" data-kg-reveal><?php kg_e( 'payment.kicker' ); ?></span>
	<h1 class="kg-h2" data-kg-reveal style="--kg-delay:60ms"><?php kg_e( 'payment.title' ); ?></h1>
	<p class="kg-lede" data-kg-reveal style="max-width:38em; --kg-delay:120ms"><?php kg_e( 'payment.text' ); ?></p>

	<div class="kg-404__ctas" data-kg-reveal style="--kg-delay:200ms">
		<?php kg_cta( 'payment.support_cta', kg_url( 'support' ), 'support_page_view', 'kg-btn kg-btn--primary kg-btn--lg' ); ?>
		<?php kg_cta( 'payment.home_cta', kg_url(), '', 'kg-btn kg-btn--secondary kg-btn--lg' ); ?>
	</div>

	<p class="kg-payment__email" data-kg-reveal style="--kg-delay:260ms">
		<?php kg_e( 'payment.email_label' ); ?>
		<a href="mailto:<?php echo esc_attr( kg_support_email() ); ?>" data-kg-event="support_email_click"><?php echo esc_html( kg_support_email() ); ?></a>
	</p>
</section>

<?php
get_footer();
