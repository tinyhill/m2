					<div class="comment">
<?php if ( $comments ) : ?>
						<h3>已经有 <?php comments_number('0', '1', '%'); ?> 条群众意见</h3>
						<ol class="comment-list clearfix">
<?php $floor = 1; foreach($comments as $comment) : ?>
							<li class="item <?php if ($comment->comment_author_email == get_the_author_email()) echo 'admin '; ?>clearfix" id="comment-<?php comment_ID(); ?>">
								<div class="gravatar"><?php if(get_comment_type() == 'comment') echo get_avatar($comment, 32); else echo '?'; ?></div>
								<dl>
									<dt><em><?php comment_author_link(); ?></em> <?php if($comment->comment_parent) echo '对 <em><a href="#comment-' . $comment->comment_parent . '" title="查看被回应楼层">' . get_comment($comment->comment_parent)->comment_author.'</a></em> 说'; ?><s>/</s><?php comment_date('Y-m-d H:i'); ?></dt>
									<dd><?php echo substr(apply_filters('comment_text', $comment->comment_content), 0, -5).' <a href="#comment" class="reply-to" rel="' . $comment->comment_ID . '">回应</a></p>'; ?></dd>
								</dl>
								<b class="floor">#<?php echo $floor; ?></b>
							</li>
<?php $floor++; endforeach; ?>
						</ol>
<?php endif; ?>
						<h3><?php if ($comment_author) : echo '<span>欢迎回来，简单说几句吧</span><a href="javascript:;" class="author-toggle" id="author-toggle">修改资料</a>'; else : echo '<span>下面我简单说几句</span>'; endif; ?></h3>
						<form id="comment-form" class="comment-form" action="<?php bloginfo('url'); ?>/wp-comments-post.php" method="post">
							<ul class="comment-author"<?php if ($comment_author) echo ' style="display:none;"'; ?>>
								<li class="item clearfix">
									<input type="text" value="<?php echo $comment_author; ?>" name="author" id="author" tabindex="1">
									<label for="author">昵称<i>（必填）</i></label>
								</li>
								<li class="item clearfix">
									<input type="text" value="<?php echo $comment_author_email; ?>" name="email" id="email" tabindex="2">
									<label for="email">邮件<i>（必填）</i></label>
								</li>
								<li class="item clearfix">
									<input type="text" value="<?php echo $comment_author_url; ?>" name="url" id="url" tabindex="3">
									<label for="url">网址</label>
								</li>
							</ul>
							<span class="textarea"><textarea name="comment" id="comment" tabindex="4"></textarea></span>
							<span class="submit"><button id="submit" tabindex="5" type="submit">我说完了</button></span>
							<input type="hidden" id="comment_post_ID" name="comment_post_ID" value="<?php echo $id; ?>">
							<input type="hidden" id="comment_parent" name="comment_parent">
							<?php do_action('comment_form', $post->ID); ?>
						</form>
					</div>