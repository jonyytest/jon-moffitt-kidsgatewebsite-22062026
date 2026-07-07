<?php
/**
 * Home page — the full scroll story:
 * hero gate → stats → problem/solution → learning loop → experience tabs
 * → AI personalisation → parent dashboard → rewards → testimonials
 * → pricing summary → final CTA.
 */
get_header();
$p = kg_pricing_for_lang();
?>

<!-- ============================== Hero ============================== -->
<section class="kg-hero">
	<!-- Decorative colour blobs + playful shapes behind everything -->
	<div class="kg-hero__decor" aria-hidden="true">
		<span class="kg-hero__blob kg-hero__blob--amber"></span>
		<span class="kg-hero__blob kg-hero__blob--teal"></span>
		<span class="kg-hero__blob kg-hero__blob--red"></span>
		<span class="kg-hero__dot kg-hero__dot--1"></span>
		<span class="kg-hero__dot kg-hero__dot--2"></span>
		<span class="kg-hero__dot kg-hero__dot--3"></span>
		<svg class="kg-hero__plus kg-hero__plus--1" width="26" height="26" viewBox="0 0 24 24" fill="none"><path d="M12 4v16M4 12h16" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>
		<svg class="kg-hero__plus kg-hero__plus--2" width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M12 4v16M4 12h16" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>
	</div>

	<div class="kg-container kg-hero__inner">
		<!-- Left: brand + message -->
		<div class="kg-hero__copy">
			<a class="kg-hero__brand" href="#kg-download" data-kg-reveal aria-label="<?php echo esc_attr( kg_t( 'home.hero.eyebrow' ) ); ?> — scroll to download">
				<img src="<?php echo esc_url( kg_asset( 'img/kg-logo.png' ) ); ?>" alt="The Kids Gate" width="40" height="42">
				<span><?php kg_e( 'home.hero.eyebrow' ); ?></span>
			</a>
			<h1 class="kg-h1 kg-hero__title" data-kg-reveal style="--kg-delay:80ms"><?php kg_e( 'home.hero.title' ); ?></h1>
			<p class="kg-hero__lede" data-kg-reveal style="--kg-delay:160ms"><?php kg_e( 'home.hero.lede' ); ?></p>

			<div class="kg-hero__ctas" data-kg-reveal style="--kg-delay:220ms">
				<?php kg_cta( 'common.cta_primary', kg_url( 'pricing' ), 'hero_cta_click', 'kg-btn kg-btn--primary kg-btn--lg' ); ?>
				<?php kg_cta( 'common.cta_secondary', kg_url( 'how-it-works' ), '', 'kg-btn kg-btn--secondary kg-btn--lg' ); ?>
			</div>

			<?php kg_trust_chips(); ?>
		</div>

		<!-- Right: the gate visual with floating product cards -->
		<div class="kg-hero__stage" data-kg-reveal="pop" style="--kg-delay:200ms">
			<div class="kg-hero__gate">
				<div class="kg-hero__gate-img">
					<img src="<?php echo esc_url( kg_asset( 'img/child-learning.jpg' ) ); ?>" alt="<?php echo esc_attr( kg_t( 'home.hero.img_alt' ) ); ?>" width="1600" height="1195" fetchpriority="high">
				</div>

				<?php
				// Float the four trust points (incl. "No ads. Ever.") as cards so
				// the key reassurances are the most visible thing on the hero.
				$card_pos = array(
					'top:7%; left:-15%;',
					'top:25%; right:-14%;',
					'bottom:24%; left:-14%;',
					'bottom:7%; right:-12%;',
				);
				foreach ( kg_list( 'common.trust_chips' ) as $ci => $chip ) :
					$is_cross = is_array( $chip ) && ! empty( $chip['cross'] );
					$label    = is_array( $chip ) ? $chip['text'] : $chip;
					$pos      = isset( $card_pos[ $ci ] ) ? $card_pos[ $ci ] : '';
					?>
					<div class="kg-hero__card kg-hero__card--trust" style="<?php echo esc_attr( $pos ); ?>" aria-hidden="true">
						<span class="kg-hero__tick<?php echo $is_cross ? ' kg-hero__tick--cross' : ''; ?>">
							<?php if ( $is_cross ) : ?>
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="3.4" stroke-linecap="round"/></svg>
							<?php else : ?>
								<svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="m5 13 4 4L19 7" stroke="currentColor" stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
							<?php endif; ?>
						</span>
						<span class="kg-hero__card-txt"><strong><?php echo $label; // phpcs:ignore ?></strong></span>
					</div>
				<?php endforeach; ?>

				<!-- (decorative tokens removed) -->
			</div>
		</div>
	</div>
