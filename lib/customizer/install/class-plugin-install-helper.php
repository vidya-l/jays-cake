<?php
/**
 * Plugin install helper.
 *
 * @package Entr
 */
if ( ! class_exists( 'jays_cake_Plugin_Install_Helper' ) ) {
	/**
	 * Class jays_cake_Plugin_Install_Helper
	 */
	class jays_cake_Plugin_Install_Helper {
		/**
		 * Instance of class.
		 *
		 * @var bool $instance instance variable.
		 */
		private static $instance;

		/**
		 * Check if instance already exists.
		 *
		 * @return jays_cake_Plugin_Install_Helper
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof jays_cake_Plugin_Install_Helper ) ) {
				self::$instance = new jays_cake_Plugin_Install_Helper;
			}

			return self::$instance;
		}

		/**
		 * Generate action button html.
		 *
		 * @param string $slug plugin slug.
		 *
		 * @return string
		 */
		public function get_button_html( $slug ) {
			$button = '';
			$state  = $this->check_plugin_state( $slug );
			if ( ! empty( $slug ) ) {

				$button .= '<div class=" plugin-card-' . esc_attr( $slug ) . '" style="padding: 8px 0 5px;">';

				switch ( $state ) {
					case 'install':
						$nonce  = wp_nonce_url(
							add_query_arg(
								array(
									'action' => 'install-plugin',
									'from'   => 'import',
									'plugin' => $slug,
								),
								network_admin_url( 'update.php' )
							),
							'install-plugin_' . $slug
						);
						$button .= '<a data-slug="' . esc_attr( $slug ) . '" class="install-now ta-install-plugin button  " href="' . esc_url( $nonce ) . '" data-name="' . $slug . '" aria-label="' . esc_attr__( 'Activate', 'jays' ) . '">' . esc_html__( 'Activate', 'jays' ) . '</a>';
						break;

					case 'activate':					
						$plugin_link_suffix = $slug . '/' . $slug . '.php';

						$nonce = add_query_arg(
							array(
								'action'        => 'activate',
								'plugin'        => rawurlencode( $plugin_link_suffix ),
								'plugin_status' => 'all',
								'paged'         => '1',
								'_wpnonce'      => wp_create_nonce( 'activate-plugin_' . $plugin_link_suffix ),
							), network_admin_url( 'plugins.php' )
						);

						$button .= '<a data-slug="' . esc_attr( $slug ) . '" class="activate button button-primary" href="' . esc_url( $nonce ) . '" aria-label="' . esc_attr__( 'Activate', 'jays' ) . '">' . esc_html__( 'Activate', 'jays' ) . '</a>';
						break;
				}// End switch().
				$button .= '</div>';
			}// End if().

			return $button;
		}

		/**
		 * Check plugin state.
		 *
		 * @param string $slug plugin slug.
		 *
		 * @return bool
		 */
		private function check_plugin_state( $slug ) {
			if ( file_exists( ABSPATH . 'wp-content/plugins/' . $slug . '/' . $slug . '.php' ) || file_exists( ABSPATH . 'wp-content/plugins/' . $slug . '/index.php' ) ) {
				$needs = ( is_plugin_active( $slug . '/' . $slug . '.php' ) || is_plugin_active( $slug . '/index.php' ) ) ? 'deactivate' : 'activate';
				return $needs;
			} else {
				return 'install';
			}
		}

		/**
		 * Enqueue Function.
		 */
		public function enqueue_scripts() {
			wp_enqueue_script( 'plugin-install' );
			wp_enqueue_script( 'updates' );
			wp_enqueue_script( 'jays-cake-plugin-install-helper', get_template_directory_uri() . '/lib/customizer/install/js/plugin-install.js', array( 'jquery' ), '', true );
			wp_localize_script(
				'jays-cake-plugin-install-helper', 'plugin_helper',
				array(
					'activating' => esc_html__( 'Activating', 'jays' ),
				)
			);
			wp_localize_script(
				'jays-cake-plugin-install-helper', 'pagenow',
				array( 'import' )
			);
		}
	}
}
