<?= $this->extend('template') ?>
<?= $this->section('title') ?> - トークンカウントツール<?= $this->endSection() ?>

<?= $this->section('ogp') ?>
<?php $description = 'AIのべりすと向けの非公式トークンカウントツール。' ?>
<meta property="og:image" content="<?= base_url('img/ogp.png') ?>" />
<meta name="twitter:image" content="<?= base_url('img/ogp.png') ?>" />
<meta property="og:type" content="website" />
<meta property="og:title" content="トークンカウントツール" />
<meta name="twitter:title" content="トークンカウントツール">
<meta property="og:description" content="<?= $description ?>" />
<meta name="twitter:description" content="<?= $description ?>">
<meta name="description" content="<?= $description ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<h3>トークンカウントツール</h3>
	<div>
		これはAIのべりすとに送られた文章がどのようにトークンに分割されるかを、簡易的に表示してカウントするツールです。<br>
		AIのべりすとが内部で行っていると思われるトークン分割処理とは異なる方法で分割していますので、必ずしもこのツールの通りに分割されているとは限りません。<br>
		特に、全角の記号や改行やスペースが上手く取り扱えません。あくまで簡易的なものとご承知ください。<br>
		とりんさま/でりだのトークン分割/カウントなら、より正確な<a href="https://colab.research.google.com/github/acorncat/unofficial-tools/blob/main/TokenizeForAIN.ipynb" rel="noopener noreferrer">AIのべりすと非公式トークン化ツール</a>(要Googleアカウント)をお使い下さい。
	</div>
	<form id="search-form" class="mt-3 mb-3">
		<div class="mb-3">
			<label class="form-label">入力</label>
			<textarea id="text" class="form-control"></textarea>
		</div>
		<div class="form-check form-check-inline">
			<input id="type-0" type="radio" name="type" value="0" class="form-check-input" checked><label for="type-0" class="form-check-label">とりんさま/でりだ</label>
		</div>
		<div class="form-check form-check-inline mb-3">
			<input id="type-1" type="radio" name="type" value="1" class="form-check-input"><label for="type-1" class="form-check-label">やみおとめ</label>
		</div>
		<div>
			<button id="submit" class="btn btn-primary" type="button">送信</button>
		</div>
	</form>
	<div>
		<h5>結果</h5>
		<div id="output-area"></div>
	</div>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			document.getElementById('submit').addEventListener('click', function () {
				document.getElementById('output-area').innerHTML = ''
				const text = document.getElementById('text').value,
					type = document.getElementById('search-form').type.value
				fetch('<?= site_url('api/count_tokens') ?>/' + (type ? type : 0) + '?q=' + encodeURIComponent(text))
					.then(res => res.json())
					.then(json => {
						let html = `<table class="table table-striped mb-3">
							<thead>
								<tr>
									<th>トークン</th>
									<th>文字数</th>
								</tr>
							</thead>
							<tbody>`,
							length = 0
						for (const token of json.result) {
							if (token === '<unk>') {
								length += 1
								html += `<tr><td>不明(unknown)</td><td>1</td></tr>`
							} else {
								const len = token.length - ((token.indexOf('\\n') >= 0) ? 1 : 0)
								length += len
								html += `<tr><td>${token.replace('&', '&amp;').replace('<', '&lt;').replace('>', '&gt;')}</td><td>${len}</td></tr>`
							}
						}
						html += `</tbody></table>`
						html = `<div>
							合計：${length}文字 / ${json.result.length} トークン ・ 1トークンあたり${Math.round(length/json.result.length*100)/100} 文字
						</div>` + html
						document.getElementById('output-area').innerHTML = html
					})
					.catch(error => {
						document.getElementById('output-area').innerText = 'error'
					})
			})
		})
	</script>
</main>
<?= $this->endSection() ?>