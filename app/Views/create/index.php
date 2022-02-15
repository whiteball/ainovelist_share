<?= $this->extend('template') ?>
<?= $this->section('title') ?> - プロンプト投稿<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<h1 class="h3 mb-3 fw-normal">プロンプト投稿</h1>
	<?php if (isset($error_message)) : ?>
		<div class="alert alert-danger"><?= nl2br(esc($error_message)) ?></div>
	<?php endif ?>


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
			<?= form_open('create') ?>
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
					<?= $validation->showError('description') ?>
				</div>
				<div class="mb-3">
					<label for="prompt" class="form-label">プロンプト(本文) <span class="text-danger" style="font-size:80%">(必須)</span></label>
					<textarea class="form-control" id="prompt" name="prompt" rows="6"><?= set_value('prompt', $post_data['prompt'] ?? '') ?></textarea>
					<?= $validation->showError('prompt') ?>
				</div>
				<div class="mb-3">
					<input class="btn-check" type="checkbox" value="1" id="r18" name="r18" <?= set_checkbox('r18', '1', isset($post_data['r18']) ? ($post_data['r18'] === '1') : false) ?> autocomplete="off">
					<label class="btn btn-outline-danger" for="r18" id="r18-text">
						現在は全年齢設定
					</label>
					<?= $validation->showError('r18') ?>
					<script>
						const toggleR18Button = function() {
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
					<input class="btn-check" type="checkbox" value="1" id="draft" name="draft" <?= set_checkbox('draft', '1', isset($post_data['draft']) ? ($post_data['draft'] === '1') : false) ?> autocomplete="off">
					<label class="btn btn-success ms-4" for="draft" id="draft-text">
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
					<div class="mt-2" style="font-size: 75%;">R-18設定にすると、トップページや検索の「全年齢」の一覧には表示されません。</div>
					<div class="mt-1" style="font-size: 75%;">非公開設定にすると、公開状態に変更するまでトップページや検索の一覧には表示されません。</div>
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
								<?= $validation->showError('char_book[' . $i . '][tag]') ?>
							</div>
						</div>
						<div class="row mb-3">
							<label class="col-md-1 col-form-label" for="char_book[<?= $i ?>][content]">説明</label>
							<div class="col-md-11">
								<textarea class="form-control" id="char_book[<?= $i ?>][content]" name="char_book[<?= $i ?>][content]" rows="4" maxlength="1000" disabled><?= set_value('char_book[' . $i . '][content]', $post_data['char_book'][$i]['content'] ?? '') ?></textarea>
								<?= $validation->showError('char_book[' . $i . '][content]') ?>
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
									<option value="script_in_pin" <?= set_select('script[' . $i . '][type]', 'script_in_pin', isset($post_data['script'][$i]['type']) ? ($post_data['script'][$i]['type'] === 'script_in_pin') : false) ?>>最新入力文の確定置換</option>
									<option value="script_in_regex" <?= set_select('script[' . $i . '][type]', 'script_in_regex', isset($post_data['script'][$i]['type']) ? ($post_data['script'][$i]['type'] === 'script_in_regex') : false) ?>>入力文の置換（正規表現）</option>
									<option value="script_out_regex" <?= set_select('script[' . $i . '][type]', 'script_out_regex', isset($post_data['script'][$i]['type']) ? ($post_data['script'][$i]['type'] === 'script_out_regex') : false) ?>>出力文の置換（正規表現）</option>
									<option value="script_in_pin_regex" <?= set_select('script[' . $i . '][type]', 'script_in_pin_regex', isset($post_data['script'][$i]['type']) ? ($post_data['script'][$i]['type'] === 'script_in_pin_regex') : false) ?>>最新入力文の確定置換（正規表現）</option>
									<option value="script_none" <?= set_select('script[' . $i . '][type]', 'script_none', isset($post_data['script'][$i]['type']) ? ($post_data['script'][$i]['type'] === 'script_none') : false) ?>>使用しない</option>
								</select>
								<?= $validation->showError('script[' . $i . '][type]') ?>
							</div>
						</div>
						<div class="row mb-3">
							<label class="col-md-1 col-form-label" for="script[<?= $i ?>][in]">IN</label>
							<div class="col-md-11">
								<input type="text" class="form-control" id="script[<?= $i ?>][in]" name="script[<?= $i ?>][in]" value="<?= set_value('script[' . $i . '][in]', $post_data['script'][$i]['in'] ?? '') ?>" disabled>
								<?= $validation->showError('script[' . $i . '][in]') ?>
							</div>
						</div>
						<div class="row mb-3">
							<label class="col-md-1 col-form-label" for="script[<?= $i ?>][out]">OUT</label>
							<div class="col-md-11">
								<input type="text" class="form-control" id="script[<?= $i ?>][out]" name="script[<?= $i ?>][out]" value="<?= set_value('script[' . $i . '][out]', $post_data['script'][$i]['out'] ?? '') ?>" disabled>
								<?= $validation->showError('script[' . $i . '][out]') ?>
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

			<div class="text-center">
				<button type="submit" class="btn btn-primary">投稿内容確認</button>
			</div>
			<?= form_close() ?>
		</div>
		<div class="tab-pane<?= $default_pane === 'file' ? ' show active' : '' ?>" id="file-upload" role="tabpanel" aria-labelledby="file-upload-tab">
			<?= form_open_multipart('create/file') ?>
			<?= csrf_field() ?>
			<div class="mb-3 border border-top-0 rounded-bottom p-2">
				<div class="mb-3">
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
					<div style="font-size: 75%;">各タグは半角または全角スペースで区切ってください</div>
					<?= $validation->showError('tags-file') ?>
				</div>
				<div class="mb-3">
					<label for="description-file" class="form-label">説明 <span class="text-danger" style="font-size:80%">(必須)</span></label>
					<textarea class="form-control" id="description-file" name="description-file" rows="4" maxlength="2000"><?= set_value('description-file', $post_data['description-file'] ?? '') ?></textarea>
					<?= $validation->showError('description-file') ?>
				</div>
				<div class="mb-3">
					<input class="btn-check" type="checkbox" value="1" id="r18-file" name="r18-file" <?= set_checkbox('r18-file', '1', isset($post_data['r18-file']) ? ($post_data['r18-file'] === '1') : false) ?> autocomplete="off">
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
					<input class="btn-check" type="checkbox" value="1" id="draft-file" name="draft-file" <?= set_checkbox('draft-file', '1', isset($post_data['draft-file']) ? ($post_data['draft-file'] === '1') : false) ?> autocomplete="off">
					<label class="btn btn-success ms-4" for="draft-file" id="draft-file-text">
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
					<div class="mt-2" style="font-size: 75%;">R-18設定にすると、トップページや検索の「全年齢」の一覧には表示されません。</div>
					<div class="mt-1" style="font-size: 75%;">非公開設定にすると、公開状態に変更するまでトップページや検索の一覧には表示されません。</div>
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