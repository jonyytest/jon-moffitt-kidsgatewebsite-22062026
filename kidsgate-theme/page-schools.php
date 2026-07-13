<?php
/**
 * For Schools & Teachers — B2B page: curriculum alignment, use cases,
 * teacher dashboard preview, curriculum download placeholder and the
 * enquiry form (backend integration documented as pending).
 */
get_header();
?>

<section class="kg-page-hero kg-section--cream">
	<div class="kg-container">
		<span class="kg-kicker" data-kg-reveal><?php kg_e( 'schools.hero.kicker' ); ?></span>
		<h1 class="kg-h1" data-kg-reveal style="--kg-delay:80ms"><?php kg_e( 'schools.hero.title' ); ?></h1>
		<p class="kg-lede" data-kg-reveal style="--kg-delay:160ms"><?php kg_e( 'schools.hero.lede' ); ?></p>
		<div class="kg-hero__ctas" data-kg-reveal style="margin-top:26px; --kg-delay:240ms">
			<a class="kg-btn kg-btn--primary kg-btn--lg" href="#kg-school-form"><span><?php kg_e( 'schools.form.title' ); ?></span><svg class="kg-btn__arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
			<button class="kg-btn kg-btn--secondary kg-btn--lg" type="button" title="<?php echo esc_attr( kg_t( 'schools.curriculum_note' ) ); ?>"><span><?php kg_e( 'schools.curriculum_cta' ); ?></span><svg class="kg-btn__arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
		</div>
		<p style="color:var(--kg-text-soft); font-size:.9rem; margin-top:10px;" data-kg-reveal><?php kg_e( 'schools.curriculum_note' ); ?></p>
	</div>
</section>

