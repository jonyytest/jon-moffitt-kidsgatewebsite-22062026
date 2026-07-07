<?php
/**
 * Rewards — effort-based motivation, no rankings: what earns tokens
 * (effort, consistency, improvement), the token meter, the Store,
 * the avatar studio (placeholder slots awaiting real app art),
 * personal milestones and the parent reassurance section.
 */
get_header();
?>

<section class="kg-page-hero kg-section--teal-wash">
	<div class="kg-container">
		<span class="kg-kicker" data-kg-reveal><?php kg_e( 'rewards.hero.kicker' ); ?></span>
		<h1 class="kg-h1" data-kg-reveal style="--kg-delay:80ms"><?php kg_e( 'rewards.hero.title' ); ?></h1>
		<p class="kg-lede" data-kg-reveal style="--kg-delay:160ms"><?php kg_e( 'rewards.hero.lede' ); ?></p>
	</div>
</section>

<!-- What earns rewards: the three effort factors + the token meter -->
<section class="kg-section kg-section--white">
	<div class="kg-container">
		<?php kg_section_head( 'rewards.earn' ); ?>

		<!-- The earning demo: the three factor cards take turns "earning" — a
		     token flies from the live card into the jar, the jar fills, and at
		     60 tokens a reward pops and the jar banks. Driven by main.js. -->
		<div class="kg-earn" data-kg-earn>
			<div class="kg-earn__cards">
				<?php
				$earn_bubbles = array( 'kg-bubble--amber', 'kg-bubble--teal', 'kg-bubble--red' );
				$earn_icons   = array(
					// Raised hand — giving it a go
					'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M8 13V5.5a1.5 1.5 0 0 1 3 0V12m0-6.5v-2a1.5 1.5 0 0 1 3 0V12m0-5.5a1.5 1.5 0 0 1 3 0V13m0-3.5a1.5 1.5 0 0 1 3 0V15a7 7 0 0 1-7 7h-1c-2.5 0-4.2-1.2-5.6-3.2L4.3 15c-.8-1.1-.5-2.4.6-3 1-.6 2.1-.2 2.8.8L8 13z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
					// Calendar heart — showing up
					'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="3" stroke="currentColor" stroke-width="2"/><path d="M3 10h18M8 3v4m8-4v4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M12 17.5s-2.4-1.6-2.4-3.1c0-.9.7-1.5 1.4-1.5.4 0 .8.2 1 .6.2-.4.6-.6 1-.6.7 0 1.4.6 1.4 1.5 0 1.5-2.4 3.1-2.4 3.1z" fill="currentColor"/></svg>',
					// Chart up — getting better
					'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 3v16a2 2 0 0 0 2 2h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="m7 14 4-4 3 3 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M20 7h-4m4 0v4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
				);
				$earn_coin = '<svg viewBox="0 0 38 38" focusable="false" aria-hidden="true"><circle cx="19" cy="19" r="16.5" fill="var(--kg-amber)" stroke="var(--kg-amber-deep)" stroke-width="3"/><path d="M19 12.2l1.9 4 4.4.5-3.3 3 .9 4.3-3.9-2.2-3.9 2.2.9-4.3-3.3-3 4.4-.5z" fill="var(--kg-amber-deep)" opacity=".8"/></svg>';
				foreach ( kg_list( 'rewards.earn.items' ) as $i => $item ) :
					?>
					<div class="kg-earn__card" data-kg-reveal="right" style="--kg-delay:<?php echo (int) ( $i * 110 ); ?>ms">
						<span class="kg-bubble <?php echo esc_attr( $earn_bubbles[ $i % 3 ] ); ?>"><?php echo $earn_icons[ $i % 3 ]; // phpcs:ignore ?></span>
						<div class="kg-earn__card-body">
							<h3 class="kg-h3"><?php echo $item['title']; // phpcs:ignore ?></h3>
							<p><?php echo $item['text']; // phpcs:ignore ?></p>
						</div>
						<span class="kg-earn__card-earn" aria-hidden="true"><?php echo $earn_coin; // phpcs:ignore ?>+10</span>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="kg-earn__jarwrap" data-kg-reveal="pop">
				<span class="kg-earn__unlock" data-kg-earn-unlock hidden>
					<svg width="17" height="17" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 12v8a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-8M2 7h20v5H2zM12 21V7M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7zM12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
					<em><?php kg_e( 'rewards.earn.unlock' ); ?></em>
				</span>
				<div class="kg-earn__jar" data-kg-earn-jar>
					<svg class="kg-earnjar" viewBox="0 0 120 140" focusable="false" aria-hidden="true">
						<defs>
							<clipPath id="kg-earnjar-clip"><rect x="18" y="32" width="84" height="96" rx="14"/></clipPath>
							<linearGradient id="kg-earnjar-grad" x1="0" y1="0" x2="0" y2="1">
								<stop offset="0" stop-color="#FFC145"/><stop offset="1" stop-color="#F5A623"/>
							</linearGradient>
						</defs>
						<rect x="14" y="28" width="92" height="104" rx="18" fill="rgba(42, 191, 191, .10)" stroke="var(--kg-navy)" stroke-width="3"/>
						<g clip-path="url(#kg-earnjar-clip)">
							<rect class="kg-earnjar__fill" data-kg-earn-fill x="18" y="128" width="84" height="0" fill="url(#kg-earnjar-grad)"/>
						</g>
						<rect x="34" y="12" width="52" height="18" rx="9" fill="var(--kg-amber)" stroke="var(--kg-amber-deep)" stroke-width="3"/>
					</svg>
					<span class="kg-earn__count" aria-hidden="true">
						<svg width="16" height="16" viewBox="0 0 38 38" focusable="false"><circle cx="19" cy="19" r="16.5" fill="var(--kg-amber)" stroke="var(--kg-amber-deep)" stroke-width="3"/><path d="M19 12.2l1.9 4 4.4.5-3.3 3 .9 4.3-3.9-2.2-3.9 2.2.9-4.3-3.3-3 4.4-.5z" fill="var(--kg-amber-deep)" opacity=".8"/></svg>
						<b data-kg-earn-count>0</b>
					</span>
				</div>
				<p class="kg-earn__caption"><?php kg_e( 'rewards.earn.meter_label' ); ?></p>
			</div>
		</div>

		<p class="kg-earn-note" data-kg-reveal>
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
			<span><?php kg_e( 'rewards.earn.note' ); ?></span>
		</p>
	</div>
