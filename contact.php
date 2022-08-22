<?php
/*
Template Name: Contact 〜お問合せ〜
*/
?>

<!-- ヘッダー -->
<?php get_header(); ?>
    <!-- メニュー -->
    <?php get_template_part('content','menu'); ?>
		<div id="main">

			<!-- Contact -->
			<section id="contact" class="site-width">
				<h1 class="title"><?php get_the_title(); ?></h1>
                <?php
                    if(have_posts()) : //wordpressループ
                        while(have_posts()) : the_post(); //繰り返し処理開始
                ?>
                            <div id="post-<?php the_ID(); ?>" <?php  post_class(); ?>>
                                <?php  the_content(); ?>
                            </div>
                <?php
                        endwhile;
                    else :
                ?>
                        <div class="post"></div>
                        <h2>記事はありません</h2>
                        <p>お探しの記事はありませんでした</p>
                <?php
                    endif;
                ?>
			</section>


		</div>
<!-- フッター -->
<?php  get_footer(); ?>
