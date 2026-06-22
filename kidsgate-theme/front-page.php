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
	<div class="kg-container kg-hero__inner">
		<h1 class="kg-h1" data-kg-reveal><?php kg_e( 'home.hero.title' ); ?></h1>
		<p class="kg-hero__lede" data-kg-reveal style="--kg-delay:120ms"><?php kg_e( 'home.hero.lede' ); ?></p>

		<div class="kg-hero__ctas" data-kg-reveal style="--kg-delay:200ms">
			<?php kg_cta( 'common.cta_primary', kg_url( 'pricing' ), 'hero_cta_click', 'kg-btn kg-btn--primary kg-btn--lg' ); ?>
			<?php kg_cta( 'common.cta_secondary', kg_url( 'how-it-works' ), '', 'kg-btn kg-btn--secondary kg-btn--lg' ); ?>
		</div>

		<?php kg_trust_chips(); ?>

		<div class="kg-hero__gate" data-kg-reveal="pop" style="--kg-delay:240ms">
			<div class="kg-hero__gate-img">
				<img src="<?php echo esc_url( kg_asset( 'img/child-learning.jpg' ) ); ?>" alt="<?php echo esc_attr( kg_t( 'home.hero.img_alt' ) ); ?>" width="1600" height="1195" fetchpriority="high">
			</div>
			<div class="kg-hero__badge" style="top:14%; left:-7%;" aria-hidden="true">
				<span class="kg-token" style="width:44px;height:44px;font-size:1.1rem;">★</span>
				<span><strong><?php kg_e( 'home.hero.badge_lessons' ); ?></strong><small><?php kg_e( 'home.hero.badge_lessons_label' ); ?></small></span>
			</div>
			<div class="kg-hero__badge" style="bottom:12%; right:-6%;" aria-hidden="true">
				<span class="kg-token kg-token--teal" style="width:44px;height:44px;font-size:1.1rem;">✓</span>
				<span><strong><?php kg_e( 'home.hero.badge_daily' ); ?></strong><small><?php kg_e( 'home.hero.badge_daily_label' ); ?></small></span>
			</div>
			<span class="kg-float kg-float--bob kg-token" style="top:-26px; left:8%; --kg-tilt:-8deg;" aria-hidden="true">A</span>
			<span class="kg-float kg-float--bob kg-float--slow kg-token kg-token--teal" style="top:6%; right:4%; --kg-tilt:10deg;" aria-hidden="true">7</span>
			<span class="kg-float kg-float--bob kg-token kg-token--red" style="bottom:30%; left:-4%; --kg-tilt:6deg;" aria-hidden="true">?</span>
		</div>
	</div>
</section>

<!-- ============================ Stats band ========================== -->
<section class="kg-section kg-section--white">
	<div class="kg-container">
		<?php kg_section_head( 'home.stats' ); ?>
		<div class="kg-stats">
			<?php foreach ( kg_list( 'home.stats.items' ) as $i => $stat ) : ?>
				<div class="kg-stat kg-card" data-kg-reveal="pop" style="--kg-delay:<?php echo (int) ( $i * 90 ); ?>ms">
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
		<div class="kg-solution-turn" data-kg-reveal="pop">
			<h2 class="kg-h2"><span class="kg-squiggle kg-squiggle--green"><?php kg_e( 'home.problem.turn_title' ); ?></span></h2>
			<p class="kg-lede"><?php kg_e( 'home.problem.turn_text' ); ?></p>
		</div>
	</div>
</section>

