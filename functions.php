<?php

remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');

/**
 * 使主题支持自定义菜单
 */
if (function_exists('register_nav_menus')) {

	register_nav_menus(array(
		'tab' => '导航菜单',
		'sitemap' => '页尾菜单'
	));

}

/**
 * 使主题支持小工具
 */
if (function_exists('register_sidebar')) {

	register_sidebar(array(
		'before_widget' => '<div class="widget %2$s" id="%1$s">',
		'after_widget' => '</div>',
		'before_title' => '<div class="hd">',
		'after_title' => '</div>'
	));

}

/**
 * 不用插件实现翻页功能
 */
function get_pagenavi () {

	global $wp_query, $wp_rewrite;

	$current = $wp_query->query_vars['paged'] > 1 ? $wp_query->query_vars['paged'] : 1;
	$format = $wp_rewrite->using_permalinks() ? 'page/%#%/' : '?paged=%#%';

	$pagination = array(
		'base' => @add_query_arg('page', '%#%'),
		'format' => '',
		'total' => $wp_query->max_num_pages,
		'current' => $current,
		'mid_size' => 5,
		'prev_text' => '«',
		'next_text' => '»'
	);

	$pagenum_link = array_shift(explode('?', get_pagenum_link(1)));
	$pagination['base'] = user_trailingslashit(trailingslashit($pagenum_link) . $format, 'paged');

	if (!empty($wp_query->query_vars['s'])) {
		$pagination['add_args'] = array('s' => get_query_var('s'));
	}

	echo paginate_links($pagination);

}

/**
 * 不用插件实现阅读计数功能（计数）
 */
function get_post_views ($post_id) {

	$count_key = 'views';
	$count = get_post_meta($post_id, $count_key, true);

	if ($count == '') {
		delete_post_meta($post_id, $count_key);
		add_post_meta($post_id, $count_key, '0');
		$count = '0';
	}

	echo number_format_i18n($count);

}

/**
 * 不用插件实现阅读计数功能（读数）
 */
function set_post_views () {

	global $post;

	$post_id = $post->ID;
	$count_key = 'views';
	$count = get_post_meta($post_id, $count_key, true);

	if (is_single() || is_page()) {

		if ($count == '') {
			delete_post_meta($post_id, $count_key);
			add_post_meta($post_id, $count_key, '0');
		} else {
			update_post_meta($post_id, $count_key, $count + 1);
		}

	}

}

add_action('get_header', 'set_post_views');

/**
 * 过滤文章搜索类型
 */
function search_query_filter ($query) {

	if ($query->is_search) {
		$query->set('post_type', 'post');
	}
	return $query;

}

add_filter('pre_get_posts', 'search_query_filter');

/**
 * 评论邮件通知功能
 */
function comment_mail_notify ($comment_id) {

	$comment = get_comment($comment_id);
	$parent_id = $comment->comment_parent ? $comment->comment_parent : '';
	$spam_confirmed = $comment->comment_approved;

	if (($parent_id != '') && ($spam_confirmed != 'spam')) {

		$wp_email = 'webmaster@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
		$to = trim(get_comment($parent_id)->comment_author_email);

		$subject = '你在 [' . get_option("blogname") . '] 的留言有了回应';
		$message = '
	<div style="background-color:#EEF2FA;border:1px solid #D8E3E8;color:#111;padding:0 15px;border-radius:5px;">
		<p>' . trim(get_comment($parent_id)->comment_author) . ', 你好!</p>
		<p>你曾在《' . get_the_title($comment->comment_post_ID) . '》的留言:<br>' . trim(get_comment($parent_id)->comment_content) . '</p>
		<p>' . trim($comment->comment_author) . ' 给你的回应:<br />' . trim($comment->comment_content) . '<br></p>
		<p>你可以点击 <a href="' . htmlspecialchars(get_comment_link($parent_id)) . '">查看回应完整内容</a></p>
		<p><strong>感谢你对 <a href="' . get_option('home') . '" target="_blank">' . get_option('blogname') . '</a> 的关注，欢迎<a href="' . get_option('home') . '/feed/" target="_blank">订阅本站</a></strong></p>
		<p><strong>您可以直接回复此邮件与我联系～</strong></p>
	</div>';

		$from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
		$headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";

		wp_mail($to, $subject, $message, $headers);

	}

}

