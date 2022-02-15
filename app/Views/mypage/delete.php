<?= $this->extend('template') ?>
<?= $this->section('title') ?> - マイページ - 退会<?= $this->endSection() ?>

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
			<a class="nav-link active" href="<?= site_url('mypage/delete') ?>" aria-current="page">
				退会
			</a>
		</li>
	</ul>
	<div class="border border-top-0 rounded-bottom p-2">
		<h4 class="h4 fw-normal">退会</h4>
		<?= form_open('', ['name' => 'delete_form']) ?>
		<?= csrf_field() ?>
		<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">退会する</button>
		<?= form_close() ?>
		<div class="alert alert-danger mt-3">
			退会処理を行うと、すべての投稿したプロンプトが削除され、ログインができなくなります。
		</div>
	</div>
	<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="deleteAccountModalLabel">退会処理を実行します</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					退会処理を実行します。<br>退会処理を行うと、すべての投稿したプロンプトが削除され、ログインができなくなります。<br>よろしいですか？
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" id="delete_button">退会する</button>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
				</div>
			</div>
		</div>
	</div>
	<script>
		document.getElementById('delete_button').addEventListener('click', function() {
			document.delete_form.submit()
		})
	</script>

</main>
<?= $this->endSection() ?>