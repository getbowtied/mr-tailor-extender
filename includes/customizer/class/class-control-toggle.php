<?php
/**
 * Toggle control class
 *
 * @package Customize_Toggle_Control
 */

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	include ABSPATH . WPINC . '/class-wp-customize-control.php';
}

/**
 * Class WP_MT_Customize_Toggle_Control
 */
if ( ! class_exists( 'WP_MT_Customize_Toggle_Control' ) ) {
	class WP_MT_Customize_Toggle_Control extends WP_Customize_Control {

		public $type = 'toggle';

		/**
		 * Constructor.
		 *
		 * Supplied `$args` override class property defaults.
		 *
		 * If `$args['settings']` is not defined, use the $id as the setting ID.
		 *
		 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
		 * @param string               $id      Control ID.
		 * @param array                $args    Optional. Arguments to override class property defaults.
		 */
		public function __construct( $manager, $id, $args = array() ) {
			parent::__construct( $manager, $id, $args );
		}

		/**
		 * Render the control's content.
		 */
		public function render_content()
		{
			?>
			<label>
				<div class="mt-toggle-content">
					<hr />
					<span class="customize-control-title" style="width: calc(100% - 55px);"><?php echo esc_html( $this->label ); ?></span>
					<input id="cb<?php echo $this->instance_number ?>" type="checkbox" class="mt-tgl" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); checked( $this->value() ); ?> />
					<label for="cb<?php echo $this->instance_number ?>" class="mt-tgl-btn"></label>
				</div>
				<div class="mt-toggle-description"><i><?php echo $this->description; ?></i></div>
			</label>
			<?php
		}

		/**
		 * Enqueue control related scripts/styles.
		 */
		public function enqueue() {
			wp_enqueue_style('mt-extender-customizer-styles', plugins_url( 'assets/css/customizer.css', dirname( __FILE__ ) ), NULL );
		}
	}
}