<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package pencil
 */

if ( ! function_exists( 'pencil_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function pencil_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( 'c' ) ),
			human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) )
		);

			$posted_on = sprintf(
				// Translators: months/hours/days.
				esc_html_x( '%s ago', 'post date', 'pencil' ),
				'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
			);

			$byline = sprintf(
				// Translators: Author.
				esc_html_x( ' by %s', 'post author', 'pencil' ),
				'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
			);

		if ( is_singular() ) {

			echo '<div class="author-avatar">' . get_avatar( get_the_author_meta( 'ID' ) ) . '</div><span class="byline"> ' . $byline . '</span><span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

		} else {

			echo '<span class="byline"> ' . $byline . '</span><span class="posted-on"> / ' . $posted_on . '</span>'; // WPCS: XSS OK.

		}
	}
endif;

if ( ! function_exists( 'pencil_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function pencil_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' == get_post_type() ) {

			// Translators: used between list items, there is a space after the comma.
			$tags_list = get_the_tag_list( '', esc_html__( ', ', 'pencil' ) );
			if ( $tags_list ) {
				// Translators: Tags.
				printf( '<span class="tags-links">' . esc_html__( 'Tagged: %1$s', 'pencil' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link( esc_html__( 'Leave a comment', 'pencil' ), esc_html__( '1 Comment', 'pencil' ), esc_html__( '% Comments', 'pencil' ) );
			echo '</span>';
		}

		edit_post_link( esc_html__( 'Edit', 'pencil' ), '<span class="edit-link">', '</span>' );
	}
endif;

if ( ! function_exists( 'pencil_categorized_blog' ) ) :
	/**
	 * Returns true if a blog has more than 1 category.
	 *
	 * @return bool
	 */
	function pencil_categorized_blog() {
		$all_the_cool_cats = get_transient( 'pencil_categories' );
		if ( false === $all_the_cool_cats ) {
			// Create an array of all the categories that are attached to posts.
			$all_the_cool_cats = get_categories(
				array(
					'fields'     => 'ids',
					'hide_empty' => 1,
					// We only need to know if there is more than one category.
					'number'     => 2,
				)
			);

			// Count the number of categories that are attached to the posts.
			$all_the_cool_cats = count( $all_the_cool_cats );

			set_transient( 'pencil_categories', $all_the_cool_cats );
		}

		if ( $all_the_cool_cats > 1 ) {
			// This blog has more than 1 category so pencil_categorized_blog should return true.
			return true;
		} else {
			// This blog has only 1 category so pencil_categorized_blog should return false.
			return false;
		}
	}
endif;

/**
 * Flush out the transients used in pencil_categorized_blog.
 */
function pencil_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'pencil_categories' );
}
add_action( 'edit_category', 'pencil_category_transient_flusher' );
add_action( 'save_post', 'pencil_category_transient_flusher' );

if ( ! function_exists( 'pencil_comment' ) ) :

	/**
	 *
	 * Template for comments and pingbacks.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 *
	 * @since pencil 1.0
	 * @param type $comment comment.
	 * @param type $args comment args.
	 * @param type $depth comments depth.
	 */
	function pencil_comment( $comment, $args, $depth ) {
		// $GLOBALS['comment'] = $comment;
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<article id="comment-<?php comment_ID(); ?>" class="comment">
				<footer>
					<div class="comment-author vcard">
						<?php $avatar = get_avatar( $comment, $args['avatar_size'] ); ?>
						<?php if ( ! empty( $avatar ) ) : ?>
						<div class="comments-avatar">
							<?php echo wp_kses_post( $avatar ); ?>
						</div>    
						<?php endif; ?>
						<div class="comment-meta commentmetadata">
							<?php printf( sprintf( '<cite class="fn"><b>%s</b></cite>', get_comment_author_link() ) ); ?>
							<br />
							<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time pubdate datetime="<?php comment_time( 'c' ); ?>">
							<?php
							/* translators: 1: date, 2: time */
							printf( esc_html__( '%s ago', 'pencil' ), esc_html( human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ) ) );
							?>
							</time></a>
							<span class="reply">
								<?php
								comment_reply_link(
									array_merge(
										$args,
										array(
											'depth' => $depth,
											'max_depth' => $args['max_depth'],
											'reply_text' => 'REPLY',
											'before' => ' &#8901; ',
										)
									)
								);
								?>
									</span><!-- .reply -->
									<?php
									edit_comment_link( __( 'Edit', 'pencil' ), ' &#8901; ' );
									?>
									</div><!-- .comment-meta .commentmetadata -->
									</div><!-- .comment-author .vcard -->
									<?php if ( '0' == $comment->comment_approved ) : ?>
						<em><?php esc_html_e( 'Your comment is awaiting moderation.', 'pencil' ); ?></em>
						<br />
					<?php endif; ?>
									</footer>
									<div class="comment-content"><?php comment_text(); ?></div>
									</article><!-- #comment-## -->
									<?php
	}
