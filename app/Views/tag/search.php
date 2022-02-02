<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<?= $this->include('header_nav') ?>
<main class="container">
	<h3><?= esc($query) ?>の検索結果</h3>
	<?= $this->include('search_part') ?>
	<hr>
	<?= $this->include('prompt_list') ?>
</main>
<?= $this->endSection() ?>