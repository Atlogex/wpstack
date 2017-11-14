<?php
/**
 * The template part for displaying content
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php if (is_sticky() && is_home() && !is_paged()) : ?>
			<span class="sticky-post"><?php _e('Featured', 'twentysixteen'); ?></span>
		<?php endif; ?>

		<?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>
	</header><!-- .entry-header -->

	<?php twentysixteen_post_thumbnail(); ?>

	<div class="entry-price">
		<?php if (get_field('tp_price_sale')) : ?>
			<span class="ep-price"><?php echo get_field('tp_price') ?></span>
			<span class="ep-pricesale"><?php echo get_field('tp_price_sale') ?></span>
		<?php else : ?>
			<span class="ep-standart"><?php echo get_field('tp_price') ?></span>
		<?php endif; ?>
	</div>

	<div class="entry-content">
		<?php twentysixteen_excerpt(''); ?>
	</div><!-- .entry-content -->

	<footer class="entry-footer" style="display: none;">
		<?php twentysixteen_entry_meta(); ?>
		<?php
		edit_post_link(
			sprintf(
			/* translators: %s: Name of current post */
				__('Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteen'),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
		?>
	</footer><!-- .entry-footer -->
	<div class="btn-order" onclick="cfbuyitnow('<?php echo get_the_title() ?>', '<?php echo esc_url(get_permalink()) ?>')">
		Buy it now
	</div>

</article><!-- #post-## -->
