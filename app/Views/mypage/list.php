<?= $this->extend('template') ?>
<?= $this->section('title') ?> - マイページ - 投稿一覧<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<h1 class="h3 mb-3 fw-normal">マイページ</h1>

	<ul class="nav nav-tabs" id="myTab" role="tablist">
		<li class="nav-item" role="presentation">
			<a class="nav-link" href="<?= site_url('mypage') ?>">
				設定
			</a>
		</li>
		<li class="nav-item" role="presentation">
			<a class="nav-link active" href="<?= site_url('mypage/list') ?>" aria-current="page">
				投稿一覧
			</a>
		</li>
		<li class="nav-item" role="presentation">
			<a class="nav-link" href="<?= site_url('mypage/comment_posted') ?>">
				コメント
			</a>
		</li>
		<li class="nav-item" role="presentation">
			<a class="nav-link" href="<?= site_url('mypage/delete') ?>">
				退会
			</a>
		</li>
	</ul>
	<div class="border border-top-0 rounded-bottom p-2">
		<h4 class="h4 fw-normal">投稿したプロンプト</h4>
		<div style="font-size: 85%;">編集/削除はタイトルをクリック/タップしてください。<br>作品ページへ行くには投稿日をクリック/タップしてください。<br>非公開設定のプロンプトはタイトルに「【非公開】」と表示しています。</div>
		<table class="table">
			<thead>
				<tr>
					<th scope="col" class="text-center">タイトル</th>
					<th scope="col" class="text-center">投稿日</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($prompts as $prompt) : ?>
					<tr scope="row">
						<td style="word-break: break-all;overflow-wrap: break-word;">
							<div class="d-grid gap-2">
								<a href="<?= site_url('edit/' . $prompt->id) ?>" class="btn btn-outline-success"><?= $prompt->draft === '1' ? '【非公開】 ' : '' ?><?= esc(strip_tags($prompt->title)) ?></a>
							</div>
						</td>
						<td class="text-center" style="width: 11rem;">
							<?php if ($prompt->draft === '1'): ?>
								<?= esc($prompt->registered_at) ?>
							<?php else: ?>
								<a href="<?= site_url('prompt/' . $prompt->id) ?>" class="link-secondary"><?= esc($prompt->registered_at) ?></a>
							<?php endif ?>
						</td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>

</main>
<?= $this->endSection() ?>