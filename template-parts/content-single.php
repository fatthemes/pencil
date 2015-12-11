<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package whatever
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'col-md-12' ); ?>>
    
        <header class="entry-header row">
                
                <div class="entry-meta col-md-2 col-sm-3 col-xs-4">
			<?php whatever_posted_on(); ?>
		</div><!-- .entry-meta -->
            
		<?php the_title( '<h1 class="entry-title col-md-10 col-sm-9 col-xs-8">', '</h1>' ); ?>

	</header><!-- .entry-header -->
        
        <?php // if ( has_post_thumbnail() ) : ?>
        <!--<div class="featured-media row">
                <figure class="featured-image wp-caption col-md-12">
                    <?php // the_post_thumbnail( 'full' ); ?>   
                    <figcaption class="wp-caption-text"><?php // echo get_post( get_post_thumbnail_id() )->post_excerpt; ?></figcaption>
                </figure>
        </div>-->
        <?php // endif; ?>

        <div class="row">
        
	<div class="entry-content col-md-10 col-md-push-2">
		<?php the_content(); ?>
		<?php /*
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'whatever' ),
				'after'  => '</div>',
			) );
		*/?>
	</div><!-- .entry-content -->

	<footer class="entry-footer col-md-12">
		<?php whatever_entry_footer(); ?>
	</footer><!-- .entry-footer -->
        
        </div><!-- .row -->
        
</article><!-- #post-## -->

