<?php

echo 'test';
return;
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the questions.
 */
if ( post_password_required() ) {
	return;
}

?>
<div class="wpc-questions-container">

	<?php

	// You can start editing here -- including this comment!
	if ( have_comments() ) :
		?>
		<h2 class="comments-title">
			<?php

			$comments_number = get_comments_number();

			if ( '1' === $comments_number ) {
				printf( _x( 'One Reply to &ldquo;%s&rdquo;', 'comments title', 'wpcampus-2019' ), get_the_title() );
			} else {
				printf(
					/* translators: 1: number of comments, 2: post title */
					_nx(
						'%1$s Reply to &ldquo;%2$s&rdquo;',
						'%1$s Replies to &ldquo;%2$s&rdquo;',
						$comments_number,
						'comments title',
						'wpcampus-2019'
					),
					number_format_i18n( $comments_number ),
					get_the_title()
				);
			}
			?>
		</h2>

		<ol class="comment-list">
			<?php
				wp_list_comments(
					array(
						'avatar_size' => 100,
						'style'       => 'ol',
						'short_ping'  => true,
						'reply_text'  => __( 'Reply', 'wpcampus-2019' ), //twentyseventeen_get_svg( array( 'icon' => 'mail-reply' ) ) . __( 'Reply', 'wpcampus-2019' ),
					)
				);
			?>
		</ol>

		<?php
		the_comments_pagination(
			array(
				'prev_text' => '<span class="screen-reader-text">' . __( 'Previous', 'wpcampus-2019' ) . '</span>', //twentyseventeen_get_svg( array( 'icon' => 'arrow-left' ) ) . '<span class="screen-reader-text">' . __( 'Previous', 'wpcampus-2019' ) . '</span>',
				'next_text' => '<span class="screen-reader-text">' . __( 'Next', 'wpcampus-2019' ) . '</span>', // . twentyseventeen_get_svg( array( 'icon' => 'arrow-right' ) ),
			)
		);

	endif; // Check for have_comments().

	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>

		<p class="no-comments"><?php _e( 'Comments are closed.', 'wpcampus-2019' ); ?></p>
	<?php
	endif;

	question_form();

	?>
</div>
