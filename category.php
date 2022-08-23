<?php
get_header();
?>
	<body>
		<header class="site-width">
			<h1><a href="index.html">WEBUKATU</a></h1>
			<nav id="top-nav">
				<ul>
					<li><a href="index.html">HOME</a></li>
					<li><a href="http://webukatu.com/sample/html_practice/#about">ABOUT</a></li>
					<li><a href="http://webukatu.com/sample/html_practice/#merit">MERIT</a></li>
					<li><a href="http://webukatu.com/sample/html_practice/#recruit">RECRUIT</a></li>
					<li><a href="contact.html">CONTACT</a></li>
					<li><a href="map.html">INFO</a></li>
					<li><a href="blog_list.html">BlOG</a></li>
				</ul>
			</nav>
		</header>
		<div id="main">

			<!-- blog_list -->
			<section id="blog_list" class="site-width">
				<h1 class="title">BLOG</h1>
				<div id="content" class="article">
					<!-- 記事のループ -->
					<?php get_template_part('loop'); ?>


				<?php if (function_exists("pagination")) pagination($additional_loop->max_num_pages); ?>

				</div>
                <!--sidebar-->
				<?php  get_sidebar(); ?>
			</section>


		</div>
<?php  get_footer(); ?>