</section>

<!-- ============================ Stats band ========================== -->
<section class="kg-section kg-section--white" id="kg-home-stats">
	<div class="kg-container">
		<?php kg_section_head( 'home.stats' ); ?>
		<div class="kg-stats">
			<?php
			// Icons + accent tone per stat (locale-independent, so keyed by index):
			// lessons, grade levels, minutes-a-day, free-trial days.
			$stat_tones = array( 'amber', 'teal', 'red', 'green' );
			$stat_icons = array(
				// Open book — interactive lessons
				'<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 6.5C10.5 5 8 4.4 4 4.4V18c4 0 6.5.6 8 2 1.5-1.4 4-2 8-2V4.4c-4 0-6.5.6-8 2.1z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M12 6.5V20" stroke="currentColor" stroke-width="1.8"/></svg>',
				// Stacked layers — grade levels
				'<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3l9 5-9 5-9-5 9-5z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M3 12l9 5 9-5M3 16.5l9 5 9-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
				// Clock — 20 minutes a day
				'<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/><path d="M12 7v5.2l3.3 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
				// Gift — free trial, no card
				'<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3.5" y="8.5" width="17" height="12.5" rx="1.6" stroke="currentColor" stroke-width="1.8"/><path d="M3.5 12.5h17M12 8.5V21" stroke="currentColor" stroke-width="1.8"/><path d="M12 8.5S10.6 4 8.2 4.9 8 8.5 8 8.5h4zm0 0s1.4-4.5 3.8-3.6S16 8.5 16 8.5h-4z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>',
			);
			foreach ( kg_list( 'home.stats.items' ) as $i => $stat ) :
				$tone = $stat_tones[ $i % 4 ];
				?>
				<div class="kg-stat kg-card kg-stat--<?php echo esc_attr( $tone ); ?>" data-kg-reveal="pop" style="--kg-delay:<?php echo (int) ( $i * 90 ); ?>ms">
					<span class="kg-stat__icon" aria-hidden="true"><?php echo $stat_icons[ $i % 4 ]; // phpcs:ignore ?></span>
					<span class="kg-stat__num kg-counter" data-kg-count="<?php echo esc_attr( $stat['num'] ); ?>" data-kg-suffix="<?php echo esc_attr( $stat['suffix'] ); ?>">0</span>
					<span class="kg-stat__label"><?php echo $stat['label']; // phpcs:ignore ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ====================== Problem → solution ======================= -->
