<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<?= $this->include('header_nav') ?>
<main class="container">
	<?= $this->include('pagination') ?>
	<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-3">
		<?php foreach ($prompts as $prompt) : ?>
			<div class="col">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title" style="word-break: break-all;overflow-wrap: break-word;"><?= esc($prompt->title) ?></h4>
						<p class="card-text" style="word-break: break-all;overflow-wrap: break-word;"><?= str_replace(' ', '&nbsp;', esc(mb_strimwidth(preg_replace('/[\r\n]/u', ' ', trim($prompt->description)), 0, 128, '...'))) ?></p>
						<a href="<?= site_url('prompt/' . $prompt->id) ?>" class="btn btn-primary">詳細</a>
						<!-- <hr>
						<h6 class="card-subtitle">タグ:</h6> -->
					</div>
				</div>
			</div>
		<?php endforeach ?>
	</div>
	<?= $this->include('pagination') ?>
</main>
<?= $this->endSection() ?>