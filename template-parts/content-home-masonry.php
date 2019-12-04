<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package pencil
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'col-md-6 masonry' ); ?>>
	<header class="entry-header">
		<?php if ( has_post_thumbnail() ) : ?>
		<div class="featured-image">
		<a href="<?php esc_url( the_permalink() ); ?>" rel="bookmark">
			<?php the_post_thumbnail( 'medium' ); ?>   
		</a>
		</div>
		<?php endif; ?>
		<?php echo wp_kses_post( pencil_post_format_icon( get_the_ID() ) ); ?>
		<div class="featured-image-cat">
		<?php the_category( __( ' &#x2f; ', 'pencil' ) ); ?>
		</div>
		<?php pencil_the_title(); ?>

		<?php if ( 'post' == get_post_type() ) : ?>
			<?php pencil_entry_meta(); ?>
		<?php endif; ?>
		<?php pencil_the_content(); ?>
	</header><!-- .entry-header -->
</article><!-- #post-## -->