endif; // Ends check for pencil_comment().

if ( ! function_exists( 'pencil_comments_fields' ) ) :

	/**
	 * Comments function.
	 *
	 * @param array $fields comment form fields.
	 * @return string
	 */
	function pencil_comments_fields( $fields ) {

		$commenter = wp_get_current_commenter();
		// $user = wp_get_current_user();
		// $user_identity = $user->exists() ? $user->display_name : '';
		if ( ! isset( $args['format'] ) ) {
			$args['format'] = current_theme_supports( 'html5', 'comment-form' ) ? 'html5' : 'xhtml'; }

		$req      = get_option( 'require_name_email' );
		$aria_req = ( $req ? ' aria-required="true"' : '' );
		$html_req = ( $req ? ' required="required"' : '' );
		$html5    = 'html5' === $args['format'];

		$fields   = array(
			'author' => '<div class="comment-fields"><p class="comment-form-author"><label for="author">' . esc_html__( 'Name', 'pencil' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
					'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '"' . $aria_req . $html_req . ' placeholder="' . esc_html__( 'Name', 'pencil' ) . '" /></p>',
			'email'  => '<p class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'pencil' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
					'<input id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_email'] ) . '"' . $aria_req . $html_req . ' placeholder="' . esc_html__( 'Email', 'pencil' ) . '" /></p>',
			'url'    => '<p class="comment-form-ur"><label for="url">' . esc_html__( 'Website', 'pencil' ) . '</label> ' .
					'<input id="url" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_url'] ) . '" placeholder="' . esc_html__( 'Website', 'pencil' ) . '" /></p></div>',
		);

			return $fields;
	}
	add_filter( 'comment_form_default_fields', 'pencil_comments_fields' );
endif;

/**
 * Gets the excerpt using the post ID outside the loop.
 *
 * @author      Withers David
 * @link        http://uplifted.net/programming/wordpress-get-the-excerpt-automatically-using-the-post-id-outside-of-the-loop/
 * @param       int $post_id WordPres post ID.
 * @return      string
 */
function pencil_get_excerpt_by_id( $post_id ) {
	$the_post = get_post( $post_id ); // Gets post ID.
	$the_excerpt = $the_post->post_content; // Gets post_content to be used as a basis for the excerpt.
	$excerpt_length = 35; // Sets excerpt length by word count.
	$the_excerpt = strip_tags( strip_shortcodes( $the_excerpt ) ); // Strips tags and images.
	$words = explode( ' ', $the_excerpt, $excerpt_length + 1 );

	if ( count( $words ) > $excerpt_length ) :
		array_pop( $words );
		array_push( $words, '...' );
		$the_excerpt = implode( ' ', $words );
	endif;

	$the_excerpt = '<p>' . $the_excerpt . '</p>';
	return $the_excerpt;
}

if ( ! function_exists( 'pencil_custom_popular_posts_html_list' ) ) :
	/**
	 * Builds custom HTML
	 *
	 * With this function, I can alter WPP's HTML output from my theme's functions.php.
	 * This way, the modification is permanent even if the plugin gets updated.
	 *
	 * @param   array $mostpopular list of popular posts.
	 * @param   array $instance popular posts instance.
	 * @return  string
	 */
	function pencil_custom_popular_posts_html_list( $mostpopular, $instance ) {
		$output = '<ul class="fat-wpp-list">';

		// Loop the array of popular posts objects.
		foreach ( $mostpopular as $popular ) {

			$post_cat = get_the_category_list( __( ' &#x2f; ', 'pencil' ), '', $popular->id );

			$thumb = get_the_post_thumbnail( $popular->id, 'medium' );

			$output .= '<li>';
			$output .= ( ! empty( $thumb ) ) ? '<div class="fat-wpp-image"><a href="' . esc_url( get_the_permalink( $popular->id ) ) . '" title="' . esc_attr( $popular->title ) . '">' /* . pencil_post_format_icon( $popular->id ) */ . $thumb . '</a>' : '';
			$output .= pencil_post_format_icon( $popular->id ) . '<div class="fat-wpp-image-cat">' . $post_cat . '</div>';
			$output .= ( ! empty( $thumb ) ) ? '</div>' : '';
			$output .= '<h2 class="entry-title"><a href="' . esc_url( get_the_permalink( $popular->id ) ) . '" title="' . esc_attr( $popular->title ) . '">' . $popular->title . '</a></h2>';
			// $output .= ( ! empty ($stats)) ? $stats : "";
			// $output .= $excerpt;
			$output .= '</li>';

		}

		$output .= '</ul>';

		return $output;
	}
	if ( ! get_theme_mod( 'wpp_styling' ) ) {
		add_filter( 'wpp_custom_html', 'pencil_custom_popular_posts_html_list', 10, 2 );
	}