add_action('comment_post', 'comment_mail_notify');

/**
 * 为主题添加管理选项
 * @class Options
 */
class Options {

	/**
	 * 获取选项组
	 */
	function get_options () {

		//在数据库中获取选项组
		$options = get_option('m2_options');

		//如果数据库中不存在该选项组, 设定这些选项的默认值, 并将它们插入数据库
		if (!is_array($options)) {
			$options['page_width'] = '';
			$options['permalink'] = '2';
			$options['minify'] = false;
			$options['slide_links'] = array(
				array('全球最具业界良心的主机 - Linode', '毫无疑问，目前做得最好的主机供应商', 'http://www.linode.com/?r=f1f14331bd4d366782ca5dfd24d9ecef8ac0cab5'),
				array('芒果小站目前使用的主机 - Linode', '客服响应快，随时退款，XEN 架构稳定', 'http://www.linode.com/?r=f1f14331bd4d366782ca5dfd24d9ecef8ac0cab5'),
				array('最好的日本东京线路主机 - Linode', '可选弗里蒙特、达拉斯、亚特兰大、纽瓦克、伦敦、东京机房', 'http://www.linode.com/?r=f1f14331bd4d366782ca5dfd24d9ecef8ac0cab5')
			);
			$options['text_links'] = array(
				array('全球最具业界良心的主机', 'red', 'http://www.linode.com/?r=f1f14331bd4d366782ca5dfd24d9ecef8ac0cab5'),
				array('芒果小站目前使用的主机', 'green', 'http://www.linode.com/?r=f1f14331bd4d366782ca5dfd24d9ecef8ac0cab5'),
				array('最好的日本东京线路主机', 'orange', 'http://www.linode.com/?r=f1f14331bd4d366782ca5dfd24d9ecef8ac0cab5')
			);
			$options['article_a1'] = array(
				'active' => true,
				'content' => '<a href="http://www.linode.com/?r=f1f14331bd4d366782ca5dfd24d9ecef8ac0cab5" target="_blank"><img src="http://gtms01.alicdn.com/tps/i1/T1Qj8tFyVrXXbwqofS-300-250.jpg" width="300" height="250" alt=""></a>'
			);
			$options['article_a2'] = array(
				'active' => true,
				'content' => '<a href="http://www.linode.com/?r=f1f14331bd4d366782ca5dfd24d9ecef8ac0cab5" target="_blank"><img src="http://gtms01.alicdn.com/tps/i1/T1Qj8tFyVrXXbwqofS-300-250.jpg" width="300" height="250" alt=""></a>'
			);
			$options['sidebar_a3'] = array(
				'home' => false,
				'single' => true,
				'content' => '<script>
google_ad_client = "ca-pub-9763316970959340";
google_ad_slot = "4165466641";
google_ad_width = 250;
google_ad_height = 250;
</script>
<script src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>'
			);
			$options['analytics'] = array(
				'active' => false,
				'content' => ''
			);
			update_option('m2_options', $options);
		}

		//返回选项组
		return $options;
	}

	/**
	 * 初始化选项
	 */
	function set_options () {

		//如果是post提交数据, 对数据进行限制, 并更新到数据库
		if (isset($_POST['options_save'])) {
			$options = array(
				'page_width' => $_POST['page_width'],
				'permalink' => $_POST['permalink'],
				'minify' => $_POST['minify'],
				'slide_links' => array(),
				'text_links' => array(),
				'article_a1' => array(
					'active' => isset($_POST['a1_active']),
					'content' => stripslashes($_POST['a1_content']),
				),
				'article_a2' => array(
					'active' => isset($_POST['a2_active']),
					'content' => stripslashes($_POST['a2_content']),
				),
				'sidebar_a3' => array(
					'home' => isset($_POST['a3_home']),
					'single' => isset($_POST['a3_single']),
					'content' => stripslashes($_POST['a3_content']),
				),
				'analytics' => array(
					'active' => isset($_POST['analytics_active']),
					'content' => stripslashes($_POST['analytics_content']),
				)
			);
			for ($i = 0; $i < sizeof($_POST['slide_name']); $i++) {
				array_push($options['slide_links'], array(
					$_POST['slide_name'][$i],
					$_POST['slide_desc'][$i],
					$_POST['slide_url'][$i]
				));
			}
			for ($i = 0; $i < sizeof($_POST['text_name']); $i++) {
				array_push($options['text_links'], array(
					$_POST['text_name'][$i],
					$_POST['text_desc'][$i],
					$_POST['text_url'][$i]
				));
			}
			update_option('m2_options', $options);
		} //否则，重新获取选项组，也就是对数据进行初始化
		else {
			Options::get_options();
		}

		add_menu_page('主题选项', '主题选项', 'edit_themes', basename(__FILE__), array('Options', 'display'));

	}

