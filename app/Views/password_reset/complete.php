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
		パスワード変更が完了しました。<br>
		ログインページに戻り、再設定したパスワードを使ってログインしてください。<br>
		<br>
		<a href="<?= site_url('login') ?>">ログインページへ移動</a>
	</p>
</main>
<?= $this->endSection() ?>