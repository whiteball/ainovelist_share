<?= $this->extend('template') ?>
<?= $this->section('title') ?> - NG設定<?= $this->endSection() ?>

<?= $this->section('ogp') ?>
<?php $description = 'プロンプト共有のNG設定ページです。' ?>
<meta property="og:image" content="<?= base_url('img/ogp.png') ?>" />
<meta name="twitter:image" content="<?= base_url('img/ogp.png') ?>" />
<meta property="og:type" content="website" />
<meta property="og:title" content="NG設定" />
<meta name="twitter:title" content="NG設定">
<meta property="og:description" content="<?= $description ?>" />
<meta name="twitter:description" content="<?= $description ?>">
<meta name="description" content="<?= $description ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<h3>NG設定</h3>
	<div class="mb-3">
		プロンプト共有のサイト内で使用する、NGユーザー、NGタグの確認・削除を行うページです。
		<h5 class="mt-2">注意事項</h5>
		<ul>
			<li>NG設定は各ブラウザ(Cookie)に保存されます。ブラウザを変えると、NG設定をやり直す必要があります。また、ブラウザの設定を消したり、長期間サイトにアクセスがない場合にも、NG設定が消える可能性があります。</li>
			<li>NGユーザーは、投稿プロンプトのユーザー名をクリックして表示できる、ユーザー個別ページから追加できます。</li>
			<li>NGタグは、投稿プロンプトのタグ名、またはタグ一覧のタグ名をクリックして表示できる、タグ個別ページから追加できます。</li>
			<li>NGユーザーに指定していても、そのユーザーの個別ページでは投稿プロンプトが表示されます。</li>
			<li>ランキングはNGの対象外です。</li>
			<li>大量にNGを登録すると、表示が遅くなる可能性があります。</li>
		</ul>
	</div>
	<ul class="nav nav-tabs" id="myTab" role="tablist">
		<li class="nav-item" role="presentation">
			<button class="nav-link active" id="user-tab" data-bs-toggle="tab" data-bs-target="#user" type="button" role="tab" aria-controls="user" aria-selected="true">
				NGユーザー
			</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link" id="tag-tab" data-bs-toggle="tab" data-bs-target="#tag" type="button" role="tab" aria-controls="tag" aria-selected="false">
				NGタグ
			</button>
		</li>
	</ul>
	<div class="tab-content" id="myTabContent">
		<div class="tab-pane show active" id="user" role="tabpanel" aria-labelledby="user-tab">
			<?php if (empty($userList)): ?>
				<div class="m-4">NGユーザーは登録されていません。</div>
			<?php else: ?>
				<div class="mx-4">
					<table id="ng-user-table" class="table table-striped">
						<thead>
							<tr>
								<td style="width: 3rem;"></td>
								<td>ユーザー名</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($userList as $id => $name): ?>
								<tr>
									<td>
										<input type="checkbox" data-user="<?= esc($id) ?>">
									</td>
									<td>
										<?= esc($name) ?>
									</td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
					<div>
						<button id="ng-user-remove-button" class="btn btn-danger">チェックしたユーザーをNGから削除</button>
					</div>
				</div>
			<?php endif ?>
		</div>
		<div class="tab-pane" id="tag" role="tabpanel" aria-labelledby="tag-tab">
			<?php if (empty($tagList)): ?>
				<div class="m-4">NGタグは登録されていません。</div>
			<?php else: ?>
				<div class="mx-4">
					<table id="ng-tag-table" class="table table-striped mt-2">
						<thead>
							<tr>
								<td style="width: 3rem;"></td>
								<td>タグ名</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($tagList as $name): ?>
								<tr>
									<td>
										<input type="checkbox" data-tag="<?= esc($name, 'attr') ?>">
									</td>
									<td>
										<?= esc($name) ?>
									</td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
					<div>
						<button id="ng-tag-remove-button" class="btn btn-danger">チェックしたタグをNGから削除</button>
					</div>
				</div>
			<?php endif ?>
		</div>
	</div>
	<script>
		const userButton = document.getElementById('ng-user-remove-button')
		if (userButton) {
			userButton.addEventListener('click', function () {
				const list = document.querySelectorAll('#ng-user-table input:checked')
				if (list.length > 0) {
					if (window.confirm('チェックされたユーザーをNGから削除します。\nよろしいですか？')) {
						const ng_users_data = document.cookie.split('; ').find(row => row.startsWith('ng_users='))
						const ng_users = ng_users_data ? ng_users_data.split('=')[1] : ''
						if (ng_users === '') {
							return;
						}
						const ng_user_list = decodeURIComponent(ng_users).split(' ')
						const delete_target = new Set()
						for (const node of list) {
							const user = node.getAttribute('data-user')
							if (user !== '') {
								delete_target.add(user)
								node.parentNode.parentNode.remove()
							}
						}
						document.cookie = 'ng_users=' + encodeURIComponent(ng_user_list.filter(val => !delete_target.has(val)).join(' ')) + '; samesite=lax;<?= ENVIRONMENT === 'production' ? ' secure;' : '' ?> max-age=31536000; path=/'
					}
				} else {
					window.alert('削除対象のユーザーが選択されていません。')
				}
			})
		}
		const tagButton = document.getElementById('ng-tag-remove-button')
		if (tagButton) {
			tagButton.addEventListener('click', function () {
				const list = document.querySelectorAll('#ng-tag-table input:checked')
				if (list.length > 0) {
					if (window.confirm('チェックされたタグをNGから削除します。\nよろしいですか？')) {
						const ng_tags_data = document.cookie.split('; ').find(row => row.startsWith('ng_tags='))
						const ng_tags = ng_tags_data ? ng_tags_data.split('=')[1] : ''
						if (ng_tags === '') {
							return;
						}
						const ng_tag_list = decodeURIComponent(ng_tags).split(' ')
						const delete_target = new Set()
						for (const node of list) {
							const tag = node.getAttribute('data-tag')
							if (tag !== '') {
								delete_target.add(tag)
								node.parentNode.parentNode.remove()
							}
						}
						document.cookie = 'ng_tags=' + encodeURIComponent(ng_tag_list.filter(val => !delete_target.has(val)).join(' ')) + '; samesite=lax;<?= ENVIRONMENT === 'production' ? ' secure;' : '' ?> max-age=31536000; path=/'
					}
				} else {
					window.alert('削除対象のタグが選択されていません。')
				}
			})
		}
	</script>
</main>
<?= $this->endSection();