</section>

<!-- Tokens & the Store — the shop window. The jar above earns the tokens;
     this navy night-market section is where they get spent. The storefront
     card is a tappable demo (main.js): buying an item drops the balance and
     unlocks it, and once the shelf is empty the shop quietly restocks.
     Pure illustration — no real money anywhere, which is the whole point. -->
<?php
$kg_coin_svg = '<svg viewBox="0 0 38 38" fill="none" focusable="false" aria-hidden="true"><circle cx="19" cy="19" r="16.5" fill="var(--kg-amber)" stroke="var(--kg-amber-deep)" stroke-width="3"/><path d="M19 12.2l1.9 4 4.4.5-3.3 3 .9 4.3-3.9-2.2-3.9 2.2.9-4.3-3.3-3 4.4-.5z" fill="var(--kg-amber-deep)" opacity=".8"/></svg>';
?>
<section class="kg-section kg-section--navy kg-storefront">
	<div class="kg-storefront__coins" aria-hidden="true">
		<span><?php echo $kg_coin_svg; // phpcs:ignore ?></span>
		<span><?php echo $kg_coin_svg; // phpcs:ignore ?></span>
		<span><?php echo $kg_coin_svg; // phpcs:ignore ?></span>
		<span><?php echo $kg_coin_svg; // phpcs:ignore ?></span>
	</div>
	<div class="kg-container">
		<div class="kg-spot">
			<div data-kg-reveal="left">
				<div class="kg-storecard" data-kg-store>
					<div class="kg-storecard__awning" aria-hidden="true"></div>
					<div class="kg-storecard__head">
						<strong class="kg-storecard__name"><?php kg_e( 'rewards.store.shop_name' ); ?></strong>
						<span class="kg-storecard__balance" title="<?php echo esc_attr( kg_t( 'rewards.store.balance' ) ); ?>">
							<?php echo $kg_coin_svg; // phpcs:ignore ?>
							<b data-kg-store-balance>95</b>
							<span class="screen-reader-text"><?php kg_e( 'rewards.store.balance' ); ?></span>
						</span>
					</div>
					<div class="kg-storecard__banner">
						<img src="<?php echo esc_url( kg_asset( 'img/store-map.jpg' ) ); ?>" alt="<?php echo esc_attr( kg_t( 'rewards.store.img_alt' ) ); ?>" loading="lazy" width="1600" height="1200">
					</div>
					<div class="kg-storecard__shelf">
						<?php
						$store_prices = array( 30, 25, 40 );
						$store_icons  = array(
							// Explorer hat
							'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 15c0-6 2.5-10 7-10s7 4 7 10M3 15h18v3H3z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>',
							// Cool shades
							'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="7" cy="14" r="4" stroke="currentColor" stroke-width="2"/><circle cx="17" cy="14" r="4" stroke="currentColor" stroke-width="2"/><path d="M11 14h2M3 14 2 8m20 6 1-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
							// Mystery gift
							'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 12v8a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-8M2 7h20v5H2zM12 21V7M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7zM12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
						);
						foreach ( kg_list( 'rewards.store.items' ) as $i => $item_name ) :
							?>
							<button type="button" class="kg-storecard__item" data-kg-store-item data-kg-price="<?php echo (int) $store_prices[ $i % 3 ]; ?>" aria-pressed="false">
								<span class="kg-storecard__item-icon" aria-hidden="true"><?php echo $store_icons[ $i % 3 ]; // phpcs:ignore ?></span>
								<small><?php echo $item_name; // phpcs:ignore ?></small>
								<span class="kg-storecard__price"><?php echo $kg_coin_svg; // phpcs:ignore ?><b><?php echo (int) $store_prices[ $i % 3 ]; ?></b></span>
								<span class="kg-storecard__owned"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m5 13 4 4L19 7" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/></svg><?php kg_e( 'rewards.store.owned' ); ?></span>
							</button>
						<?php endforeach; ?>
					</div>
				</div>
				<p class="kg-storecard__hint"><?php kg_e( 'rewards.store.hint' ); ?></p>
			</div>
			<div data-kg-reveal="right">
				<span class="kg-kicker"><?php kg_e( 'rewards.store.kicker' ); ?></span>
				<h2 class="kg-h2"><?php kg_e( 'rewards.store.title' ); ?></h2>
				<p class="kg-lede" style="font-size:1.05rem;"><?php kg_e( 'rewards.store.text' ); ?></p>
				<ul class="kg-storepts">
					<?php foreach ( kg_list( 'rewards.store.points' ) as $point ) : ?>
						<li><?php echo $kg_coin_svg; // phpcs:ignore ?><span><?php echo $point; // phpcs:ignore ?></span></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</section>

