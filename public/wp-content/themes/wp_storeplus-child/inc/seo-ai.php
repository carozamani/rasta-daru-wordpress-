<?php
/**
 * SEO + AI/GEO: Schema.org JSON-LD, Open Graph, Twitter Cards, robots.txt, llms.txt.
 *
 * Skips overlapping output when Yoast / Rank Math / AIOSEO is active.
 * WooCommerce Product schema is left to WC; we only enrich brand / GTIN when missing.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * True when a full SEO plugin already owns meta + schema.
 */
function storeplus_child_seo_has_plugin() {
	return defined( 'WPSEO_VERSION' )
		|| defined( 'RANK_MATH_VERSION' )
		|| defined( 'AIOSEO_VERSION' )
		|| class_exists( 'WPSEO_Frontend', false );
}

/**
 * Absolute logo URL for Organization / OG fallback.
 */
function storeplus_child_seo_logo_url() {
	$id = function_exists( 'storeplus_child_saved_image_id' )
		? storeplus_child_saved_image_id( STOREPLUS_CHILD_LOGO_OPTION )
		: 0;
	if ( $id ) {
		$url = wp_get_attachment_image_url( $id, 'full' );
		if ( $url ) {
			return $url;
		}
	}
	$custom = get_theme_mod( 'custom_logo' );
	if ( $custom ) {
		$url = wp_get_attachment_image_url( (int) $custom, 'full' );
		if ( $url ) {
			return $url;
		}
	}
	return '';
}

/**
 * Same-origin image for the current view (featured / product / term / logo).
 */
function storeplus_child_seo_share_image() {
	if ( is_singular() ) {
		$id = get_post_thumbnail_id();
		if ( $id ) {
			$url = wp_get_attachment_image_url( $id, 'full' );
			if ( $url ) {
				return $url;
			}
		}
		if ( function_exists( 'is_product' ) && is_product() && function_exists( 'wc_get_product' ) ) {
			$product = wc_get_product( get_the_ID() );
			if ( $product ) {
				$img = wp_get_attachment_image_url( $product->get_image_id(), 'full' );
				if ( $img ) {
					return $img;
				}
			}
		}
	}
	if ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();
		if ( $term && ! empty( $term->term_id ) ) {
			$thumb = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
			if ( $thumb ) {
				$url = wp_get_attachment_image_url( $thumb, 'full' );
				if ( $url ) {
					return $url;
				}
			}
		}
	}
	return storeplus_child_seo_logo_url();
}

/**
 * Canonical URL for the current request (no query args).
 */
function storeplus_child_seo_canonical() {
	if ( function_exists( 'wp_get_canonical_url' ) && is_singular() ) {
		$url = wp_get_canonical_url();
		if ( $url ) {
			return $url;
		}
	}
	if ( is_front_page() ) {
		return home_url( '/' );
	}
	if ( is_home() && ! is_front_page() ) {
		$page = (int) get_option( 'page_for_posts' );
		return $page ? get_permalink( $page ) : home_url( '/' );
	}
	if ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();
		if ( $term && ! is_wp_error( $term ) ) {
			$link = get_term_link( $term );
			return is_wp_error( $link ) ? home_url( '/' ) : $link;
		}
	}
	if ( function_exists( 'is_shop' ) && is_shop() ) {
		return get_permalink( wc_get_page_id( 'shop' ) );
	}
	global $wp;
	$path = isset( $wp->request ) ? $wp->request : '';
	return home_url( user_trailingslashit( $path ) );
}

/**
 * Meta description for the current view (plain text, trimmed).
 */
function storeplus_child_seo_description() {
	$description = '';

	if ( is_front_page() || is_home() ) {
		$description = get_bloginfo( 'description', 'display' );
	} elseif ( is_singular() ) {
		$post = get_queried_object();
		if ( $post instanceof WP_Post ) {
			if ( ! empty( $post->post_excerpt ) ) {
				$description = $post->post_excerpt;
			} elseif ( function_exists( 'is_product' ) && is_product() && function_exists( 'wc_get_product' ) ) {
				$product = wc_get_product( $post->ID );
				if ( $product ) {
					$description = $product->get_short_description();
					if ( '' === $description ) {
						$description = $product->get_description();
					}
				}
			} else {
				$description = $post->post_content;
			}
		}
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();
		if ( $term && ! empty( $term->description ) ) {
			$description = $term->description;
		} else {
			$description = sprintf(
				/* translators: %s: term name */
				__( 'مشاهده محصولات و مطالب مربوط به %s در فروشگاه رستا دارو.', 'wp_storeplus-child' ),
				$term->name ?? ''
			);
		}
	} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
		$description = get_bloginfo( 'description', 'display' );
	} else {
		$description = get_bloginfo( 'description', 'display' );
	}

	$description = wp_strip_all_tags( (string) $description );
	$description = preg_replace( '/\s+/u', ' ', $description );
	$description = trim( $description );
	if ( function_exists( 'mb_substr' ) ) {
		if ( mb_strlen( $description ) > 160 ) {
			$description = mb_substr( $description, 0, 157 ) . '…';
		}
	} elseif ( strlen( $description ) > 160 ) {
		$description = substr( $description, 0, 157 ) . '...';
	}

	return $description;
}

