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
		受け取ったメールに記載されているコードと、新しいパスワードを入力してください。<br>
		コードが送られてこない場合は、前のページに戻ってログインIDとメールアドレスを入力し、メールの再送を待ってください。<br>
		登録したメールアドレス以外を入力した場合や、そもそもメールアドレスを登録していなかった場合などでメールが送信されなかった時でも、このページは表示されます。<br>
		※コードを複数回間違えると、そのコードは無効になり、最初のページに戻ります。初めからやり直してください。
	</p>

	<?php if (! empty($error_message)): ?>
		<div class="alert alert-danger"><?= esc($error_message) ?></div>
	<?php endif ?>
	<?= form_open() ?>
	<?= csrf_field() ?>
	<div class="mb-3">
		<label for="code" class="form-label">コード</label>
		<input type="text" class="form-control" id="code" name="code" value="<?= set_value('code'); ?>" autocomplete="off">
		<?= $validation->showError('code')?>
	</div>
	<div class="mb-3">
		<label for="new-password" class="col-form-label">新しいパスワード</label>
		<input type="password" class="form-control" id="new-password" name="new_password">
		<?= $validation->showError('new_password') ?>
	</div>
	<div class="mb-3">
		<label for="new-password-confirm" class="col-form-label">新しいパスワード(再入力)</label>
		<input type="password" class="form-control" id="new-password-confirm" name="new_password_confirm">
		<?= $validation->showError('new_password_confirm') ?>
	</div>
	<button type="submit" class="btn btn-primary">パスワード再設定</button>
	<?= form_close() ?>
</main>
<?= $this->endSection() ?>