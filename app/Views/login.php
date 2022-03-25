<?= $this->extend('template') ?>
<?= $this->section('title') ?> - ログイン<?= $this->endSection() ?>

<?= $this->section('ogp') ?>
<?php $description = 'ログインページ。' ?>
<meta property="og:image" content="<?= base_url('img/ogp.png') ?>" />
<meta name="twitter:image" content="<?= base_url('img/ogp.png') ?>" />
<meta property="og:type" content="website" />
<meta property="og:title" content="ログイン" />
<meta name="twitter:title" content="ログイン">
<meta property="og:description" content="<?= $description ?>" />
<meta name="twitter:description" content="<?= $description ?>">
<meta name="description" content="<?= $description ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="form-register">
	<h1 class="h3 mb-3 fw-normal">ログイン</h1>

	<?php if (! empty($error_message)): ?>
		<div class="alert alert-danger"><?= esc($error_message) ?></div>
	<?php endif ?>
	<?= form_open() ?>
	<?= csrf_field() ?>
	<div class="mb-3">
		<label for="login-id" class="form-label">ログインID</label>
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