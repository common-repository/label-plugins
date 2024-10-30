<?php
/**
* Class and Function List:
* Function list:
* - label_plugin_menu()
* - initial_plugin_labels()
* - label_plugins_options()
* Classes list:
*/
add_action('admin_menu', 'label_plugin_menu');

function label_plugin_menu()
{
    add_options_page(__('Plugin') . '-' . __('Tags') , __('Plugin') . '-' . __('Tags') , "delete_users", "label-plugins", "label_plugins_options");
}

// dynamic field code by http://www.mustbebuilt.co.uk/2012/07/27/adding-form-fields-dynamically-with-jquery/
function initial_plugin_labels()
{
    $labels = get_option("plugin_labels");
    //if ($labels==""){
    $labels = array(
        label1 => __("good") ,
        label2 => ("average") ,
        label3 => ("bad") ,
        add_plugin_labels => ""
    );
    update_option("plugin_labels", $labels);
    //}
    
}

function label_plugins_options()
{
    if (isset($_POST['add_plugin_labels']))
    {
        update_option("plugin_labels", $_POST);
    }
    $labels = get_option("plugin_labels");

?>
<div class="wrap">
<?php screen_icon();
?>
<h2><?php _e('Plugin');
    echo '-';
    _e("Tags");
?></h2>
<div id="container">

		<section id="main">
<?php _e('Please provide a custom field name.'); ?><br />
<form method="POST" action="">
<?php
    $num = 1;
    
    if(empty($labels))
    {
        initial_plugin_labels();
    }
    $count = count($labels);
    foreach ($labels as $key => $value)
    {
        if ($num < $count)
        {
?>
<p><label for="var<?php echo $num;
?>"><?php _e('Label'); ?>: </label><input type="text" name="label<?php echo $num;
?>" id="var<?php echo $num;
?>" value="<?php echo $value;
?>" /><span class="removeVar button"><?php _e('Remove'); ?> <?php _e('Label'); ?></span></p>
<?php
        }
        
        $num++;
    } ?>
<p>
<span  class="button button-small" id="addVar"><?php _e('Add'); ?> <?php _e('Label'); ?></span>
</p>
<p>		<input type="hidden" name="add_plugin_labels">
<input type="submit"  class="button button-primary button-large" value="<?php _e("Save Changes"); ?>">
</p>
		</form>
	<?php //echo "num1 ".$num1;
    
?>
	</div><!--!/#container -->
<script>
	//create three initial fields
var startingNo = <?php echo $num;
?>;
var node = "";
for(varCount=0;varCount<=startingNo;varCount++){
    var displayCount = varCount+1;
    node += '<p><label for="var'+displayCount+'">Label: </label><input type="text" name="label'+displayCount+'" id="var'+displayCount+'"><span class="removeVar button"><?php _e('Remove'); ?> <?php _e('Label'); ?></span></p>';
}
//add them to the DOM
// $('form').prepend($node);
//remove a textfield
jQuery('form').on('click', '.removeVar', function(){
   jQuery(this).parent().remove();
});
//add a new node
jQuery('#addVar').on('click', function(){
varCount++;
node = '<p><label for="var'+varCount+'">Label: </label><input type="text" name="label'+varCount+'" id="var'+varCount+'"><span class="removeVar button"><?php _e('Remove'); ?>  <?php _e('Label'); ?></span></p>';
jQuery(this).parent().before(node);
});
	</script>
<?php
}

?>