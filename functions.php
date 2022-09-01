<?php

define( 'WP_DEBUG_DISPLAY', false );
define( 'WP_DEBUG_LOG', true );

//カスタムヘッダー画像の設置
$custom_header_defaults = array(
    'default-image' => get_bloginfo('template_url').'/images/headers/logo.png',
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
    add_meta_box('top_img_id', 'トップ画像URL入力欄', 'custom_area4', 'page', 'normal');
    add_meta_box('about_id', 'ABOUT入力欄', 'custom_area', 'page', 'normal');
    add_meta_box('recruit_id', 'RECRUIT入力欄', 'custom_area2', 'page', 'normal');
    add_meta_box('map_id', 'map入力値', 'custom_area3', 'page', 'normal');
}

//管理画面に表示される内容
function custom_area() {
    global $post;

    //get_post_metaの引数は、1、3つ目は固定、真ん中のはDBに登録される名前らしい
    echo 'コメント :<textarea cols="50" row="5" name="about_msg">'.get_post_meta($post->ID, 'about', true).'</textarea><br>';
}
function custom_area2() {
    global $post;

    echo '<table>';
    for ($i=1; $i<8; $i++) {
        echo
            '<tr>
                <td>
                    info'.$i.':
                </td>
                <td>
                    <input cols="50" rows="5" name="recruit_info'.$i.'" value="'.get_post_meta($post->ID, 'recruit_info'.$i, true).'">
                </td>
            </tr>';
    }
    echo '</table>';
}
function custom_area3() {
    global $post;

    echo 'マップ :<textarea cols="50" rows="5" name="map">'.get_post_meta($post->ID, 'map', true).'</textarea><br>';
}
function custom_area4(){
    global $post;

    echo 'トップ画像URL :<input type="text" name="img-top" value="'.get_post_meta($post->ID, 'img-top',true).'">';
}

//投稿ボタンを押した際のデータ更新と保存
function save_custom_postdata($post_id){
    $about_msg = '';
    $recruit_data = '';
    $map = '';
    $img_top = '';

    //トップイメージ
    if(isset($_POST['img-top'])){
        $img_top = $_POST['img-top'];
    }
    if ($img_top != get_post_meta($post_id, 'img-top', true)) {
        update_post_meta($post_id, 'img-top', $img_top);
    } else {
        delete_post_meta($post_id, 'img-top', get_post_meta($post_id, 'img-top', true));
    }

    //about
    if(isset($_POST['about_msg'])){
        $about_msg = $_POST['about_msg'];
    }
    //内容がかわっていた場合、保存していた情報を更新する
    if ($about_msg != get_post_meta($post_id, 'about',true)) {
        update_post_meta($post_id, 'about', $about_msg);
    } else if ($about_msg == '') {
        delete_post_meta($post_id, 'about', get_post_meta($post_id, 'about', true));
    }

    //RECRUIT
    for ($i=1; $i<=8; $i++) {
        if (isset($_POST['recruit_info'.$i])){
            $recruit_data = $_POST['recruit_info'.$i];
        }

        if ($recruit_data != get_post_meta($post_id, 'recruit_info'.$i, true)) {
            update_post_meta($post_id, 'recruit_info'.$i, $recruit_data);
        } else if ($recruit_data != '') {
            delete_post_meta($post_id, 'recruit_info'.$i, get_post_meta($post_id, 'recruit_info'.$i, true));
        }
    }
    //map
    if (isset($_POST['map'])) {
        $map = $_POST['map'];
    }
    if ($map != get_post_meta($post_id, 'map', true)) {
        update_post_meta($post_id, 'map', $map);
    } else {
        delete_post_meta($post_id, get_post_meta($post_id, 'map', true));
    }
}
//-----------------
//カスタムウィジェット
//-----------------
//ウィジェットエリアを作成する関数がどれなのかを登録する
add_action('widgets_init', 'my_widgets_area');
//add_action( 'widgets_init', function(){register_widget( 'my_widgets_area' );});
//ウィジェット自体の作成するクラスがどれなのかを登録する
//add_action('widgets_init', create_function('', 'return register_widget("my_widgets_item1");'));
//memo:上がサンプル。PHP 5.3 以上から、ウィジェット作成する際には下記の書き方に仕様が変更されています。
add_action( 'widgets_init', function(){register_widget( 'My_Widget' );});

//ウィジェットエリアを作成する

function my_widgets_area() {
    register_sidebar(array(
        'name' => 'メリットエリア', //memo:管理画面上の表示名
        'id' => 'widget_merit',
        'before_widget' =>'<div>',  //実際のウィジェットエリアを囲むhtmlタグの指定
        'after_widget' => '</div>'
    ));
}

//ウィジェット自体を作成する
class My_Widget extends WP_Widget 
{
    //初期化(管理画面で表示するウィジェットの名前を設定する)

    public function __construct() {
        parent::__construct(false, $name = 'メリットウィジェット');
    }

/*
    public function My_Widget() {
        parent::WP_Widget(false, $name='メリットウィジェット');
        //parent::__construct(false, $name='メリットウィジェット');
    }
*/

    //ウィジェットの入力文字を作成する処理
    //memo:$instanceは、WordPressからの情報を格納するための変数らしい
    function form($instance)
    {
        //入力された情報をサニタイズして変数へ格納
        $title = esc_attr($instance['title']);
        $body = esc_attr($instance['body']);
?>

        <p>
            <!-- memo:for属性は、ID属性を指定する属性のこと -->
            <label for="<?php echo $this->get_field_id('title'); ?>">
                <?php echo 'タイトル'; ?>
            </label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text"
             value="<?php echo $title; ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('body'); ?>">
                <?php echo '内容:'; ?>
            </label>
            <textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('body'); ?>" name="<?php echo $this->get_field_name('body'); ?>">
                <?php echo $body; ?>
            </textarea>
        </p>
<?php 
    }
    //ウィジェットに入力された情報を保存する処理

    function update($new_instance, $old_instance){
        $instance = $old_instance;
        $instance['title'] = strip_tag($new_instance['title']); //これもサニタイズ(php,htmlタグを取り除く)
        //$instance['title']( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['body'] = trim($new_instance['body']);    //先頭と最後尾の空白を取り除く
        //$instance['body']( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';


        return $instance;
    }

    //管理画面から入力されたウィジェットを画面に表示する処理
    function widget($args, $instance)
    {
        //配列を変数に展開
        //memo:今回はつかわないが、extractは、連想配列を変数に変換するもの(連想配列のキーが変数になり、値はvalueのものが入る)
        extract($args);

        //ウィジェットから入力された情報を取得
        $title = apply_filters('widget_title', $instance['title']);
        $body = isset($instance['body'])?esc_attr($instance['body']):'';
        //$body = apply_filters('widget_body', $instance['body']);
 
        //ウィジェットから入力された情報がある場合、htmlを表示する
        if ($title) {
?>
            <section class="panel">
                <h2><?php echo $title; ?></h2>
                <p>
                    <?php echo $body; ?>
                </p>
            </section>
<?php 
        }
    }
}
