<?php
/*
Template Name: 友情链接
*/
?>
<?php $options = get_option('m2_options'); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="description" content="<?php bloginfo('description'); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>
<?php if ($options['minify']) : ?>
<link rel="stylesheet" href="<?php bloginfo('template_url');?>/min/?b=<?php echo str_replace(get_bloginfo('home') . '/', '', get_bloginfo('template_directory')); ?>/assets&f=page.css">
<?php else : ?>
<link rel="stylesheet" href="<?php bloginfo('template_url');?>/assets/page.css">
<?php endif; ?>
</head>
<body>
<div id="page">
	<h1><a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></h1>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<h2><?php the_title(); ?></h2>
	<p><?php the_content(); ?></p>
<?php endwhile; endif; ?>
	<ol>
<?php wp_list_bookmarks('title_li=&categorize=0&show_name=1&show_description=1&orderby=rand&between= - '); ?>
	</ol>
	<h3>加入链接</h3>
	<ul>
		<li>网站名称：<?php bloginfo('name'); ?></li>
		<li>网站地址：<?php bloginfo('url'); ?></li>
		<li>网站描述：<?php bloginfo('description'); ?></li>
	</ul>
	<h2>其他页面</h2>
	<ul>
<?php $pages = get_pages(); foreach ($pages as $v) : ?>
		<li><a href="<?php echo get_page_link( $v->ID ); ?>"><?php echo $v->post_title; ?></a></li>
<?php endforeach; ?>
	</ul>
	<p id="footer">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
</div>
<?php wp_footer(); ?>
<?php if ($options['analytics']['active']) echo $options['analytics']['content']; ?>
</body>
</html>