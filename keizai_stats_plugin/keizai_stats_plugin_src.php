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
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = $instance['title'];
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo attribute_escape($title); ?>" ></label></p>
		<?php
 	}
 
  	function update($new_instance, $old_instance)
  	{
    	$instance = $old_instance;
    	$instance['title'] = $new_instance['title'];
    	return $instance;
  	}
 
  	function widget($args, $instance)
  	{
  		extract($args, EXTR_SKIP);
  		
  		echo $before_widget;
  		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    	if (!empty($title))
      	echo $before_title . $title . $after_title;;
  		
  		?>
  		
  		<Form action="/wp-content/plugins/keizai_stats_plugin/keizaireport.php" method="post">
  			From:
  			<select id="cstatsFrom" name="cstatsFrom">
  			<option value="" selected="selected"></option>
  			<?php
  			getCCodes("<option value=", "</option>");
  			?>
  			</select>
  			To:
  			<select id="cstatsTo" name="cstatsTo">
  			<option value="" selected="selected"></option>
  			<?php
  			getCCodes("<option value=", "</option>");
  			?>
  			</select>
  			<button type="submit">Fetch!</button>
  		</Form>
  		<?php
  		echo $after_widget;
  	}
 
}

add_action( 'widgets_init', create_function('', 'return register_widget("keizaiStatsPlugin");') );?>
