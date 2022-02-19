<!doctype html>
<html lang="ja">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link href="<?= base_url('css/default.css') ?>" rel="stylesheet">
	<title>AIのべりすと プロンプト共有</title>
</head>

<body>
	<div class="container">
		<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
			<div class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
				<a href="<?= site_url('/') ?>" class="nav-link px-2 link-dark">AIのべりすと プロンプト共有</a>
			</div>

			<ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
				<li><a href="<?= site_url('/tags')?>" class="nav-link px-2 link-dark">タグ一覧</a></li>
				<li><a href="<?= site_url('/about')?>" class="nav-link px-2 link-dark">サイトについて</a></li>
				<li><a href="<?= site_url('/script')?>" class="nav-link px-2 link-dark">スクリプト</a></li>
			</ul>

			<div class="col-md-3 text-end">
				<?php service('session') ?>
				<?php if (isset($_SESSION['login'])) : ?>
					<a type="button" class="btn btn-outline-success me-2 btn-sm" href="<?= site_url('create') ?>">投稿</a>
					<a type="button" class="btn btn-outline-info me-2 btn-sm" href="<?= site_url('mypage') ?>">マイページ</a>
				<?php else : ?>
					<a type="button" class="btn btn-outline-primary me-2 btn-sm" href="<?= site_url('login') ?>">サインイン</a>
					<a type="button" class="btn btn-primary btn-sm" href="<?= site_url('register') ?>">登録</a>
				<?php endif ?>
			</div>
		</header>
	</div>
	<main class="container">
		<h1>404 - File Not Found</h1>

		<p>
			<?php if (! empty($message) && $message !== '(null)') : ?>
				<?= nl2br(esc($message)) ?>
			<?php else : ?>
				指定されたページは存在しません。
			<?php endif ?>
		</p>
	</main>

	<div class="container">
		<footer class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mt-4 border-top">
			<div class="col-1"></div>
			<div class="col-10 col-md-auto mt-2 justify-content-center mt-md-0" style="font-size: 75%;color: gray;">
				AIのべりすと プロンプト共有
			</div>
			<div class="col-1"></div>
		</footer>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>