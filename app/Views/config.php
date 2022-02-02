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
	<h4>投稿したプロンプト</h4>
	<div>編集/削除はタイトルをクリック/タップしてください。</div>
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
							<a href="<?= site_url('edit/' . $prompt->id) ?>" class="btn btn-outline-success"><?= esc($prompt->title) ?></a>
						</div>
					</td>
					<td class="text-center" style="width: 11rem;"><?= esc($prompt->registered_at) ?></td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>

</main>
<?= $this->endSection() ?>