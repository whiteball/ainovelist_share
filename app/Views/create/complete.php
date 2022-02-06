<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<h1 class="h3 mb-3 fw-normal">プロンプト投稿 - 投稿完了</h1>
	<div>
		プロンプトの投稿が完了しました。<br>
		<?php if ($draft) : ?>
			<a href="<?= site_url('edit/' . $prompt_id) ?>">編集ページへ</a>
		<?php else : ?>
			<a href="<?= site_url('prompt/' . $prompt_id) ?>" class="me-2">個別ページへ</a> - <a href="<?= site_url('edit/' . $prompt_id) ?>" class="ms-2">編集ページへ</a>
		<?php endif ?>
	</div>
</main>
<?= $this->endSection() ?>