<section class="kg-section kg-section--cream">
	<div class="kg-container">
		<?php kg_section_head( 'home.problem' ); ?>

		<!-- "Them": the three pains, boxed and labelled so they clearly read
		     as other tools' failures, never The Kids Gate's -->
		<div class="kg-vs kg-vs--bad" data-kg-reveal>
			<span class="kg-vs__pill kg-vs__pill--bad">
				<svg width="13" height="13" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="3.4" stroke-linecap="round"/></svg>
				<?php kg_e( 'home.problem.others_label' ); ?>
			</span>
			<div class="kg-problems">
			<?php
			$problem_icons = array(
				/* Rising cost — dollar sign in circle */
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><path d="M15.5 9.2c-.5-1-1.7-1.7-3.5-1.7-2 0-3.2.9-3.2 2.2 0 1.3 1.1 1.9 3.2 2.3 2.1.4 3.4 1 3.4 2.4 0 1.4-1.3 2.3-3.4 2.3-1.9 0-3.1-.7-3.6-1.8M12 6v1.5m0 9V18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
				/* One-size-fits-all fails — circle with slash */
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><line x1="6.2" y1="6.2" x2="17.8" y2="17.8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>',
				/* Child loses interest — frown face */
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><path d="M8 15Q12 11 16 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/><circle cx="9" cy="10" r="1.1" fill="currentColor"/><circle cx="15" cy="10" r="1.1" fill="currentColor"/></svg>',
			);
			foreach ( kg_list( 'home.problem.items' ) as $i => $item ) :
				?>
				<div class="kg-card kg-problem" data-kg-reveal style="--kg-delay:<?php echo (int) ( $i * 110 ); ?>ms">
					<span class="kg-bubble kg-bubble--red"><?php echo $problem_icons[ $i % 3 ]; // phpcs:ignore ?></span>
					<h3 class="kg-h3"><?php echo $item['title']; // phpcs:ignore ?></h3>
					<p><?php echo $item['text']; // phpcs:ignore ?></p>
				</div>
			<?php endforeach; ?>
			</div>
		</div>

		<div class="kg-vs__turnarrow" aria-hidden="true" data-kg-reveal="pop">
			<svg width="26" height="26" viewBox="0 0 24 24" fill="none"><path d="M12 4v16m0 0 6-6m-6 6-6-6" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
		</div>

		<!-- "Us": the flip, boxed green and labelled with the brand -->
		<div class="kg-vs kg-vs--good" data-kg-reveal="pop">
			<span class="kg-vs__pill kg-vs__pill--good">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m4 12.5 5 5L20 6.5" stroke="currentColor" stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
				<?php kg_e( 'home.problem.us_label' ); ?>
			</span>
			<div class="kg-solution-turn">
				<h2 class="kg-h2"><span class="kg-squiggle kg-squiggle--green kg-squiggle--draw"><?php kg_e( 'home.problem.turn_title' ); ?></span></h2>
				<p class="kg-lede"><?php
					$_kg_turn_map = array( 'in' => 'home.problem.turn_text_in', 'ph' => 'home.problem.turn_text_ph', 'id' => 'home.problem.turn_text_id' );
					kg_e( $_kg_turn_map[ kg_country() ] ?? 'home.problem.turn_text' );
				?></p>
			</div>
		</div>
	</div>
</section>

