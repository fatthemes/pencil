<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package pencil
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'col-md-12' ); ?>>
	<div class="row">
		<div class="entry-content col-md-10 col-md-push-2">
			<?php pencil_the_content(); ?>
		</div><!-- .entry-content -->
	</div><!-- .row -->
</article><!-- #post-## -->
