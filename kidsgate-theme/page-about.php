<?php
/**
 * About Us — the brand story told as a scrolling storybook: numbered
 * chapters on a dashed story-thread, a vague origin tale, the question
 * that started it, the journey, the four pillars holding up the Gate,
 * the global spread, and an epilogue with company contact details.
 */
get_header();

/** Chapter badge on the story thread. */
if ( ! function_exists( 'kg_about_chap' ) ) {
	function kg_about_chap( $num, $label_key ) {
		?>
		<div class="kg-chap" data-kg-reveal>
			<span class="kg-chap__num" aria-hidden="true"><?php echo esc_html( $num ); ?></span>
			<span class="kg-chap__label"><?php kg_e( $label_key ); ?></span>
		</div>
		<?php
	}
}
?>

<section class="kg-page-hero kg-section--cream">
	<div class="kg-container">
		<span class="kg-kicker" data-kg-reveal><?php kg_e( 'about.hero.kicker' ); ?></span>
		<h1 class="kg-h1" data-kg-reveal style="--kg-delay:80ms"><?php kg_e( 'about.hero.title' ); ?></h1>
		<p class="kg-lede" data-kg-reveal style="--kg-delay:160ms"><?php kg_e( 'about.hero.lede' ); ?></p>
	</div>
</section>

<!-- Prologue: the mission as a pull-quote -->
<section class="kg-section kg-section--white" style="padding-bottom: clamp(40px, 6vw, 72px);">
	<div class="kg-container">
		<p class="kg-mission" data-kg-reveal="pop"><?php kg_e( 'about.mission' ); ?></p>
	</div>
</section>

<!-- Chapter 1: the noticing -->
<section class="kg-section kg-section--cream-deep" style="padding-top: clamp(40px, 6vw, 72px);">
	<div class="kg-container">
		<?php kg_about_chap( 1, 'about.ch1.label' ); ?>
		<div class="kg-spot">
			<div class="kg-spot__visual kg-spot__visual--arch" data-kg-reveal="left">
				<img src="<?php echo esc_url( kg_asset( 'img/aboutus.png' ) ); ?>" alt="A child smiling while holding a tablet showing The Kids Gate rewards" loading="lazy" width="1672" height="941">
			</div>
			<div data-kg-reveal="right">
				<h2 class="kg-h2"><?php kg_e( 'about.ch1.title' ); ?></h2>
				<?php foreach ( kg_list( 'about.ch1.paras' ) as $para ) : ?>
					<p class="kg-lede" style="font-size:1.05rem;"><?php echo $para; // phpcs:ignore ?></p>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<!-- Chapter 2: the question -->
<section class="kg-section kg-section--white">
	<div class="kg-container">
		<?php kg_about_chap( 2, 'about.ch2.label' ); ?>
		<div class="kg-storyq">
			<h2 class="kg-h2" data-kg-reveal><?php kg_e( 'about.ch2.title' ); ?></h2>
			<blockquote class="kg-storyq__quote" data-kg-reveal="pop"><?php kg_e( 'about.ch2.question' ); ?></blockquote>
			<div class="kg-storyq__cards">
				<?php
				$q_icons = array(
					// Book — make school stick
					'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V4a2 2 0 0 0-2-2H6.5A2.5 2.5 0 0 0 4 4.5v15zM20 17v5H6.5a2.5 2.5 0 0 1 0-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
					// Compass — follow the child
					'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><path d="m15.5 8.5-2 5-5 2 2-5 5-2z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>',
					// Home-heart — honour the home
					'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V10z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M12 16.5s-2.8-1.9-2.8-3.6c0-1 .8-1.7 1.6-1.7.5 0 1 .3 1.2.7.2-.4.7-.7 1.2-.7.8 0 1.6.7 1.6 1.7 0 1.7-2.8 3.6-2.8 3.6z" fill="currentColor"/></svg>',
				);
				$q_bubbles = array( 'kg-bubble--amber', 'kg-bubble--teal', 'kg-bubble--red' );
				foreach ( kg_list( 'about.ch2.cards' ) as $i => $card ) :
					?>
					<div class="kg-card" data-kg-reveal style="--kg-delay:<?php echo (int) ( 120 + $i * 110 ); ?>ms">
						<span class="kg-bubble <?php echo esc_attr( $q_bubbles[ $i % 3 ] ); ?>"><?php echo $q_icons[ $i % 3 ]; // phpcs:ignore ?></span>
						<h3 class="kg-h3" style="font-size:1.1rem;"><?php echo $card['title']; // phpcs:ignore ?></h3>
						<p style="margin:0; font-size:.95rem;"><?php echo $card['text']; // phpcs:ignore ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<!-- Chapter 3: the journey -->
<section class="kg-section kg-section--cream">
	<div class="kg-container">
		<?php kg_about_chap( 3, 'about.ch3.label' ); ?>
		<h2 class="kg-h2" style="text-align:center;" data-kg-reveal><?php kg_e( 'about.ch3.title' ); ?></h2>
		<div class="kg-storyline">
			<?php
			$j_tones = array( 'amber', 'red', 'teal', 'navy' );
			foreach ( kg_list( 'about.ch3.steps' ) as $i => $step ) :
				?>
				<div class="kg-storyline__step kg-storyline__step--<?php echo esc_attr( $j_tones[ $i % 4 ] ); ?>" data-kg-reveal style="--kg-delay:<?php echo (int) ( $i * 130 ); ?>ms">
					<span class="kg-storyline__dot" aria-hidden="true"></span>
					<h3 class="kg-h3" style="font-size:1.05rem; margin-bottom:4px;"><?php echo $step['title']; // phpcs:ignore ?></h3>
					<p style="margin:0; font-size:.93rem; color:var(--kg-text-soft);"><?php echo $step['text']; // phpcs:ignore ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Chapter 4: the four pillars, holding up the Gate -->
