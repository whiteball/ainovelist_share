<?= $this->extend('template') ?>
<?= $this->section('title') ?> - <?= esc($query) ?>の検索結果一覧<?= $this->endSection() ?>

<?= $this->section('ogp') ?>
<?php $description = esc($query, 'attr') . 'で検索してヒットした投稿プロンプト一覧。' ?>
<meta property="og:image" content="<?= base_url('img/ogp.png') ?>" />
<meta name="twitter:image" content="<?= base_url('img/ogp.png') ?>" />
<meta property="og:type" content="website" />
<meta property="og:title" content="<?= esc($query, 'attr') ?>の検索結果一覧" />
<meta name="twitter:title" content="<?= esc($query, 'attr') ?>の検索結果一覧">
<meta property="og:description" content="<?= $description ?>" />
<meta name="twitter:description" content="<?= $description ?>">
<meta name="description" content="<?= $description ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<h3><?= esc($query) ?>の検索結果</h3>
	<?= $this->include('_parts/search_part') ?>
	<hr>
	<?= $this->include('_parts/prompt_list') ?>
</main>
<?= $this->endSection() ?>