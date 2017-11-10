<?php
/**
 * Atlogex-Minimal functions and definitions
 *
 * @link https://atlogex.com/
 * @link https://atlogex.com/linkforarticle
 *
 * @package WordPress
 * @subpackage Atlogex-Minimal
 * @since Atlogex-Minimal 1.0
 *
 * YOUR_THEME_NAME - Name (alias/name dir theme) Your theme
 */


/**
 * Add Support Menu
 */
if (function_exists('add_theme_support')) {
	add_theme_support('menus');
}

register_nav_menus(array(
	'top_menu' => 'Main menu in header',
));
/* ----------- /Add Menu ----------- */


/**
 * Add Support Widget
 */
function axtheme_widgets_init()
{
	register_sidebar(array(
		'name' => __('Sidebar', 'YOUR_THEME_NAME'),
		'id' => 'sidebar-1',
		'description' => __('Add widgets here to appear in your sidebar.', 'axtheme'),
		'before_widget' => '<section id="%1$s" class="b_aside widget %2$s">',
		'after_widget' => '</section>',
		'before_title' => '<div class="b_heading"><h3 class="b_heading__title">',
		'after_title' => '</div></h3>',
	));

	register_sidebar(array(
		'name' => __('Footer 1', 'YOUR_THEME_NAME'),
		'id' => 'sidebar-2',
		'description' => __('Add widgets here to appear in your footer.', 'axtheme'),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget' => '</section>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	));
}

add_action('widgets_init', 'axtheme_widgets_init');
/* ----------- /Add ----------- */


/**
 * Add Support Hierarchy Theme in Parent Categoty
 */
function theme_subcategory_hierarchy()
{
	$category = get_queried_object();

	$parent_id = $category->category_parent;

	$templates = array();

	if ($parent_id == 0) {
		$templates[] = "category-{$category->slug}.php";
		$templates[] = "category-{$category->term_id}.php";
		$templates[] = 'category.php';
	} else {
		$parent = get_category($parent_id);
		$templates[] = "category-sub-{$parent->slug}.php";

		$templates[] = "category-{$category->slug}.php";
		$templates[] = "category-{$category->term_id}.php";

		$templates[] = "category-{$parent->slug}.php";
		$templates[] = "category-{$parent->term_id}.php";
		$templates[] = 'category.php';
	}

	return locate_template($templates);
}

add_filter('category_template', 'theme_subcategory_hierarchy');
/* ----------- /Add ----------- */

/**
 * Add Simple Callback - Best use official wp plugin: Contact From 7
 */
function my_action_callback()
{
	if (isset($_POST["name"]) AND isset($_POST["phone"]) AND isset ($_POST["email"])) {

		$msg = '';

		if (isset($_POST['msg']) AND $_POST['msg'] != '') {
			$msg = trim(strip_tags(stripslashes($_POST["msg"])));
		}

		$name = trim(strip_tags(stripslashes($_POST["name"])));
		$phone = trim(strip_tags(stripslashes($_POST["phone"])));
		$mail = trim(strip_tags(stripslashes($_POST["email"])));

		$page_title = "New order from YOUR-SITE";
		$message = "Phone: $phone \n Name: $name  \n Mail: $mail \n Text: $msg";

		mail('atlogex@gmail.com', $page_title, $message, "Content-type: text/plain; charset=\"utf-8\"\n From: atlogex@gmail.com");

		$sm = 'OK';
	} else {
		$sm = 'DATA ERROR';
	}

	echo json_encode($sm);
	wp_die();
}

add_action('wp_ajax_sendmail', 'my_action_callback');
add_action('wp_ajax_nopriv_sendmail', 'my_action_callback');
/* ----------- /Add ----------- */


/**
 * Add new image size for crop
 */
function image_settings()
{
	// ru: Принудительно заставляем обрезать/сжимать изображения
	// en: Make your size for image
	if (function_exists('add_image_size')) {
		// use out get_the_post_thumbnail($post->ID, 'thumb110x73' );
		add_image_size('thumb110x73', 110, 73, true); //(cropped)
		add_image_size('thumb370x247', 370, 247, true); //(cropped)
		add_image_size('thumb385x257', 385, 257, true); //(cropped)
	}
}

add_action('after_setup_theme', 'image_settings');
/* ----------- /Add ----------- */


/**
 * Load Bootstrap
 */

function load_bootstrap()
{
	// Bootstrap stylesheet.
	wp_enqueue_style('bootstrap-style', get_template_directory_uri() . '/libs/bootstrap/css/bootstrap.min.css', array(), ' ');

	//Mytheme stylesheet.
	wp_enqueue_style('mytheme-style', get_template_directory_uri() . '/css/style.css', array(), ' ');

	//Bootstrap js
	wp_enqueue_script('bootstrap-js', get_template_directory_uri() . '/libs/bootstrap/js/bootstrap.min.js', array('jquery'), ' ');
}

add_action('wp_enqueue_scripts', 'load_bootstrap');
/* ----------- /Add ----------- */



/* --------------------------------------------------------------------------
 * Disable Emojii
 * -------------------------------------------------------------------------- */
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('comment_text_rss', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
add_filter('tiny_mce_plugins', 'disable_wp_emojis_in_tinymce');
function disable_wp_emojis_in_tinymce($plugins)
{
	if (is_array($plugins)) {
		return array_diff($plugins, array('wpemoji'));
	} else {
		return array();
	}
}
/* ----------- /Add ----------- */



/**
 * Pagination BAD PRACTICE
 * Template - <?php pagination($posts->max_num_pages); ?>
 */

function pagination($pages = '', $range = 4)
{
	$showitems = ($range * 2) + 1;
	$out = '';
	global $paged; // Номер текущей страницы
//	var_dump($paged);
	if (empty($paged)) $paged = 1;

	if ($pages == '') {
		global $wp_query;
		$pages = $wp_query->max_num_pages;
		if (!$pages) {
			$pages = 1;
		}
	}
	if (1 != $pages OR 1) {

		$out .= '<div class="b_pagination"> ';

		($paged > 1) ? $class = '' : $class = 'b_pgn__no-activ';

		$out .= '<a href="' . get_pagenum_link($paged - 1) . '" class="b_pagination_prev ' . $class . '">
					<span class="b_slide-pgn_arrow-prev">➜</span> <span 	class="b_pgn-text">Previous Page</span>
				</a> ';

		$out .= '<div class="b_pagination_pages">';
		for ($i = 1; $i <= $pages; $i++) {
			$class = '';
			if ($paged == $i) {
				$class = 'b_pgn__a-no-activ';
			}
			$out .= '<div class="b_pagination_pages-block ' . $class . '"><a href="' . get_pagenum_link($i) . ' ">' . $i . '</a></div>';

		}

		$out .= '</div> ';

		if ($paged < $pages) {
			$class = '';
		} else {
			$class = 'b_pgn__no-activ';
		}
		$out .= '	<a href="' . get_pagenum_link($paged + 1) . '" class="b_pagination_next text-right ' . $class . '">
						<span class="b_pgn-text">Next Page</span>➜
					</a>';
		$out .= '</div>';

		echo $out;
	}
}
/* ----------- /Add ----------- */


/**
 * Add
 */

/* ----------- /Add ----------- */