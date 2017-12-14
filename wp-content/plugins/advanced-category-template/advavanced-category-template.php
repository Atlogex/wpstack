<?php
/*
Plugin Name: Advanced Category Template
Plugin URI:  http://saurabhspeaks.com
Description: A custom built plug-in, which helps you to assign the custom template to the default as well as registered custom post_type category.
Author: Praveen Goswami
Author URI: http://saurabhspeaks.com
Version: 0.1
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,this
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/* Disallow users to direct access to the plugin file */

if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
	die('Sorry, but you cannot access this page directly.');
}

if (!class_exists('Advanced_Categories_Template')) {
	/**
	 *  @author Praveen Goswami <praveenpgoswami@gmail.com>
	 *  @access public
	 *  @version 0.1	 
	 */
	class Advanced_Categories_Template {
		/**
		 *	
		 *  class constructor
		 *	@author Praveen Goswami
		 *
		 */
		public function __construct() {

			//activation hook
			register_activation_hook(__FILE__,array($this,'advance_category_template_activate'));

			//deactivation hook
			register_deactivation_hook( __FILE__, array($this,'advance_category_template_deactivate' ));

			if ( is_admin() ){
				// add admin menu
				add_action('admin_menu', array($this,'advance_category_template_admin_menu'));
				}	

			add_action( 'admin_init',array($this, 'advance_category_template_css'));
			

			//get custom taxonomy value
			if(get_option('advance_category_template') != ''){		
				$category_title = get_option('advance_category_template');
				$cat_arr = explode(',',$category_title);
			}			
			
			$plugin_status = get_option('category_template_status');

			if(!empty($cat_arr) && $plugin_status != 1 ){ //check empty taxonomy array
				
				add_filter('taxonomy_template', array($this, 'advance_taxonomy_template'));
				
				add_filter('category_template', array($this, 'advance_category_template'));			
			
			foreach ($cat_arr as $cat => $name) {				
				//add extra fields to category NEW/EDIT form hook
				add_action($name.'_edit_form_fields', array($this, 'advance_cat_template_meta_box'));
				add_action($name.'_add_form_fields', array($this, 'advance_cat_template_meta_box'));			

				// save extra category extra fields hook
				add_action('created_'.$name, array($this, 'save_advance_category_template'));
				add_action('edited_'.$name, array($this, 'save_advance_category_template'));	
				}
			}		
		}	

		/**
		 *
		 * advance_category_template_activate add plugin options while activating plugin
		 * @author Praveen Goswami
		 *
		 */
		public function advance_category_template_activate() {			
			add_option ( 'advance_category_template','category');
			add_option ( 'category_template_status','0');
		}

		/**
		 *
		 * advance_category_template_deactivate remove plugin options while deactivating plugin
		 * @author Praveen Goswami
		 *
		 */
		public function advance_category_template_deactivate() {			
			delete_option ( 'advance_category_template');
			delete_option ( 'category_template_status');
		}

		/**
		 *
		 * advance_category_template_admin_menu add menu to dashboard
		 * @Praveen Goswami
		 *
		 */
		public function advance_category_template_admin_menu(){			
			add_menu_page( 'Advanced Category Template', 'Advance Category Template', 'manage_options', 'category_template_setting', array($this,'advance_category_template_setting'),plugins_url('ico.png',__FILE__ )); 
		}

		/**
		 *
		 * advance_category_template_setting plugin setting area on dashboard 
		 * @author Praveen Goswami
		 *
		 */
		public function advance_category_template_setting(){			
			_e('<h2>Advance Category Template</h2>');				
			include( plugin_dir_path( __FILE__ ) . '/_form.php');
		}

		/**
		 *
		 * advance_category_template_css add css to plugin
		 * @author Praveen Goswami
		 *
		 */
		public function advance_category_template_css() {			
			wp_register_style('category-style', plugins_url('css/template-style.css',__FILE__ ));
			wp_enqueue_style('category-style');			
		}

		/**
		 *
		 * get_advance_cat_templates get category template from theme
		 * @author Praveen Goswami
		 *
		 */
		public function get_advance_cat_templates() {
			
			if(function_exists('wp_get_themes')){
				$themes = wp_get_themes();
			}else{
				$themes = get_themes();
			}			
			
			$theme = get_option( 'template' );

			$templates = $themes[$theme]['Template Files'];
			$post_templates = array();

			if (is_array($templates)) {
				$base = array(trailingslashit(get_template_directory()), trailingslashit(get_stylesheet_directory()));

				foreach ($templates as $template) {
					$basename = str_replace($base, '', $template);
					if ($basename != 'functions.php') {
						// don't allow template files in subdirectories
						if (false !== strpos($basename, '/')) {
							continue;
						}

						$template_data = implode('', file($template));

						$name = '';
						if (preg_match('|Category Template:(.*)$|mi', $template_data, $name)) {
							$name = _cleanup_header_comment($name[1]);
						}

						if (!empty($name)) {
							$post_templates[trim($name)] = $basename;
						}
					}
				}
			}
			return $post_templates;
		}

		/**
		 *
		 * advance_cat_template_meta_box add meta template box to category callback function
		 * @author Praveen Goswami
		 *
		 */
		public function advance_cat_template_meta_box($tag) {
			$t_id = '';
			if(!empty($tag) && is_object($tag)): 
				$t_id = $tag->term_id;
			endif;
			$cat_meta = get_option("category_templates");

			$template = isset($cat_meta[$t_id]) ? $cat_meta[$t_id] : false;

			?>
			<tr class="form-field">
				<th scope="row" valign="top"><label for="cat_Image_url"><?php _e('Category Template');?></label></th>
				<td>
					<select name="cat_template" id="cat_template">
						<option value='default'><?php _e('Default Template');?></option>
						<?php $this->advance_cat_template_dropdown($template);?>
					</select>
					<br />
			            <!-- <span class="description"><?php _e('Select a specific template for this category');?></span> -->
			    </td>
			</tr>
			<?php			
		}

		/**
		 *
		 * advance_cat_template_dropdown to add category dropdown
		 * @author Praveen Goswami
		 *
		 */
		public function advance_cat_template_dropdown($default = '') {
			$templates = $this->get_advance_cat_templates();
			ksort($templates);
			foreach (array_keys($templates) as $template)
			:if ($default == $templates[$template]) {
					$selected = " selected='selected'";
				} else {
					$selected = '';
				}

				echo "\n\t<option value='" . $templates[$template] . "' $selected>$template</option>";
			endforeach;
		}

		/**
		 *
		 * save_advance_category_template save extra category extra fields callback function
		 * @author Praveen Goswami
		 *
		 */
		public function save_advance_category_template($term_id) {
			if (isset($_POST['cat_template'])) {
				$cat_meta = get_option("category_templates");
				$cat_meta[$term_id] = $_POST['cat_template'];
				update_option("category_templates", $cat_meta);				
			}
		}

		/**
		 *
		 * advance_category_template handle category template picking
		 * @author Praveen Goswami
		 *
		 */
		public function advance_category_template($category_template) {			
			
			$cat_ID = absint(get_query_var('cat'));
			$cat_meta = get_option('category_templates');
			if (isset($cat_meta[$cat_ID]) && $cat_meta[$cat_ID] != 'default') {
				$temp = locate_template($cat_meta[$cat_ID]);				
				if (!empty($temp)) {
					return apply_filters("Advance_Cat_Template_found", $temp);
				}
			}
			return $category_template;
		}

		/**
		 *
		 * advance_taxonomy_template add category template for register post type texonomy
		 * @author Praveen Goswami
		 *
		 */
		public function advance_taxonomy_template($taxonomy_template) {					
			
			$this_category = get_queried_object();			
			$cat_ID = $this_category->term_id;
			$cat_meta = get_option('category_templates');
			if (isset($cat_meta[$cat_ID]) && $cat_meta[$cat_ID] != 'default') {
				$temp = locate_template($cat_meta[$cat_ID]);				
				if (!empty($temp)) {
					return apply_filters("Advance_Cat_Template_found", $temp);
				}
			}
			return $taxonomy_template;
		}

	}//end class
}//end if
$advanced_category_template = new Advanced_Categories_Template();