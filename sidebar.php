<?php global $options; ?>
		<div class="sidebar">
<?php $a3 = '<div class="widget">' . $options['sidebar_a3']['content'] . '</div>'; if ($options['sidebar_a3']['home'] && is_home()) echo $a3; if ($options['sidebar_a3']['single'] && is_single()) echo $a3; ?>
<?php dynamic_sidebar(); ?>
			<div class="random-post">
				<div class="hd">随机推荐</div>
				<div class="bd">
					<ul class="clearfix">
<?php $random_post = get_posts('orderby=rand&numberposts=10'); foreach ($random_post as $post) : ?>
						<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<div class="latest-comment">
				<div class="hd">最新评论</div>
				<div class="bd">
					<ul class="clearfix">
<?php $latest_comment = get_comments('number=10&type=comment&status=approve'); foreach ($latest_comment as $comment) : ?>
						<li><em><?php echo get_comment_author(); ?> - </em><a href="<?php echo get_comment_link(); ?>" rel="nofollow" title="<?php echo get_comment_text(); ?>"><?php echo mb_strimwidth(get_comment_text(), 0, 50, '...'); ?></a></li>
<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<div class="site-link">
				<div class="permalink">
					<div class="hd">固定链接</div>
					<div class="bd">
						<ul class="clearfix">
<?php wp_list_bookmarks('limit=15&title_li=&categorize=0&category=' . $options['permalink'] . '&category_before=&category_after=&before=<li>&after=</li>'); ?>
						</ul>
					</div>
				</div>
				<div class="random-link">
					<div class="hd">随机链接</div>
					<div class="bd">
						<ul class="clearfix">
<?php wp_list_bookmarks('orderby=rand&limit=15&title_li=&categorize=0&exclude=2&category_before=&category_after=&before=<li>&after=</li>'); ?>
						</ul>
					</div>
				</div>
			</div>
		</div>