<!-- Avatar studio: placeholder slots awaiting real app artwork -->
<section class="kg-section kg-section--white">
	<div class="kg-container">
		<?php kg_section_head( 'rewards.avatar' ); ?>
		<div class="kg-avastudio" data-kg-reveal="pop">
			<div class="kg-avastudio__stage">
				<!-- PLACEHOLDER: replace with real avatar artwork from the app when supplied -->
				<svg viewBox="0 0 120 150" fill="none" aria-hidden="true" focusable="false">
					<circle cx="60" cy="42" r="26" stroke="currentColor" stroke-width="4" stroke-dasharray="8 7" stroke-linecap="round"/>
					<path d="M20 138c3-28 18-42 40-42s37 14 40 42" stroke="currentColor" stroke-width="4" stroke-dasharray="8 7" stroke-linecap="round"/>
				</svg>
				<span class="kg-avastudio__ph"><?php kg_e( 'rewards.avatar.placeholder' ); ?></span>
			</div>
			<div class="kg-avastudio__slots">
				<?php
				$slot_icons = array(
					// Hat
					'<svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 15c0-6 2.5-10 7-10s7 4 7 10M3 15h18v3H3z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>',
					// Shirt
					'<svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m9 4-6 4 2.5 3.5L8 10v10h8V10l2.5 1.5L21 8l-6-4a3 3 0 0 1-6 0z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>',
					// Glasses
					'<svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="7" cy="14" r="4" stroke="currentColor" stroke-width="2"/><circle cx="17" cy="14" r="4" stroke="currentColor" stroke-width="2"/><path d="M11 14h2M3 14 2 8m20 6 1-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
					// Palette
					'<svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 2a10 10 0 1 0 0 20c1.7 0 2.5-1 2-2.5-.6-1.7.3-3 2-3H18a4 4 0 0 0 4-4c0-5.8-4.5-10.5-10-10.5z" stroke="currentColor" stroke-width="2"/><circle cx="7.5" cy="10.5" r="1.3" fill="currentColor"/><circle cx="12" cy="7.5" r="1.3" fill="currentColor"/><circle cx="16.5" cy="10.5" r="1.3" fill="currentColor"/></svg>',
					// Background / image
					'<svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="3" stroke="currentColor" stroke-width="2"/><path d="M3 16l5-5 4 4 3-3 6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="9" cy="9" r="1.5" fill="currentColor"/></svg>',
					// Gift / surprise
					'<svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 12v8a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-8M2 7h20v5H2zM12 21V7M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7zM12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
				);
				foreach ( kg_list( 'rewards.avatar.slots' ) as $i => $slot ) :
					?>
					<div class="kg-avastudio__slot" data-kg-reveal="pop" style="--kg-delay:<?php echo (int) ( 120 + $i * 80 ); ?>ms">
						<!-- PLACEHOLDER: swap the icon for a real item image when app art arrives -->
						<span class="kg-avastudio__slot-box" aria-hidden="true"><?php echo $slot_icons[ $i % 6 ]; // phpcs:ignore ?></span>
						<small><?php echo $slot; // phpcs:ignore ?></small>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<!-- Milestones: personal badges, navy showcase -->
