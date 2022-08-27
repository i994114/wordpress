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

function pagination($pages = '', $range = 2){

    $showitems = ($range * 2) + 1; //表示するページ数(5ページを表示) ※これはページネーションのアイコンの数のこと
    
    global $paged;      //WordPressで準備されている変数。現在のページ数
    if(empty($paged)) $paged = 1;   //デフォルトのページ
    
    if($pages == ''){
        global $wp_query;
        $pages = $wp_query->max_num_pages;  //全ページ数を取得

        if(!$pages){    //全ページが空の場合は1にする処理
            $pages = 1;
        }
    }
    
    if($pages != 1){    //全ページが1でない場合はページネーションを表示する
        echo "<div class=\"pagenation\">\n";
        echo "<ul>\n";
        
        //Prev 現在のページ値が1より大きい場合は表示
        if($paged > 1) echo "<li class=\"prev\"><a href='".get_pagenum_link($paged - 1)."'>Prev</a></li>\n";
        
        for($i=1; $i <= $pages; $i++){
            
            if($pages != 1 && (!($i >= $paged + $range+1 || $i <= $paged-$range-1) || $pages <= $showitems))
            {
                echo ($paged == $i)? "<li class=\"active\">".$i."</li>\n":"<li><a href='".get_pagenum_link($i)."'>".$i."</a></li>\n";
            }
        }
        //Next:総ページ数より現在のページ値が小さい場合は表示
        if($paged < $pages) echo "<li class=\"next\"><a href=\"".get_pagenum_link($paged + 1)."\">Next</a></li>\n";
        echo "</ul>\n";
        echo "</div>\n";
    } 
}
//---------------
//カスタムフィールド
//---------------
//投稿ページへ表示するカスタムボックスを定義する(admin_menuは決め打ち。こう入力すること)
add_action('admin_menu', 'add_custom_inputbox');
//追加した表示項目のデータ更新、保存のためのアクションフック(save_postは決め打ち)
add_action('save_post','save_custom_postdata');

//入力項目がどの投稿タイプのページに表示されるかの設定
function add_custom_inputbox() {
    //第1引数：編集画面のhtmlに表示されるid属性名
    //第2引数：管理画面に表示されるアスタムフィールド名
    //第3引数：メタボックスの中に出力される関数名
    //第4引数：管理画面に表示するカスタムフィールドの場所(postなら投稿、pageなら固定リンク)
    //第5引数：配置される順序
    add_meta_box('about_id', 'ABOUT入力欄', 'custom_area', 'page', 'normal');
}

//管理画面に表示される内容
function custom_area() {
    global $post;

    //get_post_metaの引数は、1、3つ目は固定、真ん中のはDBに登録される名前らしい
    echo 'コメント :<textarea cols="50" row="5" name="about_msg">'.get_post_meta($post->ID, 'about', true).'</textarea><br>';
}

//投稿ボタンを押した際のデータ更新と保存
function save_custom_postdata($post_id){
    $about_msg = '';

    //カスタムフィールドに入力された情報を取り出す
    if(isset($_POST['about_msg'])){
        $about_msg = $_POST['about_msg'];
    }

    //内容がかわっていた場合、保存していた情報を更新する
    if ($about_msg != get_post_meta($post_id, 'about',true)) {
        update_post_meta($post_id, 'about', $about_msg);
    } else {
        delete_post_meta($post_id, 'about', get_post_meta($post_id, 'about', true));
    }
}

?>