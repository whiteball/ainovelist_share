<?= $this->extend('template') ?>
<?= $this->section('title') ?> - <?= esc($tag_name) ?>の投稿一覧<?= $this->endSection() ?>

<?= $this->section('ogp') ?>
<?php $description = esc($tag_name, 'attr') . 'のタグがついた投稿プロンプトの一覧。' ?>
<meta property="og:type" content="website" />
<meta property="og:title" content="<?= esc($tag_name, 'attr') ?>の投稿一覧" />
<meta name="twitter:title" content="<?= esc($tag_name, 'attr') ?>の投稿一覧">
<meta property="og:description" content="<?= $description ?>" />
<meta name="twitter:description" content="<?= $description ?>">
<meta name="description" content="<?= $description ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<h3>タグ:<?= esc($tag_name) ?>の投稿一覧</h3>
	<hr>
	<?= $this->include('_parts/prompt_list') ?>
</main>
<?= $this->endSection() ?>