endif;

if ( ! function_exists( 'pencil_gallery_content' ) ) :
	/**
	 * Template for cutting images from gallery post format.
	 *
	 * @since pencil 1.0
	 */
	function pencil_gallery_content() {

		// Translators: Post title.
		$content = get_the_content( sprintf( __( 'Read more %s <span class="meta-nav">&rarr;</span>', 'pencil' ), the_title( '<span class="screen-reader-text">"', '"</span>', false ) ) );
		$pattern = '#\[gallery[^\]]*\]#';
		$replacement = '';

		$newcontent = preg_replace( $pattern, $replacement, $content, 1 );
		$newcontent = apply_filters( 'the_content', $newcontent ); // WPCS: prefix ok.
		$newcontent = str_replace( ']]>', ']]&gt;', $newcontent );
		echo wp_kses_post( $newcontent );
	}
endif;

if ( ! function_exists( 'pencil_media_content' ) ) :
	/**
	 * Template for cutting media from audio/video post formats.
	 *
	 * @since pencil 1.0
	 */
	function pencil_media_content() {
		// Translators: Post title.
		$content = get_the_content( sprintf( esc_html__( 'Read more %s <span class="meta-nav">&rarr;</span>', 'pencil' ), the_title( '<span class="screen-reader-text">"', '"</span>', false ) ) );
		$content = apply_filters( 'the_content', $content ); // WPCS: prefix ok.
		$content = str_replace( ']]>', ']]&gt;', $content );

		$tags = 'audio|video|object|embed|iframe|img';

		$replacement = '';

		$newcontent = preg_replace( '#<(?P<tag>' . $tags . ')[^<]*?(?:>[\s\S]*?<\/(?P=tag)>|\s*\/>)#', $replacement, $content, 1 );

		echo wp_kses_post( $newcontent );
	}
endif;

if ( ! function_exists( 'pencil_gallery_shortcode' ) ) :

	/**
	 * Function for modify gallery shortcode
	 *
	 * @param type  $output gallery shortcode output.
	 * @param array $atts gallery shortcode attributes.
	 * @return type
	 */
	function pencil_gallery_shortcode( $output = '', $atts ) {
		// $return = $output; // Fallback.
		$atts = array(
			'size' => 'medium',
		);

			return $output;
	}

	add_filter( 'post_gallery', 'pencil_gallery_shortcode', 10, 3 );
endif;

if ( ! function_exists( 'pencil_post_format_icon' ) ) :

	/**
	 * Function for getting post format icon
	 *
	 * @param type $post_id WordPress post ID.
	 * @return string
	 */
	function pencil_post_format_icon( $post_id ) {

		if ( empty( $post_id ) ) {
			return;
		}

		$format = get_post_format( $post_id );

		if ( ! $format ) {
			return;
		} else {

			if ( 'audio' === $format ) {
				return '<div class="pencil-post-format-icon"><span class="fa fa-music"></span></div>';
			} elseif ( 'video' === $format ) {
				return '<div class="pencil-post-format-icon"><span class="fa fa-video-camera"></span></div>';
			} elseif ( 'gallery' === $format ) {
				return '<div class="pencil-post-format-icon"><span class="fa fa-camera"></span></div>';
			} elseif ( 'image' === $format ) {
				return '<div class="pencil-post-format-icon"><span class="fa fa-image"></span></div>';
			} elseif ( 'quote' === $format ) {
				return '<div class="pencil-post-format-icon"><span class="fa fa-quote-right"></span></div>';
			} elseif ( 'link' === $format ) {
				return '<div class="pencil-post-format-icon"><span class="fa fa-link"></span></div>';
			}
		}
	}
endif;

if ( ! function_exists( 'pencil_show_sticky' ) ) :

	/**
	 * Show sticky posts below slider depends on option
	 *
	 * @return bool
	 */
	function pencil_show_sticky() {
		if ( is_sticky() && ! get_theme_mod( 'home_page_show_sticky', 0 ) ) {
			return false;
		}
		return true;
	}
endif;

if ( ! function_exists( 'pencil_excerpt_length' ) ) :

	add_filter( 'excerpt_length', 'pencil_excerpt_length', 100 );
	/**
	 * Custom excerpt length
	 *
	 * @return int
	 */
	function pencil_excerpt_length() {
		return get_theme_mod( 'excerpt_length', 55 );
	}
endif;

