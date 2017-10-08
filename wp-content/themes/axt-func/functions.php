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
 * Add
 */

/* ----------- /Add ----------- */