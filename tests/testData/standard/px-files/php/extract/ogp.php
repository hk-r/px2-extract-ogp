<?php

/**
 * HTML Validator
 */
namespace hk\pickles2\extractOgp;

//use Sunra\PhpSimple\HtmlDomParser;

/**
 * processor "extract" class
 */
class extract{

    /**
     * 変換処理の実行
     * @param object $px Picklesオブジェクト
     */
    public static function exec( $px, $json ){

        //require_once( './vendor/sunra/php-simple-html-dom-parser/Src/Sunra/PhpSimple/simplehtmldom_1_5/simple_html_dom.php' );

        foreach( $px->bowl()->get_keys() as $key ){
            $src = $px->bowl()->pull( $key );
            
            // head要素の最後にスクリプトを追加
            if( preg_match( '/<\/head>/is', $src ) ){

                $src = mb_convert_encoding($src, mb_internal_encoding(), "auto" ); 

                // if ( preg_match( '/class="og-title">(.*?)<\//is', $src, $matches) ) {
                if ( $og_title = $px->bowl()->pull( 'og:title' ) ) {
                    
                    $src = preg_replace('/<\/head>/is', '<meta property="og:title" content="' . $og_title . '" />'.'</head>', $src);
                    
                } else {
                    // マッチしない場合の処理
                    
                }

                // if ( preg_match( '/class="og-description">(.*?)<\//is', $src, $matches) ) {
                if ( $og_description = $px->bowl()->pull( 'og:description' ) ) {

                    $src = preg_replace('/<\/head>/is', '<meta property="og:description" content="' . $og_description . '" />'.'</head>', $src);

                } else {
                    // マッチしない場合の処理
                    
                }

                // if ( preg_match( '/class="og-image">(.*?)<\//is', $src, $matches) ) {
                if ( $og_image = $px->bowl()->pull( 'og:image' ) ) {

                    if ( preg_match( '/<img.*src\s*=\s*[\"|\'](.*?)[\"|\'].*>/i', $og_image, $matches ) ) {

                        $src = preg_replace('/<\/head>/is', '<meta property="og:image" content="' . $matches[1] . '" />'.'</head>', $src);
                    }                    

                } else {
                    // マッチしない場合の処理
                    
                }

                // if ( preg_match( '/class="og-type">(.*?)<\//is', $src, $matches) ) {
                if ( $og_type = $px->bowl()->pull( 'og:type' ) ) {

                    $src = preg_replace('/<\/head>/is', '<meta property="og:type" content="' . $og_type . '" />'.'</head>', $src);

                } else {
                    // マッチしない場合の処理
                    
                }

                // if ( preg_match( '/class="og-url">(.*?)<\//is', $src, $matches) ) {
                if ( $og_url = $px->bowl()->pull( 'og:url' ) ) {

                    $src = preg_replace('/<\/head>/is', '<meta property="og:url" content="' . $og_url . '" />'.'</head>', $src);

                } else {
                    // マッチしない場合の処理
                    
                }

                // if ( preg_match( '/class="og-site_name">(.*?)<\//is', $src, $matches) ) {
                if ( $og_site_name = $px->bowl()->pull( 'og:site_name' ) ) {

                    $src = preg_replace('/<\/head>/is', '<meta property="og:site_name" content="' . $og_site_name . '" />'.'</head>', $src);

                } else {
                    // マッチしない場合の処理
                    
                }
            }
            
            $px->bowl()->replace( $src, $key );
        }

        return true;
    }

    /**
     * 変換処理の実行
     * @param object $px Picklesオブジェクト
     */
    public static function exec2( $px, $json ){
        
        //require_once( './vendor/sunra/php-simple-html-dom-parser/Src/Sunra/PhpSimple/simplehtmldom_1_5/simple_html_dom.php' );

        // $page_info = $px->site()->get_current_page_info();
        // var_dump( json_decode( $page_info ) );
        
        foreach( $px->bowl()->get_keys() as $key ){
            $src = $px->bowl()->pull( $key );



            $src = $src . "abc";
            
            // head要素の最後にスクリプトを追加
            //if( preg_match( '/<\/head>/is', $src ) ){
            if( preg_match( '/<\/body>/is', $src ) ){

                $src = mb_convert_encoding($src, mb_internal_encoding(), "auto" ); 

                //if ( preg_match( '/<(.*?) class="theme_mainblock">(.*?)<(.*?)>/is', $src, $matches) ) {
                if ( preg_match( '/class="og-title">(.*?)<\//is', $src, $matches) ) {

                    //$src = preg_replace('/<\/head>/is', $matches[1] . '</head>', $src);

                    $src = preg_replace('/<\/body>/is', $matches[1] . '</body>', $src);

                    //return $matches[1];
                } else {
                    //return false;

                    $src = preg_replace('/<\/body>/is', 'test</body>', $src);
                }
                
                //$html = HtmlDomParser::str_get_html( $str );

                //$src = preg_replace('/<\/body>/is', $value.'</body>', $src);

                /* サイトマップを検索して置換する処理
                $infos = $px->site()->get_current_page_info();

                foreach ( $infos as $info_key => $info ) {
                    
                    if( preg_match( '/og:/', $info_key ) ){

                        // head要素の閉じタグが見つかる場合、
                        // その手前に挿入
                        $src = preg_replace('/<\/head>/is', '<meta property="' . $info_key . '" content="' . $info . '" />'.'</head>', $src);
                    }
                }
                */
            }
            
            $px->bowl()->replace( $src, $key );
        }

        return true;
    }
}