<!-- ===================== Learning loop preview ===================== -->
<section class="kg-section kg-section--white" data-kg-hiw-watch>
	<div class="kg-container">
		<?php kg_section_head( 'home.loop' ); ?>
		<div class="kg-journey">
			<div class="kg-journey__steps">
				<?php
				$gate_colors = array( 'var(--kg-teal)', 'var(--kg-amber)', 'var(--kg-red)', 'var(--kg-navy)' );
				$gate_icons  = array(
					'<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M9 11l3 3 8-8M21 12v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h11" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
					'<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 2v4m0 12v4M2 12h4m12 0h4m-3.5-6.5-3 3m-7 7-3 3m13 0-3-3m-7-7-3-3" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>',
					'<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 6v6l4 2M22 12c0 5.5-4.5 10-10 10S2 17.5 2 12 6.5 2 12 2s10 4.5 10 10z" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>',
					'<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M8 21h8m-4-4v4m-6-9a6 6 0 0 0 12 0V4H6v8zM6 9H4a2 2 0 0 1-2-2V5h4m12 4h2a2 2 0 0 0 2-2V5h-4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
				);
				foreach ( kg_list( 'home.loop.steps' ) as $i => $step ) :
					?>
					<div class="kg-journey__step" data-kg-reveal="<?php echo 0 === $i % 2 ? 'left' : 'right'; ?>">
						<span class="kg-journey__gate" style="background:<?php echo esc_attr( $gate_colors[ $i % 4 ] ); ?>"><?php echo $gate_icons[ $i % 4 ]; // phpcs:ignore ?></span>
						<div>
							<h3 class="kg-h3"><?php echo $step['title']; // phpcs:ignore ?></h3>
							<p><?php echo $step['text']; // phpcs:ignore ?></p>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<div style="text-align:center; margin-top: clamp(32px, 5vw, 48px);" data-kg-reveal>
			<?php kg_cta( 'home.loop.cta_label', kg_url( 'how-it-works' ), '', 'kg-btn kg-btn--teal' ); ?>
		</div>
	</div>
</section>

<!-- ==================== Learning experience tabs =================== -->
<section class="kg-section kg-section--teal-wash">
	<div class="kg-container" data-kg-tabs>
		<?php kg_section_head( 'home.experience' ); ?>
		<?php
		$tabs       = kg_list( 'home.experience.tabs' );
		$tab_keys   = array_keys( $tabs );
		$tab_images = array(
			'english' => 'img/homework-sample.png',
			'maths'   => '',
			'games'   => '',
			'rewards' => 'img/achievement.jpg',
			'world'   => 'img/store-map.jpg',
		);
		?>
		<div class="kg-tabs__bar" role="tablist" aria-label="<?php echo esc_attr( kg_t( 'home.experience.title' ) ); ?>" data-kg-reveal>
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<button class="kg-tabs__tab" role="tab" id="kg-exp-tab-<?php echo esc_attr( $key ); ?>" aria-controls="kg-exp-panel-<?php echo esc_attr( $key ); ?>" aria-selected="<?php echo $key === $tab_keys[0] ? 'true' : 'false'; ?>" <?php echo $key === $tab_keys[0] ? '' : 'tabindex="-1"'; ?>><?php echo $tab['label']; // phpcs:ignore ?></button>
			<?php endforeach; ?>
		</div>
		<?php foreach ( $tabs as $key => $tab ) : ?>
			<div class="kg-tabs__panel kg-exp__panel" role="tabpanel" id="kg-exp-panel-<?php echo esc_attr( $key ); ?>" aria-labelledby="kg-exp-tab-<?php echo esc_attr( $key ); ?>" <?php echo $key === $tab_keys[0] ? '' : 'hidden'; ?>>
				<div class="kg-exp__visual">
					<?php if ( ! empty( $tab_images[ $key ] ) ) : ?>
						<img src="<?php echo esc_url( kg_asset( $tab_images[ $key ] ) ); ?>" alt="<?php echo esc_attr( $tab['title'] ); ?>" loading="lazy">
					<?php else : ?>
						<div class="kg-placeholder">
							<svg width="40" height="40" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="3" stroke="currentColor" stroke-width="1.8"/><path d="m3 16 5-5 4 4 3-3 6 6" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><circle cx="9" cy="8" r="1.6" stroke="currentColor" stroke-width="1.6"/></svg>
							<?php kg_e( 'home.experience.placeholder' ); ?>
						</div>
					<?php endif; ?>
				</div>
				<div class="kg-exp__copy">
					<h3><?php echo $tab['title']; // phpcs:ignore ?></h3>
					<p class="kg-lede"><?php echo $tab['text']; // phpcs:ignore ?></p>
					<ul class="kg-exp__points">
						<?php foreach ( $tab['points'] as $point ) : ?>
							<li><svg width="17" height="17" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m5 13 4 4L19 7" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg><?php echo $point; // phpcs:ignore ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>

