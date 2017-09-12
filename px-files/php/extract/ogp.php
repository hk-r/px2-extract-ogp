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

            // head要素の最後にスクリプトを追加
            if( preg_match( '/<\/head>/is', $src ) ){

                $src = mb_convert_encoding( $src, mb_internal_encoding(), "auto" );

                // if ( preg_match( '/class="og-title">(.*?)<\//is', $src, $matches) ) {
                if ( $og_title = $px->bowl()->pull( 'og:title' ) ) {

                    $src = preg_replace( '/<\/head>/is', '<meta property="og:title" content="' . $og_title . '" />'.'</head>', $src );

                }

                // if ( preg_match( '/class="og-description">(.*?)<\//is', $src, $matches) ) {
                if ( $og_description = $px->bowl()->pull( 'og:description' ) ) {

                    $src = preg_replace( '/<\/head>/is', '<meta property="og:description" content="' . $og_description . '" />'.'</head>', $src );

                }

                // if ( preg_match( '/class="og-image">(.*?)<\//is', $src, $matches) ) {
                if ( $og_image = $px->bowl()->pull( 'og:image' ) ) {

                    if ( preg_match( '/<img.*src\s*=\s*[\"|\'](.*?)[\"|\'].*>/i', $og_image, $matches ) ) {

                        // 指定されたファイルの拡張子が画像ファイルか判定
                        if ( preg_match( "/.*?\.jpg|.*?\.png|.*?\.gif|.*?\.jpeg/i", $matches[1]) ) {

                            $src = preg_replace( '/<\/head>/is', '<meta property="og:image" content="' . $px->conf()->scheme.'://'.$px->conf()->domain.$px->href( $matches[1] ) . '" />'.'</head>', $src );

                        }

                    }

                }

                // if ( preg_match( '/class="og-type">(.*?)<\//is', $src, $matches) ) {
                if ( $og_type = $px->bowl()->pull( 'og:type' ) ) {

                    $src = preg_replace( '/<\/head>/is', '<meta property="og:type" content="' . $og_type . '" />'.'</head>', $src );

                }

                // if ( preg_match( '/class="og-url">(.*?)<\//is', $src, $matches) ) {
                if ( $og_url = $px->bowl()->pull( 'og:url' ) ) {

                    $src = preg_replace( '/<\/head>/is', '<meta property="og:url" content="' . $og_url . '" />'.'</head>', $src );

                } else {

                    $src = preg_replace( '/<\/head>/is', '<meta property="og:url" content="' . $px->conf()->scheme.'://'.$px->conf()->domain.$px->href( $px->req()->get_request_file_path() ) . '" />'.'</head>', $src );

                }

                // if ( preg_match( '/class="og-site_name">(.*?)<\//is', $src, $matches) ) {
                if ( $og_site_name = $px->bowl()->pull( 'og:site_name' ) ) {

                    $src = preg_replace( '/<\/head>/is', '<meta property="og:site_name" content="' . $og_site_name . '" />'.'</head>', $src );

                }


                // カスタム
                if ( ( $og_custom_property = $px->bowl()->pull( 'og:custom-property' )) &&
                     ( $og_custom_content = $px->bowl()->pull( 'og:custom-content' )) ) {

                    $og_custom_property_ary = json_decode($og_custom_property);
                    $og_custom_content_ary = json_decode($og_custom_content);
                    $count = 0;

                    foreach ($og_custom_property_ary as $og_custom_property_val) {

                        if ($og_custom_property_val && $og_custom_content_ary[$count]) {

                            $src = preg_replace( '/<\/head>/is', '<meta property="' . $og_custom_property_val . '" content="' . $og_custom_content_ary[$count] . '" />'.'</head>', $src );

                        }

                        $count++;
                    }
                }

            }

            $px->bowl()->replace( $src, $key );
        }

        return true;
    }
}
