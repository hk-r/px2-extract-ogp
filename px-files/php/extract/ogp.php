<?php

/**
 * OGP Extract
 */
namespace hk\pickles2\extractOgp;

/**
 * processor "extract" class
 */
class extract{

    /**
     * 変換処理の実行
     * @param object $px Picklesオブジェクト
     */
    public static function exec( $px, $json ){

        foreach( $px->bowl()->get_keys() as $key ){

            $src = $px->bowl()->pull( $key );

            // head要素の最後にmetaを追加
            if( preg_match( '/<\/head>/is', $src ) ){

                $custom_property_collection = array();

                // domain
                $domain = $px->get_domain();
                if ( !strlen($domain) ) {
                    $px->error('[px2-extract-ogp] $conf->domain is EMPTY. Check config.php and set domain name of your website. (ex: www.example.com, 127.0.0.1, hogefuga.com:8080, etc...)');
                    $domain = '127.0.0.1';
                }

                $src = mb_convert_encoding( $src, mb_internal_encoding(), "auto" );

                // custom property
                if ( ( $og_custom_property = $px->bowl()->pull( 'og:custom-property' )) &&
                     ( $og_custom_content = $px->bowl()->pull( 'og:custom-content' )) ) {

                    $og_custom_property_ary = json_decode( $og_custom_property );
                    $og_custom_content_ary = json_decode( $og_custom_content );
                    $count = 0;

                    foreach ( $og_custom_property_ary as $og_custom_property_val ) {

                        if ( $og_custom_property_val && $og_custom_content_ary[$count] ) {

                            if ( (empty( $custom_property_collection )) ||
                                 (FALSE === array_search( $og_custom_property_val, $custom_property_collection ))) {
                                $custom_property_collection[] = $og_custom_property_val;
                            }

                            $src = preg_replace( '/<\/head>/is', '<meta property="' . htmlspecialchars( $og_custom_property_val ) . '" content="' . htmlspecialchars( $og_custom_content_ary[$count] ) . '" />' . "\n" . '</head>', $src );
                        }

                        $count++;
                    }

                }

                // og:title property
                if ( (empty( $custom_property_collection )) ||
                     (FALSE === array_search( 'og:title', $custom_property_collection ))) {

                    if ( $og_title = $px->bowl()->pull( 'og:title' ) ) {

                        $src = preg_replace( '/<\/head>/is', '<meta property="og:title" content="' . htmlspecialchars( $og_title ) . '" />' . "\n" . '</head>', $src );

                    }

                }

                // og:description property
                if ( (empty( $custom_property_collection )) ||
                     (FALSE === array_search( 'og:description', $custom_property_collection ))) {

                    if ( $og_description = $px->bowl()->pull( 'og:description' ) ) {

                        $src = preg_replace( '/<\/head>/is', '<meta property="og:description" content="' . htmlspecialchars( $og_description ) . '" />' . "\n" . '</head>', $src );

                    }

                }

                // og:image property
                if ( (empty( $custom_property_collection )) ||
                     (FALSE === array_search( 'og:image', $custom_property_collection ))) {

                    if ( $og_image = $px->bowl()->pull( 'og:image' ) ) {

                        if ( preg_match( '/<img.*src\s*=\s*[\"|\'](.*?)[\"|\'].*>/i', $og_image, $matches ) ) {

                            // 指定されたファイルの拡張子が画像ファイルか判定
                            if ( preg_match( "/.*?\.jpg|.*?\.png|.*?\.gif|.*?\.jpeg/i", $matches[1]) ) {


                                $img_path = preg_replace( '/^\.\//', '', $px->href( $matches[1] ) ) ;
                                if( !preg_match( '/^\//', $img_path ) ){
                                    $img_path = $px->fs()->get_realpath( dirname($px->req()->get_request_file_path()) . '/' . $img_path );
                                }
                                $src = preg_replace( '/<\/head>/is', '<meta property="og:image" content="' . htmlspecialchars( $px->get_scheme() . '://' . $domain . $px->href( $img_path ) ) . '" />' . '</head>', $src );

                            }

                        }

                    }

                }

                // og:type property
                if ( (empty( $custom_property_collection )) ||
                     (FALSE === array_search( 'og:type', $custom_property_collection ))) {

                    if ( $og_type = $px->bowl()->pull( 'og:type' ) ) {

                        $src = preg_replace( '/<\/head>/is', '<meta property="og:type" content="' . htmlspecialchars( $og_type ) . '" />' . "\n" . '</head>', $src );

                    }

                }

                // og:url property
                if ( (empty( $custom_property_collection )) ||
                     (FALSE === array_search( 'og:url', $custom_property_collection ))) {

                    $src = preg_replace( '/<\/head>/is', '<meta property="og:url" content="' . htmlspecialchars( $px->get_scheme() . '://' . $domain . $px->href( $px->req()->get_request_file_path() ) ) . '" />' . "\n" . '</head>', $src );

                }

                // og:site_name property
                if ( (empty( $custom_property_collection )) ||
                     (FALSE === array_search( 'og:site_name', $custom_property_collection ))) {

                    $src = preg_replace( '/<\/head>/is', '<meta property="og:site_name" content="' . htmlspecialchars( $px->conf()->name ) . '" />' . "\n" . '</head>', $src );

                }

            }

            $px->bowl()->replace( $src, $key );
        }

        return true;
    }

}
