<?= $this->extend('template') ?>

<?= $this->section('ogp') ?>
<?php $description = 'AIのべりすとのプロンプトを投稿・共有するためのサイト。投稿されたプロンプトは直接AIのべりすとに読み込み可能。' ?>
<meta property="og:type" content="website" />
<meta property="og:title" content="AIのべりすとプロンプト共有 トップページ" />
<meta name="twitter:title" content="AIのべりすとプロンプト共有 トップページ">
<meta property="og:description" content="<?= $description ?>" />
<meta name="twitter:description" content="<?= $description ?>">
<meta name="description" content="<?= $description ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<?= $this->include('_parts/search_part') ?>
	<hr>
	<?= $this->include('_parts/prompt_list') ?>
</main>
<?= $this->endSection() ?>