/**
 * Page title for OG (without site name duplication when possible).
 */
function storeplus_child_seo_title() {
	$title = wp_get_document_title();
	return is_string( $title ) ? $title : get_bloginfo( 'name', 'display' );
}

/**
 * Social profile URLs from footer options + theme options.
 *
 * @return string[]
 */
function storeplus_child_seo_same_as() {
	$urls = array();

	$theme_keys = array(
		'irantheme_instagram_social_url',
		'irantheme_facebook_social_url',
		'irantheme_twitter_social_url',
		'irantheme_linkedin_social_url',
		'irantheme_youtube_social_url',
		'irantheme_telegram_social_url',
		'irantheme_aparat_social_url',
	);
	foreach ( $theme_keys as $key ) {
		$u = get_option( $key );
		if ( is_string( $u ) && preg_match( '#^https?://#i', $u ) ) {
			$urls[] = esc_url_raw( $u );
		}
	}

	$footer_key = defined( 'SPFC_OPTION' ) ? SPFC_OPTION : 'sp_footer_content';
	$footer     = get_option( $footer_key );
	if ( is_array( $footer ) && ! empty( $footer['social'] ) && is_array( $footer['social'] ) ) {
		foreach ( $footer['social'] as $row ) {
			$u = isset( $row['url'] ) ? trim( (string) $row['url'] ) : '';
			if ( preg_match( '#^https?://#i', $u ) ) {
				$urls[] = esc_url_raw( $u );
			}
		}
	}

	return array_values( array_unique( array_filter( $urls ) ) );
}

/**
 * Phone from footer contact rows (first tel-like value).
 */
function storeplus_child_seo_telephone() {
	$footer_key = defined( 'SPFC_OPTION' ) ? SPFC_OPTION : 'sp_footer_content';
	$footer     = get_option( $footer_key );
	if ( ! is_array( $footer ) || empty( $footer['contact']['rows'] ) ) {
		return '';
	}
	foreach ( (array) $footer['contact']['rows'] as $row ) {
		$val = isset( $row['value'] ) ? trim( wp_strip_all_tags( (string) $row['value'] ) ) : '';
		if ( preg_match( '/[0-9۰-۹]{6,}/u', $val ) ) {
			return $val;
		}
	}
	return '';
}

/**
 * Print a JSON-LD script tag.
 *
 * @param array $data Schema graph or node.
 */
function storeplus_child_seo_print_jsonld( $data ) {
	if ( empty( $data ) || ! is_array( $data ) ) {
		return;
	}
	$data = apply_filters( 'storeplus_child_seo_jsonld', $data );
	if ( empty( $data ) || ! is_array( $data ) ) {
		return;
	}
	$json = wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
	if ( ! $json ) {
		return;
	}
	echo '<script type="application/ld+json">' . $json . "</script>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Organization node.
 */
function storeplus_child_seo_organization() {
	$org = array(
		'@type' => 'Organization',
		'@id'   => home_url( '/#organization' ),
		'name'  => get_bloginfo( 'name', 'display' ),
		'url'   => home_url( '/' ),
	);
	$logo = storeplus_child_seo_logo_url();
	if ( $logo ) {
		$org['logo'] = array(
			'@type' => 'ImageObject',
			'url'   => $logo,
		);
		$org['image'] = $logo;
	}
	$desc = get_bloginfo( 'description', 'display' );
	if ( $desc ) {
		$org['description'] = $desc;
	}
	$same = storeplus_child_seo_same_as();
	if ( $same ) {
		$org['sameAs'] = $same;
	}
	$tel = storeplus_child_seo_telephone();
	if ( $tel ) {
		$org['telephone'] = $tel;
	}
	$address = array(
		'@type'          => 'PostalAddress',
		'addressCountry' => 'IR',
		'addressRegion'  => 'Isfahan',
	);
	$city = get_option( 'woocommerce_store_city' );
	if ( is_string( $city ) && $city !== '' ) {
		$address['addressLocality'] = $city;
	}
	$org['address'] = $address;
	/**
	 * Filter Organization schema node (GEO / AI enrichers hook here).
	 *
	 * @param array $org Organization node.
	 */
	return apply_filters( 'storeplus_child_seo_organization', $org );
}

/**
 * WebSite + SearchAction (site search / product search).
 */
function storeplus_child_seo_website() {
	$search = home_url( '/?s={search_term_string}&post_type=product' );

	return array(
		'@type'       => 'WebSite',
		'@id'         => home_url( '/#website' ),
		'url'         => home_url( '/' ),
		'name'        => get_bloginfo( 'name', 'display' ),
		'description' => get_bloginfo( 'description', 'display' ),
		'publisher'   => array( '@id' => home_url( '/#organization' ) ),
		'inLanguage'  => 'fa-IR',
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => array(
				'@type'       => 'EntryPoint',
				'urlTemplate' => $search,
			),
			'query-input' => 'required name=search_term_string',
		),
	);
}

