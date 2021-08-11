<?php
/**
 * File for the event page (single).
 *
 * @package event-plugin.
 */

$attributes_manager = new Attributes_Manager();

get_header();
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<?php
		// Start the loop.
		while ( have_posts() ) :
			the_post();

			?>
				<h1> <?php the_title(); ?> </h1>
			<?php

			if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'medium' );
			}

			foreach ( $attributes_manager->attributes_array as $attribute ) {
				$attribute->render_single_field( $post->ID );
			}

		endwhile;
		?>
	</main>
</div>
<?php
get_footer();
