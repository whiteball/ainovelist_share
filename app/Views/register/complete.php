<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<?= $this->include('header_nav') ?>
<main class="form-register">
	<h1 class="h3 mb-3 fw-normal">ユーザー登録完了</h1>
	<div>
		ユーザー登録が完了しました。<br>入力したIDとパスワードでログインしてください。
	</div>
</main>
<?= $this->endSection() ?>