<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<?= $this->include('header_nav') ?>
<main class="container">
	<?= $this->include('search_part') ?>
	<hr>
	<?= $this->include('prompt_list') ?>
</main>
<?= $this->endSection() ?>