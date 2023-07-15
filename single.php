<?php
// 現在のページURLを取得してURLエンコード
$url_encode = urlencode( get_permalink() );
// 現在のページのタイトルを取得してURLエンコード
$title_encode = urlencode( get_the_title() );
    global $item;
	global $wpdb;
	// $item_count = $wpdb->get_var( "SELECT * FROM $wpdb->users" )
	require_once(dirname(__FILE__) . "../../../../wp-load.php");
	$ID = get_the_ID();
	$sql = "SELECT *   FROM $wpdb->posts WHERE ID = $ID";
	$item = $wpdb->get_results($wpdb->prepare($sql));
	?>
<?php get_header();?>	
		<div class="com_form" id="contents" >
		<div class="menu" id="main">
		<?php if (have_posts()): ?>
			<?php while (have_posts()): the_post();?>
			<div class="post">
				<script>
					$(function(){
						$('.comment').each(function(){
							$(this).html($(this).html().replace(/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig,"<a href='$1'>$1</a>"));
						});
					});
				</script>
			<?php foreach($item as $item){ ?>
			<div class="map_name"><?php print $item -> map_name?><a href="<?php bloginfo('url');?>/" data-hover="閉じる">✖️</a></div>
			<div class="address"><?php print $item -> address?></div>
			<div class="comment"><?php print $item -> comment?></div>
			<time class="make_date" datetime="<?php the_time($item -> make_date); ?>">投稿年月日：<?php the_time('Y年n月j日(D)');?></time>
			<?php } ?>

		    <ul class="fas_sns_icontopcolor">
				<li><a href="<?php echo esc_url( 'https://twitter.com/share?url=' . $url_encode . '&text=' . $title_encode ); ?>" target="_blank" rel="nofollow"><img class="logo-abema" src="<?php echo get_template_directory_uri(); ?>/images/twitter.png"></a></li>
				<li><a href="<?php echo esc_url( 'https://www.facebook.com/share.php?u=' . $url_encode ); ?>" target="_blank" rel="nofollow"><img class="logo-youtube" src="<?php echo get_template_directory_uri(); ?>/images/facebook.png"></a></li>
				<li><a href="<?php echo esc_url( 'https://line.me/R/msg/text/?' . $title_encode . '%0A' . $url_encode ); ?>" target="_blank" rel="nofollow" class="logo-instagram"><img class="logo-abema" src="<?php echo get_template_directory_uri(); ?>/images/line.png"></a></li>
				<li><a href="<?php echo esc_url( 'https://getpocket.com/edit?url=' . $url_encode . '&title=' . $title_encode ); ?>" target="_blank" rel="nofollow"><img class="logo-instagram" src="<?php echo get_template_directory_uri(); ?>/images/pocket.png"></a></li>
				<li><a href="<?php echo esc_url( 'https://getpocket.com/edit?url=' . $url_encode . '&title=' . $title_encode ); ?>"target="_blank" rel="nofollow" class="logo-instagram"><img class="logo-hatena" src="<?php echo get_template_directory_uri(); ?>/images/hatena.png"></a></li>
			</ul>
		</div><!-- #comments -->	
		<!-- <?php comments_template(); ?> -->
		</div>
			<?php $args = array(
				'title_reply' => null,
				// 'comment_notes_after'  => '<p>コメント記入欄の下に表示するメッセージ</p>',
				'comment_field'        => '<p class="comment-form-comment"  width="100%" ><label for="comment">' . _x( 'Comment', 'noun' ) . '</label><textarea type="textarea" id="comment" name="comment" width="100%" rows="4" aria-required="true"></textarea></p>', // テキストエリア
				'label_submit' => 'COMMENT'
			);
			comment_form( $args ); ?>
				<?php if( have_comments() ): //コメントがあったらコメントリストを表示する ?>
			<ol class="commets-list">
				<?php $args = array(
					'walker'            => null,
					'max_depth'         => 1,
					'style'             => 'div',
					'callback'          => null,
					'end-callback'      => null,
					'type'              => 'all',
					'page'              => '',
					'per_page'          => '',
					'avatar_size'       => null,
					'reverse_top_level' => null,
					'reverse_children'  => ''
					);
				?>
				<?php wp_list_comments( $args ); ?>
			</ol>
			<?php endif; ?>
		</div><!-- /.post -->
	<?php endwhile;  else:?>

<?php endif;?>
	
	
<?php get_footer();?>
