<?php
/**
 * Supported_Post_Types class file
 *
 * @package wp-curate
 */

namespace Alley\WP\WP_Curate;

/**
 * The post types that should allow the Curation blocks and related meta.
 */
final class Supported_Post_Types {
	/**
	 * Stores the supported types.
	 *
	 * @var array
	 */
	private array $supported_post_types;

	/**
	 * Set up.
	 *
	 */
	public function __construct() {
		$this->initialize_supported_post_types();
	}

	/**
	 * Initialize the supported post types.
	 */
	public function initialize_supported_post_types() {
		// Get all post types.
		$post_types                 = get_post_types( [], 'objects' );
		$supported_post_types       = array_filter( $post_types, fn( $type ) => $type->public && use_block_editor_for_post_type( $type->name ) );
		$this->supported_post_types = ( wp_list_pluck( $supported_post_types, 'name' ) );
	}

	/**
	 * Get the supported post types.
	 *
	 * @return array
	 */
	public function get_supported_post_types() {
		return apply_filters( 'wp_curate_supported_post_types', $this->supported_post_types );
	}

	/**
	 * Get the current post type.
	 *
	 * @return string
	 */
	public function get_current_post_type() {
		$post_type = '';

		// Ensure we are in the admin before proceeding.
		if ( is_admin() ) {
			global $pagenow;

			// phpcs:ignore WordPress.VIP.SuperGlobalInputUsage.AccessDetected, WordPress.Security.NonceVerification.NoNonceVerification, WordPress.Security.NonceVerification.Recommended
			if ( 'post.php' === $pagenow && ! empty( $_GET['post'] ) ) {
				// phpcs:ignore WordPress.VIP.SuperGlobalInputUsage.AccessDetected, WordPress.Security.NonceVerification.Recommended
				$post_id   = absint( $_GET['post'] );
				$post_type = get_post_type( $post_id );
			// phpcs:ignore WordPress.VIP.SuperGlobalInputUsage.AccessDetected, WordPress.Security.NonceVerification.NoNonceVerification, WordPress.Security.NonceVerification.Recommended
			} elseif ( 'post-new.php' === $pagenow && ! empty( $_GET['post_type'] ) ) {
				// phpcs:ignore WordPress.VIP.SuperGlobalInputUsage.AccessDetected, WordPress.Security.NonceVerification.Recommended
				$post_type = sanitize_text_field( $_GET['post_type'] );
			}
		}
		return $post_type;
	}
}
