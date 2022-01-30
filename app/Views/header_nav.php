<div class="container">
	<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
		<div class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
			<a href="<?= site_url('/')?>" class="nav-link px-2 link-dark">AIのべりすと プロンプト共有(仮)</a>
		</div>

		<ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
			<li><a href="<?= site_url('/about')?>" class="nav-link px-2 link-dark">サイトについて</a></li>
		</ul>

		<div class="col-md-3 text-end">
			<?php if (isset($_SESSION['login'])): ?>
				<a type="button" class="btn btn-success me-2 btn-sm" href="<?= site_url('create') ?>">投稿</a>
				<a type="button" class="btn btn-info me-2 btn-sm" href="<?= site_url('config') ?>">マイページ</a>
			<?php else: ?>
				<a type="button" class="btn btn-outline-primary me-2 btn-sm" href="<?= site_url('login') ?>">サインイン</a>
				<a type="button" class="btn btn-primary btn-sm" href="<?= site_url('register') ?>">登録</a>
			<?php endif ?>
		</div>
	</header>
</div>