<section class="kg-section kg-section--white">
	<div class="kg-container">
		<?php kg_about_chap( 4, 'about.ch4.label' ); ?>
		<h2 class="kg-h2" style="text-align:center;" data-kg-reveal><?php kg_e( 'about.ch4.title' ); ?></h2>
		<p class="kg-lede" style="text-align:center; margin-inline:auto; margin-bottom: clamp(28px, 4vw, 44px);" data-kg-reveal><?php kg_e( 'about.ch4.lede' ); ?></p>
		<div class="kg-pillars" data-kg-reveal="pop">
			<div class="kg-pillars__arch" aria-hidden="true"><?php kg_e( 'about.ch4.arch_label' ); ?></div>
			<div class="kg-pillars__grid">
				<?php
				$p_tones = array( 'amber', 'red', 'teal', 'navy' );
				$p_icons = array(
					// Megaphone — continuous encouragement
					'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m3 11 15-6v14L3 13v-2zM6 13v4a2 2 0 0 0 2 2h1v-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 9.5c.6.6 1 1.5 1 2.5s-.4 1.9-1 2.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
					// Chart up — capability development
					'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 3v16a2 2 0 0 0 2 2h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="m7 14 4-4 3 3 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M20 7h-4m4 0v4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
					// Target — custom personalisation
					'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="4.5" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="1.2" fill="currentColor"/></svg>',
					// Eye — parental awareness
					'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/></svg>',
				);
				foreach ( kg_list( 'about.ch4.items' ) as $i => $pillar ) :
					$tone = $p_tones[ $i % 4 ];
					?>
					<div class="kg-pillar kg-pillar--<?php echo esc_attr( $tone ); ?>">
						<span class="kg-bubble kg-bubble--<?php echo esc_attr( $tone ); ?>" style="width:46px; height:46px;"><?php echo $p_icons[ $i % 4 ]; // phpcs:ignore ?></span>
						<h3 class="kg-h3" style="font-size:1.02rem; margin:10px 0 6px;"><?php echo $pillar['title']; // phpcs:ignore ?></h3>
						<p style="margin:0 0 12px; font-size:.9rem; color:var(--kg-text-soft);"><?php echo $pillar['text']; // phpcs:ignore ?></p>
						<span class="kg-pillar__chips">
							<?php foreach ( $pillar['chips'] as $chip ) : ?>
								<i><?php echo $chip; // phpcs:ignore ?></i>
							<?php endforeach; ?>
						</span>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<!-- Chapter 5: the story goes global -->
<section class="kg-section kg-section--navy">
	<div class="kg-container kg-container--narrow" style="text-align:center;">
		<?php kg_about_chap( 5, 'about.ch5.label' ); ?>
		<div data-kg-reveal>
			<h2 class="kg-h2"><?php kg_e( 'about.ch5.title' ); ?></h2>
			<p class="kg-lede" style="margin-inline:auto;"><?php kg_e( 'about.ch5.text' ); ?></p>
			<div class="kg-about-flags" aria-hidden="true">
				<?php foreach ( array( 'en', 'id', 'th', 'zh' ) as $flag_code ) : ?>
					<span><?php echo kg_flag( $flag_code ); // phpcs:ignore ?></span>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<!-- Epilogue: contact -->
<section class="kg-section kg-section--cream">
	<div class="kg-container kg-container--narrow" style="text-align:center;">
		<?php kg_about_chap( '✦', 'about.contact.label' ); ?>
		<div data-kg-reveal>
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

<!-- Easter egg: click the "About Us" kicker twice to reveal the credit (works on tap & click). -->
<div class="kg-egg" data-kg-egg>
	<span aria-hidden="true">✨</span> website made by <strong>Jonathan Moffitt</strong>
</div>

<style>
.kg-egg {
	display: none; /* hidden until the .is-on class is added */
	position: fixed; left: 50%; bottom: 24px; transform: translateX(-50%);
	z-index: 9999;
	align-items: center; gap: 8px; max-width: calc(100% - 32px);
	background: var(--kg-navy, #1C2B3A); color: #fff;
	font-family: var(--kg-font-display, system-ui, sans-serif);
	font-weight: 700; font-size: .95rem;
	padding: 12px 22px; border-radius: 999px;
	box-shadow: 0 14px 44px rgba(0, 0, 0, .32);
	cursor: pointer;
}
.kg-egg.is-on { display: inline-flex; }
.kg-egg strong { color: var(--kg-amber, #F5A623); }
</style>

<script>
(function () {
	var egg = document.querySelector('[data-kg-egg]');
	var trigger = document.querySelector('.kg-page-hero .kg-kicker');
	if (!egg || !trigger) { return; }
	var clicks = 0;
	trigger.addEventListener('click', function () {
		if (++clicks >= 2) { egg.classList.add('is-on'); }
	});
	egg.addEventListener('click', function () { egg.classList.remove('is-on'); clicks = 0; });
})();
</script>

<?php
get_footer();
