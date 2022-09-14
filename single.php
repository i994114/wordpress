<!-- ヘッダー -->
<?php get_header(); ?>

<!-- メニュー -->
<?php get_template_part('content', 'menu'); ?>
    <!-- やり残し：サイドバーが崩れるのと、両端記事のページネーションが狂う点 -->
		<div id="main">

			<!-- blog_list -->
			<section id="blog" class="site-width">
				<h1 class="title">BLOG</h1>
				<div id="content" class="article">

				</div>
                <?php if(have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                            <article class="article-item">
                                <h2 class="article-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                <h3 style="font-size:80%;"><?php the_author_meta(); ?> <?php the_time("Y年m月j日"); ?> <?php single_cat_title('カテゴリー: '); ?></h3>
                                <p class="article-body">
                                    <?php the_content(); ?>
                                </p>
                            </article>
                    <?php endwhile;?>
                    
                    <div class="pagenation">
						<ul>
                            <!-- 前のページへいくのと、次のページへいくリンク。2つ目の引数はその文字列 -->
                            <!-- ここはとある1記事の下に表示されるので、いつものページネーションではなく、このようになっている -->
                            <li class="prev"><?php previous_post_link('%link', 'PREV'); ?></li>
                            <li class="next"><?php next_post_link('%link', 'NEXT'); ?></li>
						</ul>
					</div>

                    <!-- comment -->
                    <?php comment_form(); ?>
                <?php else : ?>
					<h2 class="title">記事がみつかりませんでした</h2>
                    <p>検索でみつかるかもしれません</p>
                    <?php get_search_form(); ?>
                <?php endif; ?>

                <!-- サイドバー -->
                <?php get_sidebar(); ?>
			</section>


		</div>
<?php get_footer(); ?>