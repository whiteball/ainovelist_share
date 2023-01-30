<div class="container">
	<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-md-3 mb-4 border-bottom">
		<div class="d-flex align-items-center col-3 col-sm-2 col-md-3 mb-0 text-dark text-decoration-none" style="word-break: keep-all;">
			<a href="<?= site_url('/')?>" class="nav-link px-2 link-dark text-center">AIのべりすと<wbr> プロンプト共有</a>
		</div>

		<ul class="nav col-4 col-sm-6 col-md-6 ps-5 ps-sm-4 ps-md-0 justify-content-center">
			<li><a href="<?= site_url('/about')?>" class="nav-link px-2 link-dark" style="word-break: keep-all;">サイトについて</a></li>
			<li class="d-none d-lg-inline"><a href="<?= site_url('/tags')?>" class="nav-link px-2 link-dark">タグ一覧</a></li>
			<li class="d-none d-lg-inline"><a href="<?= site_url('/ranking')?>" class="nav-link px-2 link-dark">ランキング</a></li>
			<li class="d-none d-lg-inline"><a href="<?= site_url('/script')?>" class="nav-link px-2 link-dark">ユーザースクリプト</a></li>
			<li class="d-none d-lg-inline"><a href="<?= site_url('/tool')?>" class="nav-link px-2 link-dark">ツール</a></li>
			<li class="nav-item dropdown d-inline d-lg-none">
				<a class="nav-link dropdown-toggle px-2 link-dark" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
					ナビゲーション
				</a>
				<ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
					<li><a href="<?= site_url('/tags')?>" class="dropdown-item link-dark">タグ一覧</a></li>
					<li><a href="<?= site_url('/ranking')?>" class="dropdown-item link-dark">ランキング</a></li>
					<li><a href="<?= site_url('/script')?>" class="dropdown-item link-dark">ユーザースクリプト</a></li>
					<li><a href="<?= site_url('/tool')?>" class="dropdown-item link-dark">ツール</a></li>
				</ul>
			</li>
		</ul>

		<div class="col-5 col-sm-4 col-md-3 text-end">
			<?php if (isset($_SESSION['login'])): ?>
				<a type="button" class="btn btn-outline-success me-2 btn-sm" href="<?= site_url('create') ?>" style="font-size:75%">投稿</a>
				<a type="button" class="btn btn-outline-info me-2 btn-sm" href="<?= site_url('mypage') ?>" style="font-size:75%">マイページ</a>
			<?php else: ?>
				<a type="button" class="btn btn-outline-primary me-2 btn-sm" href="<?= site_url('login') ?>" style="font-size:75%">サインイン</a>
				<a type="button" class="btn btn-primary btn-sm" href="<?= site_url('register') ?>" style="font-size:75%">登録</a>
			<?php endif ?>
		</div>
	</header>
</div>