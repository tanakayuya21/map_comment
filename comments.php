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