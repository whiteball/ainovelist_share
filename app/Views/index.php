<?= $this->extend('template') ?>

<?= $this->section('ogp') ?>
<?php $description = 'AIのべりすとのプロンプトを投稿・共有するためのサイト。投稿されたプロンプトは直接AIのべりすとに読み込み可能。' ?>
<meta property="og:image" content="<?= base_url('img/ogp.png') ?>" />
<meta name="twitter:image" content="<?= base_url('img/ogp.png') ?>" />
<meta property="og:type" content="website" />
<meta property="og:title" content="AIのべりすとプロンプト共有 トップページ" />
<meta name="twitter:title" content="AIのべりすとプロンプト共有 トップページ">
<meta property="og:description" content="<?= $description ?>" />
<meta name="twitter:description" content="<?= $description ?>">
<meta name="description" content="<?= $description ?>" />
<?php
$suffix = '';
switch ($_SESSION['list_mode'] ?? 's') {
	case 'n':
		$suffix = '-r18';
		break;
	case 'a':
		$suffix = '-all';
		break;
	case 's':
	default:
		$suffix = '';
		break;
}
?>
<link rel="alternate" type="application/rss+xml" type="RSS" href="<?= site_url('/rss' . $suffix . '.xml') ?>"/>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<?php if (! empty($recent_prompts)): ?>
		<div style="font-size: 75%;" class="text-center my-1"><span class="text-warning">★お知らせ★</span>　RSSフィードを追加しました。上のメニューのRSSアイコンからご利用ください。現在の全年齢/R-18/すべての表示状態にあわせたRSSフィードへのリンクが表示されます。</div>
	<?php endif ?>
	<?= $this->include('_parts/search_part') ?>
	<hr>
	<?php if (! empty($recent_prompts)): ?>
		<div class="accordion" id="recent-prompt">
			<div class="accordion-item">
				<h2 class="accordion-header" id="recent-prompt-header">
					<button class="accordion-button py-2" type="button" data-bs-toggle="collapse" data-bs-target="#recent-prompt-content" aria-expanded="true" aria-controls="recent-prompt-content">
						最近ダウンロード/インポートされたプロンプト
					</button>
				</h2>
				<div id="recent-prompt-content" class="accordion-collapse collapse<?= $recent_show ? ' show' : '' ?>" aria-labelledby="recent-prompt-header" data-bs-parent="#recent-prompt">
					<div class="accordion-body p-0">
						<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 rounded-bottom p-1 mx-0" style="overflow-x: scroll;flex-wrap: nowrap;">
							<?php foreach ($recent_prompts as $prompt) : ?>
								<div class="card m-2">
									<div class="card-body">
										<h5 class="card-title" style="word-break: break-all;overflow-wrap: break-word;"><a class="link-secondary" href="<?= site_url('prompt/' . $prompt->id) ?>"><?= esc(strip_tags($prompt->title)) ?></a></h5>
										<h6 class="card-subtitle mb-2 text-muted">投稿: <?= esc(mb_substr($prompt->registered_at, 0, 10)) ?></h6>
										<p class="card-text" style="word-break: break-all;overflow-wrap: break-word;"><?= str_replace(' ', '&nbsp;', esc(mb_strimwidth(preg_replace('/[\r\n]/u', ' ', trim($prompt->description)), 0, 128, '...'))) ?></p>
										<a href="<?= site_url('prompt/' . $prompt->id) ?>" class="btn btn-secondary">詳細</a>
										<hr>
										<h6 class="card-subtitle">タグ:
											<?php if (mb_strstr($prompt->parameters, '<>chat<>') !== false) : ?>
												<a class="btn rounded-pill btn-sm tag-link chat-icon" href="<?= site_url('search/chat') ?>"><img src="<?= base_url('img/chat_mode.svg')?>" title="「チャット／ゲーム」モード用プロンプト"></a>
											<?php endif ?>
											<?php if ($prompt->r18 === '1') : ?>
												<a class="btn rounded-pill btn-danger btn-sm tag-link" href="<?= site_url('tag/R-18') ?>">R-18</a>
											<?php endif ?>
											<?php foreach ($recent_tags[$prompt->id] as $tag) : ?>
												<a class="btn rounded-pill btn-outline-secondary btn-sm tag-link" href="<?= site_url('tag/' . $tag->tag_name) ?>"><?= esc($tag->tag_name) ?></a>
											<?php endforeach ?>
										</h6>
									</div>
								</div>
							<?php endforeach ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			window.addEventListener('beforeunload', function () {
				let show_recent = false
				if (document.getElementById('recent-prompt-content').classList.contains('show')) {
					show_recent = true
				}
				document.cookie = 'show_recent=' + (show_recent ? '1' : '0') + '; samesite=lax;<?= ENVIRONMENT === 'production' ? ' secure;' : '' ?> max-age=31536000; path=/'
			})
		</script>
		<hr>
	<?php endif ?>
	<?= $this->include('_parts/prompt_list') ?>
</main>
<?= $this->endSection();
