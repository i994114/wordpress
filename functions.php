<?php
//カスタムヘッダー画像の設置
$custom_header_defaults = array(
    'default-image' =>get_bloginfo('template_url').'/images/headers/logo.png',
    'header-text' => false, //ヘッダー画面上にテキストをかぶせる
);
//カスタムヘッダー機能を有効にする
add_theme_support('custom-header', $custom_header_defaults);

//カスタムメニュー変更
register_nav_menu('mainmenu', 'メインメニュー');
?>