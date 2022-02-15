<?= $this->extend('template') ?>
<?= $this->section('title') ?> - タグ一覧<?= $this->endSection() ?>

<?= $this->section('ogp') ?>
<?php $description = '現在サイト内に存在する全てのタグの一覧。' ?>
<meta property="og:type" content="website" />
<meta property="og:title" content="タグ一覧" />
<meta name="twitter:title" content="タグ一覧">
<meta property="og:description" content="<?= $description ?>" />
<meta name="twitter:description" content="<?= $description ?>">
<meta name="description" content="<?= $description ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<h1>タグ一覧</h1>
	<div class="mt-3">各タグをクリック/タップすると、そのタグがついたプロンプト一覧を表示します。</div>
	<hr>
	<div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4 mb-3 text-center text-break">
		<?php foreach ($tag_count as $tag) : ?>
			<div class="col d-grid gap-2">
				<a href="<?= site_url('tag/' . $tag->tag_name) ?>" class="btn btn-outline-secondary text-dark">
					<?= esc($tag->tag_name) ?> <span class="badge bg-light text-dark"><?= esc($tag->count) ?></span>
				</a>
			</div>
		<?php endforeach ?>
		<!-- <div class="col d-grid gap-2">
			<button type="button" class="btn btn-outline-secondary text-dark">
				aaaa <span class="badge bg-light text-dark">4</span>
			</button>
		</div> -->
	</div>
</main>
<?= $this->endSection() ?>