<section class="kg-section kg-section--navy">
	<div class="kg-container">
		<?php kg_section_head( 'rewards.milestones' ); ?>
		<div class="kg-badges">
			<?php
			$badge_tones = array( 'amber', 'teal', 'red', 'green' );
			$badge_icons = array(
				// Rocket — first lesson
				'<svg width="28" height="28" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 15c-2 0-3-1-3-3 0-4 2-8 3-10 1 2 3 6 3 10 0 2-1 3-3 3zM9 13l-3 2 1.5 3M15 13l3 2-1.5 3M10.5 18.5 12 22l1.5-3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
				// Checklist — questions tried
				'<svg width="28" height="28" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m3 7 1.6 1.6L8 5.2M13 7.5h8M3 16l1.6 1.6L8 14.2M13 16.5h8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
				// Target check — topic mastered
				'<svg width="28" height="28" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/><path d="m8.6 12 2.3 2.3 4.5-4.6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
				// Double chevron up — level up
				'<svg width="28" height="28" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m6 13 6-6 6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/><path d="m6 19 6-6 6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" opacity=".55"/></svg>',
			);
			foreach ( kg_list( 'rewards.milestones.items' ) as $i => $item ) :
				?>
				<div class="kg-badge kg-badge--<?php echo esc_attr( $badge_tones[ $i % 4 ] ); ?>" data-kg-reveal="pop" style="--kg-delay:<?php echo (int) ( $i * 110 ); ?>ms">
					<span class="kg-badge__medal" aria-hidden="true"><?php echo $badge_icons[ $i % 4 ]; // phpcs:ignore ?></span>
					<h3 class="kg-h3"><?php echo $item['title']; // phpcs:ignore ?></h3>
					<p><?php echo $item['text']; // phpcs:ignore ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Parent reassurance — the "safe zone": the three reassurances sit inside
     an enclosed panel with a dashed guarded boundary, a shield seal
     overlapping the top edge and a faint shield watermark. Each icon lives
     in a shield-shaped badge in calming trust colours. -->
