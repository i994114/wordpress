<?php
/*
Template Name: INFO 〜インフォメーション〜
*/
?>

<!-- header -->
<?php get_header(); ?>
    <!-- メニュー -->
    <?php get_template_part('content','menu');  ?>
		<div id="main">

			<!-- blog_list -->
			<section id="map">
				<h1 class="title"><?php  echo get_the_title(); ?></h1>
				<div id="content">
					<?php  echo get_post_meta($post->ID, 'map', 'true'); ?>
				</div>					
			</section>
			<section id="shop_info" class="site-width">
                
                <?php
                    if (have_posts()) : //WordPressループ
                        while(have_posts()) : the_post(); //繰り返し処理開始
                ?>
                            <div id="post-<?php the_ID();  ?>" <?php post_class(); ?>>
                                <?php the_content(); ?>
                            </div>
                <?php
                        endwhile;
                    else : //ここから記事がみつからなかったときの処理
                ?>
                        <div class="post">
                            <h2>記事はありません</h2>
                            <p>お探しの記事はみつかりませんでした</p>
                        </div>
                <?php endif; //WordPressループ終了 ?>
			</section>
		</div>
    <!-- footer -->
    <?php  get_footer(); ?>