/**
 * BreadcrumbList for the current view.
 *
 * @return array|null
 */
function storeplus_child_seo_breadcrumbs() {
	$items = array(
		array(
			'@type'    => 'ListItem',
			'position' => 1,
			'name'     => get_bloginfo( 'name', 'display' ),
			'item'     => home_url( '/' ),
		),
	);
	$pos = 2;

	if ( is_front_page() ) {
		return null;
	}

	if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() || is_product() ) ) {
		$shop_id = function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'shop' ) : 0;
		if ( $shop_id > 0 ) {
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $pos++,
				'name'     => get_the_title( $shop_id ),
				'item'     => get_permalink( $shop_id ),
			);
		}
	}

	if ( is_singular( 'post' ) ) {
		$cats = get_the_category();
		if ( $cats ) {
			$cat = $cats[0];
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $pos++,
				'name'     => $cat->name,
				'item'     => get_category_link( $cat->term_id ),
			);
		}
		$items[] = array(
			'@type'    => 'ListItem',
			'position' => $pos,
			'name'     => get_the_title(),
			'item'     => get_permalink(),
		);
	} elseif ( function_exists( 'is_product' ) && is_product() ) {
		$terms = get_the_terms( get_the_ID(), 'product_cat' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$term = array_shift( $terms );
			$ancestors = array_reverse( get_ancestors( $term->term_id, 'product_cat' ) );
			foreach ( $ancestors as $aid ) {
				$a = get_term( $aid, 'product_cat' );
				if ( $a && ! is_wp_error( $a ) ) {
					$items[] = array(
						'@type'    => 'ListItem',
						'position' => $pos++,
						'name'     => $a->name,
						'item'     => get_term_link( $a ),
					);
				}
			}
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $pos++,
				'name'     => $term->name,
				'item'     => get_term_link( $term ),
			);
		}
		$items[] = array(
			'@type'    => 'ListItem',
			'position' => $pos,
			'name'     => get_the_title(),
			'item'     => get_permalink(),
		);
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();
		if ( $term && ! is_wp_error( $term ) ) {
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $pos,
				'name'     => $term->name,
				'item'     => get_term_link( $term ),
			);
		}
	} elseif ( is_singular() ) {
		$items[] = array(
			'@type'    => 'ListItem',
			'position' => $pos,
			'name'     => get_the_title(),
			'item'     => get_permalink(),
		);
	} else {
		return null;
	}

	return array(
		'@type'           => 'BreadcrumbList',
		'@id'             => storeplus_child_seo_canonical() . '#breadcrumb',
		'itemListElement' => $items,
	);
}

/**
 * Product schema (fallback when WooCommerce does not emit it on custom templates).
 *
 * @return array|null
 */
