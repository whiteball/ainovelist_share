<?= $this->extend('template') ?>
<?= $this->section('title') ?> - <?= esc(strip_tags($prompt->title)) ?><?= $this->endSection() ?>

<?= $this->section('ogp') ?>
<?php $description = esc(str_replace("\n", ' ', $prompt->description), 'attr') ?>
<meta property="og:image" content="<?= esc($ogp, 'attr') ?>" />
<meta name="twitter:image" content="<?= esc($ogp, 'attr') ?>" />
<meta property="og:type" content="article" />
<meta property="og:title" content="<?= esc(strip_tags($prompt->title), 'attr') ?>" />
<meta name="twitter:title" content="<?= esc(strip_tags($prompt->title), 'attr') ?>">
<meta property="og:description" content="<?= $description ?>" />
<meta name="twitter:description" content="<?= $description ?>">
<meta name="description" content="<?= $description ?>" />
<meta property="article:published_time" content="<?= date_format(date_create($prompt->registered_at), DATE_ATOM)?>" />
<meta property="article:modified_time" content="<?= date_format(date_create($prompt->updated_at), DATE_ATOM)?>" />
<meta property="article:author" content="<?= esc($author, 'attr') ?>" />
<?php if ($prompt->r18 === '1') : ?>
	<meta property="article:tag" content="R-18" />
<?php endif ?>
<?php foreach ($tags as $tag) : ?>
	<meta property="article:tag" content="<?= esc($tag->tag_name, 'attr') ?>" />
