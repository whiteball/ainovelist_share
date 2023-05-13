<?= $this->extend('template') ?>
<?= $this->section('title') ?> - マイページ - 設定<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<h1 class="h3 mb-3 fw-normal">マイページ</h1>

	<ul class="nav nav-tabs" id="myTab" role="tablist">
		<li class="nav-item" role="presentation">
			<a class="nav-link active" href="<?= site_url('mypage') ?>" aria-current="page">
				設定
			</a>
		</li>
		<li class="nav-item" role="presentation">
			<a class="nav-link" href="<?= site_url('mypage/list') ?>">
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
		<?= form_open('logout') ?>
		<?= csrf_field() ?>
		<button type="submit" class="btn btn-outline-danger">ログアウト</button>
		<?= form_close() ?>
		<hr>
		<?= form_open() ?>
		<?= csrf_field() ?>
		<?= form_hidden('type', 'change_name') ?>
		<div class="mb-3 row">
			<h4 class="h4 fw-normal">ユーザー名変更</h4>
			<label for="screen-name" class="col-sm-2 col-form-label">新しいユーザー名</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="screen-name" name="screen_name" value="<?= set_value('screen_name', $screen_name); ?>">
				<?= $validation->showError('screen_name') ?>
			</div>
		</div>
		<?php if (! empty($success_message)) : ?>
			<div class="alert alert-success"><?= esc($success_message) ?></div>
		<?php endif ?>
		<button type="submit" class="btn btn-primary">変更</button>
		<?= form_close() ?>
		<hr>
		<h4 class="h4 fw-normal">パスワード変更</h4>
		<?= form_open() ?>
		<?= csrf_field() ?>
		<?= form_hidden('type', 'change_password') ?>
		<div class="mb-3 row">
			<label for="current-password" class="col-sm-2 col-form-label">現在のパスワード</label>
			<div class="col-sm-10">
				<input type="password" class="form-control" id="current-password" name="current_password">
				<?= $validation->showError('current_password') ?>
			</div>
		</div>
		<div class="mb-3 row">
			<label for="new-password" class="col-sm-2 col-form-label">新しいパスワード</label>
			<div class="col-sm-10">
				<input type="password" class="form-control" id="new-password" name="new_password">
				<?= $validation->showError('new_password') ?>
			</div>
		</div>
		<div class="mb-3 row">
			<label for="new-password-confirm" class="col-sm-2 col-form-label">新しいパスワード(再入力)</label>
			<div class="col-sm-10">
				<input type="password" class="form-control" id="new-password-confirm" name="new_password_confirm">
				<?= $validation->showError('new_password_confirm') ?>
			</div>
		</div>
		<?php if (! empty($success_message2)) : ?>
			<div class="alert alert-success"><?= esc($success_message2) ?></div>
		<?php endif ?>
		<?php if (! empty($error_message2)) : ?>
			<div class="alert alert-danger"><?= esc($error_message2) ?></div>
		<?php endif ?>
		<button type="submit" class="btn btn-primary">変更</button>
		<?= form_close() ?>
		<hr>
		<?= form_open() ?>
		<?= csrf_field() ?>
		<?= form_hidden('type', 'password_reset') ?>
		<div class="mb-3 row">
			<h4 class="h4 fw-normal">パスワードリセット設定</h4>
			<div>
				パスワードを忘れたとき、ここで設定したメールアドレスとログインIDを入力することで、パスワードリセットをすることができます。<br>
				メールアドレスの登録に成功すると、そのアドレスに受付完了メールが送られます。<br>
				メールアドレスはハッシュ化して保存するため、サイト運営者にメールアドレスが知られることはありません。<br>
				※申し訳ありませんが、Gmailは送信元のセキュリティ要件が厳しいため、登録してもメールが届きません。
			</div>
			<?php if (empty($has_reset)) : ?>
				<div>設定状態：未設定</div>
			<?php else : ?>
				<div>設定状態：<span class="text-success">設定済み</span></div>
			<?php endif ?>
			<label for="mail-address" class="col-sm-2 col-form-label">メールアドレス</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="mail-address" name="mail_address" value="">
				<?= $validation->showError('mail_address') ?>
			</div>
		</div>
		<?php if (! empty($mail_address) && ! empty($from_address)) : ?>
			<div class="alert alert-success" role="alert">
				「<?= esc($mail_address) ?>」をパスワードリセット用に登録して、「<?= esc($from_address) ?>」から受付完了メールを送信しました。<br>
				もしメールが届かない場合は、再度パスワードリセット設定を行ってください。
			</div>
		<?php endif ?>
		<button type="submit" class="btn btn-primary">設定</button>
		<?= form_close() ?>
	</div>
</main>
<?= $this->endSection() ?>