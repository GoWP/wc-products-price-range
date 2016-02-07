<?php

/*
Plugin Name: Products Price Range Shortcode for WooCommerce
Description: Provides a shortcode for listing WooCommerce products within a price range: <code>[wc_products_price_range min=100 max=300]</code>. It also supports all attributes of the built-in <code>[products]</code> shortcode</em> (ie. columns, order, orderby)
Version:     1.0
Author:      GoWP
Author URI:  https://www.gowp.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

	add_shortcode( 'wc_products_price_range', 'wc_products_price_range' );
	function wc_products_price_range( $atts, $content, $shortcode ) {
		if ( class_exists( 'WooCommerce' ) ) {
			$shortcodes = new WC_Shortcodes();
			if ( is_array( $atts ) ) {
				$min = (int) $atts['min'];
				$max = (int) $atts['max'];
				if ( $min && $max ) {
					$and = "meta_value BETWEEN $min AND $max";
				} else {
					if ( $min ) {
						$and = "meta_value >= $min";
					} elseif ( $max ) {
						$and = "meta_value <= $max";
					}
				}
				if ( $and ) {
					global $wpdb;
					$query = "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_price' AND $and";
					$ids = $wpdb->get_col( $query );
					if ( ! empty( $ids ) ) {
						$atts['ids'] = implode( ",", $ids );
					}
				}
			}
			return $shortcodes->products( $atts );
		}
	}
