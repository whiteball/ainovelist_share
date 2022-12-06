<?= $this->extend('template') ?>
<?= $this->section('title') ?> - AIのべりすと向けツール<?= $this->endSection() ?>

<?= $this->section('ogp') ?>
<?php $description = 'AIのべりすと向けのツールです。' ?>
<meta property="og:image" content="<?= base_url('img/ogp.png') ?>" />
<meta name="twitter:image" content="<?= base_url('img/ogp.png') ?>" />
<meta property="og:type" content="website" />
<meta property="og:title" content="AIのべりすと向けツール" />
<meta name="twitter:title" content="AIのべりすと向けツール">
<meta property="og:description" content="<?= $description ?>" />
<meta name="twitter:description" content="<?= $description ?>">
<meta name="description" content="<?= $description ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<h3>AIのべりすと向けツール</h3>
	<div>
		AIのべりすと向けのちょっとした便利ツールです。下記リンクから各ツールへ移動できます。
	</div>
	<div class="mt-3">
		<ul>
			<li><a href="<?= site_url('tool/token_count') ?>">トークンカウントツール</a></li>
			<li><a href="<?= site_url('tool/token_search') ?>">トークン検索ツール</a></li>
		</ul>
	</div>
</main>
<?= $this->endSection() ?>