function storeplus_child_seo_product() {
	if ( ! function_exists( 'is_product' ) || ! is_product() || ! function_exists( 'wc_get_product' ) ) {
		return null;
	}
	$product = wc_get_product( get_the_ID() );
	if ( ! $product ) {
		return null;
	}

	$images = array();
	$main   = wp_get_attachment_image_url( $product->get_image_id(), 'full' );
	if ( $main ) {
		$images[] = $main;
	}
	foreach ( $product->get_gallery_image_ids() as $gid ) {
		$u = wp_get_attachment_image_url( $gid, 'full' );
		if ( $u ) {
			$images[] = $u;
		}
	}

	$desc = $product->get_short_description();
	if ( '' === $desc ) {
		$desc = $product->get_description();
	}
	$desc = wp_strip_all_tags( (string) $desc );
	$desc = preg_replace( '/\s+/u', ' ', $desc );
	$desc = trim( $desc );

	$data = array(
		'@type'       => 'Product',
		'@id'         => get_permalink() . '#product',
		'name'        => $product->get_name(),
		'description' => $desc ?: storeplus_child_seo_description(),
		'url'         => get_permalink(),
		'sku'         => $product->get_sku() ?: (string) $product->get_id(),
		'inLanguage'  => 'fa-IR',
	);
	if ( $images ) {
		$data['image'] = $images;
	}

	$brands = array();
	foreach ( array( 'product_brand', 'pwb-brand', 'pa_brand' ) as $tax ) {
		if ( ! taxonomy_exists( $tax ) ) {
			continue;
		}
		$terms = get_the_terms( $product->get_id(), $tax );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$brands = $terms;
			break;
		}
	}
	if ( $brands ) {
		$data['brand'] = array(
			'@type' => 'Brand',
			'name'  => $brands[0]->name,
		);
	}

	$cats = get_the_terms( $product->get_id(), 'product_cat' );
	if ( $cats && ! is_wp_error( $cats ) ) {
		$data['category'] = $cats[0]->name;
	}

	$currency = get_woocommerce_currency();
	$price    = $product->get_price();
	if ( $product->is_type( 'variable' ) ) {
		$price = $product->get_variation_price( 'min', true );
	}

	$offer = array(
		'@type'           => 'Offer',
		'url'             => get_permalink(),
		'priceCurrency'   => $currency,
		'availability'    => $product->is_in_stock()
			? 'https://schema.org/InStock'
			: 'https://schema.org/OutOfStock',
		'itemCondition'   => 'https://schema.org/NewCondition',
		'seller'          => array( '@id' => home_url( '/#organization' ) ),
		'priceValidUntil' => gmdate( 'Y-m-d', time() + WEEK_IN_SECONDS ),
	);
	if ( '' !== $price && null !== $price ) {
		$offer['price'] = wc_format_decimal( $price, wc_get_price_decimals() );
	}
	$returns = function_exists( 'storeplus_child_store_page_url' )
		? storeplus_child_store_page_url( 'returns', 'bazgasht-kala' )
		: home_url( '/bazgasht-kala/' );
	if ( $returns ) {
		$offer['hasMerchantReturnPolicy'] = array(
			'@type'                => 'MerchantReturnPolicy',
			'applicableCountry'    => 'IR',
			'returnPolicyCategory' => 'https://schema.org/MerchantReturnFiniteReturnWindow',
			'merchantReturnLink'   => $returns,
		);
	}
	$data['offers'] = $offer;

	$gtin = storeplus_child_seo_product_gtin( $product );
	if ( $gtin ) {
		$key          = ( 13 === strlen( $gtin ) ) ? 'gtin13' : 'gtin';
		$data[ $key ] = $gtin;
	}

	if ( $product->get_review_count() > 0 ) {
		$data['aggregateRating'] = array(
			'@type'       => 'AggregateRating',
			'ratingValue' => $product->get_average_rating(),
			'reviewCount' => $product->get_review_count(),
		);
	}

	return $data;
}

/**
 * Resolve GTIN / barcode from common Woo meta keys.
 *
 * @param WC_Product $product Product.
 * @return string Digits only or empty.
 */
function storeplus_child_seo_product_gtin( $product ) {
	if ( ! $product ) {
		return '';
	}
	$keys = array( '_global_unique_id', '_wc_gtin', '_gtin', '_gtin13', '_barcode', 'barcode' );
	foreach ( $keys as $key ) {
		$raw = $product->get_meta( $key, true );
		if ( ! $raw && method_exists( $product, 'get_global_unique_id' ) ) {
			$raw = $product->get_global_unique_id();
		}
		$digits = preg_replace( '/\D+/', '', (string) $raw );
		if ( $digits && strlen( $digits ) >= 8 ) {
			return $digits;
		}
	}
	return '';
}

/**
 * Article schema for blog posts.
 */
function storeplus_child_seo_article() {
	if ( ! is_singular( 'post' ) ) {
		return null;
	}
	$post_id = get_the_ID();
	$img     = storeplus_child_seo_share_image();
	$data    = array(
		'@type'            => 'Article',
		'@id'              => get_permalink() . '#article',
		'headline'         => get_the_title(),
		'description'      => storeplus_child_seo_description(),
		'datePublished'    => get_the_date( DATE_W3C ),
		'dateModified'     => get_the_modified_date( DATE_W3C ),
		'mainEntityOfPage' => array(
			'@type' => 'WebPage',
			'@id'   => get_permalink(),
		),
		/* YMYL: brand as author/publisher (pharmacy guides are store editorial, not anonymous person). */
		'author'           => array(
			'@type' => 'Organization',
			'@id'   => home_url( '/#organization' ),
			'name'  => get_bloginfo( 'name', 'display' ),
		),
		'publisher'        => array( '@id' => home_url( '/#organization' ) ),
		'inLanguage'       => 'fa-IR',
	);
	if ( $img ) {
		$data['image'] = array( $img );
	}
	return $data;
}

/**
 * CollectionPage + ItemList for product category / shop archives.
 */
