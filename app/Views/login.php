<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<?= $this->include('header_nav') ?>
<main class="form-register">
	<h1 class="h3 mb-3 fw-normal">ログイン</h1>

	<?php if (! empty($error_message)): ?>
		<div class="alert alert-danger"><?= esc($error_message) ?></div>
	<?php endif ?>
	<?= form_open() ?>
	<?= csrf_field() ?>
	<div class="mb-3">
		<label for="login-id" class="form-label">ログイン名</label>
		<input type="text" class="form-control" id="login-id" name="login_name" value="<?= set_value('login_name'); ?>">
		<?= $validation->showError('login_name')?>
	</div>
	<div class="mb-3">
		<label for="password" class="form-label">パスワード</label>
		<input type="password" class="form-control" id="password" name="password">
		<?= $validation->showError('password')?>
	</div>
	<button type="submit" class="btn btn-primary">ログイン</button>
	<?= form_close() ?>
</main>
<?= $this->endSection() ?>