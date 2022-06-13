<?= $this->extend('template') ?>
<?= $this->section('title') ?> - 過去のランキング履歴<?= $r18 ? '(R-18)' : '' ?><?= $this->endSection() ?>

<?= $this->section('ogp') ?>
<?php $description = 'これまでに集計した、novelファイルのダウンロード/AIのべりすとへ読み込みの合計数のランキングの履歴' . ($r18 ? '(R-18)' : '') . 'です。' ?>
<meta property="og:image" content="<?= base_url('img/ogp.png') ?>" />
<meta name="twitter:image" content="<?= base_url('img/ogp.png') ?>" />
<meta property="og:type" content="website" />
<meta property="og:title" content="過去のランキング履歴<?= $r18 ? '(R-18)' : '' ?>" />
<meta name="twitter:title" content="過去のランキング履歴<?= $r18 ? '(R-18)' : '' ?>">
<meta property="og:description" content="<?= $description ?>" />
<meta name="twitter:description" content="<?= $description ?>">
<meta name="description" content="<?= $description ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<h3>過去のランキング履歴<?= $r18 ? '(R-18)' : '' ?></h3>
	<hr>
	<div class="mb-4">
		これまでに集計した、novelファイルのダウンロード/AIのべりすとへ読み込みの合計数のランキングの履歴<?= $r18 ? '(R-18)' : '' ?>です。<br>
		確認したい日付をクリックすると、その週のランキングページへと移動します。
	</div>

	<ul class="nav nav-tabs" id="myTab" role="tablist">
		<li class="nav-item" role="presentation">
			<a class="nav-link<?= ! $r18 ? ' active' : '' ?>" href="<?= site_url('ranking/history') ?>"<?= ! $r18 ? ' aria-current="page"' : '' ?>>
				全年齢
			</a>
		</li>
		<li class="nav-item" role="presentation">
			<a class="nav-link<?= $r18 ? ' active' : '' ?>" href="<?= site_url('ranking_r18/history') ?>"<?= $r18 ? ' aria-current="page"' : '' ?>>
				R-18
			</a>
		</li>
	</ul>
	<div class="border border-top-0 rounded-bottom p-2">
		<?php if (empty($date_list)): ?>
			<div class="row">
				<div class="col text-center">
					ランキングは存在しません。
				</div>
			</div>
		<?php else : ?>
			<div class="list-group">
				<?php foreach ($date_list as $row) : ?>
					<?php $date = new DateTime($row->date); $start_date = $date->sub(new DateInterval('P7D'))->format('Y/m/d'); $end_date = $date->add(new DateInterval('P6D'))->format('Y/m/d') ?>
					<a href="<?= site_url('ranking' . ($r18 ? '_r18' : '') . '/' . $row->date)?>" class="list-group-item list-group-item-action"><?= $start_date ?>～<?= $end_date ?></a>
				<?php endforeach ?>
			</div>
		<?php endif ?>
	</div>
</main>
<?= $this->endSection() ?>