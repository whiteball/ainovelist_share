<?= $this->extend('template') ?>
<?= $this->section('title') ?> - トークン検索ツール<?= $this->endSection() ?>

<?= $this->section('ogp') ?>
<?php $description = 'AIのべりすと向けの非公式トークン検索ツール。' ?>
<meta property="og:image" content="<?= base_url('img/ogp.png') ?>" />
<meta name="twitter:image" content="<?= base_url('img/ogp.png') ?>" />
<meta property="og:type" content="website" />
<meta property="og:title" content="トークン検索ツール" />
<meta name="twitter:title" content="トークン検索ツール">
<meta property="og:description" content="<?= $description ?>" />
<meta name="twitter:description" content="<?= $description ?>">
<meta name="description" content="<?= $description ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<h3>トークン検索ツール</h3>
	<div>
		これはAIのべりすとのトークンを検索して表示するツールです。<br>
		表示形式には単純なリスト、禁止ワードにそのままコピーして使える形式、@addbiasコマンドとしてそのままコピーして使える形式の3種類を選択できます。<br>
		検索対象となるトークンは、AIのべりすとWikiのトークン一覧を参考にしています。<br>
		トークンの過不足がないよう注意していますが、もし検索結果の不備により何らかの不都合が生じても、作者は責任を負いかねますのでご注意ください。
	</div>
	<form id="search-form" class="mt-3 mb-3">
		<div class="mb-3">
			<label class="form-label">入力</label>
			<input id="text" class="form-control">
		</div>
		<div>
			検索オプション：
			<div class="form-check form-check-inline">
				<input id="match-0" type="radio" name="match" value="p" class="form-check-input" checked><label for="match-0" class="form-check-label">～を含む</label>
			</div>
			<div class="form-check form-check-inline mb-3">
				<input id="match-1" type="radio" name="match" value="b" class="form-check-input"><label for="match-1" class="form-check-label">～から始まる</label>
			</div>
			<div class="form-check form-check-inline mb-3">
				<input id="match-2" type="radio" name="match" value="e" class="form-check-input"><label for="match-1" class="form-check-label">～で終わる</label>
			</div>
		</div>
		<div>
			対象モデル：
			<div class="form-check form-check-inline">
				<input id="type-0" type="radio" name="type" value="0" class="form-check-input" checked><label for="type-0" class="form-check-label">とりんさま/でりだ</label>
			</div>
			<div class="form-check form-check-inline mb-3">
				<input id="type-1" type="radio" name="type" value="1" class="form-check-input"><label for="type-1" class="form-check-label">やみおとめ</label>
			</div>
		</div>
		<div>
			<button id="submit" class="btn btn-primary" type="button">送信</button>
		</div>
	</form>
	<div>
		<h5>結果</h5>
		<ul class="nav nav-tabs" role="tablist">
			<li class="nav-item" role="presentation">
				<button class="nav-link active" id="output-area-list-tab" data-bs-toggle="tab" data-bs-target="#output-area-list" type="button" role="tab" aria-controls="output-area-list" aria-selected="true">
					リスト
				</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="output-area-ban-tab" data-bs-toggle="tab" data-bs-target="#output-area-ban" type="button" role="tab" aria-controls="output-area-ban" aria-selected="false">
					禁止ワード
				</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="output-area-bias-tab" data-bs-toggle="tab" data-bs-target="#output-area-bias" type="button" role="tab" aria-controls="output-area-bias" aria-selected="false">
					bias
				</button>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane show active border border-top-0 rounded-bottom p-2" id="output-area-list" role="tabpanel" aria-labelledby="list-tab">
			</div>
			<div class="tab-pane border border-top-0 rounded-bottom p-2" id="output-area-ban" role="tabpanel" aria-labelledby="ban-tab">
				<textarea id="output-area-ban-inner" class="form-control form-control-plaintext" rows="10" readonly></textarea>
			</div>
			<div class="tab-pane border border-top-0 rounded-bottom p-2" id="output-area-bias" role="tabpanel" aria-labelledby="bias-tab">
				<div class="input-group mb-3" style="width: 13rem;">
					<span class="input-group-text" id="basic-addon3">bias値</span>
					<input id="bias_value" class="form-control col-auto" type="number" min="-40" max="40" step="0.1" value="0">
				</div>
				<input type="hidden" id="bias_template">
				<textarea id="output-area-bias-inner" class="form-control form-control-plaintext" rows="10" readonly></textarea>
			</div>
		</div>

		<div id="output_area"></div>
	</div>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			document.getElementById('submit').addEventListener('click', function() {
				document.getElementById('output_area').innerHTML = ''
				const text = document.getElementById('text').value,
					type = document.getElementById('search-form').type.value,
					match = document.getElementById('search-form').match.value
				fetch('<?= site_url('api/get_tokens') ?>/' + (type ? type : 0) + '?m=' + (match ? match : 0) + '&q=' + encodeURIComponent(text))
					.then(res => res.json())
					.then(json => {
						const bias_value = document.getElementById('bias_value').value
						let list_html = `<div>${json.result.length} 件ヒット</div>
						<table class="table table-striped mb-3">
							<thead>
								<tr>
									<th>トークン</th>
								</tr>
							</thead>
							<tbody>`,
							ban_text = json.result.join('<<|>>'),
							bias_text = json.result.join(',\x01<<|>>') + ',\x01'
						for (const token of json.result) {
							list_html += `<tr><td>${token.replace('&', '&amp;').replace('<', '&lt;').replace('>', '&gt;')}</td></tr>`
						}
						list_html += `</tbody></table>`
						document.getElementById('output-area-list').innerHTML = list_html
						document.getElementById('output-area-ban-inner').value = ban_text
						document.getElementById('bias_template').value = bias_text
						document.getElementById('output-area-bias-inner').value = '@addbias ' + bias_text.replace(/\x01/g, bias_value)
					})
					.catch(error => {
						document.getElementById('output-area-list').innerText = 'error'
						document.getElementById('output-area-ban-inner').value = 'error'
						document.getElementById('bias_template').value = ''
						document.getElementById('output-area-bias-inner').value = 'error'
					})
			})
			document.getElementById('bias_value').addEventListener('change', function () {
				const template = document.getElementById('bias_template').value
				if (template) {
					document.getElementById('output-area-bias-inner').value = '@addbias ' + template.replace(/\x01/g, this.value)
				}
			})
		})
	</script>
</main>
<?= $this->endSection() ?>