<?= $this->extend('template') ?>
<?= $this->section('title') ?> - <?= esc($start_date) ?>～<?= esc($end_date) ?>のランキング<?= $r18 ? '(R-18)' : '' ?><?= $this->endSection() ?>

<?= $this->section('ogp') ?>
<?php $description = esc($start_date, 'attr') . 'から' . esc($end_date, 'attr') . 'までの期間内の行われたnovelファイルのダウンロード/AIのべりすとへ読み込みの合計数のランキング' . ($r18 ? '(R-18)' : '') . 'です。' ?>
<meta property="og:image" content="<?= base_url('img/ogp.png') ?>" />
<meta name="twitter:image" content="<?= base_url('img/ogp.png') ?>" />
<meta property="og:type" content="website" />
<meta property="og:title" content="<?= esc($start_date, 'attr')?>～<?= esc($end_date, 'attr')?>のランキング<?= $r18 ? '(R-18)' : '' ?>" />
<meta name="twitter:title" content="<?= esc($start_date, 'attr')?>～<?= esc($end_date, 'attr')?>のランキング<?= $r18 ? '(R-18)' : '' ?>">
<meta property="og:description" content="<?= $description ?>" />
<meta name="twitter:description" content="<?= $description ?>">
<meta name="description" content="<?= $description ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<h3><?= esc($start_date) ?>～<?= esc($end_date) ?>のランキング<?= $r18 ? '(R-18)' : '' ?></h3>
	<hr>
	<div class="mb-4">
		<?= esc($start_date) ?>から<?= esc($end_date) ?>までの期間内の行われたnovelファイルのダウンロード/AIのべりすとへ読み込みの合計数のランキング<?= $r18 ? '(R-18)' : '' ?>です。
	</div>

	<ul class="nav nav-tabs" id="myTab" role="tablist">
		<li class="nav-item" role="presentation">
			<a class="nav-link<?= ! $r18 ? ' active' : '' ?>" href="<?= site_url('ranking' . (empty($date) ? '' : '/' . $date)) ?>"<?= ! $r18 ? ' aria-current="page"' : '' ?>>
				全年齢
			</a>
		</li>
		<li class="nav-item" role="presentation">
			<a class="nav-link<?= $r18 ? ' active' : '' ?>" href="<?= site_url('ranking_r18' . (empty($date) ? '' : '/' . $date)) ?>"<?= $r18 ? ' aria-current="page"' : '' ?>>
				R-18
			</a>
		</li>
	</ul>
	<div class="border border-top-0 rounded-bottom p-2">
		<?php foreach ($ranking as $prompt): ?>
			<div class="row<?= (int) $prompt->rank === 1 ? '' : ' mt-3'?>">
				<div class="col">
					<div class="card">
						<div class="card-body">
							<h5 class="card-title" style="word-break: break-all;overflow-wrap: break-word;"><span class="ranking rank rank-<?= $prompt->rank ?>"><?= $prompt->rank ?>位</span><a class="link-secondary" href="<?= site_url('prompt/' . $prompt->prompt_id) ?>"><?= esc(strip_tags($prompt->title)) ?></a></h5>
							<h6 class="card-subtitle mb-2 text-muted row"><div class="col-6">投稿日:<?= esc($prompt->registered_at) ?><?php if ($prompt->updated_at !== $prompt->registered_at):?>/更新日:<?= esc($prompt->updated_at) ?><?php endif ?></div><div class="col-6 text-end">期間中[View: <?= esc($prompt->view) ?>/Download: <?= esc($prompt->download) ?>/Import: <?= esc($prompt->import) ?>]</div></h6>
							<p class="card-text" style="word-break: break-all;overflow-wrap: break-word;"><?= str_replace(' ', '&nbsp;', esc(mb_strimwidth(preg_replace('/[\r\n]/u', ' ', trim($prompt->description)), 0, 512, '...'))) ?></p>
							<a href="<?= site_url('prompt/' . $prompt->prompt_id) ?>" class="btn btn-secondary">詳細</a>
							<hr>
							<h6 class="card-subtitle">タグ:
								<?php if ($prompt->r18 === '1') : ?>
									<a class="btn rounded-pill btn-danger btn-sm tag-link" href="<?= site_url('tag/R-18') ?>">R-18</a>
								<?php endif ?>
								<?php foreach ($tags[$prompt->prompt_id] as $tag) : ?>
									<a class="btn rounded-pill btn-outline-secondary btn-sm tag-link" href="<?= site_url('tag/' . $tag->tag_name) ?>"><?= esc($tag->tag_name) ?></a>
								<?php endforeach ?>
							</h6>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach ?>
		<?php if (empty($ranking)): ?>
			<div class="row">
				<div class="col text-center">
					対象のランキングは存在しません。
				</div>
			</div>
		<?php endif ?>
	</div>
</main>
<?= $this->endSection() ?>