<!-- ============ How it works: the daily loop + live AI demo ======== -->
<section class="kg-section kg-section--white kg-hiw" data-kg-hiw-watch>
	<div class="kg-hiw__bg" aria-hidden="true"></div>
	<div class="kg-container">
		<?php kg_section_head( 'home.loop' ); ?>

		<!-- The four-step daily loop: auto-spotlights each stage, on repeat -->
		<div class="kg-loop" data-kg-loop data-kg-reveal>
			<ol class="kg-loop__steps">
				<?php
				$loop_colors = array( 'var(--kg-teal)', 'var(--kg-amber)', 'var(--kg-red)', 'var(--kg-navy)' );
				$loop_icons  = array(
					/* Assessment — clipboard check */
					'<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M9 11l3 3 8-8M21 12v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h11" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
					/* Personalised path — sparkle */
					'<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 2v4m0 12v4M2 12h4m12 0h4m-3.5-6.5-3 3m-7 7-3 3m13 0-3-3m-7-7-3-3" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>',
					/* Daily lesson — clock */
					'<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 6v6l4 2M22 12c0 5.5-4.5 10-10 10S2 17.5 2 12 6.5 2 12 2s10 4.5 10 10z" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>',
					/* Rewards — trophy */
					'<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M8 21h8m-4-4v4m-6-9a6 6 0 0 0 12 0V4H6v8zM6 9H4a2 2 0 0 1-2-2V5h4m12 4h2a2 2 0 0 0 2-2V5h-4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
				);
				foreach ( kg_list( 'home.loop.steps' ) as $i => $step ) :
					?>
					<li class="kg-loop__step<?php echo 0 === $i ? ' is-current' : ''; ?>" data-kg-loop-step style="--g:<?php echo esc_attr( $loop_colors[ $i % 4 ] ); ?>">
						<span class="kg-loop__gate" aria-hidden="true"><?php echo $loop_icons[ $i % 4 ]; // phpcs:ignore ?><span class="kg-loop__num"><?php echo (int) ( $i + 1 ); ?></span></span>
						<h3 class="kg-loop__title"><?php echo $step['title']; // phpcs:ignore ?></h3>
						<p><?php echo $step['text']; // phpcs:ignore ?></p>
					</li>
				<?php endforeach; ?>
			</ol>
			<div class="kg-loop__progress" aria-hidden="true">
				<span class="kg-loop__progress-fill" data-kg-loop-bar></span>
				<span class="kg-loop__repeat"><svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M21 12a9 9 0 1 1-3-6.7M21 4v5h-5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg><?php kg_e( 'home.loop.repeat' ); ?></span>
			</div>
		</div>

		<!-- The clever part: a live, interactive taste of the AI adapting -->
		<div class="kg-loop-demo" data-kg-reveal>
			<div class="kg-loop-demo__copy">
				<span class="kg-kicker"><?php kg_e( 'home.ai.kicker' ); ?></span>
				<h3 class="kg-loop-demo__title"><?php kg_e( 'home.ai.title' ); ?></h3>
				<p class="kg-loop-demo__lede"><?php kg_e( 'home.ai.lede' ); ?></p>
				<p class="kg-loop-demo__note">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M21 12a9 9 0 1 1-3-6.7M21 4v5h-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
					<span><?php kg_e( 'home.ai.reassess' ); ?></span>
				</p>
			</div>
			<div class="kg-ai__demo" data-kg-ai-demo>
				<div class="kg-ai__demo-q">
					<p><strong><?php kg_e( 'home.ai.demo_label' ); ?></strong> <span class="kg-nowrap"><?php kg_e( 'home.ai.demo_question' ); ?></span></p>
					<div class="kg-ai__demo-buttons">
						<button type="button" data-kg-answer="correct"><?php kg_e( 'home.ai.demo_correct_btn' ); ?></button>
						<button type="button" data-kg-answer="wrong"><?php kg_e( 'home.ai.demo_wrong_btn' ); ?></button>
					</div>
				</div>
				<div class="kg-ai__demo-result" role="status" data-kg-correct="<?php echo esc_attr( kg_t( 'home.ai.demo_correct' ) ); ?>" data-kg-wrong="<?php echo esc_attr( kg_t( 'home.ai.demo_wrong' ) ); ?>"></div>
			</div>
		</div>

		<div style="text-align:center; margin-top: clamp(36px, 5vw, 56px);" data-kg-reveal>
			<?php kg_cta( 'home.loop.cta_label', kg_url( 'how-it-works' ), '', 'kg-btn kg-btn--teal' ); ?>
		</div>
	</div>
</section>

