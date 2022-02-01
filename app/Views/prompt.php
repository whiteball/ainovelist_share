<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<?= $this->include('header_nav') ?>
<main class="container" id="create-confirm">
	<h1 class="h3 mb-3 fw-normal">プロンプト詳細</h1>
	<div class="mb-3 border rounded p-2">
		<a class="btn btn-secondary" href="<?= site_url('prompt_download/' . $prompt->id) ?>">
			novelファイルをダウンロード
		</a>
	</div>
	<div class="mb-3 border rounded p-2">
		<div class="mb-3">
			<h5>タイトル</h5>
			<div class="wrap border rounded p-2"><?= esc($prompt->title) ?></div>
		</div>
		<div class="mb-3">
			<h5>タグ</h5>
			<div class="wrap border rounded p-2">
				<?php if ($prompt->r18 === '1') : ?>
					<a class="btn rounded-pill btn-danger btn-sm tag-link" href="<?= site_url('tag/R-18') ?>">R-18</a>
				<?php endif ?>
				<?php foreach ($tags as $tag) : ?>
					<a class="btn rounded-pill btn-outline-secondary btn-sm tag-link" href="<?= site_url('tag/' . $tag->tag_name) ?>"><?= esc($tag->tag_name) ?></a>
				<?php endforeach ?>
			</div>
		</div>
		<div class="mb-3">
			<h5>著者</h5>
			<div class="wrap border rounded p-2"><?= esc($author) ?></div>
		</div>
		<div class="mb-3">
			<h5>説明</h5>
			<div class="wrap border rounded p-2"><?= nl2br(str_replace(' ', '&nbsp;', esc($prompt->description))) ?></div>
		</div>
		<div class="mb-3">
			<h5>プロンプト(本文)</h5>
			<div class="wrap border rounded p-2"><?= nl2br(str_replace(' ', '&nbsp;', esc($prompt->prompt))) ?></div>
		</div>
	</div>
	<?php if (! (empty($prompt->memory) && empty($prompt->authors_note) && empty($prompt->ng_words))) : ?>
		<div class="mb-3 border rounded p-2">
			<?php if (! empty($prompt->memory)) : ?>
				<div class="mb-3">
					<h5>メモリ</h5>
					<div class="wrap border rounded p-2"><?= nl2br(str_replace(' ', '&nbsp;', esc($prompt->memory))) ?></div>
				</div>
			<?php endif ?>
			<?php if (! empty($prompt->authors_note)) : ?>
				<div class="mb-3">
					<h5>脚注</h5>
					<div class="wrap border rounded p-2"><?= nl2br(str_replace(' ', '&nbsp;', esc($prompt->authors_note))) ?></div>
				</div>
			<?php endif ?>
			<?php if (! empty($prompt->ng_words)) : ?>
				<div class="mb-3">
					<h5>禁止ワード</h5>
					<div class="wrap border rounded p-2"><?= nl2br(str_replace(' ', '&nbsp;', esc($prompt->ng_words))) ?></div>
				</div>
			<?php endif ?>
		</div>
	<?php endif ?>
	<?php if (! empty($prompt->char_book)) : ?>
		<div class="mb-3 border rounded p-2">
			<h5>キャラクターブック</h5>
			<?php foreach ($prompt->char_book as $char_book) : ?>
				<div>
					<h6 class="wrap">タグ: <?= esc($char_book['tag']) ?></h6>
					<div class="wrap border rounded p-2"><?= nl2br(str_replace(' ', '&nbsp;', esc($char_book['content']))) ?></div>
				</div>
			<?php endforeach ?>
		</div>
	<?php endif ?>
	<?php if (! empty($prompt->script)) : ?>
		<div class="mb-3 border rounded p-2">
			<h5>スクリプト</h5>
			<?php $type_list = ['script_in' => '入力文の置換', 'script_out' => '出力文の置換', 'script_in_pin' => '最新入力文の確定置換', 'script_in_regexp' => '入力文の置換（正規表現）', 'script_out_regexp' => '出力文の置換（正規表現）', 'script_in_pin_regexp' => '最新入力文の確定置換（正規表現）', 'script_none' => '使用しない'] ?>
			<?php foreach ($prompt->script as $script) : ?>
				<div class="border rounded p-2">
					<div class="border-bottom p-1">種類: <?= esc($type_list[$script['type']]) ?></div>
					<div class="wrap border-bottom p-1">IN: <?= esc($script['in']) ?></div>
					<div class="wrap border-bottom p-1">OUT: <?= esc($script['out']) ?></div>
				</div>
			<?php endforeach ?>
		</div>
	<?php endif ?>

</main>
<?= $this->endSection() ?>