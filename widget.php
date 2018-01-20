<?php
// vim:set ft=php noexpandtab:

/**
 * Adds Foo_Widget widget.
 */
class Dolo_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'dolo_widget', // Base ID
			__( 'Dolos widget', 'dolomon' ), // Name
			[ 'description' => __( 'Show dolos', 'dolomon' ), ] // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		global $dolo_cache;
		if ( time() - $dolo_cache['last_fetch'] > $cache_expiration ) {
			dolomon_refresh_cache();
		}
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		$a = [
			'link' => true,
			'self' => true,
			'name' => '%name',
		];
		if ( ! empty( $instance['format'] ) ) {
			$a['name'] = $instance['format'];
		}
		switch ( $instance['dolo-widget-type'] ) {
			case 'dolo-choose-cat':
				foreach ( $instance['dolomon-cat'] as $cat_id ) {
					$cat = $dolo_cache['cats']["$cat_id"]; ?>
					<h6 style="margin: 5px 0"><?php echo apply_filters( 'widget_text', $cat['name'] ); ?></h6>
					<ul>
						<?php foreach ( $cat['dolos'] as $dolo ) { ?>
							<li><?php echo apply_filters( 'widget_text', dolo_format( $dolo, $a ) ); ?></li>
						<?php } ?>
					</ul>
				<?php }
				break;
			case 'dolo-choose-tag':
				foreach ( $instance['dolomon-tag'] as $tag_id ) {
					$tag = $dolo_cache['tags']["$tag_id"]; ?>
					<h6 style="margin: 5px 0"><?php echo apply_filters( 'widget_text', $tag['name'] ); ?></h6>
					<ul>
						<?php foreach ( $tag['dolos'] as $dolo ) { ?>
							<li><?php echo apply_filters( 'widget_text', dolo_format( $dolo, $a ) ); ?></li>
						<?php } ?>
					</ul>
				<?php }
				break;
			case 'dolo-choose-dolo': ?>
				<ul>
					<?php foreach ( $instance['dolomon-dolo'] as $dolo_id ) {
						$dolo = $dolo_cache['dolos']["$dolo_id"]; ?>
						<li><?php echo apply_filters( 'widget_text', dolo_format( $dolo, $a ) ); ?></li>
					<?php } ?>
				</ul>
				<?php break;
		}
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		global $dolo_cache;
		dolomon_refresh_cache();
		$defaults = [
			'title'            => __( 'New title', 'dolomon' ),
			'format'           => '%name',
			'dolo-widget-type' => 'dolo-choose-cat',
			'dolomon-cat'      => [],
			'dolomon-tag'      => [],
			'dolomon-dolo'     => [],
		];
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title    = $instance['title'];
		$format   = $instance['format'];
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ) ?>"><?php _e( 'Title:', 'dolomon' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ) ?>" name="<?php echo $this->get_field_name( 'title' ) ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'format' ) ?>"><?php _e( 'Format:', 'dolomon' ) ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'format' ) ?>" name="<?php echo $this->get_field_name( 'format' ) ?>" type="text" value="<?php echo esc_attr( $format ); ?>" placeholder="%name">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'dolo-widget-type' ) ?>"><?php _e( 'Choose type of widget', 'dolomon' ) ?></label>
			<select id="<?php echo $this->get_field_id( 'dolo-widget-type' ) ?>" name="<?php echo $this->get_field_name( 'dolo-widget-type' ) ?>" class="widefat dolo-widget-type">
				<option value="dolo-choose-cat"<?php if ( $instance['dolo-widget-type'] === 'dolo-choose-cat' ) { ?> selected<?php } ?>><?php _e( 'Category', 'dolomon' ) ?></option>
				<option value="dolo-choose-tag"<?php if ( $instance['dolo-widget-type'] === 'dolo-choose-tag' ) { ?> selected<?php } ?>><?php _e( 'Tags', 'dolomon' ) ?></option>
				<option value="dolo-choose-dolo"<?php if ( $instance['dolo-widget-type'] === 'dolo-choose-dolo' ) { ?> selected<?php } ?>><?php _e( 'Dolos', 'dolomon' ) ?></option>
			</select>
		</p>
		<p class="dolo-choose-cat<?php if ( $instance['dolo-widget-type'] != 'dolo-choose-cat' ) { ?> hidden<?php } ?>">
			<label for="<?php echo $this->get_field_id( 'dolomon-cat' ) ?>"><?php _e( 'Categories', 'dolomon' ) ?></label><br>
			<select id="<?php echo $this->get_field_id( 'dolomon-cat' ) ?>" name="<?php echo $this->get_field_name( 'dolomon-cat[]' ) ?>" class="widefat" multiple>
				<?php foreach ( $dolo_cache['cats'] as $cat ) { ?>
					<option value="<?php echo $cat['id'] ?>"<?php if ( in_array( $cat['id'], $instance['dolomon-cat'] ) ) { ?> selected<?php } ?>><?php echo $cat['name'] ?></option>
				<?php } ?>
			</select>
		</p>
		<p class="dolo-choose-tag<?php if ( $instance['dolo-widget-type'] != 'dolo-choose-tag' ) { ?> hidden<?php } ?>">
			<label for="<?php echo $this->get_field_id( 'dolomon-tag' ) ?>"><?php _e( 'Tags', 'dolomon' ) ?></label><br>
			<select id="<?php echo $this->get_field_id( 'dolomon-tag' ) ?>" name="<?php echo $this->get_field_name( 'dolomon-tag[]' ) ?>" class="widefat" multiple>
				<?php foreach ( $dolo_cache['tags'] as $tag ) { ?>
					<option value="<?php echo $tag['id'] ?>"<?php if ( in_array( $tag['id'], $instance['dolomon-tag'] ) ) { ?> selected<?php } ?>><?php echo $tag['name'] ?></option>
				<?php } ?>
			</select>
		</p>
		<p class="dolo-choose-dolo<?php if ( $instance['dolo-widget-type'] != 'dolo-choose-dolo' ) { ?> hidden<?php } ?>">
			<label for="<?php echo $this->get_field_id( 'dolomon-dolo' ) ?>"><?php _e( 'Dolos', 'dolomon' ) ?></label><br>
			<input class="widefat dolo-filter" type="text" placeholder="filter">
			<select id="<?php echo $this->get_field_id( 'dolomon-dolo' ) ?>" name="<?php echo $this->get_field_name( 'dolomon-dolo[]' ) ?>" class="widefat" multiple>
				<?php foreach ( $dolo_cache['dolos'] as $dolo ) {
					if ( empty( $dolo['name'] ) ) { ?>
						<option value="<?php echo $dolo['id'] ?>"<?php if ( in_array( $dolo['id'], $instance['dolomon-dolo'] ) ) { ?> selected<?php } ?>><?php echo $dolo['url'] ?></option>
					<?php } else { ?>
						<option value="<?php echo $dolo['id'] ?>"<?php if ( in_array( $dolo['id'], $instance['dolomon-dolo'] ) ) { ?> selected<?php } ?>><?php echo $dolo['name'] ?></option>
					<?php } ?>
				<?php } ?>
			</select>
		</p>
		<script>
			jQuery( document ).ready( function ( $ ) {
				$( '.dolo-widget-type' ).unbind( 'change' );
				$( '.dolo-widget-type' ).change( function () {
					var e = $( this );
					var par = e.parent().parent();
					par.find( '.dolo-choose-cat' ).addClass( 'hidden' );
					par.find( '.dolo-choose-tag' ).addClass( 'hidden' );
					par.find( '.dolo-choose-dolo' ).addClass( 'hidden' );
					par.find( '.' + e.val() ).removeClass( 'hidden' );
				} );
				$( '.dolo-filter' ).val( '' );
				$( '.dolo-filter' ).unbind( 'keyup' );
				$( '.dolo-filter' ).on( 'keyup', function ( event ) {
					var thi = this;
					var input = $( thi );
					var val = input.val();
					input.parent().find( 'option' ).each( function ( index, element ) {
						var e = $( element );
						if ( val === undefined || val === '' ) {
							e.removeClass( 'hidden' );
						} else {
							if ( !e.text().match( new RegExp( val ) ) ) {
								e.addClass( 'hidden' );
							} else {
								e.removeClass( 'hidden' );
							}
						}
					} );
				} );
			} );
		</script>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance           = $new_instance;
		$instance['title']  = ( ! empty( $instance['title'] ) ) ? strip_tags( $instance['title'] ) : '';
		$instance['format'] = ( ! empty( $instance['format'] ) ) ? strip_tags( $instance['format'] ) : '';

		return $instance;
	}

} // class Foo_Widget

// register Foo_Widget widget
function register_dolo_widget() {
	register_widget( 'Dolo_Widget' );
}
add_action( 'widgets_init', 'register_dolo_widget' );
?>
