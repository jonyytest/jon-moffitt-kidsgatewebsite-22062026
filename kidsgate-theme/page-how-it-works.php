<?php
/**
 * How It Works — the full journey: 7 expandable steps, the demo video,
 * the 20-minute session timeline, and a closing trial CTA.
 */
get_header();
?>

<section class="kg-page-hero kg-section--cream">
	<div class="kg-container">
		<span class="kg-kicker" data-kg-reveal><?php kg_e( 'hiw.hero.kicker' ); ?></span>
		<h1 class="kg-h1" data-kg-reveal style="--kg-delay:80ms"><?php kg_e( 'hiw.hero.title' ); ?></h1>
		<p class="kg-lede" data-kg-reveal style="--kg-delay:160ms"><?php kg_e( 'hiw.hero.lede' ); ?></p>
	</div>
</section>

<!-- Journey: scroll-driven timeline — a glowing orb travels down the line
     and opens each numbered gate as it reaches it (no clicking needed). -->
<section class="kg-section kg-section--white" data-kg-hiw-watch>
	<div class="kg-container kg-container--narrow">
		<div class="kg-jt" data-kg-journey>
			<div class="kg-jt__track" aria-hidden="true">
				<span class="kg-jt__fill" data-kg-journey-fill></span>
				<span class="kg-jt__orb" data-kg-journey-orb></span>
			</div>
			<ol class="kg-jt__steps">
				<?php
				$gate_colors = array( 'var(--kg-teal)', 'var(--kg-amber)', 'var(--kg-red)', 'var(--kg-navy)', 'var(--kg-teal)', 'var(--kg-amber)', 'var(--kg-red)' );
				foreach ( kg_list( 'hiw.steps' ) as $i => $step ) :
					// The node badge shows the number, so strip a leading "1." from the title if present.
					$title = preg_replace( '/^\s*\d+\s*[.)]\s*/u', '', $step['title'] );
					?>
					<li class="kg-jt__step" data-kg-journey-step>
						<span class="kg-jt__node" style="--kg-node:<?php echo esc_attr( $gate_colors[ $i ] ); ?>" aria-hidden="true"><?php echo (int) ( $i + 1 ); ?></span>
						<div class="kg-jt__card">
							<h2 class="kg-jt__title"><?php echo $title; // phpcs:ignore ?></h2>
							<div class="kg-jt__body">
								<div class="kg-jt__body-inner">
									<p><?php echo $step['text']; // phpcs:ignore ?></p>
									<p class="kg-jt__detail"><em><?php echo $step['detail']; // phpcs:ignore ?></em></p>
								</div>
							</div>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</div>
</section>

<!-- Demo video -->
<?php
$video = kg_video_url();
$yt_id = $video ? kg_video_youtube_id( $video ) : '';
?>
<section class="kg-section kg-section--navy">
	<div class="kg-container">
		<?php kg_section_head( 'hiw.video' ); ?>
		<?php if ( $yt_id ) : ?>
			<!-- Click-to-load YouTube facade: only the local poster renders on
			     page load; main.js injects the privacy-enhanced youtube-nocookie
			     iframe (and YouTube's scripts) when the visitor presses play. -->
			<div class="kg-video" data-kg-video data-kg-youtube="<?php echo esc_attr( $yt_id ); ?>" data-kg-reveal="pop">
				<img class="kg-video__poster" src="<?php echo esc_url( kg_asset( 'img/child-learning.jpg' ) ); ?>" alt="" loading="lazy">
				<button class="kg-video__cover" type="button">
					<span class="kg-video__play" aria-hidden="true">
						<svg width="34" height="34" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5.5v13l11-6.5-11-6.5z"/></svg>
					</span>
					<?php kg_e( 'hiw.video.play' ); ?>
				</button>
			</div>
		<?php elseif ( $video ) : ?>
			<div class="kg-video" data-kg-video data-kg-reveal="pop">
				<video preload="metadata" playsinline poster="<?php echo esc_url( kg_asset( 'img/child-learning.jpg' ) ); ?>">
					<source src="<?php echo esc_url( $video ); ?>" type="video/mp4">
				</video>
				<button class="kg-video__cover" type="button">
					<span class="kg-video__play" aria-hidden="true">
						<svg width="34" height="34" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5.5v13l11-6.5-11-6.5z"/></svg>
					</span>
					<?php kg_e( 'hiw.video.play' ); ?>
				</button>
			</div>
		<?php else : ?>
			<div class="kg-video kg-placeholder" data-kg-reveal style="aspect-ratio:16/9; max-width:920px; margin-inline:auto;">
				<svg width="44" height="44" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="3" stroke="currentColor" stroke-width="1.8"/><path d="m10 9 5 3-5 3V9z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
				Placeholder — upload GateDemoVideo.mp4 to the Media Library and set its URL in Customizer → The Kids Gate Settings.
			</div>
		<?php endif; ?>
	</div>
</section>

<!-- 20-minute session timeline. A ball drops out from behind the navy video
     section above into the first dot, then the line lights up dot by dot as
     a one-shot animation (driven by main.js). overflow:clip hides the ball
     until it emerges below the navy edge. -->
<section class="kg-section kg-section--cream" style="overflow:clip;">
	<div class="kg-container">
		<?php kg_section_head( 'hiw.session' ); ?>
		<div class="kg-timeline" data-kg-session>
			<span class="kg-timeline__ball" data-kg-session-ball aria-hidden="true"></span>
			<?php
			$dot_colors = array( 'var(--kg-teal)', 'var(--kg-amber)', 'var(--kg-red)', 'var(--kg-navy)' );
			foreach ( kg_list( 'hiw.session.items' ) as $i => $item ) :
				?>
				<div class="kg-timeline__item">
					<span class="kg-timeline__dot" style="background:<?php echo esc_attr( $dot_colors[ $i % 4 ] ); ?>" aria-hidden="true"></span>
					<span class="kg-timeline__time"><?php echo $item['time']; // phpcs:ignore ?></span>
					<h3><?php echo $item['title']; // phpcs:ignore ?></h3>
					<p><?php echo $item['text']; // phpcs:ignore ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Closing CTA -->
<section class="kg-section">
	<div class="kg-container">
		<div class="kg-final-cta" data-kg-reveal="pop">
			<h2 class="kg-h2"><?php kg_e( 'hiw.cta.title' ); ?></h2>
			<p class="kg-lede"><?php kg_e( 'hiw.cta.lede' ); ?></p>
			<div class="kg-final-cta__ctas">
				<?php kg_cta( 'common.cta_primary', kg_url( 'pricing' ), 'free_trial_start', 'kg-btn kg-btn--primary kg-btn--lg' ); ?>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