	/**
	 * 选项设置页
	 */
	function display () {

		$options = Options::get_options(); ?>

	<div class="wrap">
	<h2>主题选项（M2）<a class="add-new-h2" href="http://www.mangguo.org/" target="_blank">芒果小站</a></h2>

	<div class="metabox-holder has-right-sidebar">
	<div class="inner-sidebar">
		<div class="meta-box-sortabless ui-sortable">
			<div class="postbox">
				<h3 class="hndle"><span>美国主机推荐</span></h3>

				<div class="inside">
					<table class="hosting" style="width:100%;">
						<tr id="linode">
							<td class="web-hosting" align="center">
								<a target="_blank" href="http://www.linode.com/?r=f1f14331bd4d366782ca5dfd24d9ecef8ac0cab5"><img
										 alt="Linode"
										 src="<?php bloginfo('template_url');?>/assets/linode_150x35.png"></a>
							</td>
						</tr>
						<tr id="digitalocean">
							<td class="web-hosting" align="center">
								<a target="_blank"
								   href="http://affiliate.godaddy.com/redirect/95B163F64AA934DC393CB79E3EF3602108CE0DCCF913CA81DECFD2130290BC91/?r=mangguo"><img
										alt="DigitalOcean"
										src="<?php bloginfo('template_url');?>/assets/digitalocean_150x35.png"></a>
							</td>
						</tr>
						<tr id="bluehost">
							<td class="web-hosting" align="center">
								<a target="_blank" href="http://www.bluehost.com/track/mangguo"><img
										alt="BlueHost"
										src="<?php bloginfo('template_url');?>/assets/bluehost_150x35.png"></a>
							</td>
						</tr>
						<tr id="hostmonster">
							<td class="web-hosting" align="center">
								<a target="_blank" href="http://www.hostmonster.com/track/mangguo"><img
										alt="HostMonster"
										src="<?php bloginfo('template_url');?>/assets/hostmonster_150x35.png"></a>
							</td>
						</tr>
						<tr id="justhost">
							<td class="web-hosting" align="center">
								<a target="_blank"
								   href="http://stats.justhost.com/track?c38717e2731a3cc908b64aadd428b8aba"><img
										alt="JustHost"
										src="<?php bloginfo('template_url');?>/assets/justhost_150x35.png"></a>
							</td>
						</tr>
						<tr id="hostgator">
							<td class="web-hosting" align="center">
								<a target="_blank"
								   href="http://secure.hostgator.com/cgi-bin/affiliates/clickthru.cgi?id=mangguo"><img
										alt="HostGator"
										src="<?php bloginfo('template_url');?>/assets/hostgator_150x35.png"></a>
							</td>
						</tr>
						<tr id="hawkhost">
							<td class="web-hosting" align="center">
								<a target="_blank" href="https://my.hawkhost.com/aff.php?aff=3100"><img
										alt="HawkHost"
										src="<?php bloginfo('template_url');?>/assets/hawkhost_150x35.png"></a>
							</td>
						</tr>
						<tr id="lunarpages">
							<td class="web-hosting" align="center">
								<a target="_blank" href="http://www.lunarpages.com/id/mangguo"><img
										alt="LunarPages"
										src="<?php bloginfo('template_url');?>/assets/lunarpages_150x35.png"></a>
							</td>
						</tr>
						<tr id="ixwebhosting">
							<td class="web-hosting" align="center">
								<a target="_blank"
								   href="http://www.ixwebhosting.com/templates/ix/v2/affiliate/clickthru.cgi?id=mangguo"><img
										alt="IXWebHosting"
										src="<?php bloginfo('template_url');?>/assets/ixwebhosting_150x35.png"></a>
							</td>
						</tr>
						<tr id="site5">
							<td class="web-hosting" align="center">
								<a target="_blank" href="http://www.site5.com/in.php?id=85885"><img
										alt="Site5"
								        src="<?php bloginfo('template_url');?>/assets/site5_150x35.png"></a>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	<form method="post" name="options_form">
		<div class="has-sidebar">
			<div class="has-sidebar-content" id="post-body-content">
				<div class="meta-box-sortabless">
					<div class="postbox">
						<h3 class="hndle"><span>基本设置</span></h3>

						<div class="inside">
							<ul>
								<li>页面宽度：
									<input type="text" name="page_width" class="small-text"
									       value="<?php echo $options['page_width']; ?>">（示例：960px 或
									95%，默认为 990px）
								</li>
								<li>
									固定链接：
									<select name="permalink" style="vertical-align:inherit;">
										<?php $terms = get_terms('link_category'); foreach ($terms as $v) : ?>
										<option value="<?php echo $v->term_id; ?>" <?php if ($options['permalink'] === $v->term_id) echo ' selected="selected"' ?>><?php echo $v->name; ?></option>
										<?php endforeach; ?>
									</select>
									（选择默认使用的固定链接分类）
								</li>
								<li>
									资源合并：
									<label style="vertical-align:inherit;"><input type="checkbox"
									                                              name="minify"<?php if ($options['minify']) echo ' checked="checked"'; ?>>&nbsp;选择启用</label>
									（使用 <a
										href="http://www.mangguo.org/minify-merge-compress-javascript-and-css-file/"
										target="_blank">minify</a> 方案合并加载多个 css 和 js 文件，有效提升访问速度，<span
										style="color:red">强烈建议开启</span>）
								</li>
							</ul>
						</div>
					</div>
					<div class="postbox">
						<h3 class="hndle"><span>轮播广告设置</span></h3>

						<div class="inside link-box">
							<table width="100%" cellspacing="3" cellpadding="3">
								<tr>
									<th scope="col">标题</th>
									<th scope="col">描述</th>
									<th scope="col">链接</th>
									<th scope="col">操作</th>
								</tr>
								<?php foreach ($options['slide_links'] as $v) : ?>
								<tr class="alternate">
									<td><input type="text" name="slide_name[]" value="<?php echo $v[0]; ?>"
									           style="width:100%;"></td>
									<td><input type="text" name="slide_desc[]" value="<?php echo $v[1]; ?>"
									           style="width:100%;"></td>
									<td><input type="text" name="slide_url[]" value="<?php echo $v[2]; ?>"
									           style="width:100%;"></td>
									<td style="text-align:center;"><a class="del-link"
									                                  href="javascript:;">删除</a></td>
								</tr>
								<?php endforeach; ?>
							</table>
							<a class="add-link" href="javascript:;" data-type="slide">增加一个新的链接</a>
						</div>
					</div>
					<div class="postbox">
						<h3 class="hndle"><span>文字广告设置</span></h3>

						<div class="inside link-box">
							<table width="100%" cellspacing="3" cellpadding="3">
								<tr>
									<th scope="col">标题</th>
									<th scope="col">颜色（示例：#FF0000）</th>
									<th scope="col">链接</th>
									<th scope="col">操作</th>
								</tr>
								<?php foreach ($options['text_links'] as $v) : ?>
								<tr class="alternate">
									<td><input type="text" name="text_name[]" value="<?php echo $v[0]; ?>"
									           style="width:100%;"></td>
									<td><input type="text" name="text_desc[]" value="<?php echo $v[1]; ?>"
									           style="width:100%;"></td>
									<td><input type="text" name="text_url[]" value="<?php echo $v[2]; ?>"
									           style="width:100%;"></td>
									<td style="text-align:center;"><a class="del-link"
									                                  href="javascript:;">删除</a></td>
								</tr>
								<?php endforeach; ?>
							</table>
							<a class="add-link" href="javascript:;" data-type="text">增加一个新的链接</a>
						</div>
					</div>
					<div class="postbox">
						<h3 class="hndle"><span>文章前置广告设置</span></h3>

						<div class="inside">
							<ul>
								<li>
									是否启用：
									<label style="vertical-align:inherit;"><input type="checkbox"
									                                              name="a1_active"<?php if ($options['article_a1']['active']) echo ' checked="checked"'; ?>>&nbsp;选择启用</label>
									（大小：300x250 像素）
								</li>
							</ul>
							<textarea style="width:100%;height:80px;resize:vertical;"
							          name="a1_content"><?php echo $options['article_a1']['content']; ?></textarea>
						</div>
					</div>
					<div class="postbox">
						<h3 class="hndle"><span>文章后置广告设置</span></h3>

						<div class="inside">
							<ul>
								<li>
									是否启用：
									<label style="vertical-align:inherit;"><input type="checkbox"
									                                              name="a2_active"<?php if ($options['article_a2']['active']) echo ' checked="checked"'; ?>>&nbsp;选择启用</label>
									（大小：300x250 像素）
								</li>
							</ul>
							<textarea style="width:100%;height:80px;resize:vertical;"
							          name="a2_content"><?php echo $options['article_a2']['content']; ?></textarea>
						</div>
					</div>
					<div class="postbox">
						<h3 class="hndle"><span>侧栏广告设置</span></h3>

						<div class="inside">
							<ul>
								<li>
									显示范围：
									<label style="vertical-align:inherit;"><input type="checkbox"
									                                              name="a3_home"<?php if ($options['sidebar_a3']['home']) echo ' checked="checked"'; ?>>&nbsp;首页</label>
									<label style="vertical-align:inherit;"><input type="checkbox"
									                                              name="a3_single"<?php if ($options['sidebar_a3']['single']) echo ' checked="checked"'; ?>>&nbsp;内页</label>
									（宽度：250 像素）
								</li>
							</ul>
							<textarea style="width:100%;height:80px;resize:vertical;"
							          name="a3_content"><?php echo $options['sidebar_a3']['content']; ?></textarea>
						</div>
					</div>
					<div class="postbox">
						<h3 class="hndle"><span>统计代码</span></h3>

						<div class="inside">
							<ul>
								<li>
									是否启用：
									<label style="vertical-align:inherit;"><input type="checkbox"
									                                              name="analytics_active"<?php if ($options['analytics']['active']) echo ' checked="checked"'; ?>>&nbsp;选择启用</label>
								</li>
							</ul>
							<textarea style="width:100%;height:80px;resize:vertical;"
							          name="analytics_content"><?php echo $options['analytics']['content']; ?></textarea>
						</div>
					</div>
				</div>
				<div>
					<p class="submit">
						<input type="submit" name="options_save" class="button-primary" value="更新设置">
					</p>
				</div>
			</div>
		</div>
	</form>
	</div>
	</div>
	<script>
		(function ($) {

			//添加链接
			$('.link-box').delegate('.add-link', 'click', function (e) {

				var type = $(e.currentTarget).attr('data-type'),
						template = '<tr class="alternate">' +
								'	<td><input type="text" name="' + type + '_name[]" style="width:100%;"></td>' +
								'	<td><input type="text" name="' + type + '_desc[]" style="width:100%;"></td>' +
								'	<td><input type="text" name="' + type + '_url[]" style="width:100%;"></td>' +
								'	<td style="text-align:center;"><a class="del-link" href="javascript:;">删除</a></td>' +
								'</tr>';

				$(template).insertAfter($(e.delegateTarget).find('tr.alternate:last'));

			});

			//删除链接
			$('.link-box').delegate('.del-link', 'click', function (e) {

				if ($(e.delegateTarget).find('tr.alternate').length != 1) {
					$(this).parent('td').parent('tr').remove();
				}

			});

		})(jQuery);
	</script>

	<?php
	}

}

//注册初始化方法
add_action('admin_menu', array('Options', 'set_options'));

?>