<?php endforeach ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container" id="create-confirm">
	<?php if (!empty($successMessage)) : ?>
		<div class="alert alert-success" role="alert">
			<?= esc($successMessage) ?>
		</div>
	<?php endif ?>
	<?php if (!empty($errorMessage)) : ?>
		<div class="alert alert-warning" role="alert">
			<?= esc($errorMessage) ?>
		</div>
	<?php endif ?>
	<h1 class="h3 mb-3 fw-normal">プロンプト詳細</h1>
	<div class="mb-3 border rounded p-2">
		<div class="row">
			<div class="col-6 col-md-4 col-lg-3">
				<a class="btn btn-secondary" href="<?= site_url('prompt_download/' . $prompt->id) ?>">
					<span class="d-inline d-sm-none">novelファイルをDL</span>
					<span class="d-none d-sm-inline">novelファイルをダウンロード</span>
				</a>
			</div>
			<div class="col-6 col-md-4 col-lg-5">
				<a class="btn btn-secondary" href="https://ai-novel.com/prompt_load.php?uri=<?= urlencode(site_url('prompt_download/' . $prompt->id)) ?>" target="_blank" rel="noopener noreferrer">
					<span class="d-inline d-sm-none">AIのべりすとで読込</span>
					<span class="d-none d-sm-inline">AIのべりすとで読み込む</span>
				</a>
			</div>
			<div class="col-12 col-md-4 text-md-end" style="font-size: 75%;">
				<ul class="list-group list-group-horizontal">
					<li class="list-group-item flex-fill text-secondary" style="border: none;">View: <?= esc($prompt->view) ?></li>
					<li class="list-group-item flex-fill text-secondary" style="border: none;">Download: <?= esc($prompt->download) ?></li>
					<li class="list-group-item flex-fill text-secondary" style="border: none;">Import: <?= esc($prompt->import) ?></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="mb-3 text-end">
		<span class="text-secondary">投稿日:<?= esc($prompt->registered_at) ?><?php if ($prompt->updated_at !== $prompt->registered_at):?>/更新日:<?= esc($prompt->updated_at) ?><?php endif ?></span>
	</div>
	<div class="mb-3 border rounded p-2">
		<div class="mb-3">
			<h5>タイトル</h5>
			<div class="wrap border rounded p-2"><?= esc(strip_tags($prompt->title)) ?></div>
		</div>
		<div class="mb-3">
			<h5>タグ</h5>
			<div class="wrap border rounded p-2">
				<?php if ($prompt->r18 === '1') : ?>
					<a class="btn rounded-pill btn-danger btn-sm tag-link" href="<?= site_url('tag/R-18') ?>">R-18</a>
				<?php endif ?>
				<?php foreach ($tags as $tag) : ?>
					<a class="btn rounded-pill btn-outline-secondary btn-sm tag-link" href="<?= site_url('tag/' . $tag->tag_name) ?>"><?= esc($tag->tag_name) ?></a>
				<?php endforeach ?>
			</div>
		</div>
		<div class="mb-3">
			<h5>作者</h5>
			<div class="wrap border rounded p-2"><a class="btn btn-outline-secondary" href="<?= site_url('user/' . $prompt->user_id) ?>"><?= esc($author) ?></a></div>
		</div>
		<div class="mb-3">
			<h5>説明</h5>
			<div class="wrap border rounded p-2"><?= nl2br(str_replace(' ', '&nbsp;', esc($prompt->description))) ?></div>
		</div>
		<div class="mb-3">
			<h5>プロンプト(本文)<button class="btn btn-sm copy-btn" data-target="prompt"><img alt="copy" src="<?= base_url('img/copy.svg') ?>" width="20"></button></h5>
			<div class="wrap border rounded p-2" id="prompt-text"><?= nl2br(str_replace(' ', '&nbsp;', esc($prompt->prompt))) ?></div>
		</div>
	</div>
	<?php if (! (empty($prompt->memory) && empty($prompt->authors_note) && empty($prompt->ng_words) && empty($prompt->char_book) && empty($prompt->script))) : ?>
		<div class="mb-3 border rounded p-2">
			<div class="accordion" id="detail-section">
				<?php if (! empty($prompt->memory)) : ?>
					<div class="accordion-item">
						<h2 class="accordion-header" id="memory-head">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#memory-body" aria-expanded="false" aria-controls="memory-body">
								メモリ
							</button>
						</h2>
						<div id="memory-body" class="accordion-collapse collapse" aria-labelledby="memory-head">
							<div class="accordion-body">
								<div class="row">
									<div class="col-12">
										<div class="position-relative">
											<button class="btn btn-sm copy-btn position-absolute top-0 end-0" data-target="memory"><img alt="copy" src="<?= base_url('img/copy.svg') ?>" width="20"></button>
										</div>
									</div>
									<div class="col-11" id="memory-text"><?= nl2br(str_replace(' ', '&nbsp;', esc($prompt->memory))) ?></div>
								</div>
							</div>
						</div>
					</div>
				<?php endif ?>
				<?php if (! empty($prompt->authors_note)) : ?>
					<div class="accordion-item">
						<h2 class="accordion-header" id="authors-note-head">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#authors-note-body" aria-expanded="false" aria-controls="authors-note-body">
								脚注
							</button>
						</h2>
						<div id="authors-note-body" class="accordion-collapse collapse" aria-labelledby="authors-note-head">
							<div class="accordion-body">
								<div class="row">
									<div class="col-12">
										<div class="position-relative">
											<button class="btn btn-sm copy-btn position-absolute top-0 end-0" data-target="authors-note"><img alt="copy" src="<?= base_url('img/copy.svg') ?>" width="20"></button>
										</div>
									</div>
									<div class="col-11" id="authors-note-text"><?= nl2br(str_replace(' ', '&nbsp;', esc($prompt->authors_note))) ?></div>
								</div>
							</div>
						</div>
					</div>
				<?php endif ?>
				<?php if (! empty($prompt->ng_words)) : ?>
					<div class="accordion-item">
						<h2 class="accordion-header" id="ng-words-head">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ng-words-body" aria-expanded="false" aria-controls="ng-words-body">
								禁止ワード
							</button>
						</h2>
						<div id="ng-words-body" class="accordion-collapse collapse" aria-labelledby="ng-words-head">
							<div class="accordion-body">
								<div class="row">
									<div class="col-12">
										<div class="position-relative">
											<button class="btn btn-sm copy-btn position-absolute top-0 end-0" data-target="ng-words"><img alt="copy" src="<?= base_url('img/copy.svg') ?>" width="20"></button>
										</div>
									</div>
									<div class="col-11" id="ng-words-text"><?= nl2br(str_replace(' ', '&nbsp;', esc($prompt->ng_words))) ?></div>
								</div>
							</div>
						</div>
					</div>
				<?php endif ?>
				<?php if (! empty($prompt->char_book)) : ?>
					<div class="accordion-item">
						<h2 class="accordion-header" id="char-book-head">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#char-book-body" aria-expanded="false" aria-controls="char-book-body">
								キャラクターブック
							</button>
						</h2>
						<div id="char-book-body" class="accordion-collapse collapse" aria-labelledby="char-book-head">
							<div class="accordion-body">
								<?php foreach ($prompt->char_book as $char_book) : ?>
									<div>
										<h6 class="wrap">タグ: <span id="char-tag-<?= esc($char_book['id'], 'attr') ?>-text"><?= esc($char_book['tag']) ?></span><button class="btn btn-sm copy-btn" data-target="char-tag-<?= esc($char_book['id'], 'attr') ?>"><img alt="copy" src="<?= base_url('img/copy.svg') ?>" width="18"></h6>
										<div class="wrap border rounded p-2 row">
											<div class="col-12">
												<div class="position-relative">
													<button class="btn btn-sm copy-btn position-absolute top-0 end-0" data-target="char-content-<?= esc($char_book['id'], 'attr') ?>"><img alt="copy" src="<?= base_url('img/copy.svg') ?>" width="20"></button>
												</div>
											</div>
											<div class="col-11" id="char-content-<?= esc($char_book['id'], 'attr') ?>-text"><?= nl2br(str_replace(' ', '&nbsp;', esc($char_book['content']))) ?></div>
										</div>
									</div>
								<?php endforeach ?>
							</div>
						</div>
					</div>
				<?php endif ?>
				<?php if (! empty($prompt->script)) : ?>
					<div class="accordion-item">
						<h2 class="accordion-header" id="script-head">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#script-body" aria-expanded="false" aria-controls="script-body">
								スクリプト
							</button>
						</h2>
						<div id="script-body" class="accordion-collapse collapse" aria-labelledby="script-head">
							<div class="accordion-body">
								<?php $type_list = ['script_in' => '入力文の置換', 'script_out' => '出力文の置換', 'script_in_pin' => '最新入力文の確定置換', 'script_in_regex' => '入力文の置換（正規表現）', 'script_out_regex' => '出力文の置換（正規表現）', 'script_in_pin_regex' => '最新入力文の確定置換（正規表現）', 'script_none' => '使用しない'] ?>
								<?php foreach ($prompt->script as $script) : ?>
									<div class="border rounded p-2">
										<table class="table">
											<tr>
												<th scope="row" width="85">種類
												<td><?= esc($type_list[$script['type']]) ?></td>
											</tr>
											<tr>
												<th scope="row">IN<button class="btn btn-sm copy-btn" data-target="script-in-<?= esc($script['id'], 'attr') ?>"><img alt="copy" src="<?= base_url('img/copy.svg') ?>" width="16"></th></th>
												<td id="script-in-<?= esc($script['id'], 'attr') ?>-text"><?= esc($script['in']) ?></td>
											</tr>
											<tr>
												<th scope="row">OUT<button class="btn btn-sm copy-btn" data-target="script-out-<?= esc($script['id'], 'attr') ?>"><img alt="copy" src="<?= base_url('img/copy.svg') ?>" width="16"></th>
												<td id="script-out-<?= esc($script['id'], 'attr') ?>-text"><?= esc($script['out']) ?></td>
											</tr>
										</table>
									</div>
								<?php endforeach ?>
							</div>
						</div>
					</div>
				<?php endif ?>
			</div>
		</div>
	<?php endif ?>
	<?php if ($prompt->comment === '1') : ?>
		<div class="accordion" id="comment-section">
			<div class="accordion-item">
				<h2 class="accordion-header" id="comment-head">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#comment-body" aria-expanded="<?= empty($openComment) ? 'false' : 'true' ?>" aria-controls="comment-body">
						コメント
					</button>
				</h2>
				<div id="comment-body" class="accordion-collapse collapse<?= empty($openComment) ? '' : ' show' ?>" aria-labelledby="comment-head">
					<div class="accordion-body">
						<?php if (!empty($loginUserId)) : ?>
							<?= form_open('prompt/' . $prompt->id) ?>
							<?= csrf_field() ?>
							<?= form_hidden('type', 'comment') ?>
						<?php endif ?>
						<?php if (empty($comments)) : ?>
							<div>コメントはありません。</div>
						<?php else : ?>
							<ul <?= empty($loginUserId) ? 'style="list-style: circle;"' : 'class="list-unstyled"' ?>>
								<?php foreach ($comments as $comment) : ?>
									<li>
										<div class="form-check-inline">
											<?php if (!empty($loginUserId)) : ?>
												<input class="form-check-input" type="radio" name="reply-to" value="<?= $comment->id ?>" id="comment-radio-<?= $comment->id ?>" <?= isset($clearCommentInput) ? '' : set_checkbox('reply-to', $comment->id) ?>>
											<?php endif ?>
											<label for="comment-radio-<?= $comment->id ?>" id="comment-<?= $comment->id ?>"><?= esc($comment->comment) ?> / <?php if ($comment->registered_by === '0') : ?><?= esc($comment->user_name) ?><?php else : ?><a href="<?= site_url('user/' . $comment->registered_by) ?>"><?= esc($comment->user_name) ?></a><?php endif ?> <span class="comment-date">(<?= $comment->registered_at ?>)</span></label>
										</div>
										<?php if (!empty($loginUserId) && ((int) $comment->registered_by === $loginUserId || ($comment->registered_by !== '0' && (int) $prompt->user_id === $loginUserId))) : ?>
											<button type="button" class="btn btn-outline-danger btn-sm comment-delete" date-id="<?= $comment->id ?>">×</button>
										<?php endif ?>
										<?php if (!empty($comment->children)) : ?>
											<ul <?= empty($loginUserId) ? 'style="list-style: circle;"' : 'style="list-style: none;"' ?>>
												<?php foreach ($comment->children as $child) : ?>
													<li>
														<div class="form-check-inline">
															<?php if (!empty($loginUserId)) : ?>
																<input class="form-check-input" type="radio" name="reply-to" value="<?= $child->id ?>" id="comment-radio-<?= $child->id ?>" <?= isset($clearCommentInput) ? '' : set_checkbox('reply-to', (string) $child->id) ?>>
															<?php endif ?>
															<label for="comment-radio-<?= $child->id ?>" id="comment-<?= $child->id ?>"><?= esc($child->comment) ?> / <?php if ($child->registered_by === '0') : ?><?= esc($child->user_name) ?><?php else : ?><a href="<?= site_url('user/' . $child->registered_by) ?>"><?= esc($child->user_name) ?></a><?php endif ?> <span class="comment-date">(<?= $child->registered_at ?>)</span></label>
														</div>
														<?php if (!empty($loginUserId) && ((int) $child->registered_by === $loginUserId || ($child->registered_by !== '0' && (int) $prompt->user_id === $loginUserId))) : ?>
															<button type="button" class="btn btn-outline-danger btn-sm comment-delete" date-id="<?= $child->id ?>">×</button>
														<?php endif ?>
														<?php if (!empty($child->children)) : ?>
															<ul style="list-style: circle;">
																<?php foreach ($child->children as $grand_child) : ?>
																	<li>
																		<span id="comment-<?= $grand_child->id ?>"><?= esc($grand_child->comment) ?> / <?php if ($grand_child->registered_by === '0') : ?><?= esc($grand_child->user_name) ?><?php else : ?><a href="<?= site_url('user/' . $grand_child->registered_by) ?>"><?= esc($grand_child->user_name) ?></a><?php endif ?> <span class="comment-date">(<?= $grand_child->registered_at ?>)</span></span>
																		<?php if (!empty($loginUserId) && ((int) $grand_child->registered_by === $loginUserId || ($grand_child->registered_by !== '0' && (int) $prompt->user_id === $loginUserId))) : ?>
																			<button type="button" class="btn btn-outline-danger btn-sm comment-delete" date-id="<?= $grand_child->id ?>">×</button>
																		<?php endif ?>
																	</li>
																<?php endforeach ?>
															</ul>
														<?php endif ?>
													</li>
												<?php endforeach ?>
											</ul>
										<?php endif ?>
									</li>
								<?php endforeach ?>
							</ul>
						<?php endif ?>
						<?php if (!empty($loginUserId)) : ?>
							<div class="input-group mt-2">
								<div class="input-group-text">
									<input class="form-check-input mt-0" type="radio" value="0" aria-label="コメントリプライ先は無し" name="reply-to" <?= isset($clearCommentInput) ? ' checked="checked"' : set_checkbox('reply-to', "0", true) ?>>
								</div>
								<input type="text" class="form-control" aria-label="コメント入力欄" maxlength="2048" id="comment" name="comment" value="<?= isset($clearCommentInput) ? '' : set_value('comment', '') ?>" required>
								<button class="btn btn-outline-secondary" type="submit" id="comment-button">送信</button>
							</div>
							<?= $validation->showError('reply-to') ?>
							<?= $validation->showError('comment') ?>
							<?= form_close() ?>
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>
		<div style="display: none;">
			<?= form_open('prompt/' . $prompt->id, ['id' => 'comment-delete-form']) ?>
			<?= csrf_field() ?>
			<?= form_hidden('type', 'comment-delete') ?>
			<input type="hidden" name="comment_id" id="comment-delete-id" value="0">
			<?= form_close() ?>
		</div>
		<script>
			for (const btn of document.querySelectorAll('.comment-delete')){
				btn.addEventListener('click', function () {
					const id = this.getAttribute('date-id')
					const elem = document.getElementById('comment-' + id)
					if (window.confirm('以下のコメントを削除します。\n\n' + elem.textContent)) {
						document.getElementById('comment-delete-id').setAttribute('value', id)
						document.getElementById('comment-delete-form').submit()
						return
					}
			})
			}
		</script>
	<?php endif ?>
	<?php if ($prompt->r18 === '1' && ($_SESSION['nsfw_mode'] ?? 's') === 's') : ?>
		<?php
        $uri         = current_url(true);
        $query       = preg_replace('/(^|&|\?)(p=\d+|[nl]mode=\w)/u', '', $uri->getQuery());
        $current_url = str_replace(index_page(), '', implode('/', $uri->getSegments())) . '?' . ($query ? ($query . '&') : '');
        ?>
		<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#nsfw-modal" style="display: none;" id="nsfw-button"></button>
		<div class="modal" id="nsfw-modal" tabindex="-1" aria-labelledby="nsfw-modal-label" aria-hidden="true">
			<div class="modal-dialog modal-fullscreen">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="nsfw-modal-label">R-18 (NSFW) 表示確認</h4>
					</div>
					<div class="modal-body text-center">
						<div class="fs-4">このページにはR-18 (NSFW)の内容を含みます。<br>閲覧を続けますか？</div>
						<hr>
						<div class="row">
							<div class="col">
								<a type="button" class="btn btn-secondary me-3" href="javascript:history.back()">戻る</a>
								<a class="btn btn-primary ms-3" href="<?= site_url($current_url . '?nmode=n') ?>">続ける</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			document.addEventListener('DOMContentLoaded', function () {
				const ev = new Event('click')
				document.getElementById('nsfw-button').dispatchEvent(ev)
			})
		</script>
	<?php endif ?>
	<div id="copy-area" style="display: none;"><textarea id="copy-area-text"></textarea></div>
	<script>
		for (const elem of document.querySelectorAll('.copy-btn')) {
			elem.addEventListener('click',function () {
				const target = this.getAttribute('data-target')
				if (navigator.clipboard) {
					navigator.clipboard.writeText(document.getElementById(target + '-text').innerText)
				} else {
					const copyArea = document.getElementById('copy-area'), textArea = document.getElementById('copy-area-text')
					copyArea.style.display = 'block'
					textArea.innerHtml = document.getElementById(target + '-text').innerHtml
					textArea.select()
					document.execCommand('copy')
					copyArea.style.display = 'none'
				}
			})
		}
	</script>
</main>
<?= $this->endSection() ?>