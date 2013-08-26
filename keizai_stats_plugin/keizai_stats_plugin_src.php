<?php
/*
Plugin Name: keizaiStatsPlugin
Plugin URI: NA
Description: Retreives Economic Statics including currency
Author: Joshua Alday
Version: 1
Author URI: NA
*/
include 'ccodes.php';

class keizaiStatsPlugin extends WP_Widget
{
	function keizaiStatsPlugin()
	{
		$widget_ops = array('classname' => 'keizaiStatsPlugin', 'description' => "Retreives Economic Statics including currency" );
		$this->WP_Widget('keizaiStatsPlugin', 'Keizai Stats', $widget_ops);
	}

	function form($instance)
	{
		$instance = wp_parse_args( (array) $instance, array( 'cSel' => '' ) );
		$title = $instance['cSel'];
		?>
		<h2>This is only temporary for testing purposes. Will be able to change in widget details</h2>
  		<p><select id="<?php echo $this->get_field_id('cSel');?>" name="<?php echo $this->get_field_name('cSel'); ?>" >
  			<option><?php echo $instance['cSel'];?>
  			<option disabled>---</option>
  			<option value="USD">USD</option>
  			<option value="JPY">JPY</option>
  			<option value="ZWL">ZWL</option>
		</select></p>
		<?php
 	}
 
  	function update($new_instance, $old_instance)
  	{
    	$instance = $old_instance;
    	$instance['cSel'] = $new_instance['cSel'];
    	return $instance;
  	}
 
  	function widget($args, $instance)
  	{
  		/*
   		extract($args, EXTR_SKIP);
 
    	echo $before_widget;
    	$title = empty($instance['cSel']) ? ' ' : apply_filters('widget_text', $instance['cSel']);
 
    	if (!empty($title))
      	echo $before_title . $title . $after_title;;
 
    	echo $after_widget;
    	*/
  		
  		?>
  		<p><label>From:</label>
  		<select>
  		<?php
  		while($ccode=printCCodes() != "eof"){
  			echo '<option value="'.$ccode.'">'.$ccode.'</option>';
  		}
  		?>
  		</select>
  		<?php
  	}
 
}

add_action( 'widgets_init', create_function('', 'return register_widget("keizaiStatsPlugin");') );?>
