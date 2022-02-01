<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<?= $this->include('header_nav') ?>
<main class="container">
	<h3><?= esc($user_name) ?>さんの投稿一覧</h3>
	<hr>
	<?= $this->include('prompt_list') ?>
</main>
<?= $this->endSection() ?>