<?= $this->extend('template') ?>
<?= $this->section('title') ?> - プロンプト投稿 - 内容確認<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container" id="create-confirm">
	<h1 class="h3 mb-3 fw-normal">プロンプト投稿 - 内容確認</h1>
	<div class="mb-3 border rounded p-2">
		<div class="mb-3">
			<h5>タイトル</h5>
			<div class="wrap border rounded p-2"><?= esc($post_data['title']) ?></div>
		</div>
		<div class="mb-3">
			<h5>タグ</h5>
			<div class="wrap border rounded p-2">
				<?php foreach ($post_data['tags'] as $tag) : ?>
					<span class="btn rounded-pill btn-outline-secondary btn-sm tag-link"><?= esc($tag) ?></span>
				<?php endforeach ?>
			</div>
		</div>
		<div class="mb-3">
			<h5>説明</h5>
			<div class="wrap border rounded p-2"><?= nl2br(str_replace(' ', '&nbsp;', esc($post_data['description']))) ?></div>
		</div>
		<div class="mb-3">
			<h5>プロンプト(本文)</h5>
			<div class="wrap border rounded p-2"><?= nl2br(str_replace(' ', '&nbsp;', esc($post_data['prompt']))) ?></div>
		</div>
		<div class="mb-3">
			<h5>全年齢/R-18設定</h5>
			<div class="wrap border rounded p-2"><?= (! empty($post_data['r18']) && $post_data['r18'] === '1') ? 'R-18' : '全年齢' ?></div>
		</div>
		<div class="mb-3">
			<h5>公開/非公開設定</h5>
			<div class="wrap border rounded p-2"><?= (! empty($post_data['draft']) && $post_data['draft'] === '1') ? '非公開' : '公開' ?></div>
		</div>
		<div class="mb-3">
			<h5>コメント許可/不許可設定</h5>
			<div class="wrap border rounded p-2"><?= (! empty($post_data['comment']) && $post_data['comment'] === '1') ? '許可' : '不許可' ?></div>
		</div>
	</div>
	<?php if (! (empty($post_data['memory']) && empty($post_data['authors_note']) && empty($post_data['ng_words']))) : ?>
		<div class="mb-3 border rounded p-2">
			<?php if (! empty($post_data['memory'])) : ?>
				<div class="mb-3">
					<h5>メモリ</h5>
					<div class="wrap border rounded p-2"><?= nl2br(str_replace(' ', '&nbsp;', esc($post_data['memory']))) ?></div>
				</div>
			<?php endif ?>
			<?php if (! empty($post_data['authors_note'])) : ?>
				<div class="mb-3">
					<h5>脚注</h5>
					<div class="wrap border rounded p-2"><?= nl2br(str_replace(' ', '&nbsp;', esc($post_data['authors_note']))) ?></div>
				</div>
			<?php endif ?>
			<?php if (! empty($post_data['ng_words'])) : ?>
				<div class="mb-3">
					<h5>禁止ワード</h5>
					<div class="wrap border rounded p-2"><?= nl2br(str_replace(' ', '&nbsp;', esc($post_data['ng_words']))) ?></div>
				</div>
			<?php endif ?>
		</div>
	<?php endif ?>
	<?php if (! empty($post_data['char_book'])) : ?>
		<div class="mb-3 border rounded p-2">
			<h5>キャラクターブック</h5>
			<?php foreach ($post_data['char_book'] as $char_book) : ?>
				<div>
					<h6 class="wrap">タグ: <?= esc($char_book['tag']) ?></h6>
					<div class="wrap border rounded p-2"><?= nl2br(str_replace(' ', '&nbsp;', esc($char_book['content']))) ?></div>
				</div>
			<?php endforeach ?>
		</div>
	<?php endif ?>
	<?php if (! empty($post_data['script'])) : ?>
		<div class="mb-3 border rounded p-2">
			<h5>スクリプト</h5>
			<?php $type_list = ['script_in' => '入力文の置換', 'script_out' => '出力文の置換', 'script_in_pin' => '最新入力文の確定置換', 'script_in_regex' => '入力文の置換（正規表現）', 'script_out_regex' => '出力文の置換（正規表現）', 'script_in_pin_regex' => '最新入力文の確定置換（正規表現）', 'script_none' => '使用しない'] ?>
			<?php foreach ($post_data['script'] as $script) : ?>
				<div class="border rounded p-2">
					<div class="border-bottom p-1">種類: <?= esc($type_list[$script['type']]) ?></div>
					<div class="wrap border-bottom p-1">IN: <?= esc($script['in']) ?></div>
					<div class="wrap border-bottom p-1">OUT: <?= esc($script['out']) ?></div>
				</div>
			<?php endforeach ?>
		</div>
	<?php endif ?>

	<?= form_open() ?>
	<?= csrf_field() ?>
	<?= form_hidden('send', 1) ?>
	<div class="text-center">
		<a href="<?= site_url($return_url . '?back=1') ?>" class="btn btn-outline-secondary me-5">戻る</a>
		<button type="submit" class="btn btn-primary">投稿する</button>
	</div>
	<?= form_close() ?>
</main>
<?= $this->endSection() ?>