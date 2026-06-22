<?php
/**
 * About Us — mission statement, brand story, values, global scope
 * and company contact details.
 */
get_header();
?>

<section class="kg-page-hero kg-section--cream">
	<div class="kg-container">
		<span class="kg-kicker" data-kg-reveal><?php kg_e( 'about.hero.kicker' ); ?></span>
		<h1 class="kg-h1" data-kg-reveal style="--kg-delay:80ms"><?php kg_e( 'about.hero.title' ); ?></h1>
	</div>
</section>

<!-- Mission -->
<section class="kg-section kg-section--white">
	<div class="kg-container">
		<p class="kg-mission" data-kg-reveal="pop"><?php kg_e( 'about.mission' ); ?></p>
	</div>
</section>

<!-- Story -->
<section class="kg-section kg-section--cream-deep">
	<div class="kg-container">
		<div class="kg-spot">
			<div class="kg-spot__visual kg-spot__visual--arch" data-kg-reveal="left">
				<img src="<?php echo esc_url( kg_asset( 'img/achievement.jpg' ) ); ?>" alt="A child celebrating a learning achievement with Kids Gate" loading="lazy" width="1600" height="1195">
			</div>
			<div data-kg-reveal="right">
				<span class="kg-kicker"><?php kg_e( 'about.story.kicker' ); ?></span>
				<h2 class="kg-h2"><?php kg_e( 'about.story.title' ); ?></h2>
				<?php foreach ( kg_list( 'about.story.paras' ) as $para ) : ?>
					<p class="kg-lede" style="font-size:1.05rem;"><?php echo $para; // phpcs:ignore ?></p>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<!-- Values -->
<section class="kg-section kg-section--white">
	<div class="kg-container">
		<?php kg_section_head( 'about.values' ); ?>
		<div class="kg-values">
			<?php
			$icons = array(
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M22 10 12 5 2 10l10 5 10-5zM6 12v5c0 1.7 2.7 3 6 3s6-1.3 6-3v-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>',
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 2 4 6v6c0 5 3.4 8.5 8 10 4.6-1.5 8-5 8-10V6l-8-4z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
			);
			$bubbles = array( 'kg-bubble--amber', 'kg-bubble--red', 'kg-bubble--teal' );
			foreach ( kg_list( 'about.values.items' ) as $i => $item ) :
				?>
				<div class="kg-card kg-card--arch" data-kg-reveal style="--kg-delay:<?php echo (int) ( $i * 110 ); ?>ms">
					<span class="kg-bubble <?php echo esc_attr( $bubbles[ $i % 3 ] ); ?>"><?php echo $icons[ $i % 3 ]; // phpcs:ignore ?></span>
					<h3 class="kg-h3"><?php echo $item['title']; // phpcs:ignore ?></h3>
					<p style="margin:0;"><?php echo $item['text']; // phpcs:ignore ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Global -->
<section class="kg-section kg-section--navy">
	<div class="kg-container kg-container--narrow" style="text-align:center;">
		<div data-kg-reveal>
			<span class="kg-kicker"><?php kg_e( 'about.global.kicker' ); ?></span>
			<h2 class="kg-h2"><?php kg_e( 'about.global.title' ); ?></h2>
			<p class="kg-lede" style="margin-inline:auto;"><?php kg_e( 'about.global.text' ); ?></p>
			<div style="display:flex; gap:14px; justify-content:center; margin-top:24px; font-size:2rem;" aria-hidden="true">
				<span>🇬🇧</span><span>🇮🇩</span><span>🇹🇭</span>
			</div>
		</div>
	</div>
</section>

<!-- Contact -->
<section class="kg-section kg-section--cream">
	<div class="kg-container kg-container--narrow" style="text-align:center;">
		<div data-kg-reveal>
			<span class="kg-kicker"><?php kg_e( 'about.contact.kicker' ); ?></span>
			<h2 class="kg-h2"><?php kg_e( 'about.contact.title' ); ?></h2>
			<p class="kg-lede" style="margin-inline:auto;">
				<?php kg_e( 'about.contact.company' ); ?><br>
				<a href="mailto:<?php echo esc_attr( kg_support_email() ); ?>" data-kg-event="support_email_click"><?php echo esc_html( kg_support_email() ); ?></a>
			</p>
			<p style="color:var(--kg-text-soft);"><?php kg_e( 'about.contact.note' ); ?></p>
			<div style="margin-top:22px;">
				<?php kg_cta( 'about.contact.cta', kg_url( 'support' ), '', 'kg-btn kg-btn--teal kg-btn--lg' ); ?>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