<!-- ==================== Learning experience tabs =================== -->
<section class="kg-section kg-section--teal-wash">
	<div class="kg-container" data-kg-tabs>
		<?php kg_section_head( 'home.experience' ); ?>
		<?php
		// Show only the three in-app learning tabs here; tokens/rewards and the
		// virtual world get their own dedicated section lower down, so we don't
		// repeat them. (The other tab keys still live in the lang file.)
		$all_tabs = kg_list( 'home.experience.tabs' );
		$tabs     = array();
		foreach ( array( 'english', 'maths', 'games' ) as $show_key ) {
			if ( isset( $all_tabs[ $show_key ] ) ) {
				$tabs[ $show_key ] = $all_tabs[ $show_key ];
			}
		}
		$tab_keys   = array_keys( $tabs );
		// Each tab: a real in-app screenshot as an immersive backdrop, plus a
		// brand accent colour and an icon for the (now bolder) tab button.
		$tab_images = array(
			'english' => 'img/exp-english.png',
			'maths'   => 'img/exp-maths.png',
			'games'   => 'img/exp-games.png',
		);
		$tab_accent = array(
			'english' => 'teal',
			'maths'   => 'amber',
			'games'   => 'red',
		);
		$tab_icons = array(
			'english' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 6c-1.7-1.3-4-2-7-2v13c3 0 5.3.7 7 2 1.7-1.3 4-2 7-2V4c-3 0-5.3.7-7 2zM12 6v13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
			'maths'   => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="4" y="3" width="16" height="18" rx="3" stroke="currentColor" stroke-width="2"/><path d="M8 7h8M8 12h2m4 0h2M8 16h2m4 0h2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
			'games'   => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="2" y="7" width="20" height="11" rx="5.5" stroke="currentColor" stroke-width="2"/><path d="M7 11v3M5.5 12.5h3M15.5 12h.01M18 14h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
		);
		?>
		<div class="kg-expx__bar" role="tablist" aria-label="<?php echo esc_attr( kg_t( 'home.experience.title' ) ); ?>" data-kg-reveal>
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<button class="kg-expx__tab" data-accent="<?php echo esc_attr( $tab_accent[ $key ] ); ?>" role="tab" id="kg-exp-tab-<?php echo esc_attr( $key ); ?>" aria-controls="kg-exp-panel-<?php echo esc_attr( $key ); ?>" aria-selected="<?php echo $key === $tab_keys[0] ? 'true' : 'false'; ?>" <?php echo $key === $tab_keys[0] ? '' : 'tabindex="-1"'; ?>>
					<span class="kg-expx__tab-ic" aria-hidden="true"><?php echo $tab_icons[ $key ]; // phpcs:ignore ?></span>
					<?php echo $tab['label']; // phpcs:ignore ?>
				</button>
			<?php endforeach; ?>
		</div>
		<?php foreach ( $tabs as $key => $tab ) : ?>
			<div class="kg-tabs__panel kg-expx__panel" data-accent="<?php echo esc_attr( $tab_accent[ $key ] ); ?>" role="tabpanel" id="kg-exp-panel-<?php echo esc_attr( $key ); ?>" aria-labelledby="kg-exp-tab-<?php echo esc_attr( $key ); ?>" <?php echo $key === $tab_keys[0] ? '' : 'hidden'; ?>>
				<div class="kg-expx__bg" style="background-image:url('<?php echo esc_url( kg_asset( $tab_images[ $key ] ) ); ?>');" role="img" aria-label="<?php echo esc_attr( $tab['title'] ); ?>"></div>
				<div class="kg-expx__scrim" aria-hidden="true"></div>
				<div class="kg-expx__inner">
					<div class="kg-expx__head">
						<span class="kg-expx__eyebrow"><?php echo $tab['label']; // phpcs:ignore ?></span>
						<h3 class="kg-expx__title"><?php echo $tab['title']; // phpcs:ignore ?></h3>
						<p class="kg-expx__lede"><?php echo $tab['text']; // phpcs:ignore ?></p>
					</div>
					<ul class="kg-expx__topics">
						<?php foreach ( $tab['topics'] as $ti => $topic ) : ?>
							<li style="--i:<?php echo (int) $ti; ?>">
								<span class="kg-expx__topic-ic" aria-hidden="true"><svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="m5 13 4 4L19 7" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
								<span class="kg-expx__topic-txt"><strong><?php echo $topic['name']; // phpcs:ignore ?></strong><small><?php echo $topic['desc']; // phpcs:ignore ?></small></span>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>

<!-- ====================== Parent dashboard ========================= -->
<!-- "See everything. Miss nothing." — the shot sits in an always-on window
     (live indicator, watchful-eye badge) and each feature row hangs off a
     monitoring rail with pulsing status dots: everything watched, nothing
     slips past. -->