<!-- ====================== AI personalisation ======================= -->
<section class="kg-section kg-section--navy kg-ai">
	<div class="kg-container">
		<?php kg_section_head( 'home.ai' ); ?>
		<div class="kg-ai__loop">
			<?php
			$node_colors = array( 'kg-bubble--teal', 'kg-bubble--amber', 'kg-bubble--red', 'kg-bubble--teal' );
			foreach ( kg_list( 'home.ai.nodes' ) as $i => $node ) :
				?>
				<div class="kg-ai__node" data-kg-reveal="pop" style="--kg-delay:<?php echo (int) ( $i * 140 ); ?>ms">
					<span class="kg-ai__num <?php echo esc_attr( $node_colors[ $i % 4 ] ); ?>"><?php echo (int) ( $i + 1 ); ?></span>
					<h3><?php echo $node['title']; // phpcs:ignore ?></h3>
					<p><?php echo $node['text']; // phpcs:ignore ?></p>
					<?php if ( $i < 3 ) : ?>
						<svg class="kg-ai__arrow" width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="kg-ai__demo" data-kg-ai-demo data-kg-reveal>
			<div class="kg-ai__demo-q">
				<p><strong><?php kg_e( 'home.ai.demo_label' ); ?></strong> <?php kg_e( 'home.ai.demo_question' ); ?></p>
				<div class="kg-ai__demo-buttons">
					<button type="button" data-kg-answer="correct"><?php kg_e( 'home.ai.demo_correct_btn' ); ?></button>
					<button type="button" data-kg-answer="wrong"><?php kg_e( 'home.ai.demo_wrong_btn' ); ?></button>
				</div>
			</div>
			<div class="kg-ai__demo-result" role="status" data-kg-correct="<?php echo esc_attr( kg_t( 'home.ai.demo_correct' ) ); ?>" data-kg-wrong="<?php echo esc_attr( kg_t( 'home.ai.demo_wrong' ) ); ?>"></div>
		</div>
	</div>
</section>

<!-- ====================== Parent dashboard ========================= -->
<section class="kg-section kg-section--white">
	<div class="kg-container">
		<?php kg_section_head( 'home.dashboard', false ); ?>
		<div class="kg-dash">
			<div class="kg-dash__shot" data-kg-reveal="left">
				<img src="<?php echo esc_url( kg_asset( 'img/dashboard-activity.png' ) ); ?>" alt="<?php echo esc_attr( kg_t( 'home.dashboard.img_alt' ) ); ?>" loading="lazy" width="1400" height="884">
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
			<ul class="kg-dash__features">
				<?php foreach ( kg_list( 'home.dashboard.points' ) as $i => $point ) :
					$icon = $dash_pt_icons[ $i % 4 ];
					?>
					<li data-kg-reveal="right" style="--kg-delay:<?php echo (int) ( $i * 90 ); ?>ms">
						<span class="kg-bubble <?php echo esc_attr( $icon['cls'] ); ?> kg-dash__pt-icon"><?php echo $icon['svg']; // phpcs:ignore ?></span>
						<span><strong><?php echo $point['title']; // phpcs:ignore ?></strong><span><?php echo $point['text']; // phpcs:ignore ?></span></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div style="text-align:center; margin-top: clamp(28px, 4vw, 44px);" data-kg-reveal>
			<?php kg_cta( 'home.dashboard.cta_label', kg_url( 'parents' ), '', 'kg-btn kg-btn--secondary' ); ?>
		</div>
	</div>
</section>

