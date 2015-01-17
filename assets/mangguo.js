/**
 * mangguo.js
 * @author tinyhill@163.com
 */
(function () {

	/**
	 * 定义全局命名空间
	 * @type {Object} mangguo
	 */
	var mangguo = {

		/**
		 * 轮播切换
		 * @method slide
		 */
		slide: function () {

			// 圆点切换
			$('.slide-nav li').click(function (e) {

				var idx = $(e.currentTarget).text();

				$('.slide-content li').each(function (k, v) {
					if ((k + 1) == idx) {
						$(v).fadeIn();
					} else {
						$(v).hide();
					}
				});

			});

			// 翻页切换
			$('#slide .prev, #slide .next').click(function (e) {

				var trigger = $(e.currentTarget),
					target = $('.slide-content li:visible');

				// 隐藏当前页
				target.hide();

				// 前翻页
				if (trigger.hasClass('prev')) {

					var prev = target.prev();

					if (prev[0]) {
						prev.fadeIn();
					} else {
						$('.slide-content li:last').fadeIn();
					}
				}

				// 后翻页
				if (trigger.hasClass('next')) {

					var next = target.next();

					if (next[0]) {
						next.fadeIn();
					} else {
						$('.slide-content li:first').fadeIn();
					}
				}

			});

			// 自动播放
			var duration = 5000,
				timeout = setTimeout(autoplay, duration);

			function autoplay () {
				$('#slide .next').trigger('click');
				timeout = setTimeout(autoplay, duration);
			}

			$('#slide').hover(
				function () {
					clearTimeout(timeout);
				},
				function () {
					timeout = setTimeout(autoplay, duration);
				}
			);

		},

		/**
		 * 收缩展开
		 * @method slideToggle
		 */
		slideToggle: function () {

			$('#slide-toggle .toggle').click(function (e) {

				var trigger = $(e.currentTarget);

				if (trigger.hasClass('toggle-mini')) {

					trigger.text('切换到精简模式');
					trigger.removeClass('toggle-mini');

					$('#slide').animate({
						height: '90px'
					}, 'fast', function () {
						$(this).removeClass('slide-mini');
					});

					$.cookie('slide_mini', null);

				} else {

					trigger.text('切换到完整模式');
					trigger.addClass('toggle-mini');

					$('#slide').animate({
						height: '45px'
					}, 'fast', function () {
						$(this).addClass('slide-mini');
					});

					// 将模式写入 cookie 值
					$.cookie('slide_mini', true);

				}

			});

		},

		/**
		 * 回到顶部
		 * @method scrollTop
		 */
		scrollTop: function () {

			var target = $('<div id="scroll-top" class="scroll-top">返回顶部</div>'),
				target = target.appendTo($('body'));

			// 检查滚动差值
			function checkTop () {

				if ($(window).scrollTop() > 200) {
					target.fadeIn(250);
				} else {
					target.fadeOut(500);
				}

			}

			checkTop();

			$(window).scroll(checkTop);

			target.click(function () {
				$('body, html').animate({scrollTop: 0}, 1000);
			});

		},

		/**
		 * 评论回应
		 * @method reply
		 */
		replyTo: function () {

			$('.comment-list dl').delegate('.reply-to', 'click', function (e) {

				var author = $(this).find('em:first-child').text(),
					text = '回应 ' + author + ' 的发言';

				$('#comment-form').prev('h3').find('span').text(text);
				$('#comment').select();
				$('#comment_parent').val($(this).attr('rel'));

				$('html, body').animate({
					scrollTop: $('#comment').offset().top
				}, 'slow');

				return false;

			});

			$('.replied').click(function () {
				$('html, body').animate({
					scrollTop: $($(this).attr('href')).offset().top
				}, 'slow');
			});

		},

		/**
		 * 修改用户资料
		 * @method authorToggle
		 */
		authorToggle: function () {

			$('#author-toggle').toggle(

				function () {
					$('.comment-author').show('fast');
				}, function () {
					$('.comment-author').hide('fast');
				}

			);

		},

		/**
		 * 初始化入口
		 * @method init
		 */
		init: function () {

			// 初始化轮播切换
			this.slide();

			// 初始化收缩展开
			this.slideToggle();

			// 初始化回到顶部
			this.scrollTop();

			// 初始化评论回应
			this.replyTo();

			// 初始化修改资料
			this.authorToggle();

		}

	};

	// 执行初始化
	mangguo.init();

})();