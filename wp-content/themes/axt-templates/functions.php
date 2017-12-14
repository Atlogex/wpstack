<?php
/**
 * Atlogex-Templates functions and definitions
 *
 * @link https://atlogex.com/
 * @link https://atlogex.com/linkforarticle
 *
 * @package WordPress
 * @subpackage Atlogex-Templates
 * @since Atlogex-Templates 1.0
 *
 * YOUR_THEME_NAME - Name (alias/name dir theme) Your theme
 */

$tpl = get_category_template();

$cat_meta = get_option( "category_templates");

//$cat_template = new Custom_Category_Template();
//var_dump($tpl);
//var_dump($cat_meta);
//var_dump($cat_template);


// * Определим константу, которая будет хранить путь к папке single
define('SINGLE_PATH', TEMPLATEPATH . '/single');
// * Добавим фильтр, который будет запускать функцию подбора шаблонов
add_filter('single_template', 'my_single_template');

/**
 * Функция для подбора шаблона todo переработать под выбор по иерархии родительской категории
 */
function my_single_template($single)
{
	global $wp_query, $post;

	/**
	 * Проверяем наличие шаблонов по ID поста.
	 * Формат имени файла: single-ID.php
	 */
	if (file_exists(SINGLE_PATH . '/single-' . $post->ID . '.php')) {
		return SINGLE_PATH . '/single-' . $post->ID . '.php';
	}

	/**
	 * Проверяем наличие шаблонов для категорий, ищем по ID категории или слагу
	 * Формат имени файла: single-cat-SLUG.php или single-cat-ID.php
	 */
	foreach ((array)get_the_category() as $cat) :
		if (file_exists(SINGLE_PATH . '/single-cat-' . $cat->slug . '.php')) {
			return SINGLE_PATH . '/single-cat-' . $cat->slug . '.php';
		} elseif (file_exists(SINGLE_PATH . '/single-cat-' . $cat->term_id . '.php')) {
			return SINGLE_PATH . '/single-cat-' . $cat->term_id . '.php';
		}

	endforeach;
	if (isset($cat->term_id)) {
		foreach ((array)get_ancestors($cat->term_id, 'category') as $cat) :
			$pc = get_category($cat);
//			var_dump($pc);
			if (file_exists(SINGLE_PATH . '/single-cat-' . $pc->slug . '.php')) {
				return SINGLE_PATH . '/single-cat-' . $pc->slug . '.php';
			} elseif (file_exists(SINGLE_PATH . '/single-cat-' . $cat->term_id . '.php')) {
				return SINGLE_PATH . '/single-cat-' . $pc->term_id . '.php';
			}
		endforeach;
	}


	/**
	 * Проверяем наличие шаблонов для тэгов, ищем по ID тэга или слагу
	 * Формат имени файла: single-tag-SLUG.php или single-tag-ID.php
	 */
	$wp_query->in_the_loop = true;
	foreach ((array)get_the_tags() as $tag) :

		if (file_exists(SINGLE_PATH . '/single-tag-' . $tag->slug . '.php')) {
			return SINGLE_PATH . '/single-tag-' . $tag->slug . '.php';
		} elseif (file_exists(SINGLE_PATH . '/single-tag-' . $tag->term_id . '.php')) {
			return SINGLE_PATH . '/single-tag-' . $tag->term_id . '.php';
		}

	endforeach;
	$wp_query->in_the_loop = false;

	/**
	 * Если ничего не найдено открываем стандартный single.php
	 */
	if (file_exists(SINGLE_PATH . '/single.php')) {
		return SINGLE_PATH . '/single.php';
	}

	return $single;
}
