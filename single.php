<?php global $options; ?>
<?php get_header(); ?>
	<div class="grid-m0s5 clearfix">
		<div class="col-main">
			<div class="main-wrap">
				<div class="article clearfix">
<?php while (have_posts()) : the_post(); ?>
					<div class="post clearfix">
						<div class="review">
							<a href="<?php the_permalink(); ?>#comment" title="共 <?php comments_number('0', '1', '%'); ?> 条评论"><?php comments_number('0', '1', '%'); ?></a>
						</div>
						<div class="entry">
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<div class="tag">
<?php $tags = get_the_tags(); if ($tags) : foreach($tags as $tag) : ?>
								<a class="tag-<?php echo rand(1, 5); ?>" href="<?php echo get_tag_link($tag->term_id); ?>"><?php echo $tag->name; ?></a>
<?php endforeach; endif; ?>
							</div>
							<div class="desc">作者 <a href="<?php the_author_url(); ?>" target="_blank"><?php the_author(); ?></a><s>/</s>分类 <?php the_category(', '); ?><s>/</s>发布于  <?php the_time('Y-m-d H:i'); ?></div>
							<div class="view">
								<a title="共 <?php get_post_views($post->ID); ?> 次阅读" href="<?php the_permalink(); ?>"><?php get_post_views($post->ID); ?></a>
							</div>
						</div>
					</div>
<?php if ($options['article_a1']['active']) : ?>
					<div class="google">
						<div class="adsense loading">
<?php echo $options['article_a1']['content']; ?>
						</div>
						<s></s>
					</div>
<?php endif; ?>
					<div class="text">
<?php the_content(); ?>
					</div>
<?php if ($options['article_a2']['active']) : ?>
					<div class="google">
						<div class="adsense loading">
<?php echo $options['article_a2']['content']; ?>
						</div>
						<s></s>
					</div>
<?php endif; ?>
					<div class="creative-commons">
						<strong>版权所有，转载请注明出处。</strong>
						<blockquote>转载自 &lt;a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"&gt;<?php the_title(); ?> | 芒果小站&lt;/a&gt;</blockquote>
					</div>
					<div class="sibling-posts">
						<h3><?php previous_post_link('<strong>上一篇</strong> %link'); ?></h3>
						<h3><?php next_post_link('<strong>下一篇</strong> %link'); ?></h3>
					</div>
<?php endwhile; ?>
					<div class="feed-me">如果喜欢这篇文章，欢迎<a href="<?php bloginfo('rss2_url'); ?>" target="_blank">订阅<?php bloginfo('name'); ?></a>以获得最新内容。</div>
<?php comments_template(); ?>
				</div>
			</div>
		</div>
		<div class="col-sub">
<?php get_sidebar(); ?>
		</div>
	</div>
<?php get_footer(); ?>