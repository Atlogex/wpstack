<?php
$args = array(
  'public'   => true,
  '_builtin' => false
  
);
$output = 'objects'; // or objects
$operator = 'and'; // 'and' or 'or'
	
	$taxonomies = get_taxonomies( $args, $output, $operator );	
	$taxonomy_posts = get_object_taxonomies( 'post', 'objects' );
	$taxonomies = array_merge($taxonomies, $taxonomy_posts);	  
	if($taxonomies['post_format'] || !empty($taxonomies['post_format'])){unset($taxonomies['post_format']);}
	if($taxonomies['post_tag'] || !empty($taxonomies['post_tag'])){unset($taxonomies['post_tag']);}
	$msg = '';	sort($taxonomies); 

if ( count($_POST) > 0 && isset($_POST['template_settings'])){
		
		if(!empty($_POST['post_category_name'])){
			$impVal=implode(',', $_POST['post_category_name']);
			delete_option ( 'advance_category_template');
			add_option ( 'advance_category_template', $impVal );
			$msg = '<div id="message" class="updated below-h2 tmpMsgText"><p>Setting Saved.</p></div>';
		}else{
			delete_option ( 'advance_category_template');
			add_option ( 'advance_category_template', 'category' );
			$msg = '<div id="message" class="error tmpMsgText"><p>Please select atleast one taxonomy.</p></div>';
		}
		
}
if(isset($_REQUEST['template_reset']) && $_REQUEST['template_reset']=='reset'){
		
		$impVal =$_POST['post_category_name']='';
		delete_option ( 'advance_category_template');
		add_option ( 'advance_category_template','category');

		delete_option ( 'category_template_status');
		add_option ( 'category_template_status','0');	
		
		$msg = '<div id="message" class="updated below-h2 tmpMsgText"><p>Default Setting Saved.</p></div>';
}		

if(isset($_REQUEST['plugin_disable']) && $_REQUEST['plugin_disable']=='disable'){
		
		echo $disable = (isset($_POST['disable']))?$_POST['disable']:'';
		if($disable == 1):
			delete_option ( 'category_template_status');
			add_option ( 'category_template_status','1');	
			$msg = '<div id="message" class="updated below-h2 tmpMsgText"><p>Plugin Disabled Successfully.</p></div>';
		else:
			delete_option ( 'category_template_status');
			add_option ( 'category_template_status','0');
			$msg = '<div id="message" class="updated below-h2 tmpMsgText"><p>Plugin Enabled Successfully.</p></div>';
		endif;
}	
?>
<!-- setting area -->
<div class="templateFormType" style="width:70%; float:left;">
<?php echo $msg; ?>
<fieldset class="temp-setting-fieldset"><legend class="temp-setting-legend"><strong >Settings</strong></legend>
	<?php $plugin_status = get_option('category_template_status');?>	
	<form action="" method="post" enctype="multipart/form-data">
		<input type="checkbox" name="disable" id="disable" value="1" <?php echo ($plugin_status == 1)?'checked="checked"':'';?>> <label for="disable">Disable Plugin</label>	
		<input type="submit" name="Submit" value="Disable" class="tmp-btn" />
		<input type="hidden" name="plugin_disable" value="disable" style="display:none;" />
	</form>	
	<p class="temp-summery"><?php _e('Note: If you wish to disable plugin then checked above checkbox.'); ?></p>
</fieldset>
<!-- advance setting area -->
<fieldset class="temp-setting-fieldset"><legend class="temp-setting-legend"><strong >General Settings</strong></legend>
	<form action="" method="post" enctype="multipart/form-data">
	<div class="type_chkbox_main">
	<?php foreach($taxonomies as $taxonomie){ 					 
			$taxonomies_name = $taxonomie->name; 		
			if(get_option('advance_category_template') != ''){
				$taxonomie_title = get_option('advance_category_template');
				$taxonomie_chkd = explode(',',$taxonomie_title);
				$chd = '';
				if(in_array($taxonomie->name, $taxonomie_chkd)){
					 $chd = 'checked="checked"';
				}
			}		
	?>
	<div class="temp-cat-chkbox"><input type="checkbox" name="post_category_name[]" value="<?php echo $taxonomie->name; ?>" id="<?php echo $taxonomie->name; ?>" <?php echo $chd; ?> class="chkBox" /><label for="<?php echo $taxonomie->name; ?>"><?php echo ucfirst($taxonomie->object_type[0]).'_'.$taxonomie->label; ?></label> </div>

	<?php } ?>
	</div>
	<div class="tmpType_submit">
		<input type="submit" name="submit" value="Save" class="tmp-btn" />
		<input type="hidden" name="template_settings" value="save" style="display:none;" />
	</div>
	</form>
	<p class="temp-summery"><?php _e('Note: Select one or more post type category where you need to enable post category template selection.');?></p>
</fieldset>	
</div>
<!-- default setting area -->
<div class="defaultFormType" style="width:70%; float:left;">
	<fieldset class="temp-setting-fieldset"><legend class="temp-setting-legend"><strong >Default Settings</strong></legend>
	<form action="" method="post" enctype="multipart/form-data">	
		<input type="submit" name="Submit" value="Default Setting" class="tmp-btn" />
		<input type="hidden" name="template_reset" value="reset" style="display:none;" />
	</form>	
	<p class="temp-summery"><?php _e('Note: If you are using default setting then category template will show only on default post category.'); ?></p>
	</fieldset>
</div>