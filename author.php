<?php
/**
 * The template for displaying all author posts.
 *
 * @package pencil
 */

get_header(); ?>

    <div class="row">
	<div id="primary" class="content-area<?php echo ( empty (get_theme_mod( 'home_page_layout', 'masonry' ) ) ) ? ' col-md-12' : ' col-md-8'; ?>">
            <div class="pencil-page-intro">
                        <?php echo esc_html__( 'Author posts', 'pencil' );?>
            </div>
            <div class="row about-author">
                <div class="col-md-4 author-avatar-wrapper">
                    <div class="author-avatar">
                        <?php echo get_avatar( get_the_author_meta( 'ID' ), 204 ) ?>
                    </div>
                    <?php if ( class_exists( 'UserSocialProfiles' ) ) : ?>

                    <div class="pencil-author-social-icons">
                        <?php if( !empty( get_the_author_meta('user_url'))) : ?><a href="<?php the_author_meta('user_url') ?>"><span class="fa fa-external-link"></span></a><?php endif; ?>
                        <?php if( !empty( get_the_author_meta('twitter'))) : ?><a href="<?php the_author_meta('twitter') ?>"><span class="fa fa-twitter"></span></a><?php endif; ?>
                        <?php if( !empty( get_the_author_meta('facebook'))) : ?><a href="<?php the_author_meta('facebook') ?>"><span class="fa fa-facebook"></span></a><?php endif; ?>
                        <?php if( !empty( get_the_author_meta('googleplus'))) : ?><a href="<?php the_author_meta('googleplus') ?>"><span class="fa fa-google-plus"></span></a><?php endif; ?>
                        <?php if( !empty( get_the_author_meta('instagram'))) : ?><a href="<?php the_author_meta('instagram') ?>"><span class="fa fa-instagram"></span></a><?php endif; ?>
                        <?php if( !empty( get_the_author_meta('pinterest'))) : ?><a href="<?php the_author_meta('pinterest') ?>"><span class="fa fa-pinterest"></span></a><?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-8">
                    <h3><?php echo esc_html( get_the_author_meta( 'display_name' ) ) ?></h3>
                    <p><?php echo esc_html( get_the_author_meta( 'description' ) ) ?></p>
                </div>
            </div>
		<main id="main" class="site-main row masonry-container" role="main">

		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
                            <?php // if ( ! is_sticky() ) : ?>
				<?php
					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'template-parts/content-home', get_theme_mod( 'home_page_layout', 'masonry') );
				?>
                            <?php // endif; ?>
			<?php endwhile; ?>

			<?php the_posts_navigation(); ?>

		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php if ( ! empty (get_theme_mod( 'home_page_layout', 'masonry' ) ) ) { get_sidebar();} ?>
    </div><!-- .row -->
<?php get_footer(); ?>