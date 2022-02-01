<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<?= $this->include('header_nav') ?>
<main class="container">
	<h3><?= esc($tag_name) ?>の検索結果</h3>
	<hr>
	<?= $this->include('prompt_list') ?>
</main>
<?= $this->endSection() ?>