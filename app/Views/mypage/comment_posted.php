<?= $this->extend('template') ?>
<?= $this->section('title') ?> - マイページ - 投稿したコメント<?= $this->endSection() ?>

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
			<a class="nav-link" href="<?= site_url('mypage/list') ?>">
				投稿一覧
			</a>
		</li>
		<li class="nav-item" role="presentation">
			<a class="nav-link active" href="<?= site_url('mypage/comment_posted') ?>" aria-current="page">
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
		<ul class="nav nav-pills mb-3">
			<li class="nav-item">
				<a class="nav-link active" aria-current="page" href="<?= site_url('mypage/comment_posted') ?>">投稿したコメント</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?= site_url('mypage/comment_received') ?>">受け取ったコメント</a>
			</li>
		</ul>
		<h4 class="h4 fw-normal">投稿したコメント</h4>
		<div style="font-size: 85%;">作品ページへ行くには投稿日をクリック/タップしてください。<br>自分で投稿した非公開のプロンプトの場合は編集ページが開きます。<br>コメント削除は作品ページから行ってください。<br></div>
		<?php if (empty($comments)) : ?>
			<div class="text-center mt-3">コメントはありません</div>
		<?php else : ?>
			<table class="table">
				<thead>
					<tr>
						<th scope="col" class="text-center d-none d-sm-table-cell">プロンプト</th>
						<th scope="col" class="text-center">コメント</th>
						<th scope="col" class="text-center">投稿日時</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($comments as $comment) : ?>
						<tr scope="row">
							<td style="word-break: break-all;overflow-wrap: break-word;" class="d-none d-sm-table-cell">
								<div class="d-grid gap-2">
									<?php if (empty($comment->prompt_title)) : ?>
										削除済み
									<?php elseif ($comment->draft !== '0') : ?>
										<?php if ($comment->own_prompt !== '0') : ?>
											<a href="<?= site_url('edit/' . $comment->prompt_id) ?>"><?= esc(strip_tags($comment->prompt_title)) ?></a>
										<?php else : ?>
											非公開
										<?php endif ?>
									<?php else : ?>
										<a href="<?= site_url('prompt/' . $comment->prompt_id) ?>"><?= esc(strip_tags($comment->prompt_title)) ?></a>
									<?php endif ?>
								</div>
							</td>
							<td style="word-break: break-all;overflow-wrap: break-word;">
								<div class="d-grid gap-2">
									<div class="d-block d-sm-none">
										<?php if (empty($comment->prompt_title)) : ?>
											プロンプト削除済み
										<?php elseif ($comment->draft !== '0') : ?>
											<?php if ($comment->own_prompt !== '0') : ?>
												<a href="<?= site_url('edit/' . $comment->prompt_id) ?>"><?= esc(strip_tags($comment->prompt_title)) ?></a>
											<?php else : ?>
												プロンプト非公開
											<?php endif ?>
										<?php else : ?>
											<a href="<?= site_url('prompt/' . $comment->prompt_id) ?>"><?= esc(strip_tags($comment->prompt_title)) ?></a>
										<?php endif ?>
									</div>
									<?= esc($comment->comment) ?>
								</div>
							</td>
							<td class="text-center" style="width: 11rem;">
								<?= str_replace(' ', ' <br class="d-inline d-sm-none">', esc($comment->registered_at)) ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		<?php endif ?>
	</div>

</main>
<?= $this->endSection() ?>