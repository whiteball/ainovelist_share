<?= $this->extend('template') ?>

<?= $this->section('ogp') ?>
<meta property="og:type" content="website" />
<meta property="og:title" content="トップページ" />
<meta name="twitter:title" content="トップページ">
<meta property="og:description" content="AIのべりすとのプロンプトを投稿・共有するためのサイト。投稿されたプロンプトは直接AIのべりすとに読み込み可能。" />
<meta name="twitter:description" content="AIのべりすとのプロンプトを投稿・共有するためのサイト。投稿されたプロンプトは直接AIのべりすとに読み込み可能。">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<?= $this->include('_parts/search_part') ?>
	<hr>
	<?= $this->include('_parts/prompt_list') ?>
</main>
<?= $this->endSection() ?>