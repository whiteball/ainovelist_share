<?= $this->extend('template') ?>
<?= $this->section('title') ?> - パスワードリセット<?= $this->endSection() ?>

<?= $this->section('ogp') ?>
<?php $description = 'パスワードリセットページ。' ?>
<meta property="og:image" content="<?= base_url('img/ogp.png') ?>" />
<meta name="twitter:image" content="<?= base_url('img/ogp.png') ?>" />
<meta property="og:type" content="website" />
<meta property="og:title" content="パスワードリセット" />
<meta name="twitter:title" content="パスワードリセット">
<meta property="og:description" content="<?= $description ?>" />
<meta name="twitter:description" content="<?= $description ?>">
<meta name="description" content="<?= $description ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<h1 class="h3 mb-3 fw-normal">パスワードリセット</h1>
	<p>
		あらかじめマイページで登録したメールアドレスがあれば、パスワードリセットをすることができます。<br>
		ログインIDとメールアドレスを入力すると、そのメールアドレス宛にパスワードリセットのためのコードを送ります。<br>
		次のページでコードを入力して、パスワードを再設定してください。<br>
		※ログインIDはメールアドレスを登録したときに送信したメールに書いています。
	</p>

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
		<label for="mail-address" class="form-label">メールアドレス</label>
		<input type="text" class="form-control" id="mail-address" name="mail_address" value="<?= set_value('mail_address'); ?>">
		<?= $validation->showError('mail_address')?>
	</div>
	<button type="submit" class="btn btn-primary">リセットメール送信</button>
	<?= form_close() ?>
</main>
<?= $this->endSection() ?>