<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<?= $this->include('header_nav') ?>
<main class="container">
	<?= form_open('search/tag', ['method' => 'get', 'class' => 'row g-3']) ?>
	<div class="col-auto">
		<div class="input-group">
			<span class="input-group-text" id="tag-search-label">タグ検索</span>
			<input type="text" class="form-control" id="tag_search" name="q" value="<?= set_value('q', '') ?>" aria-describedby="tag-search-label">
			<button class="btn btn-secondary" type="submit" id="tag-search-button">検索</button>
		</div>
	</div>
	<?= form_close() ?>

	<hr>
	<?= $this->include('prompt_list') ?>
</main>
<?= $this->endSection() ?>