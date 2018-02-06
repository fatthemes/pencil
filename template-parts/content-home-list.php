<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package pencil
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'col-md-12 masonry' ); ?>>
	<header class="entry-header row">
				<?php if ( has_post_thumbnail() ) : ?>
					
					<div class="featured-image col-sm-6">
						<a href="<?php esc_url( the_permalink() ); ?>" rel="bookmark">
						<?php the_post_thumbnail( 'medium' ); ?>   
						</a>  
						<?php echo wp_kses_post( pencil_post_format_icon( get_the_ID() ) ); ?>
						<div class="featured-image-cat">
						<?php the_category( __( ' &#x2f; ', 'pencil' ) ); ?>
						</div>
					</div>
					
					<div class="title-meta-wrapper col-sm-6">
						<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

						<?php if ( 'post' == get_post_type() ) : ?>
							<div class="entry-meta">
									<?php pencil_posted_on(); ?>
							</div><!-- .entry-meta -->
						<?php endif; ?>
					</div><!-- .title-meta-wrapper -->
				
				<?php else : ?>
				
					<div class="title-meta-wrapper col-xs-12">
					<?php echo wp_kses_post( pencil_post_format_icon( get_the_ID() ) ); ?>
					<div class="featured-image-cat">
						<?php the_category( __( ' &#x2f; ', 'pencil' ) ); ?>
					</div>
					<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

					<?php if ( 'post' == get_post_type() ) : ?>
						<div class="entry-meta">
								<?php pencil_posted_on(); ?>
						</div><!-- .entry-meta -->
					<?php endif; ?>
					</div><!-- .title-meta-wrapper -->
				
				<?php endif; ?>
	</header><!-- .entry-header -->
</article><!-- #post-## -->
