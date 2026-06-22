<?php
/**
 * Site footer: arch-top treatment, KG logo, full navigation, prominent
 * Support link, store badges, language selector and legal placeholders.
 */
?>
</main>

<footer class="kg-footer">
	<div class="kg-footer__arch" aria-hidden="true">
		<svg viewBox="0 0 1440 80" preserveAspectRatio="none" focusable="false"><path d="M0 80V40C240 0 480 0 720 20s480 20 720-10V80z" fill="currentColor"/></svg>
	</div>
	<div class="kg-footer__inner">
		<div class="kg-footer__brand">
			<a class="kg-logo kg-logo--footer" href="<?php echo esc_url( kg_url() ); ?>" aria-label="<?php echo esc_attr( kg_t( 'common.home_aria' ) ); ?>">
				<img src="<?php echo esc_url( kg_asset( 'img/kg-logo.png' ) ); ?>" alt="Kids Gate" width="52" height="54" loading="lazy">
				<span class="kg-logo__word">Kids <em>Gate</em></span>
			</a>
			<p class="kg-footer__tag"><?php kg_e( 'footer.tagline' ); ?></p>
			<?php kg_store_badges(); ?>
			<?php
			$social = kg_social_links();
			$icons  = array(
				'instagram' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5" stroke="currentColor" stroke-width="1.9"/><circle cx="12" cy="12" r="5" stroke="currentColor" stroke-width="1.9"/><circle cx="17.5" cy="6.5" r="1.2" fill="currentColor"/></svg>',
				'tiktok'    => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.27 6.27 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.75a4.85 4.85 0 0 1-1.01-.06z"/></svg>',
				'facebook'  => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.41c0-3.025 1.792-4.697 4.533-4.697 1.312 0 2.686.236 2.686.236v2.97h-1.513c-1.491 0-1.956.93-1.956 1.886v2.268h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/></svg>',
			);
			$labels = array( 'instagram' => 'Instagram', 'tiktok' => 'TikTok', 'facebook' => 'Facebook' );
			?>
			<div class="kg-footer__social">
				<?php foreach ( $icons as $key => $svg ) :
					$url  = $social[ $key ];
					$live = ! empty( $url );
					?>
					<a class="kg-footer__social-btn<?php echo $live ? '' : ' kg-footer__social-btn--inert'; ?>"
						href="<?php echo $live ? esc_url( $url ) : '#'; ?>"
						<?php if ( $live ) : ?>target="_blank" rel="noopener noreferrer"<?php endif; ?>
						<?php if ( ! $live ) : ?>aria-disabled="true" tabindex="-1"<?php endif; ?>
						aria-label="<?php echo esc_attr( $labels[ $key ] ); ?>">
						<?php echo $svg; // phpcs:ignore ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>

		<nav class="kg-footer__col" aria-label="<?php echo esc_attr( kg_t( 'footer.explore' ) ); ?>">
			<h2 class="kg-footer__head"><?php kg_e( 'footer.explore' ); ?></h2>
			<ul>
				<li><a href="<?php echo esc_url( kg_url( 'how-it-works' ) ); ?>"><?php kg_e( 'nav.how_it_works' ); ?></a></li>
				<li><a href="<?php echo esc_url( kg_url( 'features' ) ); ?>"><?php kg_e( 'nav.features' ); ?></a></li>
				<li><a href="<?php echo esc_url( kg_url( 'parents' ) ); ?>"><?php kg_e( 'nav.parents' ); ?></a></li>
				<li><a href="<?php echo esc_url( kg_url( 'pricing' ) ); ?>"><?php kg_e( 'nav.pricing' ); ?></a></li>
				<li><a href="<?php echo esc_url( kg_url( 'leaderboard' ) ); ?>"><?php kg_e( 'nav.leaderboard' ); ?></a></li>
			</ul>
		</nav>

		<nav class="kg-footer__col" aria-label="<?php echo esc_attr( kg_t( 'footer.company' ) ); ?>">
			<h2 class="kg-footer__head"><?php kg_e( 'footer.company' ); ?></h2>
			<ul>
				<li><a href="<?php echo esc_url( kg_url( 'about' ) ); ?>"><?php kg_e( 'nav.about' ); ?></a></li>
				<li><a href="<?php echo esc_url( kg_url( 'schools' ) ); ?>"><?php kg_e( 'nav.schools' ); ?></a></li>
				<li><a href="<?php echo esc_url( kg_url( 'sponsors' ) ); ?>"><?php kg_e( 'nav.sponsors' ); ?></a></li>
				<li><a href="/assets/pdf/privacy-policy.pdf" download class="kg-footer__pdf"><?php kg_e( 'footer.privacy' ); ?></a></li>
				<li><a href="/assets/pdf/terms-of-service.pdf" download class="kg-footer__pdf"><?php kg_e( 'footer.terms' ); ?></a></li>
			</ul>
		</nav>

		<div class="kg-footer__col kg-footer__col--support">
			<h2 class="kg-footer__head"><?php kg_e( 'footer.help' ); ?></h2>
			<a class="kg-footer__support-card" href="<?php echo esc_url( kg_url( 'support' ) ); ?>" data-kg-event="404_support_click" data-kg-event-name="footer_support_click">
				<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 22c5.5 0 10-4.5 10-10S17.5 2 12 2 2 6.5 2 12c0 1.8.5 3.5 1.3 5L2 22l5-1.3c1.5.8 3.2 1.3 5 1.3z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M8.5 10.5h7M8.5 14h4.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
				<span>
					<strong><?php kg_e( 'footer.support_title' ); ?></strong>
					<small><?php kg_e( 'footer.support_sub' ); ?></small>
				</span>
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
			</a>
			<p class="kg-footer__contact">
				AI Learning Solutions Pty Ltd<br>
				<a href="mailto:<?php echo esc_attr( kg_support_email() ); ?>" data-kg-event="support_email_click"><?php echo esc_html( kg_support_email() ); ?></a>
				<?php if ( ! kg_support_email_is_live() ) : ?>
					<span class="kg-footer__email-note"><?php kg_e( 'footer.email_placeholder_note' ); ?></span>
				<?php endif; ?>
			</p>
			<?php kg_language_switcher( '-f' ); ?>
		</div>
	</div>

	<div class="kg-footer__legal">
		<p>© <?php echo esc_html( gmdate( 'Y' ) ); ?> AI Learning Solutions Pty Ltd. <?php kg_e( 'footer.rights' ); ?></p>
	</div>
</footer>

<?php kg_render_helper(); ?>

<?php wp_footer(); ?>
</body>
</html>