<?php
// Two dashboard "views" — Teacher and Principal — sharing one section and an
// Apple-style sliding toggle. Each view reuses the same spot layout; the JS in
// main.js swaps them with a directional slide-in, fades the section background,
// and auto-advances to the Principal view once after a read-pause.
$dash_views = array(
	'teacher' => array(
		'key'   => 'schools.dash',
		'img'   => 'img/teacher-dashboard.png',
		'w'     => 1569,
		'h'     => 1359,
		'event' => 'teacher_dashboard_zoom',
		'icons' => array(
			// Line chart trending up — assessment scores
			'<svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 3v18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="m7 14 3.5-3.5 3 3L21 7m0 0h-4m4 0v4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
			// Donut — session breakdown
			'<svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3.5" stroke="currentColor" stroke-width="2"/><path d="M12 3v5.5M21 12h-5.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
			// Bar chart — avg score per student
			'<svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 21h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><rect x="5" y="11" width="3.5" height="7" rx="1" stroke="currentColor" stroke-width="2"/><rect x="10.25" y="6" width="3.5" height="12" rx="1" stroke="currentColor" stroke-width="2"/><rect x="15.5" y="9" width="3.5" height="9" rx="1" stroke="currentColor" stroke-width="2"/></svg>',
			// Check badge — activity completion
			'<svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/><path d="m8.5 12 2.5 2.5 4.5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		),
	),
	'principal' => array(
		'key'   => 'schools.principal',
		'img'   => 'img/principal-dashboard.png',
		'w'     => 1141,
		'h'     => 1051,
		'event' => 'principal_dashboard_zoom',
		'icons' => array(
			// Two overlapping circles (Venn) — cross-class comparison
			'<svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="9" cy="12" r="6.5" stroke="currentColor" stroke-width="2"/><circle cx="15" cy="12" r="6.5" stroke="currentColor" stroke-width="2"/></svg>',
			// Funnel — grade & subject filters
			'<svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 5h18l-7 8v6l-4 2v-8L3 5z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
			// Trophy — avg earned tokens
			'<svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 4h10v5a5 5 0 0 1-10 0V4z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M7 6H4v1a3 3 0 0 0 3 3M17 6h3v1a3 3 0 0 1-3 3M9 19h6M10 19v-3.2M14 19v-3.2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
			// Clipboard list — avg completed assessments
			'<svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M9 3h6a1 1 0 0 1 1 1v1h2a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h2V4a1 1 0 0 1 1-1z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M9 11h6M9 15h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
		),
	),
);
$dash_bubbles = array( 'kg-bubble--teal', 'kg-bubble--amber', 'kg-bubble--red', 'kg-bubble--green-dark' );
?>
<!-- Dashboards: teacher / principal view switch -->
<section class="kg-section kg-section--navy kg-dash-section" data-kg-dashtoggle>
	<div class="kg-container">
		<div class="kg-dashstage kg-spot--wide" data-kg-reveal>
			<!-- Left column: the sliding toggle sits directly above the screenshot -->
			<div class="kg-dashstage__visual">
				<div class="kg-viewtoggle-wrap">
					<div class="kg-viewtoggle" role="group" aria-label="<?php echo esc_attr( kg_t( 'schools.viewtoggle.aria' ) ); ?>">
						<button type="button" class="kg-viewtoggle__opt is-active" data-kg-view-btn="teacher" aria-pressed="true"><?php kg_e( 'schools.viewtoggle.teacher' ); ?></button>
						<button type="button" class="kg-viewtoggle__opt" data-kg-view-btn="principal" aria-pressed="false"><?php kg_e( 'schools.viewtoggle.principal' ); ?></button>
						<span class="kg-viewtoggle__slider" aria-hidden="true"></span>
					</div>
				</div>
				<?php foreach ( $dash_views as $view => $cfg ) : ?>
					<div class="kg-dashview<?php echo 'teacher' === $view ? ' is-shown' : ''; ?>" data-kg-view="<?php echo esc_attr( $view ); ?>"<?php echo 'teacher' === $view ? '' : ' aria-hidden="true"'; ?>>
						<button type="button" class="kg-spot__visual kg-lightbox-trigger"
							data-kg-lightbox
							data-kg-event="<?php echo esc_attr( $cfg['event'] ); ?>"
							data-kg-lightbox-zoom="<?php echo esc_attr( kg_t( 'common.zoom_hint' ) ); ?>"
							data-kg-lightbox-zoomout="<?php echo esc_attr( kg_t( 'common.zoom_out_hint' ) ); ?>"
							aria-label="<?php echo esc_attr( kg_t( 'common.view_larger' ) ); ?>">
							<img src="<?php echo esc_url( kg_asset( $cfg['img'] ) ); ?>"
								alt="<?php echo esc_attr( kg_t( $cfg['key'] . '.img_alt' ) ); ?>"
								width="<?php echo (int) $cfg['w']; ?>" height="<?php echo (int) $cfg['h']; ?>" loading="lazy" decoding="async">
							<span class="kg-lightbox-trigger__badge" aria-hidden="true">
								<svg width="17" height="17" viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2.2"/><path d="m20 20-3.2-3.2M11 8.4v5.2M8.4 11h5.2" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>
								<?php kg_e( 'common.view_larger' ); ?>
							</span>
						</button>
					</div>
				<?php endforeach; ?>
			</div>
			<!-- Right column: matching copy for whichever view is active -->
			<div class="kg-dashstage__text">
				<?php foreach ( $dash_views as $view => $cfg ) : ?>
					<div class="kg-dashview<?php echo 'teacher' === $view ? ' is-shown' : ''; ?>" data-kg-view="<?php echo esc_attr( $view ); ?>"<?php echo 'teacher' === $view ? '' : ' aria-hidden="true"'; ?>>
						<span class="kg-kicker"><?php kg_e( $cfg['key'] . '.kicker' ); ?></span>
						<h2 class="kg-h2"><?php kg_e( $cfg['key'] . '.title' ); ?></h2>
						<p class="kg-lede"><?php kg_e( $cfg['key'] . '.text' ); ?></p>
						<ul class="kg-dash-feats">
							<?php foreach ( kg_list( $cfg['key'] . '.feats' ) as $i => $feat ) : ?>
								<li class="kg-dash-feat">
									<span class="kg-bubble <?php echo esc_attr( $dash_bubbles[ $i % 4 ] ); ?> kg-dash-feat__icon"><?php echo $cfg['icons'][ $i % 4 ]; // phpcs:ignore ?></span>
									<div>
										<h3 class="kg-dash-feat__title"><?php echo $feat['title']; // phpcs:ignore ?></h3>
										<p class="kg-dash-feat__text"><?php echo $feat['text']; // phpcs:ignore ?></p>
									</div>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<!-- Curriculum alignment -->
<section class="kg-section kg-section--white">
	<div class="kg-container">
		<?php kg_section_head( 'schools.align' ); ?>
		<div class="kg-problems">
			<?php
			$icons = array(
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M4 19.5A2.5 2.5 0 0 0 6.5 22H20V2H6.5A2.5 2.5 0 0 0 4 4.5v15z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M9 7h6m-6 4h6m-6 4h3M5 3h14a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M22 10 12 5 2 10l10 5 10-5zM6 12v5c0 1.7 2.7 3 6 3s6-1.3 6-3v-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
			);
			$book_tones = array( '', ' kg-book--amber', ' kg-book--red' );
			foreach ( kg_list( 'schools.align.items' ) as $i => $item ) :
				?>
				<div class="kg-card kg-card--hover kg-book<?php echo esc_attr( $book_tones[ $i % 3 ] ); ?>" data-kg-reveal style="--kg-delay:<?php echo (int) ( $i * 100 ); ?>ms">
					<span class="kg-book__ribbon" aria-hidden="true"></span>
					<span class="kg-bubble kg-bubble--navy"><?php echo $icons[ $i % 3 ]; // phpcs:ignore ?></span>
					<h3 class="kg-h3"><?php echo $item['title']; // phpcs:ignore ?></h3>
					<p style="margin:0;"><?php echo $item['text']; // phpcs:ignore ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Use cases: same platform, three places. Each card sits under a map-marker
     pin; a dashed route threads through all three, so it reads as one tool
     travelling with the learner from classroom to home to targeted help. -->
<section class="kg-section kg-section--cream-deep">
	<div class="kg-container">
		<?php kg_section_head( 'schools.value' ); ?>
		<div class="kg-places">
			<?php
			$value_icons = array(
				// Chalkboard / class — in class
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="4" width="18" height="13" rx="1.5" stroke="currentColor" stroke-width="2"/><path d="M8 21h8M12 17v4M7 9h7M7 12.5h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
				// House — at home
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 11.5 12 4l9 7.5M5 10v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.5 20v-5h5v5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
				// Target / lifebuoy — interventions
				'<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3.5" stroke="currentColor" stroke-width="2"/><path d="m4.6 4.6 4.3 4.3m6.2 6.2 4.3 4.3M19.4 4.6l-4.3 4.3M8.9 15.1l-4.3 4.3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
			);
			$value_tones = array( 'teal', 'amber', 'red' );
			foreach ( kg_list( 'schools.value.items' ) as $i => $item ) :
				$tone = $value_tones[ $i % 3 ];
				?>
				<div class="kg-place kg-place--<?php echo esc_attr( $tone ); ?>" data-kg-reveal="pop" style="--kg-delay:<?php echo (int) ( $i * 130 ); ?>ms">
					<span class="kg-place__pin" aria-hidden="true">
						<span class="kg-bubble kg-bubble--<?php echo esc_attr( $tone ); ?>"><?php echo $value_icons[ $i % 3 ]; // phpcs:ignore ?></span>
					</span>
					<div class="kg-card kg-card--hover kg-place__card">
						<?php if ( ! empty( $item['tag'] ) ) : ?>
							<span class="kg-place__label"><?php echo $item['tag']; // phpcs:ignore ?></span>
						<?php endif; ?>
						<h3 class="kg-h3"><?php echo $item['title']; // phpcs:ignore ?></h3>
						<p style="margin:0;"><?php echo $item['text']; // phpcs:ignore ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- Enquiry form -->
<section class="kg-section kg-section--white" id="kg-school-form">
	<div class="kg-container">
		<!-- Social proof leading into the enquiry form (placeholder until a
		     verified teacher/principal quote is supplied). -->
		<div class="kg-card kg-testimonial kg-testimonial--placeholder" style="max-width:640px; margin: 0 auto clamp(34px,5vw,52px);" data-kg-reveal>
			<span class="kg-testimonial__flag"><?php kg_e( 'schools.testimonial.flag' ); ?></span>
			<p class="kg-testimonial__quote">“<?php kg_e( 'schools.testimonial.quote' ); ?>”</p>
			<div class="kg-testimonial__who">
				<span class="kg-testimonial__avatar" style="background:var(--kg-teal);" aria-hidden="true">T</span>
				<span><strong><?php kg_e( 'schools.testimonial.name' ); ?></strong><small><?php kg_e( 'schools.testimonial.meta' ); ?></small></span>
			</div>
		</div>

		<?php kg_section_head( 'schools.form' ); ?>
		<p class="kg-schools-savings" data-kg-reveal><?php kg_e( 'schools.form.pricing_note' ); ?></p>
		<div class="kg-schools-form">
			<form data-kg-support-form data-kg-form-subject="The Kids Gate: School Enquiry" novalidate>
				<!-- Honeypot: hidden from humans, dropped server-side when filled. -->
				<input type="text" name="kg_website" tabindex="-1" autocomplete="off" aria-hidden="true" class="kg-visually-hidden">
				<div class="kg-form-grid">
					<div class="kg-field">
						<label for="kg-school-name"><?php kg_e( 'schools.form.name' ); ?></label>
						<input type="text" id="kg-school-name" name="kg_name" required autocomplete="name">
					</div>
					<div class="kg-field">
						<label for="kg-school-email"><?php kg_e( 'schools.form.email' ); ?></label>
						<input type="email" id="kg-school-email" name="kg_email" required autocomplete="email">
					</div>
					<div class="kg-field">
						<label for="kg-school-school"><?php kg_e( 'schools.form.school' ); ?></label>
						<input type="text" id="kg-school-school" name="kg_school" required autocomplete="organization">
					</div>
					<div class="kg-field">
						<label for="kg-school-role"><?php kg_e( 'schools.form.role' ); ?></label>
						<select id="kg-school-role" name="kg_topic" required>
							<?php foreach ( kg_list( 'schools.form.role_opts' ) as $opt ) : ?>
								<option><?php echo $opt; // phpcs:ignore ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="kg-field kg-field--full">
						<label for="kg-school-students"><?php kg_e( 'schools.form.students' ); ?></label>
						<input type="number" id="kg-school-students" name="kg_students" min="1" inputmode="numeric">
					</div>
					<div class="kg-field kg-field--full">
						<label for="kg-school-message"><?php kg_e( 'schools.form.message' ); ?></label>
						<textarea id="kg-school-message" name="kg_message"></textarea>
					</div>
				</div>
				<button class="kg-btn kg-btn--primary kg-btn--lg" type="submit" data-kg-event="school_enquiry_submit"><span><?php kg_e( 'schools.form.submit' ); ?></span></button>
			</form>
			<div class="kg-form-success" data-kg-support-form-success hidden tabindex="-1">
				<svg width="42" height="42" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m5 13 4 4L19 7" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
				<h3 class="kg-h3"><?php kg_e( 'schools.form.success_title' ); ?></h3>
				<p style="margin:0;"><?php kg_e( 'schools.form.success_text' ); ?></p>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
