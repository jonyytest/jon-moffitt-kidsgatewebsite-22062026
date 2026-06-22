<?php
/**
 * Default page template for pages without a dedicated template.
 */
get_header();
?>
<section class="kg-section">
	<div class="kg-container kg-prose">
		<?php
		while ( have_posts() ) :
			the_post();
			the_title( '<h1 class="kg-h1">', '</h1>' );
			the_content();
		endwhile;
		?>
	</div>
</section>
<?php
get_footer();