<!-- ===================== Rewards & leaderboard ===================== -->
<section class="kg-section kg-section--cream-deep">
	<div class="kg-container">
		<?php kg_section_head( 'home.rewards' ); ?>
		<div class="kg-rewards__grid">
			<figure class="kg-rewards__map" data-kg-reveal="pop" style="margin:0 0 8px;">
				<img src="<?php echo esc_url( kg_asset( 'img/store-map.jpg' ) ); ?>" alt="<?php echo esc_attr( kg_t( 'home.rewards.map_alt' ) ); ?>" loading="lazy" width="1536" height="1024">
				<figcaption><?php kg_e( 'home.rewards.map_caption' ); ?></figcaption>
			</figure>
			<?php
			$reward_bubbles = array( 'kg-bubble--amber', 'kg-bubble--teal', 'kg-bubble--red' );
			$reward_icons   = array(
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 8h12l-1 13H7L6 8zM9 8V6a3 3 0 0 1 6 0v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><path d="M2 12h20M12 2a15 15 0 0 1 0 20 15 15 0 0 1 0-20z" stroke="currentColor" stroke-width="2"/></svg>',
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 2l2.4 7.2H22l-6 4.6 2.3 7.2-6.3-4.5-6.3 4.5L8 13.8 2 9.2h7.6L12 2z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>',
			);
			foreach ( kg_list( 'home.rewards.cards' ) as $i => $card ) :
				?>
				<div class="kg-card kg-card--arch kg-card--hover" data-kg-reveal style="--kg-delay:<?php echo (int) ( $i * 110 ); ?>ms">
					<span class="kg-bubble <?php echo esc_attr( $reward_bubbles[ $i % 3 ] ); ?>"><?php echo $reward_icons[ $i % 3 ]; // phpcs:ignore ?></span>
					<h3 class="kg-h3"><?php echo $card['title']; // phpcs:ignore ?></h3>
					<p><?php echo $card['text']; // phpcs:ignore ?></p>
				</div>
			<?php endforeach; ?>
		</div>
		<div style="text-align:center; margin-top: clamp(28px, 4vw, 44px);" data-kg-reveal>
			<?php kg_cta( 'home.rewards.cta_label', kg_url( 'leaderboard' ), '', 'kg-btn kg-btn--teal' ); ?>
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
				<button type="button" data-kg-billing="m" aria-pressed="true"><?php kg_e( 'pricing.toggle.monthly' ); ?></button>
				<button type="button" data-kg-billing="y" aria-pressed="false"><?php kg_e( 'pricing.toggle.yearly' ); ?><span class="kg-save-pill"><?php kg_e( 'pricing.toggle.save' ); ?></span></button>
			</div>
		</div>
		<div class="kg-plans">
			<div class="kg-card kg-plan" data-kg-reveal>
				<h3 class="kg-h3"><?php kg_e( 'pricing.plans.one.name' ); ?></h3>
				<p style="color:var(--kg-text-soft); margin:0;"><?php kg_e( 'pricing.plans.one.desc' ); ?></p>
				<div class="kg-plan__price">
					<span class="kg-plan__amount" data-kg-price-m="<?php echo esc_attr( kg_price( $p['first'][1]['m'] ) ); ?>" data-kg-price-y="<?php echo esc_attr( kg_price( $p['first'][1]['y'] ) ); ?>"><?php echo esc_html( kg_price( $p['first'][1]['m'] ) ); ?></span>
					<span class="kg-plan__per"><?php kg_e( 'pricing.plans.per_month' ); ?></span>
				</div>
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
				</div>
				<ul class="kg-plan__list">
					<?php foreach ( kg_list( 'pricing.plans.two.features' ) as $f ) : ?>
						<li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m5 13 4 4L19 7" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg><?php echo $f; // phpcs:ignore ?></li>
					<?php endforeach; ?>
				</ul>
				<?php kg_cta( 'common.cta_primary', kg_url( 'pricing' ), 'free_trial_start' ); ?>
			</div>
		</div>
		<p style="text-align:center; margin-top:22px; color:var(--kg-text-soft);" data-kg-reveal>
			<?php echo str_replace( '{price}', kg_price( $p['addl'][1]['m'] ), kg_t( 'pricing.plans.addl_note' ) ); // phpcs:ignore ?>
			&nbsp;·&nbsp;<a href="<?php echo esc_url( kg_url( 'pricing' ) ); ?>"><?php kg_e( 'home.pricing.cta_label' ); ?></a>
		</p>
	</div>
</section>

<!-- ========================= Final CTA ============================= -->
<section class="kg-section">
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