function storeplus_child_seo_collection() {
	$is_shop_view = function_exists( 'is_shop' ) && is_shop();
	$is_tax_view  = function_exists( 'is_product_taxonomy' ) && is_product_taxonomy();
	if ( ! $is_shop_view && ! $is_tax_view ) {
		return null;
	}

	$name = '';
	$url  = storeplus_child_seo_canonical();
	if ( $is_tax_view ) {
		$term = get_queried_object();
		$name = $term->name ?? '';
	} elseif ( $is_shop_view ) {
		$name = get_the_title( wc_get_page_id( 'shop' ) );
	}

	$list = array();
	global $wp_query;
	$position = 1;
	if ( ! empty( $wp_query->posts ) ) {
		foreach ( array_slice( $wp_query->posts, 0, 20 ) as $p ) {
			$list[] = array(
				'@type'    => 'ListItem',
				'position' => $position++,
				'url'      => get_permalink( $p ),
				'name'     => get_the_title( $p ),
			);
		}
	}

	$page = array(
		'@type'       => 'CollectionPage',
		'@id'         => $url . '#webpage',
		'url'         => $url,
		'name'        => $name,
		'description' => storeplus_child_seo_description(),
		'isPartOf'    => array( '@id' => home_url( '/#website' ) ),
		'inLanguage'  => 'fa-IR',
	);
	if ( $list ) {
		$page['mainEntity'] = array(
			'@type'           => 'ItemList',
			'itemListElement' => $list,
			'numberOfItems'   => count( $list ),
		);
	}
	return $page;
}

/**
 * WebPage for front page / generic pages.
 */
function storeplus_child_seo_webpage() {
	if ( is_front_page() ) {
		return array(
			'@type'       => 'WebPage',
			'@id'         => home_url( '/#webpage' ),
			'url'         => home_url( '/' ),
			'name'        => get_bloginfo( 'name', 'display' ),
			'description' => storeplus_child_seo_description(),
			'isPartOf'    => array( '@id' => home_url( '/#website' ) ),
			'about'       => array( '@id' => home_url( '/#organization' ) ),
			'inLanguage'  => 'fa-IR',
		);
	}
	if ( is_singular( 'page' ) && ! is_front_page() ) {
		return array(
			'@type'       => 'WebPage',
			'@id'         => get_permalink() . '#webpage',
			'url'         => get_permalink(),
			'name'        => get_the_title(),
			'description' => storeplus_child_seo_description(),
			'isPartOf'    => array( '@id' => home_url( '/#website' ) ),
			'inLanguage'  => 'fa-IR',
		);
	}
	return null;
}

/**
 * Output JSON-LD graph in wp_head.
 * When Yoast/Rank Math/AIOSEO is active they own the general graph; we still
 * schedule a Product schema fallback because Yoast Free often emits only breadcrumbs.
 */
function storeplus_child_seo_output_schema() {
	if ( is_admin() ) {
		return;
	}
	if ( is_feed() || is_robots() || is_trackback() ) {
		return;
	}

	if ( storeplus_child_seo_has_plugin() ) {
		/* Only fall back when WooCommerce is NOT already emitting Product JSON-LD. */
		add_action( 'wp_footer', 'storeplus_child_seo_product_schema_fallback', 4 );
		return;
	}

	$graph = array(
		storeplus_child_seo_organization(),
		storeplus_child_seo_website(),
	);

	// Skip our breadcrumbs on WC product/shop views — WC already prints BreadcrumbList.
	$wc_crumbs = function_exists( 'is_product' ) && ( is_product() || ( function_exists( 'is_shop' ) && is_shop() ) || ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() ) );
	$crumbs    = $wc_crumbs ? null : storeplus_child_seo_breadcrumbs();
	if ( $crumbs ) {
		$graph[] = $crumbs;
	}
	/* Avoid duplicate Product nodes: WooCommerce core already prints Product JSON-LD. */
	$product = class_exists( 'WooCommerce' ) ? null : storeplus_child_seo_product();
	if ( $product ) {
		$graph[] = $product;
	}
	$article = storeplus_child_seo_article();
	if ( $article ) {
		$graph[] = $article;
	}
	$collection = storeplus_child_seo_collection();
	if ( $collection ) {
		$graph[] = $collection;
	}
	$webpage = storeplus_child_seo_webpage();
	if ( $webpage ) {
		$graph[] = $webpage;
	}

	storeplus_child_seo_print_jsonld(
		array(
			'@context' => 'https://schema.org',
			'@graph'   => array_values( array_filter( $graph ) ),
		)
	);
}
add_action( 'wp_head', 'storeplus_child_seo_output_schema', 5 );

/**
 * Ensure Product JSON-LD exists on PDP when neither WooCommerce nor Yoast emits it.
 * Default path: enrich WC structured data via filter — do not double-print.
 */
function storeplus_child_seo_product_schema_fallback() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}
	/* WooCommerce core Product JSON-LD is the source of truth. */
	if ( class_exists( 'WooCommerce' ) && apply_filters( 'storeplus_child_seo_wc_owns_product_schema', true ) ) {
		return;
	}
	$product = storeplus_child_seo_product();
	if ( ! $product ) {
		return;
	}
	$product['@context'] = 'https://schema.org';
	storeplus_child_seo_print_jsonld( $product );
}

/**
 * Open Graph + Twitter Cards (+ hreflang).
 */
