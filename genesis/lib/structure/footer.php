<?php
/**
 * Genesis Framework.
 *
 * WARNING: This file is part of the core Genesis Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Genesis\Footer
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    http://my.studiopress.com/themes/genesis/
 */

add_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );
/**
 * Echo the markup necessary to facilitate the footer widget areas.
 *
 * Check for a numerical parameter given when adding theme support - if none is found, then the function returns early.
 *
 * The child theme must style the widget areas.
 *
 * Applies the `genesis_footer_widget_areas` filter.
 *
 * @since 1.6.0
 *
 * @uses genesis_structural_wrap() Optionally adds wrap with footer-widgets context.
 *
 * @return null Return early if number of widget areas could not be determined, or nothing is added to the first widget area.
 */
function genesis_footer_widget_areas() {

	$footer_widgets = get_theme_support( 'genesis-footer-widgets' );

	if ( ! $footer_widgets || ! isset( $footer_widgets[0] ) || ! is_numeric( $footer_widgets[0] ) )
		return;

	$footer_widgets = (int) $footer_widgets[0];

	//* Check to see if first widget area has widgets. If not, do nothing. No need to check all footer widget areas.
	if ( ! is_active_sidebar( 'footer-1' ) )
		return;

	$inside  = '';
	$output  = '';
 	$counter = 1;

	while ( $counter <= $footer_widgets ) {

		//* Darn you, WordPress! Gotta output buffer.
		ob_start();
		dynamic_sidebar( 'footer-' . $counter );
		$widgets = ob_get_clean();

		$inside .= sprintf( '<div class="footer-widgets-%d widget-area">%s</div>', $counter, $widgets );

		$counter++;

	}

	if ( $inside ) {
	
		$output .= genesis_markup( array(
			'html5'   => '<div %s>',
			'xhtml'   => '<div id="footer-widgets" class="footer-widgets">',
			'context' => 'footer-widgets',
		) );
	
		$output .= genesis_structural_wrap( 'footer-widgets', 'open', 0 );
		
		$output .= $inside;
		
		$output .= genesis_structural_wrap( 'footer-widgets', 'close', 0 );
		
		$output .= '</div>';

	}

	echo apply_filters( 'genesis_footer_widget_areas', $output, $footer_widgets );

}

add_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
/**
 * Echo the opening div tag for the footer.
 *
 * Also optionally adds wrapping div opening tag.
 *
 * @since 1.2.0
 *
 * @uses genesis_structural_wrap() Maybe add opening .wrap div tag with footer context.
 * @uses genesis_markup()          Apply contextual markup.
 */
function genesis_footer_markup_open() {

	genesis_markup( array(
		'html5'   => '<footer %s>',
		'xhtml'   => '<div id="footer" class="footer">',
		'context' => 'site-footer',
	) );
	genesis_structural_wrap( 'footer', 'open' );

}

add_action( 'genesis_footer', 'genesis_footer_markup_close', 15 );
/**
 * Echo the closing div tag for the footer.
 *
 * Also optionally adds wrapping div closing tag.
 *
 * @since 1.2.0
 *
 * @uses genesis_structural_wrap() Maybe add closing .wrap div tag with footer context.
 * @uses genesis_markup()          Apply contextual markup.
 */
function genesis_footer_markup_close() {

	genesis_structural_wrap( 'footer', 'close' );
	genesis_markup( array(
		'html5'   => '</footer>',
		'xhtml'   => '</div>',
	) );

}

add_filter( 'genesis_footer_output', 'do_shortcode', 20 );
add_action( 'genesis_footer', 'genesis_do_footer' );
/**
 * Echo the contents of the footer.
 *
 * Execute any shortcodes that might be present.
 *
 * Applies `genesis_footer_backtotop_text`, `genesis_footer_creds_text` and `genesis_footer_output` filters.
 *
 * For HTML5 themes, only the credits text is used (back-to-top link is dropped).
 *
 * @since 1.0.1
 *
 * @uses genesis_html5() Check for HTML5 support.
 *
 */
function genesis_do_footer() {

	//* Build the text strings. Includes shortcodes
	$backtotop_text = '[footer_backtotop]';
	$creds_text     = sprintf( '[footer_copyright before="%s "] &#x000B7; [footer_childtheme_link before="" after=" %s"] [footer_genesis_link url="http://www.studiopress.com/" before=""] &#x000B7; [footer_wordpress_link] &#x000B7; [footer_loginout]', __( 'Copyright', 'genesis' ), __( 'on', 'genesis' ) );

	//* Filter the text strings
	$backtotop_text = apply_filters( 'genesis_footer_backtotop_text', $backtotop_text );
	$creds_text     = apply_filters( 'genesis_footer_creds_text', $creds_text );

	$backtotop = $backtotop_text ? sprintf( '<div class="gototop"><p>%s</p></div>', $backtotop_text ) : '';
	$creds     = $creds_text ? sprintf( '<div class="creds"><p>%s</p></div>', $creds_text ) : '';

	$output = $backtotop . $creds;

	//* Only use credits if HTML5
	if ( genesis_html5() )
		$output = '<p>' . $creds_text . '</p>';

	echo apply_filters( 'genesis_footer_output', $output, $backtotop_text, $creds_text );

}

add_filter( 'genesis_footer_scripts', 'do_shortcode' );
add_action( 'wp_footer', 'genesis_footer_scripts' );
/**
 * Echo the footer scripts, defined in Theme Settings.
 *
 * Applies the `genesis_footer_scripts` filter to the value returns from the footer_scripts option.
 *
 * @since 1.1.0
 *
 * @uses genesis_option() Get theme setting value.
 */
function genesis_footer_scripts() {

	echo apply_filters( 'genesis_footer_scripts', genesis_option( 'footer_scripts' ) );

}
