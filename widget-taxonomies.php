<?php
/**
* Plugin Name: Taxonomies Widget Plugin
* Plugin URI: http://dswebsolutions.in/
* Description: Taxonomies Widget Plugin is an easy to use plugin that allows admin all Taxonomies display to the sidebar, as a widget.
* Version: 1.0 
* Author: Deepak Sharma
* Author URI: http://dswebsolutions.in
*/
class vh_taxonomy_cat extends WP_Widget {
	function vh_taxonomy_cat() {
		$widget_ops = array( 'classname' => 'widget_taxonomy_cat', 'description' => 'Taxonomies widget' );
		$this->WP_Widget( 'taxonomy-cat-widget', 'Taxonomy Widget', $widget_ops );
	}
	 
	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );
		echo $before_widget;
		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$taxonomyw = empty($instance['taxonomyw']) ? '' : apply_filters('widget_count', $instance['taxonomyw']);
		$count = empty($instance['count']) ? '' : apply_filters('widget_count', $instance['count']);
			if( $instance['title'] )
				echo $before_title . $title . $after_title;
					global $wp_query;
					$argsmovi = array(
									'taxonomy'     => $taxonomyw,
									'orderby'      => 'name',
									'show_count'   => 1,
									'pad_counts'   => 0,
									'hierarchical' => 1,
									'title_li'     => ''
								);
					echo '<div class="movie-categorylist"> <ul>';
							$categories = get_categories( $argsmovi );
								foreach($categories as $categoriesdat) {
									echo '<li><a href="'.get_category_link( $categoriesdat->term_id ).'">'.$categoriesdat->name;
									if($count=='on') { 
										echo ' <span>['.$categoriesdat->category_count.']</span>';
									}
									echo '</a> </li>';
								}
					echo '</ul> </div>';

	echo $after_widget;
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = esc_attr( $new_instance['title'] );
		$instance['taxonomyw'] = strip_tags($new_instance['taxonomyw']);
		$instance['count'] = strip_tags($new_instance['count']);
		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 'title' => '' );
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		echo '<p><label for="' . $this->get_field_id( 'title' ) . '">Title: <input class="widefat" id="' . $this->get_field_id( 'title' ) .'" name="' . $this->get_field_name( 'title' ) . '" value="' . esc_attr( $instance['title'] ) . '" /></label></p>';
		echo '<p><label for="' . $this->get_field_id( 'taxonomyw' ) . '"> Select Taxonomy: <select class="widefat" id="' . $this->get_field_id( 'taxonomyw' ) .'" name="' . $this->get_field_name( 'taxonomyw' ) . '">';
		$taxonomies = get_taxonomies('','object'); 
		foreach ( $taxonomies as $taxonomy ) {
			if($taxonomy->name != 'nav_menu' && $taxonomy->name != 'link_category' && $taxonomy->name != 'post_format'){
				echo '<option ';
					if($taxonomy->name == esc_attr( $instance['taxonomyw'] )) {
						echo 'selected="selected"';
					}
				echo  'value="' . $taxonomy->name . '">' . $taxonomy->label . '</option>';
			}
		}
	echo '</select> </label></p>';
	echo '<p><label for="' . $this->get_field_id( 'count' ) . '">Show Post Count: <input type="checkbox" class="widefat" id="' . $this->get_field_id( 'count' ) .'" name="' . $this->get_field_name( 'count' ) . '"';
		if(esc_attr( $instance['count'] )=="on") {
			echo 'checked';
		}
	echo ' /> </p>';
	}
}

add_action( 'widgets_init', 'vh_register_taxonomy_cat_widget' );
function vh_register_taxonomy_cat_widget() {
	register_widget('vh_taxonomy_cat');
}