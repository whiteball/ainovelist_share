<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<?= $this->include('header_nav') ?>
<main class="container">
	<h1 class="h3 mb-3 fw-normal">プロンプト編集</h1>
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
	<?= form_open() ?>
	<?= csrf_field() ?>
	<div class="mb-3 border rounded p-2">
		<div class="mb-3">
			<label for="title" class="form-label">タイトル (必須)</label>
			<input type="text" class="form-control" id="title" name="title" value="<?= set_value('title', $post_data['title'] ?? '') ?>">
			<?= $validation->showError('title') ?>
		</div>
		<div class="mb-3">
			<label for="description" class="form-label">説明 (必須)</label>
			<textarea class="form-control" id="description" name="description" rows="4" maxlength="2000"><?= set_value('description', $post_data['description'] ?? '') ?></textarea>
			<?= $validation->showError('description') ?>
		</div>
		<div class="mb-3">
			<label for="prompt" class="form-label">プロンプト(本文) (必須)</label>
			<textarea class="form-control" id="prompt" name="prompt" rows="6"><?= set_value('prompt', $post_data['prompt'] ?? '') ?></textarea>
			<?= $validation->showError('prompt') ?>
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
					<label class="col-md-1 col-form-label" for="script[<?= $i ?>][type]">種類</label>
					<div class="col-md-11">
						<select class="form-control" id="script[<?= $i ?>][type]" name="script[<?= $i ?>][type]" disabled>
							<option value="script_in" <?= set_select('script[' . $i . '][type]', 'script_in', isset($post_data['script'][$i]['type']) ? ($post_data['script'][$i]['type'] === 'script_in') : true) ?>>入力文の置換</option>
							<option value="script_out" <?= set_select('script[' . $i . '][type]', 'script_out', isset($post_data['script'][$i]['type']) ? ($post_data['script'][$i]['type'] === 'script_out') : false) ?>>出力文の置換</option>
							<option value="script_in_pin" <?= set_select('script[' . $i . '][type]', 'script_in_pin', isset($post_data['script'][$i]['type']) ? ($post_data['script'][$i]['type'] === 'script_in_pin') : false) ?>>最新入力文の確定置換</option>
							<option value="script_in_regexp" <?= set_select('script[' . $i . '][type]', 'script_in_regexp', isset($post_data['script'][$i]['type']) ? ($post_data['script'][$i]['type'] === 'script_in_regexp') : false) ?>>入力文の置換（正規表現）</option>
							<option value="script_out_regexp" <?= set_select('script[' . $i . '][type]', 'script_out_regexp', isset($post_data['script'][$i]['type']) ? ($post_data['script'][$i]['type'] === 'script_out_regexp') : false) ?>>出力文の置換（正規表現）</option>
							<option value="script_in_pin_regexp" <?= set_select('script[' . $i . '][type]', 'script_in_pin_regexp', isset($post_data['script'][$i]['type']) ? ($post_data['script'][$i]['type'] === 'script_in_pin_regexp') : false) ?>>最新入力文の確定置換（正規表現）</option>
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

	<button type="submit" class="btn btn-primary">投稿内容確認</button>
	<?= form_close() ?>
</main>
<?= $this->endSection() ?>