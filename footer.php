<?php global $options; ?>
</div>
<div class="footer">
	<div class="copyright">COPYRIGHT &copy; <?php echo date('Y'); ?> <a href="<?php bloginfo('url'); ?>"><?php echo strtoupper(str_replace('www.', '', $_SERVER['HTTP_HOST'])); ?></a> - THEME BY <a target="_blank" href="http://www.mangguo.org/">芒果小站</a></div>
	<div class="about clearfix">
		<ul class="sitemap clearfix">
<?php wp_nav_menu(array('theme_location' => 'sitemap', 'container' => false, 'menu_class' => false, 'menu_id' => false, 'items_wrap' => '%3$s')); ?>
		</ul>
		<a href="<?php bloginfo('url'); ?>" class="licence">&copy; <?php bloginfo('name'); ?></a>
	</div>
</div>
</div>
<?php if ($options['minify']) : ?>
<script charset="utf-8" src="<?php bloginfo('template_url');?>/min/?b=<?php echo str_replace(get_bloginfo('home') . '/', '', get_bloginfo('template_directory')); ?>/assets&f=jquery.min.js,jquery.cookie.js,mangguo.js"></script>
<?php else : ?>
<script charset="utf-8" src="<?php bloginfo('template_url');?>/assets/jquery.min.js"></script>
<script charset="utf-8" src="<?php bloginfo('template_url');?>/assets/jquery.cookie.js"></script>
<script charset="utf-8" src="<?php bloginfo('template_url');?>/assets/mangguo.js"></script>
<?php endif; ?>
<?php wp_footer(); ?>
<?php if ($options['analytics']['active']) echo $options['analytics']['content']; ?>
</body>
</html>
<!-- <?php echo get_num_queries(); ?> queries in <?php timer_stop(3); ?> seconds -->