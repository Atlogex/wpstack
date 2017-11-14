<?php


function add_cf7()
{
	echo do_shortcode('[contact-form-7 id="24" title="Buy it now"]');
}

add_action('wp_footer', 'add_cf7');

function add_cf7_js()
{
	echo "
		<script>
		jQuery(document).on('click', '.cf-close', function () {
			jQuery('.cf-buyitnow').hide();
		});
		function cfbuyitnow(name, url) {
			jQuery('.cf-buyitnow').show();
			jQuery('.cf-product-name').text(name);
			jQuery('.cf-product-url').val(url);
		}
		</script>
	";
}
add_action('wp_footer', 'add_cf7_js');