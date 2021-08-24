<?php
/**
 * Class file for Template_Manager class.
 *
 * @package event-plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Template_Manager to create and redirect templates and single.
 */
class Template_Manager {
	/**
	 * Method to add the necessary filters.
	 */
	public static function initialize() {
		add_filter( 'theme_page_templates', 'Template_Manager::add_page_template' );
		add_filter( 'template_include', 'Template_Manager::redirect_page_template' );
	}

	/**
	 * Method to add the templates through a filter.
	 *
	 * @param array $templates - template path to filter.
	 *
	 * @return array
	 */
	public static function add_page_template( array $templates ) : array {
		$templates['calendar-template.php'] = 'Calendar';
		return $templates;
	}

	/**
	 * Method to redirect the template and single file to the ones in the plugin folder.
	 *
	 * @param string $template - the template to filter.
	 *
	 * @return string
	 */
	public static function redirect_page_template( string $template ) : string {
		$post          = get_post();
		$page_template = get_post_meta( $post->ID, '_wp_page_template', true );

		if ( 'calendar-template.php' === basename( $page_template ) ) {
			$template = WP_PLUGIN_DIR . '/event-plugin/templates/calendar-template.php';
		} elseif ( is_single() ) {
			$template = WP_PLUGIN_DIR . '/event-plugin/templates/single.php';
		}

		return $template;
	}
}
