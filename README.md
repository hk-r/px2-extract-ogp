hk-r/px2-extract-ogp
======================

[Pickles 2](http://pickles2.pxt.jp/) 用のプラグインです。
OGP情報を自動生成し、OGタグを設定します。

## 導入手順 - Setup

### 1. `composer.json` に `hk-r/px2-extract-ogp` を設定する

`require` の項目に、`hk-r/px2-extract-ogp` を追加します。

```
{
	〜 中略 〜
    "require": {
        "php": ">=5.3.0" ,
        "pickles2/px-fw-2.x": "^2.0",
        "hk-r/px2-extract-ogp": "^1.0"
    },
	〜 中略 〜
}
```


### 2. composer update を実行する

追加したら、`composer update` を実行して変更を反映することを忘れずに。

```
$ composer update
```


### 3. `config.php` に、設定を追加する

設定ファイル `config.php` (通常は `./px-files/config.php`) を編集します。

- スキーマの設定  
`$conf->scheme` に、スキーマを設定します。  
`$conf->scheme` を設定しないと、望ましい結果が得られないことがありますので、正しく設定してください。

```php
	$conf->scheme = 'http';
```

- ドメインの設定  
`$conf->domain` に、ドメインを設定します。  
`$conf->domain` を設定しないと、望ましい結果が得られないことがありますので、正しく設定してください。

```php
	$conf->domain = 'www.example.com';
```

- OGP自動生成の処理追加  
`$conf->funcs->processor->html` のテーマの処理の後に、本プラグインの処理 `'hk\pickles2\extractOgp\extract::exec'` を追加します。

```php
	$conf->funcs->processor->html = array(
		// テーマ
		'theme'=>'tomk79\pickles2\multitheme\theme::exec('.json_encode([
			'param_theme_switch'=>'THEME',
			'cookie_theme_switch'=>'THEME',
			'path_theme_collection'=>'./px-files/themes/',
			'attr_bowl_name_by'=>'data-contents-area',
			'default_theme_id'=>'pickles2'
		]).')' ,

		// OGPタグ自動抽出
		'hk\pickles2\extractOgp\extract::exec' ,
	);
```

- OGP設定用のモジュールを追加  
`$conf->plugins->px2dt->paths_module_template` に、モジュール定義 `"OGP" => "./vendor/hk-r/px2-extract-ogp/modules/"` を追加します。

```php
	$conf->plugins->px2dt->paths_module_template = [
		"OGP" => "./vendor/hk-r/px2-extract-ogp/modules/",
	];
```

## 更新履歴 - Change log

### hk-r/px2-extract-ogp 1.0.0 (2017年10月19日)

- og:imageの後ろだけ改行されない問題を修正
- CIサービス(Travis CI)の設定ファイルを追加

### hk-r/px2-extract-ogp 1.0.0-beta.1 (2017年9月27日)

- Initial Release.


## ライセンス - License

MIT License


## 作者 - Author

- (C)Kyota Hiyoshi <hiyoshi-kyota@imjp.co.jp>


## for Developer

### Test

```
$ ./vendor/phpunit/phpunit/phpunit
```
