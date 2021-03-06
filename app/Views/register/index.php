<?= $this->extend('template') ?>
<?= $this->section('title') ?> - ユーザー登録<?= $this->endSection() ?>

<?= $this->section('ogp') ?>
<?php $description = 'ユーザー登録ページ。' ?>
<meta property="og:image" content="<?= base_url('img/ogp.png') ?>" />
<meta name="twitter:image" content="<?= base_url('img/ogp.png') ?>" />
<meta property="og:type" content="website" />
<meta property="og:title" content="ユーザー登録" />
<meta name="twitter:title" content="ユーザー登録">
<meta property="og:description" content="<?= $description ?>" />
<meta name="twitter:description" content="<?= $description ?>">
<meta name="description" content="<?= $description ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="form-register">
	<h1 class="h3 mb-3 fw-normal">ユーザー登録</h1>

	<?= form_open() ?>
	<?= csrf_field() ?>
	<div class="mb-3">
		<label for="login-id" class="form-label">ログインID(半角英数のみ)</label>
		<input type="text" class="form-control" id="login-id" name="login_name" value="<?= set_value('login_name'); ?>">
		<?= $validation->showError('login_name')?>
	</div>
	<div class="mb-3">
		<label for="user-name" class="form-label">ユーザー名(後から変更可能)</label>
		<input type="text" class="form-control" id="user-name" name="screen_name" value="<?= set_value('screen_name'); ?>">
		<?= $validation->showError('screen_name')?>
	</div>
	<div class="mb-3">
		<label for="password" class="form-label">パスワード(12文字以上)</label>
		<input type="password" class="form-control" id="password" name="password">
		<?= $validation->showError('password')?>
	</div>
	<div class="mb-3">
		<label for="password-confirm" class="form-label">パスワード(再入力)</label>
		<input type="password" class="form-control" id="password-confirm" name="password_confirm">
		<?= $validation->showError('password_confirm')?>
	</div>
	<button type="submit" class="btn btn-primary">登録</button>
	<?= form_close() ?>
</main>
<?= $this->endSection() ?>