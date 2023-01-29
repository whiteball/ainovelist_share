<?= $this->extend('template') ?>
<?= $this->section('title') ?> - <?= esc($tag_name) ?>の投稿一覧<?= $this->endSection() ?>

<?= $this->section('ogp') ?>
<?php $description = esc($tag_name, 'attr') . 'のタグがついた投稿プロンプトの一覧。' ?>
<meta property="og:image" content="<?= base_url('img/ogp.png') ?>" />
<meta name="twitter:image" content="<?= base_url('img/ogp.png') ?>" />
<meta property="og:type" content="website" />
<meta property="og:title" content="<?= esc($tag_name, 'attr') ?>の投稿一覧" />
<meta name="twitter:title" content="<?= esc($tag_name, 'attr') ?>の投稿一覧">
<meta property="og:description" content="<?= $description ?>" />
<meta name="twitter:description" content="<?= $description ?>">
<meta name="description" content="<?= $description ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<h3 class="d-md-inline-block">タグ:<?= esc($tag_name) ?>の投稿一覧</h3>
	<div class="btn-group d-md-inline-block ms-2">
		<a id="dropdownMenuLink" href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><img src="<?= base_url('img/gear.svg')?>" style="height: 1rem;"></a>
		
		<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
			<li><a class="dropdown-item" href="#" id="add_ng_link">このタグをNGに追加</a></li>
			<li><a class="dropdown-item" href="<?= site_url('option/ng')?>">NG管理ページに移動</a></li>
		</ul>
	</div>
	<div class="d-none alert alert-success" role="alert" id="info">NGタグを追加しました。　<a href="<?= site_url('option/ng')?>">NG管理ページに移動する</a></div>
	<hr>
	<?= $this->include('_parts/prompt_list') ?>
	<script>
		document.getElementById('add_ng_link').addEventListener('click', function () {
			const tag = '<?= esc($tag_name, 'js') ?>'
			const ng_tags_data = document.cookie.split('; ').find(row => row.startsWith('ng_tags='))
			const ng_tags = ng_tags_data ? ng_tags_data.split('=')[1] : ''
			document.cookie = 'ng_tags=' + (ng_tags ? encodeURIComponent(decodeURIComponent(ng_tags) + ' ' + tag) : encodeURIComponent(tag)) + '; samesite=lax;<?= ENVIRONMENT === 'production' ? ' secure;' : '' ?> max-age=31536000; path=/'
			document.getElementById('info').classList.remove('d-none')
		})
	</script>
</main>
<?= $this->endSection() ?>

<?= $this->section('extra_js') ?>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<?= $this->endSection();