<?php
/**
 * Leaderboard — sample rankings with filters, safety explanation and the
 * monthly prize-draw story. All data is clearly-labelled sample content;
 * live rankings come from the app once the API is connected.
 */
get_header();
?>

<section class="kg-page-hero kg-section--teal-wash">
	<div class="kg-container">
		<span class="kg-kicker" data-kg-reveal><?php kg_e( 'leaderboard.hero.kicker' ); ?></span>
		<h1 class="kg-h1" data-kg-reveal style="--kg-delay:80ms"><?php kg_e( 'leaderboard.hero.title' ); ?></h1>
		<p class="kg-lede" data-kg-reveal style="--kg-delay:160ms"><?php kg_e( 'leaderboard.hero.lede' ); ?></p>
	</div>
</section>

<section class="kg-section kg-section--white" style="padding-top: clamp(36px, 5vw, 56px);">
	<div class="kg-container">
		<?php
		$lang       = kg_lang();
		$my_country = ( 'id' === $lang ) ? 'ID' : ( ( 'th' === $lang ) ? 'TH' : 'AU' );
		$thousands  = ( 'id' === $lang ) ? '.' : ',';
		$filter_map = array(
			'scope'    => array( 'key' => 'scope',   'values' => array( 'global', 'country' ) ),
			'grades'   => array( 'key' => 'grade',   'values' => array( 'all', '1', '2', '3', '4', '5', '6' ) ),
			'subjects' => array( 'key' => 'subject', 'values' => array( 'all', 'english', 'maths' ) ),
			'periods'  => array( 'key' => 'period',  'values' => array( 'week', 'month', 'all' ) ),
		);
		?>
		<div class="kg-lb-filters" data-kg-lb-filters data-my-country="<?php echo esc_attr( $my_country ); ?>" data-kg-reveal>
			<?php
			foreach ( $filter_map as $f => $meta ) :
				$opts = kg_list( 'leaderboard.filters.' . $f );
				if ( empty( $opts ) ) {
					continue;
				}
				?>
				<select data-kg-filter="<?php echo esc_attr( $meta['key'] ); ?>" aria-label="<?php echo esc_attr( $opts[0] ); ?>">
					<?php foreach ( $opts as $oi => $opt ) : ?>
						<option value="<?php echo esc_attr( isset( $meta['values'][ $oi ] ) ? $meta['values'][ $oi ] : $oi ); ?>"><?php echo $opt; // phpcs:ignore ?></option>
					<?php endforeach; ?>
				</select>
			<?php endforeach; ?>
		</div>

		<div class="kg-lb__meta" data-kg-reveal>
			<span class="kg-lb__count" data-kg-lb-count aria-live="polite"></span>
			<button type="button" class="kg-lb__reset" data-kg-lb-reset hidden><?php kg_e( 'leaderboard.reset' ); ?></button>
		</div>

		<div class="kg-lb"
			data-kg-lb
			data-thousands="<?php echo esc_attr( $thousands ); ?>"
			data-count-tpl="<?php echo esc_attr( kg_t( 'leaderboard.count' ) ); ?>"
			data-count-one="<?php echo esc_attr( kg_t( 'leaderboard.count_one' ) ); ?>">
			<?php
			$rank_classes  = array( 1 => 'kg-lb__row--gold', 2 => 'kg-lb__row--silver', 3 => 'kg-lb__row--bronze' );
			$avatar_colors = array( 'var(--kg-teal)', 'var(--kg-amber)', 'var(--kg-red)', 'var(--kg-navy)' );
			foreach ( kg_list( 'leaderboard.rows' ) as $i => $row ) :
				$rank      = $i + 1;
				$token_num = (int) preg_replace( '/\D/', '', $row['tokens'] );
				?>
				<div class="kg-lb__row <?php echo isset( $rank_classes[ $rank ] ) ? esc_attr( $rank_classes[ $rank ] ) : ''; ?>"
					data-kg-lb-row
					data-country="<?php echo esc_attr( $row['code'] ); ?>"
					data-grade="<?php echo (int) $row['grade_n']; ?>"
					data-subject="<?php echo esc_attr( $row['subject'] ); ?>"
					data-tokens="<?php echo (int) $token_num; ?>"
					data-seed="<?php echo (int) $i; ?>"
					data-kg-reveal style="--kg-delay:<?php echo (int) ( $i * 70 ); ?>ms">
					<span class="kg-lb__rank" data-kg-lb-rank><?php echo 1 === $rank ? '🏆' : '#' . (int) $rank; ?></span>
					<span class="kg-lb__avatar" style="background:<?php echo esc_attr( $avatar_colors[ $i % 4 ] ); ?>" aria-hidden="true"><?php echo esc_html( kg_initial( $row['name'] ) ); ?></span>
					<span class="kg-lb__who">
						<strong><?php echo $row['name']; // phpcs:ignore ?></strong>
						<small><?php echo $row['country'] . ' · ' . $row['grade']; // phpcs:ignore ?></small>
					</span>
					<?php if ( 'up' === $row['move'] ) : ?>
						<span class="kg-lb__movement kg-lb__movement--up"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 19V5m-6 6 6-6 6 6" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/></svg>2</span>
					<?php elseif ( 'down' === $row['move'] ) : ?>
						<span class="kg-lb__movement kg-lb__movement--down"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 5v14m6-6-6 6-6-6" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/></svg>1</span>
					<?php else : ?>
						<span class="kg-lb__movement" aria-hidden="true">–</span>
					<?php endif; ?>
					<span class="kg-lb__tokens"><svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="12" cy="12" r="10"/></svg><span data-kg-lb-tokenval><?php echo $row['tokens']; // phpcs:ignore ?></span></span>
				</div>
			<?php endforeach; ?>
		</div>

		<p class="kg-lb__empty" data-kg-lb-empty hidden><?php kg_e( 'leaderboard.empty' ); ?></p>
		<div class="kg-lb__pagination" data-kg-lb-pagination aria-label="Leaderboard pagination"></div>
		<p class="kg-lb__note" data-kg-reveal><?php kg_e( 'leaderboard.demo_note' ); ?></p>
	</div>
