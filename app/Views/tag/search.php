<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<h3><?= esc($query) ?>の検索結果</h3>
	<?= $this->include('_parts/search_part') ?>
	<hr>
	<?= $this->include('_parts/prompt_list') ?>
</main>
<?= $this->endSection() ?>