<section class="kg-section kg-section--white">
	<div class="kg-container">
		<?php kg_section_head( 'home.dashboard', false ); ?>
		<div class="kg-dash">
			<div class="kg-dash__shot kg-watch__frame" data-kg-reveal="left">
				<div class="kg-watch__bar" aria-hidden="true">
					<span class="kg-watch__windot"></span><span class="kg-watch__windot"></span><span class="kg-watch__windot"></span>
					<span class="kg-watch__live"></span>
				</div>
				<a href="<?php echo esc_url( kg_url( 'parents' ) ); ?>" aria-label="<?php echo esc_attr( kg_t( 'home.dashboard.cta_label' ) ); ?>">
					<img src="<?php echo esc_url( kg_asset( 'img/dashboard-activity.png' ) ); ?>" alt="<?php echo esc_attr( kg_t( 'home.dashboard.img_alt' ) ); ?>" loading="lazy" width="1400" height="884">
				</a>
				<span class="kg-watch__eye" aria-hidden="true">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M2 12s3.5-6.5 10-6.5S22 12 22 12s-3.5 6.5-10 6.5S2 12 2 12z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" fill="currentColor"/></svg>
				</span>
			</div>
			<?php
			$dash_pt_icons = array(
				/* Progress at a glance — trend line chart */
				array( 'cls' => 'kg-bubble--teal', 'svg' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><polyline points="3,18 8,12 13,15 20,7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/><line x1="3" y1="22" x2="21" y2="22" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>' ),
				/* Mastery tracking — bullseye */
				array( 'cls' => 'kg-bubble--amber', 'svg' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="5.5" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="1.5" fill="currentColor"/></svg>' ),
				/* Difficult topics flagged — flag */
				array( 'cls' => 'kg-bubble--red', 'svg' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 21V4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/><path d="M4 4h14l-4 5 4 5H4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>' ),
				/* Smart recommendations — lightbulb */
				array( 'cls' => 'kg-bubble--green-dark', 'svg' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M9 18h6M10 21h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M12 2a7 7 0 0 1 5 11.9V16a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1v-2.1A7 7 0 0 1 12 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>' ),
			);
			?>
			<ul class="kg-dash__features kg-watch__list">
				<?php foreach ( kg_list( 'home.dashboard.points' ) as $i => $point ) :
					$icon = $dash_pt_icons[ $i % 4 ];
					?>
					<li data-kg-reveal="right" style="--kg-delay:<?php echo (int) ( $i * 90 ); ?>ms">
						<span class="kg-watch__ping" aria-hidden="true"></span>
						<span class="kg-bubble <?php echo esc_attr( $icon['cls'] ); ?> kg-dash__pt-icon"><?php echo $icon['svg']; // phpcs:ignore ?></span>
						<span><strong><?php echo $point['title']; // phpcs:ignore ?></strong><span><?php echo $point['text']; // phpcs:ignore ?></span></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div style="text-align:center; margin-top: clamp(28px, 4vw, 44px);" data-kg-reveal>
			<?php kg_cta( 'home.dashboard.cta_label', kg_url( 'parents' ), '', 'kg-btn kg-btn--primary' ); ?>
		</div>
	</div>
</section>

<!-- ===================== Rewards & leaderboard ===================== -->
<!-- The Kids Gate world sits *behind* the reward cards as an immersive
     backdrop, rather than floating above them as a separate image. -->
<section class="kg-section kg-rewards--world">
	<div class="kg-rewards__bg" style="background-image:url('<?php echo esc_url( kg_asset( 'img/store-map.jpg' ) ); ?>');" role="img" aria-label="<?php echo esc_attr( kg_t( 'home.rewards.map_alt' ) ); ?>"></div>
	<div class="kg-rewards__overlay" aria-hidden="true"></div>
	<?php
	// Drifting tokens over the world map — the section's currency, made visible.
	$kg_coin = '<circle cx="19" cy="19" r="16.5" fill="var(--kg-amber)" stroke="var(--kg-amber-deep)" stroke-width="3"/><circle cx="19" cy="19" r="10.5" fill="none" stroke="var(--kg-amber-deep)" stroke-width="2" opacity=".55"/><path d="M19 12.2l1.9 4 4.4.5-3.3 3 .9 4.3-3.9-2.2-3.9 2.2.9-4.3-3.3-3 4.4-.5z" fill="var(--kg-amber-deep)" opacity=".8"/>';
	?>
	<div class="kg-rewards__coins" aria-hidden="true">
		<svg class="kg-coin kg-coin--1" viewBox="0 0 38 38" focusable="false"><?php echo $kg_coin; // phpcs:ignore ?></svg>
		<svg class="kg-coin kg-coin--2" viewBox="0 0 38 38" focusable="false"><?php echo $kg_coin; // phpcs:ignore ?></svg>
		<svg class="kg-coin kg-coin--3" viewBox="0 0 38 38" focusable="false"><?php echo $kg_coin; // phpcs:ignore ?></svg>
		<svg class="kg-coin kg-coin--4" viewBox="0 0 38 38" focusable="false"><?php echo $kg_coin; // phpcs:ignore ?></svg>
		<svg class="kg-coin kg-coin--5" viewBox="0 0 38 38" focusable="false"><?php echo $kg_coin; // phpcs:ignore ?></svg>
	</div>
	<div class="kg-container">
		<?php kg_section_head( 'home.rewards' ); ?>
		<!-- Winners' podium: the leaderboard card takes first place with the
		     crown; store and prizes flank it on the lower steps. Icon bubbles
		     wear medal-rosette ribbons. -->
		<div class="kg-rewards__grid3 kg-podium">
			<?php
			$reward_bubbles = array( 'kg-bubble--amber', 'kg-bubble--teal', 'kg-bubble--red' );
			$reward_medals  = array( 'amber', 'teal', 'red' );
			$reward_icons   = array(
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 8h12l-1 13H7L6 8zM9 8V6a3 3 0 0 1 6 0v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><path d="M2 12h20M12 2a15 15 0 0 1 0 20 15 15 0 0 1 0-20z" stroke="currentColor" stroke-width="2"/></svg>',
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 2l2.4 7.2H22l-6 4.6 2.3 7.2-6.3-4.5-6.3 4.5L8 13.8 2 9.2h7.6L12 2z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>',
			);
			foreach ( kg_list( 'home.rewards.cards' ) as $i => $card ) :
				?>
				<div class="kg-podium__slot<?php echo 1 === $i ? ' kg-podium__slot--first' : ''; ?>" data-kg-reveal="pop" style="--kg-delay:<?php echo (int) ( $i * 110 ); ?>ms">
					<?php if ( 1 === $i ) : ?>
						<svg class="kg-podium__crown" width="46" height="36" viewBox="0 0 44 34" fill="none" aria-hidden="true" focusable="false"><path d="M4 26 2 8l10 7L22 2l10 13L42 8l-2 18z" fill="var(--kg-amber)" stroke="var(--kg-amber-deep)" stroke-width="2.5" stroke-linejoin="round"/><rect x="5" y="26" width="34" height="6" rx="3" fill="var(--kg-amber-deep)"/></svg>
					<?php endif; ?>
					<div class="kg-card kg-card--arch kg-card--hover">
						<span class="kg-medal kg-medal--<?php echo esc_attr( $reward_medals[ $i % 3 ] ); ?>">
							<span class="kg-bubble <?php echo esc_attr( $reward_bubbles[ $i % 3 ] ); ?>"><?php echo $reward_icons[ $i % 3 ]; // phpcs:ignore ?></span>
						</span>
						<h3 class="kg-h3"><?php echo $card['title']; // phpcs:ignore ?></h3>
						<p><?php echo $card['text']; // phpcs:ignore ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<p class="kg-rewards__caption" data-kg-reveal><?php kg_e( 'home.rewards.map_caption' ); ?></p>
		<div style="text-align:center; margin-top: clamp(24px, 3vw, 36px);" data-kg-reveal>
			<?php kg_cta( 'home.rewards.cta_label', kg_url( 'rewards' ), '', 'kg-btn kg-btn--teal' ); ?>
		</div>
	</div>
</section>

<!-- ======================== Testimonials =========================== -->
<section class="kg-section kg-section--white" data-kg-testimonials>
	<div class="kg-container">
		<?php kg_section_head( 'home.testimonials' ); ?>
		<div class="kg-carousel kg-carousel--marquee" data-kg-marquee>
			<div class="kg-carousel__track">
				<?php
				$avatar_colors = array( 'var(--kg-teal)', 'var(--kg-amber)', 'var(--kg-red)' );
				foreach ( kg_list( 'home.testimonials.items' ) as $i => $t ) :
					?>
					<div class="kg-carousel__slide">
						<div class="kg-card kg-testimonial kg-testimonial--placeholder">
							<span class="kg-testimonial__flag"><?php kg_e( 'home.testimonials.flag' ); ?></span>
							<p class="kg-testimonial__quote">“<?php echo $t['quote']; // phpcs:ignore ?>”</p>
							<div class="kg-testimonial__who">
								<span class="kg-testimonial__avatar" style="background:<?php echo esc_attr( $avatar_colors[ $i % 3 ] ); ?>" aria-hidden="true"><?php echo esc_html( kg_initial( $t['name'] ) ); ?></span>
								<span><strong><?php echo $t['name']; // phpcs:ignore ?></strong><small><?php echo $t['meta']; // phpcs:ignore ?></small></span>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<!-- ====================== Pricing summary ========================== -->
<section class="kg-section kg-section--cream">
	<div class="kg-container">
		<?php kg_section_head( 'home.pricing' ); ?>
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
				<h3 class="kg-h3"><?php kg_e( 'pricing.plans.one.name' ); ?></h3>
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
				<?php kg_cta( 'common.cta_primary', kg_url( 'pricing' ), 'free_trial_start', 'kg-btn kg-btn--secondary' ); ?>
			</div>
			<div class="kg-card kg-plan kg-plan--featured" data-kg-reveal style="--kg-delay:110ms">
				<span class="kg-plan__flag"><?php kg_e( 'pricing.plans.two.flag' ); ?></span>
				<h3 class="kg-h3"><?php kg_e( 'pricing.plans.two.name' ); ?></h3>
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
				<?php kg_cta( 'common.cta_primary', kg_url( 'pricing' ), 'free_trial_start' ); ?>
			</div>
		</div>
		<p style="text-align:center; margin-top:22px; color:var(--kg-text-soft);" data-kg-reveal>
			<?php
			$addl_note_m = str_replace( '{price}', kg_price( $p['addl'][1]['m'] ), kg_t( 'pricing.plans.addl_note' ) );
			$addl_note_y = str_replace( '{price}', kg_price( $p['addl'][1]['y'] ), kg_t( 'pricing.plans.addl_note' ) );
			?>
			<span data-kg-price-m="<?php echo esc_attr( $addl_note_m ); ?>" data-kg-price-y="<?php echo esc_attr( $addl_note_y ); ?>"><?php echo $addl_note_y; // phpcs:ignore ?></span>
			&nbsp;·&nbsp;<a href="<?php echo esc_url( kg_url( 'pricing' ) ); ?>"><?php kg_e( 'home.pricing.cta_label' ); ?></a>
		</p>
	</div>
</section>

<!-- ========================= Final CTA ============================= -->
<section class="kg-section" id="kg-download">
	<div class="kg-container">
		<div class="kg-final-cta" data-kg-reveal="pop">
			<h2 class="kg-h2"><?php kg_e( 'home.final.title' ); ?></h2>
			<p class="kg-lede"><?php kg_e( 'home.final.lede' ); ?></p>
			<div class="kg-final-cta__ctas">
				<?php kg_cta( 'common.cta_primary', kg_url( 'pricing' ), 'free_trial_start', 'kg-btn kg-btn--primary kg-btn--lg' ); ?>
			</div>
			<?php kg_trust_chips( 'common.cancel_chips' ); ?>
			<?php kg_store_badges(); ?>
		</div>
	</div>
</section>

<?php
get_footer();