</section>

<!-- Safety rules -->
<section class="kg-section kg-section--cream">
	<div class="kg-container">
		<?php kg_section_head( 'leaderboard.safety' ); ?>
		<div class="kg-problems">
			<?php
			$icons = array(
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 2 4 6v6c0 5 3.4 8.5 8 10 4.6-1.5 8-5 8-10V6l-8-4z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>',
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M21 10c0 7-9 12-9 12S3 17 3 10a9 9 0 0 1 18 0z" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="10" r="3" stroke="currentColor" stroke-width="2"/></svg>',
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 6v6l4 2M22 12c0 5.5-4.5 10-10 10S2 17.5 2 12 6.5 2 12 2s10 4.5 10 10z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
			);
			foreach ( kg_list( 'leaderboard.safety.items' ) as $i => $item ) :
				?>
				<div class="kg-card" data-kg-reveal style="--kg-delay:<?php echo (int) ( $i * 100 ); ?>ms">
					<span class="kg-bubble kg-bubble--teal"><?php echo $icons[ $i % 3 ]; // phpcs:ignore ?></span>
					<h3 class="kg-h3"><?php echo $item['title']; // phpcs:ignore ?></h3>
					<p style="margin:0;"><?php echo $item['text']; // phpcs:ignore ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Prize draws -->
<section class="kg-section kg-section--navy">
	<div class="kg-container kg-container--narrow" style="text-align:center;">
		<div data-kg-reveal="pop">
			<span class="kg-kicker"><?php kg_e( 'leaderboard.prizes.kicker' ); ?></span>
			<h2 class="kg-h2"><?php kg_e( 'leaderboard.prizes.title' ); ?></h2>
			<p class="kg-lede" style="margin-inline:auto;"><?php kg_e( 'leaderboard.prizes.text' ); ?></p>
			<div style="margin-top:28px;">
				<?php kg_cta( 'leaderboard.cta_label', kg_url( 'pricing' ), 'free_trial_start', 'kg-btn kg-btn--primary kg-btn--lg' ); ?>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
