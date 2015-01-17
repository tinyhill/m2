<?php get_header(); ?>
	<div class="grid-m0s5 clearfix">
		<div class="col-main">
			<div class="main-wrap">
				<ul class="section clearfix">
<?php while (have_posts()) : the_post(); ?>
					<li class="post clearfix">
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
					</li>
<?php endwhile; ?>
				</ul>
				<div class="pagenavi clearfix">
<?php get_pagenavi(); ?>
				</div>
			</div>
		</div>
		<div class="col-sub">
<?php get_sidebar(); ?>
		</div>
	</div>
<?php get_footer(); ?>