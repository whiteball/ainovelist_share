<?= $this->extend('template') ?>
<?= $this->section('title') ?> - <?= esc($user_name) ?>さんの投稿一覧<?= $this->endSection() ?>

<?= $this->section('ogp') ?>
<meta property="og:type" content="profile" />
<meta property="og:title" content="<?= esc($user_name, 'attr') ?>さんの投稿一覧" />
<meta name="twitter:title" content="<?= esc($user_name, 'attr') ?>さんの投稿一覧">
<meta property="og:description" content="<?= esc($user_name, 'attr') ?>さんによる投稿プロンプトの一覧。" />
<meta name="twitter:description" content="<?= esc($user_name, 'attr') ?>さんによる投稿プロンプトの一覧。">
<meta property="profile:username" content="<?= esc($user_name, 'attr') ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<h3><?= esc($user_name) ?>さんの投稿一覧</h3>
	<hr>
	<?= $this->include('_parts/prompt_list') ?>
</main>
<?= $this->endSection() ?>