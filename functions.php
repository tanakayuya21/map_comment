<?php
define( 'WP_SCSS_ALWAYS_RECOMPILE', true );
// アイキャッチ画像
add_filter( 'show_admin_bar', '__return_false' );
// カスタムナビゲーションメニュー
add_theme_support('menus');

function addtional_styles() {
  if( is_front_page() ){
    wp_enqueue_style( 'front-css', get_template_directory_uri() . '/front-style.scss' );
  }
}
add_action( 'wp_enqueue_scripts', 'addtional_styles' );


function post_has_archive( $args, $post_type ) {
	if ( 'post' == $post_type ) {
		$args['rewrite'] = true;
		$args['has_archive'] = 'blogs'; //任意のスラッグ名
	}
	return $args;
}

add_filter( 'wpcf7_load_js', '__return_false' );
add_filter( 'register_post_type_args', 'post_has_archive', 10, 2 );
function my_custom_posts_custom_column( $column, $post_id ) {
switch ( $column ) {
	case 'old_price':
		$post_meta = get_post_meta( $post_id, 'old_price', true );
		if ( $post_meta ) {
			echo $post_meta;
		} else {
			echo ''; //値が無い場合
		}
	break;
	case 'new_price':
			$post_meta = get_post_meta( $post_id, 'new_price', true );
		if ( $post_meta ) {
			echo $post_meta;
		} else {
			echo ''; //値が無い場合
		}
	break;
	}
}
add_action( 'manage_cosmetic_posts_custom_column' , 'my_custom_posts_custom_column', 10, 2 );

function leaflet_enqueue_styles() {
    wp_enqueue_style( 'leaflet-style', '//unpkg.com/leaflet@1.3.1/dist/leaflet.css', NULL, NULL );
}
add_action( 'wp_enqueue_scripts', 'leaflet_enqueue_styles' );
/* 先にスタイルシートを読み込んでからJavaScriptを読み込む */
function leaflet_enqueue_script() {
    wp_enqueue_script( 'leaflet-js', '//unpkg.com/leaflet@1.3.1/dist/leaflet.js', NULL, NULL );
}
add_action('wp_enqueue_scripts', 'leaflet_enqueue_script');

?>
