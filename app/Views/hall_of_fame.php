<?= $this->extend('template') ?>
<?= $this->section('title') ?> - 殿堂入りした投稿一覧<?= $this->endSection() ?>

<?= $this->section('ogp') ?>
<?php $description = '殿堂入りした投稿プロンプトの一覧。' ?>
<meta property="og:image" content="<?= base_url('img/ogp.png') ?>" />
<meta name="twitter:image" content="<?= base_url('img/ogp.png') ?>" />
<meta property="og:type" content="website" />
<meta property="og:title" content="殿堂入りした投稿一覧" />
<meta name="twitter:title" content="殿堂入りした投稿一覧">
<meta property="og:description" content="<?= $description ?>" />
<meta name="twitter:description" content="<?= $description ?>">
<meta name="description" content="<?= $description ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<h3 class="d-md-inline-block">殿堂入り投稿プロンプト</h3>
	<p>
		このページは、ランキングで1位を8回以上獲るなどの理由により殿堂入りになった投稿プロンプトの一覧です。<br>
		殿堂入りした投稿プロンプトは、ランキングの集計対象外になります。
	</p>
	<hr>
	<?= $this->include('_parts/prompt_list') ?>
</main>
<?= $this->endSection();
