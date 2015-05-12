<?php
/*
Plugin Name: Saint du jour Widget
Plugin URI: http://www.arthos.fr
Description: Displays the Saint of the day in a widget
Version: 1.0
Author: Luc Delaborde
Author URI: http://www.arthos.fr
License: GPL2
*/

class WP_Widget_Saint_du_Jour extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	function __construct() {
		parent::__construct(
			'saint_du_jour_widget', // Base ID
			__( 'Saint du Jour Reloaded', 'saint-du-jour-reloaded' ), // Widget title
			array( 'description' => __( 'Displays the Saint of the day.', 'saint-du-jour-reloaded' ) ) // Args
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];

		if( ! empty( $instance['title'] ) )
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];

		$date 		= date_i18n( 'Ymd' ); 
		$type 		= ( $instance['type'] 		!= '' ) ? esc_attr( $instance['type'] ): '';
		$lang 		= ( $instance['lang'] 		!= '' ) ? esc_attr( $instance['lang'] ): '';
		$cont 		= ( $instance['content'] 	!= '' ) ? esc_attr( $instance['content'] ): '';
		$publicite	= $instance['publicite'];
		$url 	= "http://feed.evangelizo.org/v2/reader.php?date=" . $date . "&type=" . $type . "&lang=" . $lang . "&content=" . $cont;

		if (!$h = fopen( $url, 'r' )) {
			// Fichier non disponible
			echo "<span style='color: #FF0000; font-weight: bold;'>Fichier non disponible !</span><br />";
		} else {
			$str = '';
			while( !feof( $h )) {
				$str .= fgets( $h );
			}
		}

		// Debug
		// echo $url . "<br />";
		// echo '<p>Réponse : </p><p style="width: 800px; display: block;">' . htmlspecialchars( $str ) . '</p>';
		
		// Réponse
		echo '<p>' . $str . '</p>';

		echo '<p class="evangelizo-org">';
		echo ( $publicite == true ) ? __( 'Free service provided by <a href="http://www.evangelizo.org/" target="_blank">Evangelizo.org</a>.', 'saint-du-jour-reloaded' ): '';
		echo '</p>';

		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
			'title'		=> 'Title:',
			'type'		=> '',
			'lang'		=> '',
			'content'	=> '',
			'publicite'	=> ''
		) );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'saint-du-jour-reloaded' ); ?></label>
			<input 
				class="widefat" 
				id="<?php echo $this->get_field_id( 'title' ); ?>" 
				name="<?php echo $this->get_field_name( 'title' ); ?>" 
				type="text" 
				value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e( 'Type:', 'saint-du-jour-reloaded' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'type' ); ?>" id="<?php echo $this->get_field_id( 'type' ); ?>" class="widefat">
			<?php
			$types = array(
				'saint'			=> __( 'The Saint of the day, w/ link to life', 'saint-du-jour-reloaded' ),
				'feast'			=> __( 'The feast of the day, w/ link to explanation', 'saint-du-jour-reloaded' ),
				'liturgic_t'	=> __( 'Liturgic title', 'saint-du-jour-reloaded' ),
				'reading_lt'	=> __( 'Long title of the reading (content mandatory)', 'saint-du-jour-reloaded' ),
				'reading_st'	=> __( 'Short title of the reading (content mandatory)', 'saint-du-jour-reloaded' ),
				'reading'		=> __( 'Text of the reading (content mandatory)', 'saint-du-jour-reloaded' ),
				'all'			=> __( 'All the readings of the day (content mandatory)', 'saint-du-jour-reloaded' ),
				'comment_t'		=> __( 'Title of the commentary', 'saint-du-jour-reloaded' ),
				'comment_a'		=> __( 'Author of the commentary', 'saint-du-jour-reloaded' ),
				'comment_s'		=> __( 'Source of the commentary', 'saint-du-jour-reloaded' ),
				'comment'		=> __( 'Text of the commentary', 'saint-du-jour-reloaded' )
			);

			$options = '';

			foreach( $types as $value => $label ):
				$options .= '<option value="' . esc_attr( $value ) . '"';
				$options .= ( $instance['type'] == $value ) ? ' selected': '';
				$options .= '>' . esc_attr( $label ) . '</option>';
			endforeach;

			echo $options;
			?>				
			</select>
		</p>
		<p class="description">
			<?php _e( 'Choose what kind of information you want to display.', 'saint-du-jour-reloaded' ); ?>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'lang' ); ?>"><?php _e( 'Lang:', 'saint-du-jour-reloaded' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'lang' ); ?>" id="<?php echo $this->get_field_id( 'lang' ); ?>" class="widefat">
			<?php
			$langs = array(
				'Roman calendar Ordinary form' => array(
					'AM'	=> __( 'American-US language and Roman calendar Ordinary form', 'saint-du-jour-reloaded' ),
	                'AR'	=> __( 'Arabic language and Roman calendar Ordinary form', 'saint-du-jour-reloaded' ),
	                'DE'	=> __( 'German language and Roman calendar Ordinary form', 'saint-du-jour-reloaded' ),
	                'FR'	=> __( 'French language and Roman calendar Ordinary form', 'saint-du-jour-reloaded' ),
	                'GR'	=> __( 'Greek (Hellenic) language and Roman calendar Ordinary form', 'saint-du-jour-reloaded' ),
	                'IT'	=> __( 'Italian language and Roman calendar Ordinary form', 'saint-du-jour-reloaded' ),
	                'MG'	=> __( 'Malagasy language and Roman calendar Ordinary form', 'saint-du-jour-reloaded' ),
	                'NL'	=> __( 'Dutch language and Roman calendar Ordinary form', 'saint-du-jour-reloaded' ),
	                'PL'	=> __( 'Polish language and Roman calendar Ordinary form', 'saint-du-jour-reloaded' ),
	                'PT'	=> __( 'Portuguese language and Roman calendar Ordinary form', 'saint-du-jour-reloaded' ),
	                'SP'	=> __( 'Spanish language and Roman calendar Ordinary form', 'saint-du-jour-reloaded' )
				),
				'Other calendar forms' => array(
					'ARM'	=> __( 'Armenian language and Armenian calendar', 'saint-du-jour-reloaded' ),
	                'BYA'	=> __( 'Arabic language and Byzantine calendar', 'saint-du-jour-reloaded' ),
	                'MAA'	=> __( 'Arabic language and Maronite calendar', 'saint-du-jour-reloaded' ),
	                'TRF'	=> __( 'French language and Roman extraordinary calendar', 'saint-du-jour-reloaded' ),
	                'TRA'	=> __( 'American-US language and Roman extraordinary calendar', 'saint-du-jour-reloaded' )
				)
			);

			$options = '';
			$i = 0;

			foreach( $langs as $form ):
				$arrext = array_keys( $langs );
				$options .= '<optgroup label="' . $arrext[$i] . '">';
				$i++;

				foreach( $form as $value => $label ):
					$options .= '<option value="' . esc_attr( $value ) . '"';
					$options .= ( $instance['lang'] == $value ) ? ' selected': '';
					$options .= '>' . $label . '</option>';
				endforeach;

				$options .= '</optgroup>';
			endforeach;

			echo $options;
			?>
			</select>
		</p>
		<p class="description">
			<?php _e( 'Choose the language you want to use (and the form, if available).', 'saint-du-jour-reloaded' ); ?>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'content' ); ?>"><?php _e( 'Content:', 'saint-du-jour-reloaded' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'content' ); ?>" id="<?php echo $this->get_field_id( 'content' ); ?>" class="widefat">
			<?php
			$contents = array(
				'Roman Calendar Ordinary form'	=> array(
					'FR'	=> __( 'First lecture', 'saint-du-jour-reloaded' ),
					'PS'	=> __( 'Psalm', 'saint-du-jour-reloaded' ),
					'SR'	=> __( 'Second lecture', 'saint-du-jour-reloaded' ),
					'GSP'	=> __( 'Gospel', 'saint-du-jour-reloaded' )
				),
				'Other Catholic Calendar forms'	=> array(
					'EP'	=> __( 'Epistle', 'saint-du-jour-reloaded' ),
				)
			);

			$options = '';
			$i = 0;

			foreach( $contents as $content ):
				$arrext = array_keys( $contents );
				$options .= '<optgroup label="' . $arrext[$i] . '">';
				$i++;

				foreach( $content as $value => $label ):
					$options .= '<option value="' . esc_attr( $value ) . '"';
					$options .= ( $instance['content'] == $value ) ? ' selected': '';
					$options .= '>' . esc_attr( $label ) . '</option>';
				endforeach;

				$options .= '</optgroup>';
			endforeach;

			echo $options;
			?>
			</select>
		</p>
		<p class="description">
			<?php _e( 'Choose the content you want, in case you chose a "Reading" type.', 'saint-du-jour-reloaded' ); ?>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'publicite' ); ?>"><?php _e( 'Advertising:', 'saint-du-jour-reloaded' ); ?></label>
			<input type="checkbox" name="<?php echo $this->get_field_name( 'publicite' ); ?>" id="<?php echo $this->get_field_id( 'publicite' ); ?>"
			<?php echo ( $instance['publicite'] == true ) ? ' checked': ''; ?>
			/>
		</p>
		<p class="description">
			<?php _e( 'Wanna let your visitors know where you got that information ? Check this and there\'ll be a link to <a href="http://www.evangelizo.org/" target="_blank">Evangelizo.org</a>. :-)', 'saint-du-jour-reloaded' ); ?>
		</p>
		<?php

		// Debug
		// echo "<p>Debug :</p>";
		// var_dump( $instance );

		// echo "<p>_POST : </p>";
		// $post = print_r( $_POST, false );
		// echo "<pre>" . $post . "</pre>";

		// echo "<p>Widget options :</p>";
		// $widget_options_all = get_option($this->option_name);
  // 		$current_widget_options = print_r( $widget_options_all[ $this->number ], false );
		// echo "<pre>" . $current_widget_options . "</pre>";
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance 				= $old_instance;
		$instance['title']		= empty( $new_instance['title'] ) ? '': strip_tags( $new_instance['title'] );
		$instance['type']		= empty( $new_instance['type'] ) ? '': strip_tags( $new_instance['type'] );
		$instance['lang']		= empty( $new_instance['lang'] ) ? '': strip_tags( $new_instance['lang'] );
		$instance['content']	= empty( $new_instance['content'] ) ? '': strip_tags( $new_instance['content'] );
		$instance['publicite']	= $new_instance['publicite'];

		return $instance;
	}
} // Class Saint_du_jour_Widget

// Register widget
add_action( 'widgets_init', function(){
     register_widget( 'WP_Widget_Saint_du_Jour' );
});

$widget_dir = basename( dirname( __FILE__ ) );
load_plugin_textdomain( 'saint-du-jour-reloaded', null, $widget_dir . '/lang/' );