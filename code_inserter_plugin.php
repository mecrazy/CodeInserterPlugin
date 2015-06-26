<?php

/*
Plugin Name: Code Inserter Plugin
Plugin URI: https://mecrazy.net/
Description: This plugin can append any Tags, CSS and JavaScript to your blog.
Version: 1.0.0
Author: mecrazy
Author URI: https://mecrazy.net/
License: GPL2
*/

add_action( 'admin_menu','code_inserter_menu' );
add_action( 'admin_init','code_inserter_settings' );

function code_inserter_settings(){
	register_setting( 'codeinserter', 'codeinserter_any' );
	register_setting( 'codeinserter', 'codeinserter_css' );
	register_setting( 'codeinserter', 'codeinserter_js' );
	register_setting( 'codeinserter', 'codeinserter_jsjq' );
}

function code_inserter_menu(){  
	add_options_page(
		'code inserter plugin',
		'Code Inserter Plugin',
		'manage_options',
		'plugin_code_inserter',
		'code_inserter_menu_options' );  
}

function code_inserter_menu_options(){  
	if ( !current_user_can('manage_options') )  {  
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );  
	}  

?>
<div class="wrap">
<h2>Code Inserter Plugin Version 1.0.0</h2>
<p>( You can not use PHP code in these textarea. )</p>

<form method="post" action="options.php">
	<?php settings_fields( 'codeinserter' ); ?>
	<?php do_settings_sections( 'codeinserter' ); ?>
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row">Any tags</th>
				<td>
					<textarea name="codeinserter_any" cols="80" rows="12" style="overflow-y:scroll;"><?php echo esc_attr( get_option('codeinserter_any') ); ?></textarea>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">CSS</th>
				<td>
					&lt;style type="text/css"&gt;<br>
					<textarea name="codeinserter_css" cols="80" rows="12" style="overflow-y:scroll;"><?php echo esc_attr( get_option('codeinserter_css') ); ?></textarea><br>
					&lt;/style&gt;
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">JavaScript</th>
				<td>
					&lt;script type="text/javascript"&gt;<br>
					<textarea name="codeinserter_js" cols="80" rows="12" style="overflow-y:scroll;"><?php echo esc_attr( get_option('codeinserter_js') ); ?></textarea><br>
					&lt;/script&gt;
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">JavaScript (jQuery)</th>
				<td>
					&lt;script type="text/javascript"&gt;<br>
					if(typeof(jQuery)!='undefined'){ jQuery(function($){<br>
					<textarea name="codeinserter_jsjq" cols="80" rows="12" style="overflow-y:scroll;"><?php echo esc_attr( get_option('codeinserter_jsjq') ); ?></textarea><br>
					});	}<br>
					&lt;/script&gt;
				</td>
			</tr>
		</tbody>
	</table>
	<?php submit_button(); ?>
</form>

</div>

<?php

}

//When you remove this plugin
if ( function_exists('register_uninstall_hook') ) {
    register_uninstall_hook(__FILE__, 'uninstall_hook_example');
}
function uninstall_hook_example () {
	delete_option('codeinserter_any');
	delete_option('codeinserter_css');
	delete_option('codeinserter_js');
	delete_option('codeinserter_jsjq');
}

add_action('wp_footer','code_inserter_tag_any');
add_action('wp_footer','code_inserter_tag_css');
add_action('wp_footer','code_inserter_tag_js');

function code_inserter_tag_any(){
	echo get_option( "codeinserter_any", "" ) . "\r\n";
}

function code_inserter_tag_css(){
?>
<style type="text/css">
<?php echo get_option( "codeinserter_css", "" ); ?>
</style>
<?php
}

function code_inserter_tag_js(){

$codeinserter_js = get_option( "codeinserter_js", "" );
$codeinserter_jsjq = get_option( "codeinserter_jsjq", "" );

if( ( $codeinserter_js != "" ) || ( $codeinserter_jsjq != "" )){
?>
<script type="text/javascript">
<?php if( $codeinserter_js != "" ){ echo $codeinserter_js . "\r\n"; } ?>
<?php if( $codeinserter_jsjq != "" ){ ?>
if(typeof(jQuery)!='undefined'){jQuery(function($){
<?php echo $codeinserter_jsjq . "\r\n"; ?>
});
}
<?php } ?>
</script>
<?php
}

}
