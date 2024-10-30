<?php
/*
Plugin Name: Label Plugins
Plugin URI: http://wordpress.org/plugins/label-plugins
Description: Did you ever struggle with multiple plugins, now you have chance to label them.
Author: emojized
Version: 0.5
Author URI: https://emojized.com/
*/

//
include 'lp_admin_page.php';
register_activation_hook( __FILE__, "initial_plugin_labels" );

function label_plugins_add_ps($buffer)
{
	$labels=get_option("plugin_labels" );
	$label = 'All';
	if( $_GET['plugin_status'] == 'all' || 
		$_GET['plugin_status'] == 'active' ||
		$_GET['plugin_status'] == 'inactive' ||
		$_GET['plugin_status'] == '')
		{
		 	$category_li_string='<lI><a class="current" href="'.home_url().'/wp-admin/plugins.php?plugin_status=all">'.__($label).'</a></li> |'; 
		}
		else
		{
		 	$category_li_string='<lI><a href="'.home_url().'/wp-admin/plugins.php?plugin_status=all">'.__($label).'</a></li> |'; 
		}
	
	$num=1;
	$count=count($labels);
	foreach ($labels as $label)
	{
		if
		($num<$count)
		{
			if($_GET['plugin_status'] == $label)
			{
				$category_li_string=$category_li_string.'<lI><a class="current" href="?plugin_status='.$label.'">'.$label.'</a></li>';
			}
			else
			{
				$category_li_string=$category_li_string.'<lI><a href="?plugin_status='.$label.'">'.$label.'</a></li>';
			}
			if($num!=$count-1) $category_li_string=$category_li_string." |";
			
		}
		else
			{}
		$num++;
	}
	return str_replace('<form class="search-form search-plugins" method="get">', '<ul class="subsubsub">Plugin-'.__('Tags').': '.$category_li_string.'</ul><form class="search-form search-plugins" method="get">', $buffer);
}

function label_plugins_plugin_permissions_mp($plugins)
{
	ob_start("label_plugins_add_ps");
	//print_r($plugins);
	foreach ($plugins as $key => $value)
	{
		error_reporting(E_ALL ^ E_NOTICE);
		//$pluggy=Array('Name'=>$value['Name']);
		$pn=str_replace(" ", "_", $pluggy['Name']);
		// I know this line causes  unexpected errors but all of the magic does not work without that
		$kn=$key;
		$pn=$value['Name'];
		$pn=str_replace(" ", "_", $pn);
		$cat='plugin-category_'.$pn;
		$an=get_option($cat);

		if
		(isset($_GET['plugin_status']) && $an!=$_GET['plugin_status'] && $_GET['plugin_status']!="all" && $_GET['plugin_status']!="active" && $_GET['plugin_status']!="recently_activated" && $_GET['plugin_status']!="inactive" && $_GET['plugin_status']!="upgrade")
		{
			unset($plugins[$kn]);
		}else
		{
		}

	}
	return $plugins;
	ob_end_flush();
}

add_filter('all_plugins', 'label_plugins_plugin_permissions_mp');

function label_plugins_add_plugins_column( $columns )
{
	$label=array();
	$label[category]="Category";

	$columns = $label + $columns;
	return $columns;
}

add_filter( 'manage_plugins_columns', 'label_plugins_add_plugins_column' );
function label_plugins_render_plugins_column( $column, $plugin_file, $plugin_data )
{
	$url = plugins_url();
	switch ($column)
	{
	case "category":
		$pn=str_replace(" ", "_", $plugin_data['Name']);
		if
		(isset($_POST['plugin-category_'.$pn]))
		{
			update_option('plugin-category_'.$pn, $_POST['plugin-category_'.$pn]);
		}
		$cat=get_option('plugin-category_'.$pn);

		$labels=get_option("plugin_labels" );
		$num=1;
		$count=count($labels);

?>
<form action="" method="POST" name="plugin-category">
<select name="plugin-category <?php echo $plugin_data['Name'];
		?>" id="plugin-category" onchange="this.form.submit()">
        <option value="neutral" <?php if
		($cat=="neutral")
			{echo "selected=selected";
		}?> >neutral</option>
        <?php
		foreach ($labels as $label)
		{
			if
			($num<$count)
			{
?>
<option value="<?php echo $label;
				?>" <?php if
				($cat==$label)
					{echo "selected=selected";
				} ?> ><?php echo $label;
				?></option>
 <?php }else
				{} $num++;
		}
?>
    </select>
</form>

<?php
		break;
	}
}

add_action( 'manage_plugins_custom_column' , 'label_plugins_render_plugins_column', 10, 3 );

?>
