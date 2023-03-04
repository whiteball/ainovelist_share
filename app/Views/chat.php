<?= $this->extend('template') ?>
<?= $this->section('title') ?> - 「チャット／ゲーム」モード用プロンプトの投稿一覧<?= $this->endSection() ?>

<?= $this->section('ogp') ?>
<?php $description = '「チャット／ゲーム」モード用プロンプトの投稿プロンプトの一覧。' ?>
<meta property="og:image" content="<?= base_url('img/ogp.png') ?>" />
<meta name="twitter:image" content="<?= base_url('img/ogp.png') ?>" />
<meta property="og:type" content="website" />
<meta property="og:title" content="「チャット／ゲーム」モード用プロンプトの投稿一覧" />
<meta name="twitter:title" content="「チャット／ゲーム」モード用プロンプトの投稿一覧">
<meta property="og:description" content="<?= $description ?>" />
<meta name="twitter:description" content="<?= $description ?>">
<meta name="description" content="<?= $description ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<h3 class="d-md-inline-block">「チャット／ゲーム」モード用プロンプト</h3>
	<p>
		「チャット／ゲーム」モード用として投稿されたプロンプトの一覧です。<br>
		AIのべりすとに取り込むと、GUIが「チャット／ゲーム」モード(本文の下に送信欄が表示される状態)になります。
	</p>
	<hr>
	<?= $this->include('_parts/prompt_list') ?>
</main>
<?= $this->endSection();
