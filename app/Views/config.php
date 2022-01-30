<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<?= $this->include('header_nav') ?>
<main class="form-register">
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
		<?= $validation->showError('screen_name')?>
	</div>
	<?php if (! empty($success_message)): ?>
		<div class="alert alert-success"><?= esc($success_message) ?></div>
	<?php endif ?>
	<button type="submit" class="btn btn-primary">変更</button>
	<?= form_close() ?>

</main>
<?= $this->endSection() ?>