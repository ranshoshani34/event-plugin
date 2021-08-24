<?php
/**
 * File for the event page (single).
 *
 * @package event-plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$attributes_manager = Attributes_Manager::instance();

get_header();
the_content();
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
				$attribute->render_single_field( get_the_ID() );
			}

		endwhile;
		?>
	</main>
</div>
<?php
get_footer();