<section class="kg-section kg-section--cream kg-guard">
	<div class="kg-container">
		<?php kg_section_head( 'rewards.safety' ); ?>
		<div class="kg-guard__zone" data-kg-reveal="pop">
			<span class="kg-guard__seal" aria-hidden="true">
				<svg width="26" height="26" viewBox="0 0 24 24" fill="none"><path d="M12 2 4 5.5V11c0 5 3.4 9.3 8 11 4.6-1.7 8-6 8-11V5.5L12 2z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="m8.7 11.5 2.3 2.3 4.3-4.6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
			</span>
			<span class="kg-guard__bg" aria-hidden="true">
				<span class="kg-guard__watermark">
					<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2 4 5.5V11c0 5 3.4 9.3 8 11 4.6-1.7 8-6 8-11V5.5L12 2z"/></svg>
				</span>
			</span>
			<div class="kg-guard__grid">
				<?php
				$safe_icons = array(
					// Mask/name tag — safe display names
					'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="7" width="18" height="12" rx="3" stroke="currentColor" stroke-width="2"/><path d="M12 3v4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><circle cx="8.5" cy="12" r="1.4" fill="currentColor"/><circle cx="15.5" cy="12" r="1.4" fill="currentColor"/><path d="M9 15.5c.9.7 2 1 3 1s2.1-.3 3-1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
					// Coin from book — rewards from learning only
					'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V4a2 2 0 0 0-2-2H6.5A2.5 2.5 0 0 0 4 4.5v15zM20 17v5H6.5a2.5 2.5 0 0 1 0-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="9" r="3" stroke="currentColor" stroke-width="2"/></svg>',
					// Eye shield — parents see everything
					'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 2 4 5.5V11c0 5 3.4 9.3 8 11 4.6-1.7 8-6 8-11V5.5L12 2z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><circle cx="12" cy="11" r="2.6" stroke="currentColor" stroke-width="2"/></svg>',
				);
				foreach ( kg_list( 'rewards.safety.items' ) as $i => $item ) :
					?>
					<div class="kg-guard__card" data-kg-reveal style="--kg-delay:<?php echo (int) ( 120 + $i * 100 ); ?>ms">
						<span class="kg-guard__shield" aria-hidden="true"><?php echo $safe_icons[ $i % 3 ]; // phpcs:ignore ?></span>
						<h3 class="kg-h3"><?php echo $item['title']; // phpcs:ignore ?></h3>
						<p><?php echo $item['text']; // phpcs:ignore ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<!-- Closing CTA -->
<section class="kg-section">
	<div class="kg-container">
		<div class="kg-final-cta" data-kg-reveal="pop">
			<h2 class="kg-h2"><?php kg_e( 'rewards.final.title' ); ?></h2>
			<div class="kg-final-cta__ctas">
				<?php kg_cta( 'common.cta_primary', kg_url( 'pricing' ), 'free_trial_start', 'kg-btn kg-btn--primary kg-btn--lg' ); ?>
				<?php kg_cta( 'common.cta_secondary', kg_url( 'how-it-works' ), '', 'kg-btn kg-btn--ghost-dark kg-btn--lg' ); ?>
			</div>
			<?php kg_trust_chips( 'common.cancel_chips' ); ?>
		</div>
	</div>
</section>

<?php
get_footer();
