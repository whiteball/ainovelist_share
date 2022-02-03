<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<h1 class="h3 mb-3 fw-normal">マイページ</h1>

	<?= form_open('logout') ?>
	<?= csrf_field() ?>
	<button type="submit" class="btn btn-outline-danger">ログアウト</button>
	<?= form_close() ?>
	<hr>
	<?= form_open() ?>
	<?= csrf_field() ?>
	<?= form_hidden('type', 'change_name') ?>
	<div class="mb-3">
		<label for="screen-name" class="form-label">ユーザー名変更</label>
		<input type="text" class="form-control" id="screen-name" name="screen_name" value="<?= set_value('screen_name', $screen_name); ?>">
		<?= $validation->showError('screen_name') ?>
	</div>
	<?php if (! empty($success_message)) : ?>
		<div class="alert alert-success"><?= esc($success_message) ?></div>
	<?php endif ?>
	<button type="submit" class="btn btn-primary">変更</button>
	<?= form_close() ?>
	<hr>
	<h4 class="h4 fw-normal">パスワード変更</h4>
	<?= form_open() ?>
	<?= csrf_field() ?>
	<?= form_hidden('type', 'change_password') ?>
	<div class="mb-3 row">
		<label for="current-password" class="col-sm-2 col-form-label">現在のパスワード</label>
		<div class="col-sm-10">
			<input type="password" class="form-control" id="current-password" name="current_password">
			<?= $validation->showError('current_password') ?>
		</div>
	</div>
	<div class="mb-3 row">
		<label for="new-password" class="col-sm-2 col-form-label">新しいパスワード</label>
		<div class="col-sm-10">
			<input type="password" class="form-control" id="new-password" name="new_password">
			<?= $validation->showError('new_password') ?>
		</div>
	</div>
	<div class="mb-3 row">
		<label for="new-password-confirm" class="col-sm-2 col-form-label">新しいパスワード(再入力)</label>
		<div class="col-sm-10">
			<input type="password" class="form-control" id="new-password-confirm" name="new_password_confirm">
			<?= $validation->showError('new_password_confirm') ?>
		</div>
	</div>
	<?php if (! empty($success_message2)) : ?>
		<div class="alert alert-success"><?= esc($success_message2) ?></div>
	<?php endif ?>
	<?php if (! empty($error_message2)) : ?>
		<div class="alert alert-danger"><?= esc($error_message2) ?></div>
	<?php endif ?>
	<button type="submit" class="btn btn-primary">変更</button>
	<?= form_close() ?>
	<hr>
	<h4 class="h4 fw-normal">投稿したプロンプト</h4>
	<div style="font-size: 85%;">編集/削除はタイトルをクリック/タップしてください。<br>作品ページへ行くには投稿日をクリック/タップしてください。<br>非公開設定のプロンプトはタイトルに「【非公開】」と表示しています。</div>
	<table class="table">
		<thead>
			<tr>
				<th scope="col" class="text-center">タイトル</th>
				<th scope="col" class="text-center">投稿日</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($prompts as $prompt) : ?>
				<tr scope="row">
					<td style="word-break: break-all;overflow-wrap: break-word;">
						<div class="d-grid gap-2">
							<a href="<?= site_url('edit/' . $prompt->id) ?>" class="btn btn-outline-success"><?= $prompt->draft === '1' ? '【非公開】 ' : '' ?><?= esc(strip_tags($prompt->title)) ?></a>
						</div>
					</td>
					<td class="text-center" style="width: 11rem;">
						<a href="<?= site_url('prompt/' . $prompt->id) ?>" class="link-secondary"><?= esc($prompt->registered_at) ?></a>
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>

</main>
<?= $this->endSection() ?>