function storeplus_child_seo_output_social_meta() {
	if ( is_admin() || storeplus_child_seo_has_plugin() ) {
		return;
	}
	if ( is_feed() ) {
		return;
	}

	$title       = storeplus_child_seo_title();
	$description = storeplus_child_seo_description();
	$url         = storeplus_child_seo_canonical();
	$image       = storeplus_child_seo_share_image();
	$site_name   = get_bloginfo( 'name', 'display' );
	$type        = 'website';

	if ( is_singular( 'post' ) ) {
		$type = 'article';
	} elseif ( function_exists( 'is_product' ) && is_product() ) {
		$type = 'product';
	}

	echo '<meta property="og:locale" content="fa_IR" />' . "\n";
	echo '<meta property="og:type" content="' . esc_attr( $type ) . '" />' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />' . "\n";
	if ( $description ) {
		echo '<meta property="og:description" content="' . esc_attr( $description ) . '" />' . "\n";
	}
	echo '<meta property="og:url" content="' . esc_url( $url ) . '" />' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( $site_name ) . '" />' . "\n";
	if ( $image ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '" />' . "\n";
		echo '<meta property="og:image:alt" content="' . esc_attr( $title ) . '" />' . "\n";
	}

	if ( is_singular( 'post' ) ) {
		echo '<meta property="article:published_time" content="' . esc_attr( get_the_date( DATE_W3C ) ) . '" />' . "\n";
		echo '<meta property="article:modified_time" content="' . esc_attr( get_the_modified_date( DATE_W3C ) ) . '" />' . "\n";
	}

	if ( function_exists( 'is_product' ) && is_product() && function_exists( 'wc_get_product' ) ) {
		$product = wc_get_product( get_the_ID() );
		if ( $product ) {
			$price = $product->get_price();
			if ( '' !== $price && null !== $price ) {
				echo '<meta property="product:price:amount" content="' . esc_attr( $price ) . '" />' . "\n";
				echo '<meta property="product:price:currency" content="' . esc_attr( get_woocommerce_currency() ) . '" />' . "\n";
			}
			echo '<meta property="product:availability" content="' . esc_attr( $product->is_in_stock() ? 'in stock' : 'out of stock' ) . '" />' . "\n";
		}
	}

	echo '<meta name="twitter:card" content="' . esc_attr( $image ? 'summary_large_image' : 'summary' ) . '" />' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '" />' . "\n";
	if ( $description ) {
		echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '" />' . "\n";
	}
	if ( $image ) {
		echo '<meta name="twitter:image" content="' . esc_url( $image ) . '" />' . "\n";
	}

	echo '<link rel="alternate" hreflang="fa-IR" href="' . esc_url( $url ) . '" />' . "\n";
	echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $url ) . '" />' . "\n";
}
add_action( 'wp_head', 'storeplus_child_seo_output_social_meta', 4 );

/**
 * Remove parent-theme meta description always (Yoast or our fallback owns it).
 * When no SEO plugin is active, print a stronger description for archives/PDP.
 */
