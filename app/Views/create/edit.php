<?= $this->extend('template') ?>
<?= $this->section('title') ?> - プロンプト投稿 - 編集<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<h1 class="h3 mb-3 fw-normal">プロンプト投稿 - 編集</h1>
	<?php if (isset($error_message)) : ?>
		<div class="alert alert-danger"><?= nl2br(esc($error_message)) ?></div>
	<?php endif ?>
	<?= form_open('delete/' . $prompt_id, ['name' => 'delete_form']) ?>
	<?= csrf_field() ?>
	<div class="mb-3 border rounded p-2">
		<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
			プロンプトを削除
		</button>

		<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">プロンプトを削除</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						現在表示しているプロンプトを削除します。<br>よろしいですか？
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" id="delete_button">削除する</button>
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
					</div>
				</div>
			</div>
		</div>
		<script>
			document.getElementById('delete_button').addEventListener('click', function() {
				document.delete_form.submit()
			})
		</script>
	</div>
	<?= form_close() ?>
	<ul class="nav nav-tabs" id="myTab" role="tablist">
		<li class="nav-item" role="presentation">
			<button class="nav-link<?= $default_pane === '' ? ' active' : '' ?>" id="direct-tab" data-bs-toggle="tab" data-bs-target="#direct" type="button" role="tab" aria-controls="direct" aria-selected="<?= $default_pane === '' ? 'true' : 'false' ?>">
				直接入力
			</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link<?= $default_pane === 'file' ? ' active' : '' ?>" id="file-upload-tab" data-bs-toggle="tab" data-bs-target="#file-upload" type="button" role="tab" aria-controls="file-upload" aria-selected="<?= $default_pane === 'file' ? 'true' : 'false' ?>">
				ファイルから入力
			</button>
		</li>
	</ul>
	<div class="tab-content" id="myTabContent">
		<div class="tab-pane<?= $default_pane === '' ? ' show active' : '' ?>" id="direct" role="tabpanel" aria-labelledby="direct-tab">
		<?= form_open('edit/' . $prompt_id) ?>
		<?= csrf_field() ?>
		<div class="mb-3 border border-top-0 rounded-bottom p-2">
			<div class="mb-3">
				<label for="title" class="form-label">タイトル <span class="text-danger" style="font-size:80%">(必須)</span></label>
				<input type="text" class="form-control" id="title" name="title" value="<?= set_value('title', $post_data['title'] ?? '') ?>" maxlength="256">
				<?= $validation->showError('title') ?>
			</div>
			<div class="mb-3">
				<label for="tags" class="form-label">タグ <span class="text-danger" style="font-size:80%">(必須)</span></label>
				<input type="text" class="form-control" id="tags" name="tags" value="<?= set_value('tags', isset($post_data['tags']) ? implode(' ', $post_data['tags']) : '') ?>" maxlength="1024">
				<div style="font-size: 75%;">各タグは半角または全角スペースで区切ってください。タグの個数には制限はありません。</div>
				<?= $validation->showError('tags') ?>
			</div>
			<div class="mb-3">
				<label for="description" class="form-label">説明 <span class="text-danger" style="font-size:80%">(必須)</span></label>
				<textarea class="form-control" id="description" name="description" rows="4" maxlength="2000"><?= set_value('description', $post_data['description'] ?? '') ?></textarea>
				<div style="font-size: 75%;">このプロンプトの使い方などの説明を、簡潔に記述してください。</div>
				<?= $validation->showError('description') ?>
			</div>
			<div class="mb-3">
				<label for="prompt" class="form-label">プロンプト(本文) <span class="text-danger" style="font-size:80%">(必須)</span></label>
				<textarea class="form-control" id="prompt" name="prompt" rows="6"><?= set_value('prompt', $post_data['prompt'] ?? '') ?></textarea>
				<?= $validation->showError('prompt') ?>
			</div>
			<div class="mb-3 row">
				<div class="col-lg-3 col-sm-6 mt-2">
					<input class="btn-check" type="checkbox" value="1" id="r18" name="r18" <?= (isset($post_data['r18']) && ($post_data['r18'] === '1')) ? 'checked="checked' : (set_checkbox('r18', '1', isset($post_data['r18']) ? ($post_data['r18'] === '1') : false)) ?> autocomplete="off">
					<label class="btn btn-outline-danger" for="r18" id="r18-text">
						現在は全年齢設定
					</label>
					<?= $validation->showError('r18') ?>
					<script>
						const toggleR18Button = function () {
							const check = document.getElementById('r18')
							const text = document.getElementById('r18-text')
							if (check.checked) {
								text.classList.add('btn-outline-danger')
								text.classList.remove('btn-outline-success')
								text.innerText = '現在はR-18設定'
							} else {
								text.classList.remove('btn-outline-danger')
								text.classList.add('btn-outline-success')
								text.innerText = '現在は全年齢設定'
							}
						}
						document.addEventListener('DOMContentLoaded', toggleR18Button)
						document.getElementById('r18').addEventListener('click', toggleR18Button)
					</script>
				</div>
				<div class="col-lg-3 col-sm-6 mt-2">
					<input class="btn-check" type="checkbox" value="1" id="draft" name="draft" <?= (isset($post_data['draft']) && ($post_data['draft'] === '1')) ? 'checked="checked' : (set_checkbox('draft', '1', isset($post_data['draft']) ? ($post_data['draft'] === '1') : false)) ?> autocomplete="off">
					<label class="btn btn-success" for="draft" id="draft-text">
						現在は公開設定
					</label>
					<?= $validation->showError('draft') ?>
					<script>
						const toggleDraftButton = function() {
							const check = document.getElementById('draft')
							const text = document.getElementById('draft-text')
							if (check.checked) {
								text.classList.add('btn-secondary')
								text.classList.remove('btn-success')
								text.innerText = '現在は非公開設定'
							} else {
								text.classList.remove('btn-secondary')
								text.classList.add('btn-success')
								text.innerText = '現在は公開設定'
							}
						}
						document.addEventListener('DOMContentLoaded', toggleDraftButton)
						document.getElementById('draft').addEventListener('click', toggleDraftButton)
					</script>
				</div>
				<div class="col-lg-3 col-sm-6 mt-2">
					<input class="btn-check" type="checkbox" value="1" id="comment" name="comment" <?= (isset($post_data['comment']) && ($post_data['comment'] === '1')) ? 'checked="checked' : (set_checkbox('comment', '1', isset($post_data['comment']) ? ($post_data['comment'] === '1') : false)) ?> autocomplete="off">
					<label class="btn btn-outline-secondary" for="comment" id="comment-text">
						現在はコメント不許可設定
					</label>
					<?= $validation->showError('comment') ?>
					<script>
						const toggleCommentButton = function() {
							const check = document.getElementById('comment')
							const text = document.getElementById('comment-text')
							if (check.checked) {
								text.classList.add('btn-success')
								text.classList.remove('btn-outline-secondary')
								text.innerText = '現在はコメント許可設定'
							} else {
								text.classList.remove('btn-success')
								text.classList.add('btn-outline-secondary')
								text.innerText = '現在はコメント不許可設定'
							}
						}
						document.addEventListener('DOMContentLoaded', toggleCommentButton)
						document.getElementById('comment').addEventListener('click', toggleCommentButton)
					</script>
				</div>
				<div class="col-lg-3 col-sm-6 mt-2">
					<input class="btn-check" type="checkbox" value="1" id="updated_at_for_sort" name="updated_at_for_sort" <?= (isset($post_data['updated_at_for_sort']) && ($post_data['updated_at_for_sort'] === '1')) ? 'checked="checked' : (set_checkbox('updated_at_for_sort', '1', isset($post_data['updated_at_for_sort']) ? ($post_data['updated_at_for_sort'] === '1') : false)) ?> autocomplete="off">
					<label class="btn btn-success" for="updated_at_for_sort" id="updated_at_for_sort-text">
						現在は更新順浮上あり設定
					</label>
					<?= $validation->showError('updated_at_for_sort') ?>
					<script>
						const toggleUpdateAtForSortButton = function() {
							const check = document.getElementById('updated_at_for_sort')
							const text = document.getElementById('updated_at_for_sort-text')
							if (check.checked) {
								text.classList.remove('btn-success')
								text.classList.add('btn-outline-danger')
								text.innerText = '現在は更新順浮上なし設定'
							} else {
								text.classList.add('btn-success')
								text.classList.remove('btn-outline-danger')
								text.innerText = '現在は更新順浮上あり設定'
							}
						}
						document.addEventListener('DOMContentLoaded', toggleUpdateAtForSortButton)
						document.getElementById('updated_at_for_sort').addEventListener('click', toggleUpdateAtForSortButton)
					</script>
				</div>
				<div class="mt-2" style="font-size: 75%;">R-18設定にすると、トップページや検索の「全年齢」の一覧には表示されません。</div>
				<div class="mt-1" style="font-size: 75%;">非公開設定にすると、公開状態に変更するまでトップページや検索の一覧には表示されません。</div>
				<div class="mt-1" style="font-size: 75%;">コメント許可設定にすると、プロンプト個別ページにコメント欄が表示されます。</div>
				<div class="mt-1" style="font-size: 75%;">更新順浮上なし設定にすると、プロンプトを更新しても更新日順ソートでの並び順が前回更新日のままになります。</div>
			</div>
			<hr>
			<div class="mb-3">
				<label class="form-label">プロンプトの改変可否</label>
				<div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="license" id="license1" value="1" <?= set_checkbox('license', '1', isset($post_data['license']) ? ($post_data['license'] === '1') : false) ?> autocomplete="off">
						<label class="form-check-label" for="license1">禁止</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="license" id="license2" value="2" <?= set_checkbox('license', '2', isset($post_data['license']) ? ($post_data['license'] === '2') : false) ?> autocomplete="off">
						<label class="form-check-label" for="license2">許可</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="license" id="license0" value="0" <?= set_checkbox('license', '0', isset($post_data['license']) ? ($post_data['license'] === '0') : true) ?> autocomplete="off">
						<label class="form-check-label" for="license0">独自の条件</label>
					</div>
				</div>
				<div class="mt-2" style="font-size: 75%;">
					公開したプロンプトを、他者が転載したり改変して公開したりすることを許可するかどうかの設定です。<br>
					※許可しない場合でも「個人で楽しむ範囲内で改変すること」は禁止できません。
					<ul>
						<li>禁止：転載・改変公開を一切許可しません</li>
						<li>許可：転載・改変公開を許可します。<a href="https://creativecommons.org/licenses/by-sa/4.0/deed.ja" target="_blank" rel="noopener noreferrer">クリエイティブコモンズ 表示-継承 4.0</a>であると明示します。</li>
						<li>独自の条件：上記以外の条件を設定します。条件は説明に書いてください。</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="mb-3 border rounded p-2">
			<div class="mb-3">
				<label for="memory" class="form-label">メモリ</label>
				<textarea class="form-control" id="memory" name="memory" rows="4" maxlength="2000"><?= set_value('memory', $post_data['memory'] ?? '') ?></textarea>
				<?= $validation->showError('memory') ?>
			</div>
			<div class="mb-3">
				<label for="authors_note" class="form-label">脚注</label>
				<textarea class="form-control" id="authors_note" name="authors_note" rows="4" maxlength="2000"><?= set_value('authors_note', $post_data['authors_note'] ?? '') ?></textarea>
				<?= $validation->showError('authors_note') ?>
			</div>
			<div class="mb-3">
				<label for="ng_words" class="form-label">禁止ワード</label>
				<textarea class="form-control" id="ng_words" name="ng_words" rows="4" maxlength="2000"><?= set_value('ng_words', $post_data['ng_words'] ?? '') ?></textarea>
				<?= $validation->showError('ng_words') ?>
			</div>
		</div>
		<div class="mb-3 border rounded p-2">
			<label class="form-label">キャラクターブック</label>
			<button type="button" class="btn btn-outline-secondary btn-sm" id="char_book_button">＋</button>
			<?php $char_book_max = 250 ?>
			<?php for ($i = 0; $i < $char_book_max; $i++) : ?>
				<div id="char_book_block[<?= $i ?>]" class="d-none">
					<?php if ($i !== 0) : ?>
						<hr>
					<?php endif ?>
					<input type="hidden" id="char_book[<?= $i ?>][id]" name="char_book[<?= $i ?>][id]" value="<?= $i ?>" disabled>
					<div class="row mb-3">
						<label class="col-md-1 col-form-label" for="char_book[<?= $i ?>][tag]">タグ</label>
						<div class="col-md-11">
							<input type="text" class="form-control" id="char_book[<?= $i ?>][tag]" name="char_book[<?= $i ?>][tag]" value="<?= set_value('char_book[' . $i . '][tag]', $post_data['char_book'][$i]['tag'] ?? '') ?>" maxlength="500" disabled>
							<?= $validation->showError('char_book.' . $i . '.tag') ?>
						</div>
					</div>
					<div class="row mb-3">
						<label class="col-md-1 col-form-label" for="char_book[<?= $i ?>][content]">説明</label>
						<div class="col-md-11">
							<textarea class="form-control" id="char_book[<?= $i ?>][content]" name="char_book[<?= $i ?>][content]" rows="4" maxlength="1000" disabled><?= set_value('char_book[' . $i . '][content]', $post_data['char_book'][$i]['content'] ?? '') ?></textarea>
							<?= $validation->showError('char_book.' . $i . '.content') ?>
						</div>
					</div>
				</div>
			<?php endfor ?>
			<script>
				document.addEventListener('DOMContentLoaded', function() {
					const script_area = document.getElementById('script-area');
					for (let i = 0; i < <?= $char_book_max ?>; i++) {
						const tag = document.getElementById('char_book[' + i + '][tag]'),
							content = document.getElementById('char_book[' + i + '][content]');
						if (tag.value !== '' || content.value !== '') {
							document.getElementById('char_book_block[' + i + ']').classList.remove('d-none')
							document.getElementById('char_book[' + i + '][id]').removeAttribute('disabled')
							tag.removeAttribute('disabled')
							content.removeAttribute('disabled')
						}
					}
				})
				document.getElementById('char_book_button').addEventListener('click', function() {
					const e = document.querySelector('div.d-none[id*="char_book_block"]')
					e.classList.remove('d-none')
					const i = e.id.replace('char_book_block[', '').replace(']', '')
					document.getElementById('char_book[' + i + '][id]').removeAttribute('disabled')
					document.getElementById('char_book[' + i + '][tag]').removeAttribute('disabled')
					document.getElementById('char_book[' + i + '][content]').removeAttribute('disabled')
				})
			</script>
		</div>
		<div class="mb-3 border rounded p-2">
			<label class="form-label">スクリプト</label>
			<button type="button" class="btn btn-outline-secondary btn-sm" id="script_button">＋</button>
			<?php $script_max = 100 ?>
			<?php for ($i = 0; $i < $script_max; $i++) : ?>
				<div id="script_block[<?= $i ?>]" class="d-none">
					<?php if ($i !== 0) : ?>
						<hr>
					<?php endif ?>
					<input type="hidden" id="script[<?= $i ?>][id]" name="script[<?= $i ?>][id]" value="<?= $i ?>" disabled>
					<div class="row mb-3">
						<label class="col-md-1 col-form-label" for="script[<?= $i ?>][type]">種別</label>
						<div class="col-md-11">
							<select class="form-control" id="script[<?= $i ?>][type]" name="script[<?= $i ?>][type]" disabled>
								<option value="script_in" <?= set_select('script[' . $i . '][type]', 'script_in', isset($post_data['script'][$i]['type']) ? ($post_data['script'][$i]['type'] === 'script_in') : true) ?>>入力文の置換</option>
								<option value="script_out" <?= set_select('script[' . $i . '][type]', 'script_out', isset($post_data['script'][$i]['type']) ? ($post_data['script'][$i]['type'] === 'script_out') : false) ?>>出力文の置換</option>
								<option value="script_in_pin" <?= set_select('script[' . $i . '][type]', 'script_in_pin', isset($post_data['script'][$i]['type']) ? ($post_data['script'][$i]['type'] === 'script_in_pin') : false) ?>>【最新】入力文の確定置換</option>
								<option value="script_in_pin_all" <?= set_select('script[' . $i . '][type]', 'script_in_pin_all', isset($post_data['script'][$i]['type']) ? ($post_data['script'][$i]['type'] === 'script_in_pin_all') : false) ?>>【本文全体】入力文の確定置換</option>
								<option value="script_rephrase" <?= set_select('script[' . $i . '][type]', 'script_rephrase', isset($post_data['script'][$i]['type']) ? ($post_data['script'][$i]['type'] === 'script_rephrase') : false) ?>>単語の言い換え</option>
								<option value="script_in_regex" <?= set_select('script[' . $i . '][type]', 'script_in_regex', isset($post_data['script'][$i]['type']) ? ($post_data['script'][$i]['type'] === 'script_in_regex') : false) ?>>入力文の置換（正規表現）</option>
								<option value="script_out_regex" <?= set_select('script[' . $i . '][type]', 'script_out_regex', isset($post_data['script'][$i]['type']) ? ($post_data['script'][$i]['type'] === 'script_out_regex') : false) ?>>出力文の置換（正規表現）</option>
								<option value="script_in_pin_regex" <?= set_select('script[' . $i . '][type]', 'script_in_pin_regex', isset($post_data['script'][$i]['type']) ? ($post_data['script'][$i]['type'] === 'script_in_pin_regex') : false) ?>>【最新】入力文の確定置換（正規表現）</option>
								<option value="script_in_pin_all_regex" <?= set_select('script[' . $i . '][type]', 'script_in_pin_all_regex', isset($post_data['script'][$i]['type']) ? ($post_data['script'][$i]['type'] === 'script_in_pin_all_regex') : false) ?>>【本文全体】入力文の確定置換（正規表現）</option>
								<option value="script_rephrase_regex" <?= set_select('script[' . $i . '][type]', 'script_rephrase_regex', isset($post_data['script'][$i]['type']) ? ($post_data['script'][$i]['type'] === 'script_rephrase_regex') : false) ?>>単語の言い換え（正規表現）</option>
								<option value="script_none" <?= set_select('script[' . $i . '][type]', 'script_none', isset($post_data['script'][$i]['type']) ? ($post_data['script'][$i]['type'] === 'script_none') : false) ?>>使用しない</option>
							</select>
							<?= $validation->showError('script' . $i . '.type') ?>
						</div>
					</div>
					<div class="row mb-3">
						<label class="col-md-1 col-form-label" for="script[<?= $i ?>][in]">IN</label>
						<div class="col-md-11">
							<input type="text" class="form-control" id="script[<?= $i ?>][in]" name="script[<?= $i ?>][in]" value="<?= set_value('script[' . $i . '][in]', $post_data['script'][$i]['in'] ?? '') ?>" disabled>
							<?= $validation->showError('script.' . $i . '.in') ?>
						</div>
					</div>
					<div class="row mb-3">
						<label class="col-md-1 col-form-label" for="script[<?= $i ?>][out]">OUT</label>
						<div class="col-md-11">
							<input type="text" class="form-control" id="script[<?= $i ?>][out]" name="script[<?= $i ?>][out]" value="<?= set_value('script[' . $i . '][out]', $post_data['script'][$i]['out'] ?? '') ?>" disabled>
							<?= $validation->showError('script.' . $i . '.out') ?>
						</div>
					</div>
				</div>
			<?php endfor ?>
			<script>
				document.addEventListener('DOMContentLoaded', function() {
					const script_area = document.getElementById('script-area');
					for (let i = 0; i < <?= $script_max ?>; i++) {
						const script_in = document.getElementById('script[' + i + '][in]'),
							script_out = document.getElementById('script[' + i + '][out]');
						if (script_in.value !== '' || script_out.value !== '') {
							document.getElementById('script_block[' + i + ']').classList.remove('d-none')
							document.getElementById('script[' + i + '][id]').removeAttribute('disabled')
							document.getElementById('script[' + i + '][type]').removeAttribute('disabled')
							script_in.removeAttribute('disabled')
							script_out.removeAttribute('disabled')
						}
					}
				})
				document.getElementById('script_button').addEventListener('click', function() {
					const e = document.querySelector('div.d-none[id*="script_block"]')
					e.classList.remove('d-none')
					const i = e.id.replace('script_block[', '').replace(']', '')
					document.getElementById('script[' + i + '][id]').removeAttribute('disabled')
					document.getElementById('script[' + i + '][type]').removeAttribute('disabled')
					document.getElementById('script[' + i + '][in]').removeAttribute('disabled')
					document.getElementById('script[' + i + '][out]').removeAttribute('disabled')
				})
			</script>
		</div>

		<div class="border rounded accordion-item mb-3">
			<h2 class="accordion-header" id="parameters-head">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#parameters-body" aria-expanded="false" aria-controls="parameters-body">
					パラメーター設定など
				</button>
			</h2>
			<div id="parameters-body" class="accordion-collapse collapse p-3" aria-labelledby="parameters-head">
				<div id="parameters" class="row">
					<h6 class="col-12">詳細パラメータ</h6>
					<div class="col-12 col-lg-6 col-xxl-4">
						<label for="temperature" class="form-label">ランダム度</label>
						<div class="row">
							<div class="col-10 col-lg-9"><input type="range" class="form-range" id="temperature" name="temperature" min="12" max="100" value="<?= set_value('temperature', $post_data['temperature'] ?? '31') ?>"></div>
							<div class="col-2 col-lg-3 text-center"><label id="temperature-label"></label></div>
						</div>
						<?= $validation->showError('temperature') ?>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4">
						<label for="top_p" class="form-label">トップP</label>
						<div class="row">
							<div class="col-10 col-lg-9"><input type="range" class="form-range" id="top_p" name="top_p" min="12" max="40" value="<?= set_value('top_p', $post_data['top_p'] ?? '29') ?>"></div>
							<div class="col-2 col-lg-3 text-center"><label id="top_p-label"></label></div>
						</div>
						<?= $validation->showError('top_p') ?>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4">
						<label for="tfs" class="form-label">テイルフリー</label>
						<div class="row">
							<div class="col-10 col-lg-9"><input type="range" class="form-range" id="tfs" name="tfs" min="-8" max="40" value="<?= set_value('tfs', $post_data['tfs'] ?? '40') ?>"></div>
							<div class="col-2 col-lg-3 text-center"><label id="tfs-label"></label></div>
						</div>
						<?= $validation->showError('tfs') ?>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4">
						<label for="freq_p" class="form-label">繰り返しペナルティ</label>
						<div class="row">
							<div class="col-10 col-lg-9"><input type="range" class="form-range" id="freq_p" name="freq_p" min="84" max="120" value="<?= set_value('freq_p', $post_data['freq_p'] ?? '93') ?>"></div>
							<div class="col-2 col-lg-3 text-center"><label id="freq_p-label"></label></div>
						</div>
						<?= $validation->showError('freq_p') ?>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4">
						<label for="length" class="form-label">出力の長さ</label>
						<div class="row">
							<div class="col-10 col-lg-9"><input type="range" class="form-range" id="length" name="length" min="20" max="150" value="<?= set_value('length', $post_data['length'] ?? '60') ?>"></div>
							<div class="col-2 col-lg-3 text-center"><label id="length-label"></label></div>
						</div>
						<?= $validation->showError('length') ?>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4">
						<label for="typical_p" class="form-label">タイピカルP</label>
						<div class="row">
							<div class="col-10 col-lg-9"><input type="range" class="form-range" id="typical_p" name="typical_p" min="80" max="100" value="<?= set_value('typical_p', $post_data['typical_p'] ?? '100') ?>"></div>
							<div class="col-2 col-lg-3 text-center"><label id="typical_p-label"></label></div>
						</div>
						<?= $validation->showError('typical_p') ?>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4">
						<label for="freq_p_range" class="form-label">繰り返しペナルティ（検索範囲）</label>
						<div class="row">
							<div class="col-10 col-lg-9"><input type="range" class="form-range" id="freq_p_range" name="freq_p_range" min="1" max="256" value="<?= set_value('freq_p_range', $post_data['freq_p_range'] ?? '128') ?>"></div>
							<div class="col-2 col-lg-3 text-center"><label id="freq_p_range-label"></label></div>
						</div>
						<?= $validation->showError('freq_p_range') ?>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4">
						<label for="freq_p_slope" class="form-label">繰り返しペナルティ（傾斜）</label>
						<div class="row">
							<div class="col-10 col-lg-9"><input type="range" class="form-range" id="freq_p_slope" name="freq_p_slope" min="1" max="200" value="<?= set_value('freq_p_slope', $post_data['freq_p_slope'] ?? '37') ?>"></div>
							<div class="col-2 col-lg-3 text-center"><label id="freq_p_slope-label"></label></div>
						</div>
						<?= $validation->showError('freq_p_slope') ?>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4">
						<label for="contextwindow" class="form-label">AIが読み取るコンテキストの長さ</label>
						<div class="row">
							<div class="col-10 col-lg-9"><input type="range" class="form-range" id="contextwindow" name="contextwindow" min="38" max="256" value="<?= set_value('contextwindow', $post_data['contextwindow'] ?? '256') ?>"></div>
							<div class="col-2 col-lg-3 text-center"><label id="contextwindow-label"></label></div>
						</div>
						<?= $validation->showError('contextwindow') ?>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4">
						<label for="wiplacement" class="form-label">キャラクターブックの優先度</label>
						<div class="row">
							<div class="col-10 col-lg-9"><input type="range" class="form-range" id="wiplacement" name="wiplacement" min="1" max="30" value="<?= set_value('wiplacement', $post_data['wiplacement'] ?? '30') ?>"></div>
							<div class="col-2 col-lg-3 text-center"><label id="wiplacement-label"></label></div>
						</div>
						<?= $validation->showError('wiplacement') ?>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4">
						<label for="anplacement" class="form-label">脚注の優先度</label>
						<div class="row">
							<div class="col-10 col-lg-9"><input type="range" class="form-range" id="anplacement" name="anplacement" min="1" max="20" value="<?= set_value('anplacement', $post_data['anplacement'] ?? '3') ?>"></div>
							<div class="col-2 col-lg-3 text-center"><label id="anplacement-label"></label></div>
						</div>
						<?= $validation->showError('anplacement') ?>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4">
						<label for="wiscanrange" class="form-label">キャラクターブックをスキャンする文字数</label>
						<div class="row">
							<div class="col-10 col-lg-9"><input type="range" class="form-range" id="wiscanrange" name="wiscanrange" min="1" max="1024" value="<?= set_value('wiscanrange', $post_data['wiscanrange'] ?? '128') ?>"></div>
							<div class="col-2 col-lg-3 text-center"><label id="wiscanrange-label"></label></div>
						</div>
						<?= $validation->showError('wiscanrange') ?>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4">
						<label for="dialogue_density" class="form-label">セリフの量</label>
						<div class="row">
							<div class="col-10 col-lg-9"><input type="range" class="form-range" id="dialogue_density" name="dialogue_density" min="1" max="20" value="<?= set_value('dialogue_density', $post_data['dialogue_density'] ?? '20') ?>"></div>
							<div class="col-2 col-lg-3 text-center"><label id="dialogue_density-label"></label></div>
						</div>
						<?= $validation->showError('dialogue_density') ?>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4">
						<label for="parenthesis_density" class="form-label">括弧書きの量</label>
						<div class="row">
							<div class="col-10 col-lg-9"><input type="range" class="form-range" id="parenthesis_density" name="parenthesis_density" min="1" max="20" value="<?= set_value('parenthesis_density', $post_data['parenthesis_density'] ?? '20') ?>"> </div>
							<div class="col-2 col-lg-3 text-center"><label id="parenthesis_density-label"></label></div>
						</div>
						<?= $validation->showError('parenthesis_density') ?>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4">
						<label for="periods_density" class="form-label">3点リードの量</label>
						<div class="row">
							<div class="col-10 col-lg-9"><input type="range" class="form-range" id="periods_density" name="periods_density" min="1" max="20" value="<?= set_value('periods_density', $post_data['periods_density'] ?? '20') ?>"></div>
							<div class="col-2 col-lg-3 text-center"><label id="periods_density-label"></label></div>
						</div>
						<?= $validation->showError('periods_density') ?>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4">
						<label for="br_density" class="form-label">改行の量</label>
						<div class="row">
							<div class="col-10 col-lg-9"><input type="range" class="form-range" id="br_density" name="br_density" min="1" max="20" value="<?= set_value('br_density', $post_data['br_density'] ?? '20') ?>"></div>
							<div class="col-2 col-lg-3 text-center"><label id="br_density-label"></label></div>
						</div>
						<?= $validation->showError('br_density') ?>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4">
						<label for="comma_density" class="form-label">読点の量</label>
						<div class="row">
							<div class="col-10 col-lg-9"><input type="range" class="form-range" id="comma_density" name="comma_density" min="1" max="26" value="<?= set_value('comma_density', $post_data['comma_density'] ?? '20') ?>"></div>
							<div class="col-2 col-lg-3 text-center"><label id="comma_density-label"></label></div>
						</div>
						<?= $validation->showError('comma_density') ?>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4">
						<label for="long_term_memory" class="form-label">ロングタームメモリ</label>
						<div class="row">
							<div class="col-10 col-lg-9"><input type="range" class="form-range" id="long_term_memory" name="long_term_memory" min="0" max="4" value="<?= set_value('long_term_memory', $post_data['long_term_memory'] ?? '0') ?>"></div>
							<div class="col-2 col-lg-3 text-center"><label id="long_term_memory-label"></label></div>
						</div>
						<?= $validation->showError('long_term_memory') ?>
					</div>
					<script>
						const changeRangeLabel = function () {
							const long_term_label = [
								'なし', '低', '中', '高', '最大'
							]
							const funcList = {
								'temperature': val => Number(val) / 40,
								'top_p': val => Number(val) / 40,
								'tfs': val => 0.75 + Number(val) / 160,
								'freq_p': val => Number(val) / 80,
								'length': val => '約' + Math.trunc(Number(val) * 2.5) + '文字',
								'typical_p': val => Number(val) <= 99 ? Number(val) / 100 : '-',
								'freq_p_range': val => Number(val) * 8,
								'freq_p_slope': val => Number(val) / 20,
								'contextwindow': val => '約' + Math.trunc(Number(val) * 20) + '文字',
								'wiplacement': val => Number(val) >= 30 ? '本文の後ろ' : (Number(val) * 2),
								'anplacement': val => val,
								'wiscanrange': val => Number(val) * 8,
								'dialogue_density': val => Number(val) / 0.2 + '%',
								'parenthesis_density': val => Number(val) / 0.2 + '%',
								'periods_density': val => Number(val) / 0.2 + '%',
								'br_density': val => Number(val) / 0.2 + '%',
								'comma_density': val => Number(val) / 0.2 + '%',
								'long_term_memory': val => long_term_label[val] ? long_term_label[val] : long_term_label[0],
							}
							for (const input of document.querySelectorAll('#parameters input')) {
								document.getElementById(input.id + '-label').innerText = funcList[input.id](input.value)
							}
						}
						for (const input of document.querySelectorAll('#parameters input')) {
							input.addEventListener('change', function () {
								changeRangeLabel()
							})
							input.addEventListener('input', function () {
								changeRangeLabel()
							})
						}
						changeRangeLabel()
					</script>
				</div>
				<div class="row">
					<h6 class="col-12">GUIモード / チャット設定</h6>
					<div class="col-lg-3 col-sm-6 mt-2">
						<input class="btn-check" type="checkbox" value="1" id="gui_mode" name="gui_mode" <?= (isset($post_data['gui_mode']) && ($post_data['gui_mode'] === '1')) ? 'checked="checked' : (set_checkbox('gui_mode', '1', isset($post_data['gui_mode']) ? ($post_data['gui_mode'] === '1') : false)) ?> autocomplete="off">
						<label class="btn btn-primary" for="gui_mode" id="gui_mode-text">
							現在はノベル設定
						</label>
						<?= $validation->showError('gui_mode') ?>
						<script>
							const toggleGuiModeButton = function() {
								const check = document.getElementById('gui_mode')
								const text = document.getElementById('gui_mode-text')
								if (check.checked) {
									text.classList.add('btn-success')
									text.classList.remove('btn-primary')
									text.innerText = '現在はチャット設定'
								} else {
									text.classList.remove('btn-success')
									text.classList.add('btn-primary')
									text.innerText = '現在はノベル設定'
								}
							}
							document.addEventListener('DOMContentLoaded', toggleGuiModeButton)
							document.getElementById('gui_mode').addEventListener('click', toggleGuiModeButton)
						</script>
					</div>
					<div class="col-lg-3 col-sm-6 mt-2">
						<input class="btn-check" type="checkbox" value="1" id="chat_auto_enter" name="chat_auto_enter" <?= (isset($post_data['chat_auto_enter']) && ($post_data['chat_auto_enter'] === '1')) ? 'checked="checked' : (set_checkbox('chat_auto_enter', '1', isset($post_data['chat_auto_enter']) ? ($post_data['chat_auto_enter'] === '1') : false)) ?> autocomplete="off">
						<label class="btn btn-outline-secondary" for="chat_auto_enter" id="chat_auto_enter-text">
							現在は自動改行する
						</label>
						<?= $validation->showError('chat_auto_enter') ?>
						<script>
							const toggleChatAutoEnterButton = function() {
								const check = document.getElementById('chat_auto_enter')
								const text = document.getElementById('chat_auto_enter-text')
								if (check.checked) {
									text.classList.add('btn-outline-secondary')
									text.classList.remove('btn-success')
									text.innerText = '現在は自動改行しない'
								} else {
									text.classList.remove('btn-outline-secondary')
									text.classList.add('btn-success')
									text.innerText = '現在は自動改行する'
								}
							}
							document.addEventListener('DOMContentLoaded', toggleChatAutoEnterButton)
							document.getElementById('chat_auto_enter').addEventListener('click', toggleChatAutoEnterButton)
						</script>
					</div>
					<div class="col-lg-3 col-sm-6 mt-2">
						<input class="btn-check" type="checkbox" value="1" id="chat_auto_brackets" name="chat_auto_brackets" <?= (isset($post_data['chat_auto_brackets']) && ($post_data['chat_auto_brackets'] === '1')) ? 'checked="checked' : (set_checkbox('chat_auto_brackets', '1', isset($post_data['chat_auto_brackets']) ? ($post_data['chat_auto_brackets'] === '1') : false)) ?> autocomplete="off">
						<label class="btn btn-outline-secondary" for="chat_auto_brackets" id="chat_auto_brackets-text">
							現在は自動括弧なし
						</label>
						<?= $validation->showError('chat_auto_brackets') ?>
						<script>
							const toggleChatAutoBracketsButton = function() {
								const check = document.getElementById('chat_auto_brackets')
								const text = document.getElementById('chat_auto_brackets-text')
								if (check.checked) {
									text.classList.add('btn-success')
									text.classList.remove('btn-outline-secondary')
									text.innerText = '現在は自動括弧あり'
								} else {
									text.classList.remove('btn-success')
									text.classList.add('btn-outline-secondary')
									text.innerText = '現在は自動括弧なし'
								}
							}
							document.addEventListener('DOMContentLoaded', toggleChatAutoBracketsButton)
							document.getElementById('chat_auto_brackets').addEventListener('click', toggleChatAutoBracketsButton)
						</script>
					</div>
				</div>
				<div class="mt-2">
					<label for="chat_template" class="form-label">チャットテンプレート</label>
					<textarea class="form-control" id="chat_template" name="chat_template" rows="3"><?= set_value('chat_template', $post_data['chat_template'] ?? '') ?></textarea>
					<?= $validation->showError('chat_template') ?>
				</div>
			</div>
		</div>

		<div class="text-center">
			<button type="submit" class="btn btn-primary">編集内容確認</button>
		</div>
		<?= form_close() ?>
	</div>
	<div class="tab-pane<?= $default_pane === 'file' ? ' show active' : '' ?>" id="file-upload" role="tabpanel" aria-labelledby="file-upload-tab">
			<?= form_open_multipart('edit/' . $prompt_id . '/file') ?>
			<?= csrf_field() ?>
			<div class="mb-3 border border-top-0 rounded-bottom p-2">
				<div class="mb-3">
					<div class="mb-1">※ファイルから読み込みを行うと、既存の項目をすべて削除したのちにファイルのデータで上書きします。</div>
					<label for="novel_file" class="form-label">ファイル(10MBまで) <span class="text-danger" style="font-size:80%">(必須)</span></label>
					<input type="file" class="form-control" id="novel_file" name="novel_file" accept=".novel">
					<?= $validation->showError('novel_file') ?>
					<?php if (! empty($file_verify_error)) : ?>
						<div class="alert alert-danger" style="margin: 5px 0;" role="alert">
							ファイル検証エラー
							<ul>
								<?php foreach ($validation->getErrors() as $error) : ?>
									<li><?= esc($error) ?></li>
								<?php endforeach ?>
							</ul>
						</div>
					<?php endif ?>
				</div>
				<div class="mb-3">
					<label for="tags-file" class="form-label">タグ <span class="text-danger" style="font-size:80%">(必須)</span></label>
					<input type="text" class="form-control" id="tags-file" name="tags-file" value="<?= set_value('tags-file', isset($post_data['tags-file']) ? implode(' ', $post_data['tags-file']) : '') ?>" maxlength="1024">
					<div style="font-size: 75%;">各タグは半角または全角スペースで区切ってください。タグの個数には制限はありません。</div>
					<?= $validation->showError('tags-file') ?>
				</div>
				<div class="mb-3">
					<label for="description-file" class="form-label">説明 <span class="text-danger" style="font-size:80%">(必須)</span></label>
					<textarea class="form-control" id="description-file" name="description-file" rows="4" maxlength="2000"><?= set_value('description-file', $post_data['description-file'] ?? '') ?></textarea>
					<div style="font-size: 75%;">このプロンプトの使い方などの説明を、簡潔に記述してください。</div>
					<?= $validation->showError('description-file') ?>
				</div>
				<div class="mb-3 row">
					<div class="col-lg-3 col-sm-6 mt-2">
						<input class="btn-check" type="checkbox" value="1" id="r18-file" name="r18-file" <?= (isset($post_data['r18-file']) && ($post_data['r18-file'] === '1')) ? 'checked="checked' : (set_checkbox('r18-file', '1', isset($post_data['r18-file']) ? ($post_data['r18-file'] === '1') : false)) ?> autocomplete="off">
						<label class="btn btn-outline-danger" for="r18-file" id="r18-file-text">
							現在は全年齢設定
						</label>
						<?= $validation->showError('r18-file') ?>
						<script>
							const toggleR18Button2 = function() {
								const check = document.getElementById('r18-file')
								const text = document.getElementById('r18-file-text')
								if (check.checked) {
									text.classList.add('btn-outline-danger')
									text.classList.remove('btn-outline-success')
									text.innerText = '現在はR-18設定'
								} else {
									text.classList.remove('btn-outline-danger')
									text.classList.add('btn-outline-success')
									text.innerText = '現在は全年齢設定'
								}
							}
							document.addEventListener('DOMContentLoaded', toggleR18Button2)
							document.getElementById('r18-file').addEventListener('click', toggleR18Button2)
						</script>
					</div>
					<div class="col-lg-3 col-sm-6 mt-2">
						<input class="btn-check" type="checkbox" value="1" id="draft-file" name="draft-file" <?= (isset($post_data['draft-file']) && ($post_data['draft-file'] === '1')) ? 'checked="checked' : (set_checkbox('draft-file', '1', isset($post_data['draft-file']) ? ($post_data['draft-file'] === '1') : false)) ?> autocomplete="off">
						<label class="btn btn-success" for="draft-file" id="draft-file-text">
							現在は公開設定
						</label>
						<?= $validation->showError('draft-file') ?>
						<script>
							const toggleDraftFileButton = function() {
								const check = document.getElementById('draft-file')
								const text = document.getElementById('draft-file-text')
								if (check.checked) {
									text.classList.add('btn-secondary')
									text.classList.remove('btn-success')
									text.innerText = '現在は非公開設定'
								} else {
									text.classList.remove('btn-secondary')
									text.classList.add('btn-success')
									text.innerText = '現在は公開設定'
								}
							}
							document.addEventListener('DOMContentLoaded', toggleDraftFileButton)
							document.getElementById('draft-file').addEventListener('click', toggleDraftFileButton)
						</script>
					</div>
					<div class="col-lg-3 col-sm-6 mt-2">
						<input class="btn-check" type="checkbox" value="1" id="comment-file" name="comment-file" <?= (isset($post_data['comment-file']) && ($post_data['comment-file'] === '1')) ? 'checked="checked' : (set_checkbox('comment-file', '1', isset($post_data['comment-file']) ? ($post_data['comment-file'] === '1') : false)) ?> autocomplete="off">
						<label class="btn btn-outline-secondary" for="comment-file" id="comment-file-text">
							現在はコメント不許可設定
						</label>
						<?= $validation->showError('comment-file') ?>
						<script>
							const toggleCommentFileButton = function() {
								const check = document.getElementById('comment-file')
								const text = document.getElementById('comment-file-text')
								if (check.checked) {
									text.classList.add('btn-success')
									text.classList.remove('btn-outline-secondary')
									text.innerText = '現在はコメント許可設定'
								} else {
									text.classList.remove('btn-success')
									text.classList.add('btn-outline-secondary')
									text.innerText = '現在はコメント不許可設定'
								}
							}
							document.addEventListener('DOMContentLoaded', toggleCommentFileButton)
							document.getElementById('comment-file').addEventListener('click', toggleCommentFileButton)
						</script>
					</div>
					<div class="col-lg-3 col-sm-6 mt-2">
						<input class="btn-check" type="checkbox" value="1" id="updated_at_for_sort-file" name="updated_at_for_sort-file" <?= (isset($post_data['updated_at_for_sort-file']) && ($post_data['updated_at_for_sort-file'] === '1')) ? 'checked="checked' : (set_checkbox('updated_at_for_sort-file', '1', isset($post_data['updated_at_for_sort-file']) ? ($post_data['updated_at_for_sort-file'] === '1') : true)) ?> autocomplete="off">
						<label class="btn btn-success" for="updated_at_for_sort-file" id="updated_at_for_sort-file-text">
							現在は更新順浮上あり設定
						</label>
						<?= $validation->showError('updated_at_for_sort-file') ?>
						<script>
							const toggleUpdateAtForSortFileButton = function() {
								const check = document.getElementById('updated_at_for_sort-file')
								const text = document.getElementById('updated_at_for_sort-file-text')
								if (check.checked) {
									text.classList.remove('btn-success')
									text.classList.add('btn-outline-danger')
									text.innerText = '現在は更新順浮上なし設定'
								} else {
									text.classList.add('btn-success')
									text.classList.remove('btn-outline-danger')
									text.innerText = '現在は更新順浮上あり設定'
								}
							}
							document.addEventListener('DOMContentLoaded', toggleUpdateAtForSortFileButton)
							document.getElementById('updated_at_for_sort-file').addEventListener('click', toggleUpdateAtForSortFileButton)
						</script>
					</div>
					<div class="mt-2" style="font-size: 75%;">R-18設定にすると、トップページや検索の「全年齢」の一覧には表示されません。</div>
					<div class="mt-1" style="font-size: 75%;">非公開設定にすると、公開状態に変更するまでトップページや検索の一覧には表示されません。</div>
					<div class="mt-1" style="font-size: 75%;">コメント許可設定にすると、プロンプト個別ページにコメント欄が表示されます。</div>
					<div class="mt-1" style="font-size: 75%;">更新順浮上なし設定にすると、プロンプトを更新しても更新日順ソートでの並び順が前回更新日のままになります。</div>
				</div>
				<hr>
				<div class="mb-3">
					<label class="form-label">プロンプトの改変可否</label>
					<div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="license-file" id="license-file1" value="1" <?= set_checkbox('license-file', '1', isset($post_data['license-file']) ? ($post_data['license-file'] === '1') : false) ?> autocomplete="off">
							<label class="form-check-label" for="license-file1">禁止</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="license-file" id="license-file2" value="2" <?= set_checkbox('license-file', '2', isset($post_data['license-file']) ? ($post_data['license-file'] === '2') : false) ?> autocomplete="off">
							<label class="form-check-label" for="license-file2">許可</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="license-file" id="license-file0" value="0" <?= set_checkbox('license-file', '0', isset($post_data['license-file']) ? ($post_data['license-file'] === '0') : true) ?> autocomplete="off">
							<label class="form-check-label" for="license-file0">独自の条件</label>
						</div>
					</div>
					<div class="mt-2" style="font-size: 75%;">
						公開したプロンプトを、他者が転載したり改変して公開したりすることを許可するかどうかの設定です。<br>
						※許可しない場合でも「個人で楽しむ範囲内で改変すること」は禁止できません。
						<ul>
							<li>禁止：転載・改変公開を一切許可しません</li>
							<li>許可：転載・改変公開を許可します。<a href="https://creativecommons.org/licenses/by-sa/4.0/deed.ja" target="_blank" rel="noopener noreferrer">クリエイティブコモンズ 表示-継承 4.0</a>であると明示します。</li>
							<li>独自の条件：上記以外の条件を設定します。条件は説明に書いてください。</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="text-center">
				<button type="submit" class="btn btn-primary">投稿内容確認</button>
			</div>
			<?= form_close() ?>
		</div>
	</div>
</main>
<?= $this->endSection() ?>