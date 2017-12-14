<?php get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">PC

		<?php if (have_posts()) : ?>

			<?php if (is_home() && !is_front_page()) : ?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
			<?php endif; ?>

			<?php
			// Start the loop.
			while (have_posts()) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="post-thumbnail">
						<?php the_post_thumbnail(); ?>
					</div><!-- .post-thumbnail -->

					<header class="entry-header">
						<?php the_title('<h1 class="entry-title">', '</h1>'); ?>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<?php the_content(); ?>
						<?php
						wp_link_pages(array(
							'before' => '<div class="page-links"><span class="page-links-title">' . __('Pages:', 'twentyfifteen') . '</span>',
							'after' => '</div>',
							'link_before' => '<span>',
							'link_after' => '</span>',
							'pagelink' => '<span class="screen-reader-text">' . __('Page', 'twentyfifteen') . ' </span>%',
							'separator' => '<span class="screen-reader-text">, </span>',
						));
						?>
					</div><!-- .entry-content -->

					<?php edit_post_link(__('Edit', 'twentyfifteen'), '<footer class="entry-footer"><span class="edit-link">', '</span></footer><!-- .entry-footer -->'); ?>

				</article><!-- #post-## -->

				<?php

				// End the loop.
			endwhile;

			// Previous/next page navigation.
			the_posts_pagination(array(
				'prev_text' => __('Previous page', 'twentysixteen'),
				'next_text' => __('Next page', 'twentysixteen'),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __('Page', 'twentysixteen') . ' </span>',
			));

		// If no content, include the "No posts found" template.
		else :
			?>
			<div></div>
			<?php

		endif;
		?>

	</main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