function storeplus_child_seo_replace_theme_description() {
	if ( function_exists( 'irantheme_add_custom_meta_description' ) ) {
		remove_action( 'wp_head', 'irantheme_add_custom_meta_description' );
	}
	/* Yoast / Rank Math / AIOSEO already output description — avoid duplicates. */
	if ( storeplus_child_seo_has_plugin() ) {
		return;
	}
	$description = storeplus_child_seo_description();
	if ( $description ) {
		echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'storeplus_child_seo_replace_theme_description', 1 );

/**
 * Safety net: strip parent meta even if it was re-hooked later in the request.
 */
function storeplus_child_seo_strip_parent_meta_late() {
	if ( function_exists( 'irantheme_add_custom_meta_description' ) ) {
		remove_action( 'wp_head', 'irantheme_add_custom_meta_description' );
	}
}
add_action( 'wp_head', 'storeplus_child_seo_strip_parent_meta_late', 0 );

/**
 * Enrich WooCommerce Product structured data (brand / sku).
 *
 * @param array      $markup  Existing markup.
 * @param WC_Product $product Product.
 * @return array
 */
function storeplus_child_seo_enrich_product_schema( $markup, $product ) {
	if ( ! is_array( $markup ) || ! $product ) {
		return $markup;
	}
	if ( empty( $markup['sku'] ) && $product->get_sku() ) {
		$markup['sku'] = $product->get_sku();
	}
	if ( empty( $markup['brand'] ) ) {
		$brands = array();
		foreach ( array( 'product_brand', 'pwb-brand', 'pa_brand' ) as $tax ) {
			if ( ! taxonomy_exists( $tax ) ) {
				continue;
			}
			$terms = get_the_terms( $product->get_id(), $tax );
			if ( $terms && ! is_wp_error( $terms ) ) {
				$brands = $terms;
				break;
			}
		}
		if ( $brands ) {
			$markup['brand'] = array(
				'@type' => 'Brand',
				'name'  => $brands[0]->name,
			);
		}
	}
	if ( empty( $markup['inLanguage'] ) ) {
		$markup['inLanguage'] = 'fa-IR';
	}

	$gtin = storeplus_child_seo_product_gtin( $product );
	if ( $gtin ) {
		$key = ( 13 === strlen( $gtin ) ) ? 'gtin13' : 'gtin';
		if ( empty( $markup[ $key ] ) && empty( $markup['gtin'] ) && empty( $markup['gtin13'] ) ) {
			$markup[ $key ] = $gtin;
		}
	}

	$returns = function_exists( 'storeplus_child_store_page_url' )
		? storeplus_child_store_page_url( 'returns', 'bazgasht-kala' )
		: '';
	if ( empty( $markup['offers'] ) || ! is_array( $markup['offers'] ) ) {
		return $markup;
	}
	$offers = $markup['offers'];
	$is_list = isset( $offers[0] ) && is_array( $offers[0] );
	$nodes   = $is_list ? $offers : array( $offers );
	foreach ( $nodes as $i => $offer ) {
		if ( ! is_array( $offer ) ) {
			continue;
		}
		if ( empty( $offer['priceValidUntil'] ) ) {
			$nodes[ $i ]['priceValidUntil'] = gmdate( 'Y-m-d', time() + WEEK_IN_SECONDS );
		}
		if ( $returns && empty( $offer['hasMerchantReturnPolicy'] ) ) {
			$nodes[ $i ]['hasMerchantReturnPolicy'] = array(
				'@type'                => 'MerchantReturnPolicy',
				'applicableCountry'    => 'IR',
				'returnPolicyCategory' => 'https://schema.org/MerchantReturnFiniteReturnWindow',
				'merchantReturnLink'   => $returns,
			);
		}
	}
	$markup['offers'] = $is_list ? $nodes : $nodes[0];
	return $markup;
}
add_filter( 'woocommerce_structured_data_product', 'storeplus_child_seo_enrich_product_schema', 20, 2 );

/**
 * robots.txt: sitemap + AI crawler guidance (allow indexing content).
 *
 * @param string $output Existing robots.txt body.
 * @param bool   $public blog_public.
 * @return string
 */
function storeplus_child_seo_robots_txt( $output, $public ) {
	if ( ! $public ) {
		return $output;
	}
	/* Bot allowlist + primary Sitemap are owned by geo-ai.php (single source of truth). */
	if ( false === stripos( $output, 'Sitemap:' ) && ! defined( 'WPSEO_VERSION' ) ) {
		$output .= "\nSitemap: " . home_url( '/wp-sitemap.xml' ) . "\n";
	}
	return $output;
}
add_filter( 'robots_txt', 'storeplus_child_seo_robots_txt', 20, 2 );

/**
 * Prefer larger image previews in SERP (extends core max-image-preview).
 *
 * @param array $robots Robots directives.
 * @return array
 */
function storeplus_child_seo_robots_directives( $robots ) {
	/* Even with Yoast: ensure generous previews if missing. */
	if ( empty( $robots['max-image-preview'] ) ) {
		$robots['max-image-preview'] = 'large';
	}
	if ( ! isset( $robots['max-snippet'] ) ) {
		$robots['max-snippet'] = -1;
	}
	if ( ! isset( $robots['max-video-preview'] ) ) {
		$robots['max-video-preview'] = -1;
	}
	return $robots;
}
add_filter( 'wp_robots', 'storeplus_child_seo_robots_directives', 99 );

/**
 * Empty image alt → use attachment title / post title (a11y + image SEO).
 *
 * @param array        $attr       Attributes.
 * @param WP_Post      $attachment Attachment.
 * @param string|int[] $size       Size.
 * @return array
 */
function storeplus_child_seo_image_alt( $attr, $attachment, $size ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	if ( ! empty( $attr['alt'] ) ) {
		return $attr;
	}
	$alt = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );
	if ( ! $alt ) {
		$alt = $attachment->post_title;
	}
	if ( ! $alt && ! empty( $attachment->post_parent ) ) {
		$alt = get_the_title( $attachment->post_parent );
	}
	if ( $alt ) {
		$attr['alt'] = wp_strip_all_tags( $alt );
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'storeplus_child_seo_image_alt', 20, 3 );

/**
 * Register /llms.txt rewrite (plus REQUEST_URI fallback for nginx).
 */
function storeplus_child_seo_llms_rewrite() {
	add_rewrite_rule( '^llms\.txt$', 'index.php?storeplus_llms=1', 'top' );
}
add_action( 'init', 'storeplus_child_seo_llms_rewrite', 5 );

/**
 * @param array $vars Query vars.
 * @return array
 */
function storeplus_child_seo_query_vars( $vars ) {
	$vars[] = 'storeplus_llms';
	return $vars;
}
add_filter( 'query_vars', 'storeplus_child_seo_query_vars' );

/**
 * True when the current request is for /llms.txt.
 */
function storeplus_child_seo_is_llms_request() {
	if ( get_query_var( 'storeplus_llms' ) ) {
		return true;
	}
	$uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
	$path = (string) wp_parse_url( $uri, PHP_URL_PATH );
	return (bool) preg_match( '#/llms\.txt/?$#i', $path );
}

/**
 * Flush rewrites once after module is enabled.
 */
function storeplus_child_seo_maybe_flush() {
	if ( get_option( 'storeplus_child_seo_rewrite_v3' ) ) {
		return;
	}
	flush_rewrite_rules( false );
	update_option( 'storeplus_child_seo_rewrite_v3', 1, false );
}
add_action( 'init', 'storeplus_child_seo_maybe_flush', 99 );

/**
 * Serve llms.txt for AI crawlers / GEO.
 */
function storeplus_child_seo_serve_llms() {
	if ( ! storeplus_child_seo_is_llms_request() ) {
		return;
	}

	$site = get_bloginfo( 'name', 'display' );
	$desc = get_bloginfo( 'description', 'display' );
	$home = home_url( '/' );
	$shop = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : $home;

	$cats = array();
	if ( taxonomy_exists( 'product_cat' ) ) {
		$terms = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => true,
				'parent'     => 0,
				'number'     => 20,
			)
		);
		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $t ) {
				$cats[] = '- [' . $t->name . '](' . get_term_link( $t ) . '): ' . wp_strip_all_tags( $t->description ?: ( 'دسته محصولات ' . $t->name ) );
			}
		}
	}

	$posts = get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 8,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);
	$post_lines = array();
	foreach ( $posts as $p ) {
		$post_lines[] = '- [' . get_the_title( $p ) . '](' . get_permalink( $p ) . ')';
	}

	$body  = '# ' . $site . "\n\n";
	$body .= '> ' . ( $desc ?: 'فروشگاه آنلاین محصولات سلامت، مکمل و دارو' ) . "\n\n";
	$body .= $site . " یک فروشگاه اینترنتی فارسی‌زبان برای خرید مکمل‌های غذایی، محصولات مراقبت شخصی و اقلام سلامت است. محتوا و محصولات به زبان فارسی (fa-IR) ارائه می‌شوند.\n\n";
	$body .= "## صفحات اصلی\n\n";
	$body .= "- [صفحه اصلی]({$home})\n";
	$body .= "- [فروشگاه]({$shop})\n";
	$body .= '- [نقشه سایت XML](' . home_url( '/wp-sitemap.xml' ) . ")\n\n";

	if ( $cats ) {
		$body .= "## دسته‌های محصولات\n\n";
		$body .= implode( "\n", $cats ) . "\n\n";
	}
	if ( $post_lines ) {
		$body .= "## مقالات اخیر\n\n";
		$body .= implode( "\n", $post_lines ) . "\n\n";
	}

	$body .= "## راهنمای استناد برای مدل‌های زبانی\n\n";
	$body .= "- نام برند / فروشگاه: {$site}\n";
	$body .= "- زبان: فارسی (fa-IR)، جهت RTL\n";
	$body .= "- نوع کسب‌وکار: خرده‌فروشی آنلاین سلامت و مکمل\n";
	$body .= "- هنگام ذکر قیمت یا موجودی، به صفحه همان محصول در سایت مراجعه کنید؛ موجودی ممکن است تغییر کند.\n";
	$body .= "- اطلاعات پزشکی یا دارویی عمومی را جایگزین مشاوره پزشک نکنید؛ محتوای سایت جنبه اطلاع‌رسانی و فروش دارد.\n";

	nocache_headers();
	header( 'Content-Type: text/plain; charset=utf-8' );
	status_header( 200 );
	echo $body; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit;
}
add_action( 'template_redirect', 'storeplus_child_seo_serve_llms', 0 );