if ( ! function_exists( 'pencil_custom_logo' ) ) :
	/**
	 * Display custom logo. And migrate from "header_logo" if needed.
	 */
	function pencil_custom_logo() {
		if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) {
			the_custom_logo();
		} else {
			$header_logo = get_theme_mod( 'header_logo' );
			if ( ! empty( $header_logo ) ) {
				?>
				<img src="<?php echo esc_url( get_theme_mod( 'header_logo' ) ); ?>" alt="<?php bloginfo( 'name' ); ?>" >
				<?php
				$image_url = get_theme_mod( 'header_logo' );
				$postid = attachment_url_to_postid( $image_url );
				if ( ! $postid ) {
					$postid = pencil_get_image_id( $image_url );
				}
				if ( $postid ) {
					set_theme_mod( 'custom_logo', $postid );
				}
			}
		}
	}
endif;

if ( ! function_exists( 'pencil_has_custom_logo' ) ) :
	/**
	 * Checks if there is set up logo. Mostly for compatibility reason.
	 *
	 * @return boolean
	 */
	function pencil_has_custom_logo() {
		$header_logo = get_theme_mod( 'header_logo' );
		if ( ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) || ! empty( $header_logo ) ) {
			return true;
		}
	}
endif;

if ( ! function_exists( 'pencil_get_image_id' ) ) :
	/**
	 * Thanks to: https://pippinsplugins.com/retrieve-attachment-id-from-image-url/
	 * fallback to attachment_url_to_postid(), as it doesn't work properly on multisite installation.
	 *
	 * @global type $wpdb
	 * @param type $url URL of the image to check.
	 * @return int
	 */
	function pencil_get_image_id( $url ) {
		global $wpdb;
		$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid=%s;", $url ) );
		return $attachment[0];
	}
endif;

if ( ! function_exists( 'pencil_the_title' ) ) :
	/**
	 * Title wrapper function to handle multiple post formats.
	 *
	 * @return void
	 */
	function pencil_the_title() {
		if ( ! has_post_format( 'aside' ) && ! has_post_format( 'link' ) && ! has_post_format( 'quote' ) && ! has_post_format( 'image' ) ) {
			the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
		}
	}
	endif;

if ( ! function_exists( 'pencil_the_content' ) ) :
	/**
	 * Content wrapper function to handle multiple post formats.
	 *
	 * @return void
	 */
	function pencil_the_content() {
		if ( has_post_format( 'aside' ) || has_post_format( 'link' ) || has_post_format( 'quote' ) ) :
			?>
			<div class="pencil-post-format-wrapper">
				<?php the_content(); ?>
			</div>
			<?php
		endif;
	}
	endif;

if ( ! function_exists( 'pencil_entry_meta' ) ) :
	/**
	 * Function to handle displaying entry meta section for multiple post formats.
	 *
	 * @return void
	 */
	function pencil_entry_meta() {
		if ( 'post' == get_post_type() ) :
			?>
			<div class="entry-meta">
			<?php
			if ( ! is_single() && ( has_post_format( 'link' ) || has_post_format( 'quote' ) || has_post_format( 'image' ) || has_post_format( 'aside' ) ) ) {
				echo '';
			} else {
				pencil_posted_on();
			}
			?>
			</div><!-- .entry-meta -->
			<?php
		endif;
	}
endif;

	/*
	 * CSS output from customizer settings
	 */
if ( ! function_exists( 'pencil_customize_css' ) ) :

	/**
	 * Custom css header output
	 */
	function pencil_customize_css() {

		$custom_css = '.home .post_format-post-format-quote .pencil-post-format-wrapper, .archive .post_format-post-format-quote .pencil-post-format-wrapper, .search .post_format-post-format-quote .pencil-post-format-wrapper, .single .post_format-post-format-quote blockquote,  .single .post_format-post-format-quote cite {background-color:' . esc_attr( get_theme_mod( 'quote_post_format_bg', '#ea4848' ) ) . ';}';
		$custom_css .= '.home .post_format-post-format-link .pencil-post-format-wrapper, .archive .post_format-post-format-link .pencil-post-format-wrapper, .search .post_format-post-format-link .pencil-post-format-wrapper {background-color:' . esc_attr( get_theme_mod( 'link_post_format_bg', '#414244' ) ) . ';}';
		$custom_css .= '.home .post_format-post-format-aside .pencil-post-format-wrapper, .archive .post_format-post-format-aside .pencil-post-format-wrapper, .search .post_format-post-format-aside .pencil-post-format-wrapper {background-color:' . esc_attr( get_theme_mod( 'aside_post_format_bg', '#f0efef' ) ) . ';}';
		wp_add_inline_style( 'pencil-style', $custom_css );
	}
endif;

add_action( 'wp_enqueue_scripts', 'pencil_customize_css' );