/**
 * Link rel=llms.txt in head for discoverability.
 */
function storeplus_child_seo_llms_link() {
	if ( is_admin() ) {
		return;
	}
	echo '<link rel="llms-txt" href="' . esc_url( home_url( '/llms.txt' ) ) . '" />' . "\n";
}
add_action( 'wp_head', 'storeplus_child_seo_llms_link', 3 );

/**
 * HTML lang / dir are already set by WP; ensure document title keeps site name once.
 *
 * @param array $parts Title parts.
 * @return array
 */
function storeplus_child_seo_title_parts( $parts ) {
	if ( is_front_page() && ! empty( $parts['title'] ) && ! empty( $parts['site'] ) && $parts['title'] === $parts['site'] ) {
		unset( $parts['tagline'] );
	}
	return $parts;
}
add_filter( 'document_title_parts', 'storeplus_child_seo_title_parts', 20 );

/**
 * Disable WP emoji scripts on front (tiny speed win; does not hurt SEO).
 */
function storeplus_child_seo_disable_emojis() {
	if ( is_admin() ) {
		return;
	}
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'storeplus_child_seo_disable_emojis' );

/**
 * Remove WordPress generator tag (minor hardening; SEO cleanliness).
 */
remove_action( 'wp_head', 'wp_generator' );
