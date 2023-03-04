<?= $this->extend('template') ?>
<?= $this->section('title') ?> - <?= esc(strip_tags($prompt->title)) ?><?= $this->endSection() ?>

<?= $this->section('ogp') ?>
<?php $description = esc(str_replace("\n", ' ', $prompt->description), 'attr') ?>
<meta property="og:image" content="<?= esc($ogp, 'attr') ?>" />
<meta name="twitter:image" content="<?= esc($ogp, 'attr') ?>" />
<meta property="og:type" content="article" />
<meta property="og:title" content="<?= esc(strip_tags($prompt->title), 'attr') ?>" />
<meta name="twitter:title" content="<?= esc(strip_tags($prompt->title), 'attr') ?>">
<meta property="og:description" content="<?= $description ?>" />
<meta name="twitter:description" content="<?= $description ?>">
<meta name="description" content="<?= $description ?>" />
<meta property="article:published_time" content="<?= date_format(date_create($prompt->registered_at), DATE_ATOM)?>" />
<meta property="article:modified_time" content="<?= date_format(date_create($prompt->updated_at), DATE_ATOM)?>" />
<meta property="article:author" content="<?= esc($author, 'attr') ?>" />
<?php if ($prompt->r18 === '1') : ?>
	<meta property="article:tag" content="R-18" />
<?php endif ?>
<?php foreach ($tags as $tag) : ?>
	<meta property="article:tag" content="<?= esc($tag->tag_name, 'attr') ?>" />
<?php endforeach ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<?php if (! empty($successMessage)) : ?>
		<div class="alert alert-success" role="alert">
			<?= esc($successMessage) ?>
		</div>
	<?php endif ?>
	<?php if (! empty($errorMessage)) : ?>
		<div class="alert alert-warning" role="alert">
			<?= esc($errorMessage) ?>
		</div>
	<?php endif ?>
	<h1 class="h3 mb-3 fw-normal">プロンプト詳細</h1>
	<div class="mb-3 border rounded p-2">
		<div class="row">
			<div class="col-6 col-md-4 col-lg-3">
				<a class="btn btn-secondary" href="<?= site_url('prompt_download/' . $prompt->id) ?>">
					<span class="d-inline d-sm-none">novelファイルをDL</span>
					<span class="d-none d-sm-inline">novelファイルをダウンロード</span>
				</a>
			</div>
			<div class="col-6 col-md-4 col-lg-5">
				<a class="btn btn-secondary" href="https://ai-novel.com/prompt_load.php?uri=<?= urlencode(site_url('prompt_download/' . $prompt->id)) ?>" target="_blank" rel="noopener noreferrer">
					<span class="d-inline d-sm-none">AIのべりすとで読込</span>
					<span class="d-none d-sm-inline">AIのべりすとで読み込む</span>
				</a>
			</div>
			<div class="col-12 col-md-4 text-md-end" style="font-size: 75%;">
				<ul class="list-group list-group-horizontal">
					<li class="list-group-item flex-fill text-secondary" style="border: none;">View: <?= esc($prompt->view) ?></li>
					<li class="list-group-item flex-fill text-secondary" style="border: none;">Download: <?= esc($prompt->download) ?></li>
					<li class="list-group-item flex-fill text-secondary" style="border: none;">Import: <?= esc($prompt->import) ?></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="mb-3 text-end">
		<span class="text-secondary">投稿日:<?= esc($prompt->registered_at) ?><?php if ($prompt->updated_at !== $prompt->registered_at):?>/更新日:<?= esc($prompt->updated_at) ?><?php endif ?></span>
	<?php if (! empty($loginUserId) && (int) $prompt->user_id === $loginUserId): ?>
		&nbsp;
			<a class="btn btn-outline-success btn-sm" href="<?= site_url('edit/' . $prompt->id) ?>">
				<span class="d-inline d-md-none">編集</span>
				<span class="d-none d-md-inline">このプロンプトを編集</span>
			</a>
	<?php endif ?>
	</div>
	<div class="mb-3 border rounded p-2">
		<div class="mb-3">
			<h5>タイトル</h5>
			<div class="wrap border rounded p-2"><?= str_replace(' ', '&nbsp;', esc(strip_tags($prompt->title))) ?></div>
		</div>
		<div class="mb-3">
			<h5>タグ</h5>
			<div class="wrap border rounded p-2">
				<?php if (! empty($parameters['gui_mode']) && $parameters['gui_mode'] === '1') : ?>
					<a class="btn rounded-pill btn-sm tag-link chat-icon" href="<?= site_url('search/chat') ?>"><img src="<?= base_url('img/chat_mode.svg')?>" title="「チャット／ゲーム」モード用プロンプト"></a>
				<?php endif ?>
				<?php if ($prompt->r18 === '1') : ?>
					<a class="btn rounded-pill btn-danger btn-sm tag-link" href="<?= site_url('tag/R-18') ?>">R-18</a>
				<?php endif ?>
				<?php foreach ($tags as $tag) : ?>
					<a class="btn rounded-pill btn-outline-secondary btn-sm tag-link" href="<?= site_url('tag/' . $tag->tag_name) ?>"><?= esc($tag->tag_name) ?></a>
				<?php endforeach ?>
			</div>
		</div>
		<div class="mb-3">
			<h5>作者</h5>
			<div class="wrap border rounded p-2"><a class="btn btn-outline-secondary" href="<?= site_url('user/' . $prompt->user_id) ?>"><?= esc($author) ?></a></div>
		</div>
		<div class="mb-3">
			<h5>説明</h5>
			<div class="wrap border rounded p-2"><?= nl2br(preg_replace('#(' . site_url('prompt'). '/(\d+))#u', '<a href="$1">prompt/$2</a>', str_replace(' ', '&nbsp;', esc($prompt->description)))) ?></div>
		</div>
		<div class="mb-3">
			<h5>転載・改変可否</h5>
			<?php if ($prompt->license === '1') : ?>
				<div class="wrap border rounded p-2"><h5><span class="badge bg-danger text-white">禁止</span></h5>(公開せずに個人的に楽しむ範囲の改変は可能です)</div>
			<?php elseif ($prompt->license === '2') : ?>
				<div class="wrap border rounded p-2"><h5><span class="badge bg-success text-white">許可</span></h5>(許可条件は<a href="https://creativecommons.org/licenses/by-sa/4.0/deed.ja" target="_blank" rel="noopener noreferrer">クリエイティブコモンズ 表示-継承 4.0</a>を参照してください)</div>
			<?php else: ?>
				<div class="wrap border rounded p-2"><h5><span class="badge bg-secondary text-white">説明欄での条件による</span></h5>(未記入の場合、個別に許可を得られなければ禁止です)</div>
			<?php endif ?>
		</div>
		<div class="mb-3">
			<h5>プロンプト(本文)<button class="btn btn-sm copy-btn" data-target="prompt"><img alt="copy" src="<?= base_url('img/copy.svg') ?>" width="20"></button></h5>
			<div class="wrap border rounded p-2" id="prompt-text"><?= nl2br(str_replace(' ', '&nbsp;', esc($prompt->prompt))) ?></div>
		</div>
	</div>
	<?php if (! (empty($prompt->memory) && empty($prompt->authors_note) && empty($prompt->ng_words) && empty($prompt->char_book) && empty($prompt->script) && empty($parameters) && empty($prompt->chat_template))) : ?>
		<div class="mb-3 border rounded p-2">
			<div class="accordion" id="detail-section">
				<?php if (! empty($prompt->memory)) : ?>
					<div class="accordion-item">
						<h2 class="accordion-header" id="memory-head">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#memory-body" aria-expanded="false" aria-controls="memory-body">
								メモリ
							</button>
						</h2>
						<div id="memory-body" class="accordion-collapse collapse" aria-labelledby="memory-head">
							<div class="accordion-body">
								<div class="row">
									<div class="col-12">
										<div class="position-relative">
											<button class="btn btn-sm copy-btn position-absolute top-0 end-0" data-target="memory"><img alt="copy" src="<?= base_url('img/copy.svg') ?>" width="20"></button>
										</div>
									</div>
									<div class="col-11" id="memory-text"><?= nl2br(str_replace(' ', '&nbsp;', esc($prompt->memory))) ?></div>
								</div>
							</div>
						</div>
					</div>
				<?php endif ?>
				<?php if (! empty($prompt->authors_note)) : ?>
					<div class="accordion-item">
						<h2 class="accordion-header" id="authors-note-head">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#authors-note-body" aria-expanded="false" aria-controls="authors-note-body">
								脚注
							</button>
						</h2>
						<div id="authors-note-body" class="accordion-collapse collapse" aria-labelledby="authors-note-head">
							<div class="accordion-body">
								<div class="row">
									<div class="col-12">
										<div class="position-relative">
											<button class="btn btn-sm copy-btn position-absolute top-0 end-0" data-target="authors-note"><img alt="copy" src="<?= base_url('img/copy.svg') ?>" width="20"></button>
										</div>
									</div>
									<div class="col-11" id="authors-note-text"><?= nl2br(str_replace(' ', '&nbsp;', esc($prompt->authors_note))) ?></div>
								</div>
							</div>
						</div>
					</div>
				<?php endif ?>
				<?php if (! empty($prompt->ng_words)) : ?>
					<div class="accordion-item">
						<h2 class="accordion-header" id="ng-words-head">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ng-words-body" aria-expanded="false" aria-controls="ng-words-body">
								禁止ワード
							</button>
						</h2>
						<div id="ng-words-body" class="accordion-collapse collapse" aria-labelledby="ng-words-head">
							<div class="accordion-body">
								<div class="row">
									<div class="col-12">
										<div class="position-relative">
											<button class="btn btn-sm copy-btn position-absolute top-0 end-0" data-target="ng-words"><img alt="copy" src="<?= base_url('img/copy.svg') ?>" width="20"></button>
										</div>
									</div>
									<div class="col-11" id="ng-words-text"><?= nl2br(str_replace(' ', '&nbsp;', esc($prompt->ng_words))) ?></div>
								</div>
							</div>
						</div>
					</div>
				<?php endif ?>
				<?php if (! empty($prompt->char_book)) : ?>
					<div class="accordion-item">
						<h2 class="accordion-header" id="char-book-head">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#char-book-body" aria-expanded="false" aria-controls="char-book-body">
								キャラクターブック
							</button>
						</h2>
						<div id="char-book-body" class="accordion-collapse collapse" aria-labelledby="char-book-head">
							<div class="accordion-body">
								<?php foreach ($prompt->char_book as $char_book) : ?>
									<div>
										<h6 class="wrap">タグ: <span id="char-tag-<?= esc($char_book['id'], 'attr') ?>-text"><?= str_replace(' ', '&nbsp;', esc($char_book['tag'])) ?></span><button class="btn btn-sm copy-btn" data-target="char-tag-<?= esc($char_book['id'], 'attr') ?>"><img alt="copy" src="<?= base_url('img/copy.svg') ?>" width="18"></h6>
										<div class="wrap border rounded p-2 row">
											<div class="col-12">
												<div class="position-relative">
													<button class="btn btn-sm copy-btn position-absolute top-0 end-0" data-target="char-content-<?= esc($char_book['id'], 'attr') ?>"><img alt="copy" src="<?= base_url('img/copy.svg') ?>" width="20"></button>
												</div>
											</div>
											<div class="col-11" id="char-content-<?= esc($char_book['id'], 'attr') ?>-text"><?= nl2br(str_replace(' ', '&nbsp;', esc($char_book['content']))) ?></div>
										</div>
									</div>
								<?php endforeach ?>
							</div>
						</div>
					</div>
				<?php endif ?>
				<?php if (! empty($prompt->script)) : ?>
					<div class="accordion-item">
						<h2 class="accordion-header" id="script-head">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#script-body" aria-expanded="false" aria-controls="script-body">
								スクリプト
							</button>
						</h2>
						<div id="script-body" class="accordion-collapse collapse" aria-labelledby="script-head">
							<div class="accordion-body">
								<?php $type_list = ['script_in' => '入力文の置換', 'script_out' => '出力文の置換', 'script_in_pin' => '【最新】入力文の確定置換', 'script_in_pin_all' => '【本文全体】入力文の確定置換', 'script_in_pin_user' => '送信欄の置換', 'script_rephrase' => '単語の言い換え', 'script_in_regex' => '入力文の置換（正規表現）', 'script_out_regex' => '出力文の置換（正規表現）', 'script_in_pin_regex' => '【最新】入力文の確定置換（正規表現）', 'script_in_pin_all_regex' => '【本文全体】入力文の確定置換（正規表現）', 'script_in_pin_user_regex' => '送信欄の置換（正規表現）', 'script_rephrase_regex' => '単語の言い換え（正規表現）', 'script_none' => '使用しない'] ?>
								<?php foreach ($prompt->script as $script) : ?>
									<div class="border rounded p-2">
										<table class="table">
											<tr>
												<th scope="row" width="85">種類
												<td><?= esc($type_list[$script['type']]) ?></td>
											</tr>
											<tr>
												<th scope="row">IN<button class="btn btn-sm copy-btn" data-target="script-in-<?= esc($script['id'], 'attr') ?>"><img alt="copy" src="<?= base_url('img/copy.svg') ?>" width="16"></th></th>
												<td id="script-in-<?= esc($script['id'], 'attr') ?>-text" class="wrap-cell"><?= str_replace(' ', '&nbsp;', esc($script['in'])) ?></td>
											</tr>
											<tr>
												<th scope="row">OUT<button class="btn btn-sm copy-btn" data-target="script-out-<?= esc($script['id'], 'attr') ?>"><img alt="copy" src="<?= base_url('img/copy.svg') ?>" width="16"></th>
												<td id="script-out-<?= esc($script['id'], 'attr') ?>-text" class="wrap-cell"><?= str_replace(' ', '&nbsp;', esc($script['out'])) ?></td>
											</tr>
										</table>
									</div>
								<?php endforeach ?>
							</div>
						</div>
					</div>
				<?php endif ?>
				<?php if (! empty($parameters)) : ?>
					<div class="accordion-item">
						<h2 class="accordion-header" id="parameters-head">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#parameters-body" aria-expanded="false" aria-controls="parameters-body">
								パラメーター設定など
							</button>
						</h2>
						<div id="parameters-body" class="accordion-collapse collapse" aria-labelledby="parameters-head">
							<div class="container p-3">
								<div id="parameters" class="row">
									<h5 class="col-12">詳細パラメータ</h5>
									<div class="col-12 col-lg-6 col-xxl-4 text-center">
										<table class="table table-bordered">
											<tbody>
												<tr>
													<th style="width: 50%;">ランダム度</th>
													<td><?= esc((float) $parameters['temperature'] / 40) ?></td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-12 col-lg-6 col-xxl-4 text-center">
										<table class="table table-bordered">
											<tbody>
												<tr>
													<th style="width: 50%;">トップP</th>
													<td><?= esc((float) $parameters['top_p'] / 40) ?></td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-12 col-lg-6 col-xxl-4 text-center">
										<table class="table table-bordered">
											<tbody>
												<tr>
													<th style="width: 50%;">テイルフリー</th>
													<td><?= esc(0.75 + (float) $parameters['tfs'] / 160) ?></td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-12 col-lg-6 col-xxl-4 text-center">
										<table class="table table-bordered">
											<tbody>
												<tr>
													<th style="width: 50%;">繰り返しペナルティ</th>
													<td><?= esc((float) $parameters['freq_p'] / 80) ?></td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-12 col-lg-6 col-xxl-4 text-center">
										<table class="table table-bordered">
											<tbody>
												<tr>
													<th style="width: 50%;">出力の長さ</th>
													<td>約<?= esc(floor((float) $parameters['length'] * 2.5)) ?>文字</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-12 col-lg-6 col-xxl-4 text-center">
										<table class="table table-bordered">
											<tbody>
												<tr>
													<th style="width: 50%;">タイピカルP</th>
													<td><?= ((int) $parameters['typical_p'] <= 99) ? esc( (float) $parameters['typical_p'] / 100) : '-' ?></td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-12 col-lg-6 col-xxl-4 text-center">
										<table class="table table-bordered">
											<tbody>
												<tr>
													<th style="width: 50%;">繰り返しペナルティ（検索範囲）</th>
													<td><?= esc((int) $parameters['freq_p_range'] * 8) ?></td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-12 col-lg-6 col-xxl-4 text-center">
										<table class="table table-bordered">
											<tbody>
												<tr>
													<th style="width: 50%;">繰り返しペナルティ（傾斜）</th>
													<td><?= esc((float) $parameters['freq_p_slope'] / 20) ?></td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-12 col-lg-6 col-xxl-4 text-center">
										<table class="table table-bordered">
											<tbody>
												<tr>
													<th style="width: 50%;">AIが読み取るコンテキストの長さ</th>
													<td>約<?= esc(floor((float) $parameters['contextwindow'] * 20)) ?>文字</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-12 col-lg-6 col-xxl-4 text-center">
										<table class="table table-bordered">
											<tbody>
												<tr>
													<th style="width: 50%;">キャラクターブックの優先度</th>
													<td><?= ((int) $parameters['wiplacement'] >= 30) ? '本文の後ろ' : esc( (int) $parameters['wiplacement'] * 2) ?></td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-12 col-lg-6 col-xxl-4 text-center">
										<table class="table table-bordered">
											<tbody>
												<tr>
													<th style="width: 50%;">脚注の優先度</th>
													<td><?= esc($parameters['anplacement']) ?></td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-12 col-lg-6 col-xxl-4 text-center">
										<table class="table table-bordered">
											<tbody>
												<tr>
													<th style="width: 50%;">キャラクターブックをスキャンする文字数</th>
													<td><?= esc((int) $parameters['wiscanrange'] * 8) ?></td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-12 col-lg-6 col-xxl-4 text-center">
										<table class="table table-bordered">
											<tbody>
												<tr>
													<th style="width: 50%;">セリフの量</th>
													<td><?= esc((float) $parameters['dialogue_density'] / 0.2) ?>%</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-12 col-lg-6 col-xxl-4 text-center">
										<table class="table table-bordered">
											<tbody>
												<tr>
													<th style="width: 50%;">括弧書きの量</th>
													<td><?= esc((float) $parameters['parenthesis_density'] / 0.2) ?>%</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-12 col-lg-6 col-xxl-4 text-center">
										<table class="table table-bordered">
											<tbody>
												<tr>
													<th style="width: 50%;">3点リードの量</th>
													<td><?= esc((float) $parameters['periods_density'] / 0.2) ?>%</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-12 col-lg-6 col-xxl-4 text-center">
										<table class="table table-bordered">
											<tbody>
												<tr>
													<th style="width: 50%;">改行の量</th>
													<td><?= esc((float) $parameters['br_density'] / 0.2) ?>%</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-12 col-lg-6 col-xxl-4 text-center">
										<table class="table table-bordered">
											<tbody>
												<tr>
													<th style="width: 50%;">読点の量</th>
													<td><?= esc((float) $parameters['comma_density'] / 0.2) ?>%</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-12 col-lg-6 col-xxl-4 text-center">
										<table class="table table-bordered">
											<tbody>
												<tr>
													<th style="width: 50%;">ロングタームメモリ</th>
													<td>
														<?php $long_term_label = ['なし', '低', '中', '高', '最大'] ?>
														<?= esc($long_term_label[$parameters['long_term_memory']] ?? $long_term_label[0]) ?>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								<div id="chat_setting" class="row">
									<h5 class="col-12">GUIモード / チャット設定</h5>
									<div class="col-12 col-lg-6 col-xxl-4 text-center">
										<table class="table table-bordered">
											<tbody>
												<tr>
													<th style="width: 50%;">GUIモード</th>
													<td>
														<?= (! empty($parameters['gui_mode']) && $parameters['gui_mode'] === '1') ? 'チャットモード' : 'ノベルモード' ?>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-12 col-lg-6 col-xxl-4 text-center">
										<table class="table table-bordered">
											<tbody>
												<tr>
													<th style="width: 50%;">自動改行</th>
													<td>
														<?= (! empty($parameters['chat_auto_enter']) && $parameters['chat_auto_enter'] === '1') ? '改行しない' : '改行する' ?>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-12 col-lg-6 col-xxl-4 text-center">
										<table class="table table-bordered">
											<tbody>
												<tr>
													<th style="width: 50%;">自動括弧</th>
													<td>
														<?= (! empty($parameters['chat_auto_brackets']) && $parameters['chat_auto_brackets'] === '1') ? '括弧で囲む' : '括弧で囲まない' ?>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endif ?>
				<?php if (! empty($prompt->chat_template)) : ?>
					<div class="accordion-item">
						<h2 class="accordion-header" id="chat-template-head">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#chat-template-body" aria-expanded="false" aria-controls="chat-template-body">
							チャットテンプレート
							</button>
						</h2>
						<div id="chat-template-body" class="accordion-collapse collapse" aria-labelledby="chat-template-head">
							<div class="accordion-body">
								<div class="row">
									<div class="col-12">
										<div class="position-relative">
											<button class="btn btn-sm copy-btn position-absolute top-0 end-0" data-target="chat-template"><img alt="copy" src="<?= base_url('img/copy.svg') ?>" width="20"></button>
										</div>
									</div>
									<div class="col-11" id="chat-template-text"><?= nl2br(str_replace(' ', '&nbsp;', esc($prompt->chat_template))) ?></div>
								</div>
							</div>
						</div>
					</div>
				<?php endif ?>
			</div>
		</div>
	<?php endif ?>
	<?php if ($prompt->comment === '1') : ?>
		<div class="accordion" id="comment-section">
			<div class="accordion-item">
				<h2 class="accordion-header" id="comment-head">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#comment-body" aria-expanded="<?= empty($openComment) ? 'false' : 'true' ?>" aria-controls="comment-body">
						コメント
					</button>
				</h2>
				<div id="comment-body" class="accordion-collapse collapse<?= empty($openComment) ? '' : ' show' ?>" aria-labelledby="comment-head">
					<div class="accordion-body">
						<?php if (! empty($loginUserId)) : ?>
							<?= form_open('prompt/' . $prompt->id) ?>
							<?= csrf_field() ?>
							<?= form_hidden('type', 'comment') ?>
						<?php endif ?>
						<?php if (empty($comments)) : ?>
							<div>コメントはありません。</div>
						<?php else : ?>
							<ul <?= empty($loginUserId) ? 'style="list-style: circle;"' : 'class="list-unstyled"' ?>>
								<?php foreach ($comments as $comment) : ?>
									<li>
										<div class="form-check-inline">
											<?php if (! empty($loginUserId)) : ?>
												<input class="form-check-input" type="radio" name="reply-to" value="<?= $comment->id ?>" id="comment-radio-<?= $comment->id ?>" <?= isset($clearCommentInput) ? '' : set_checkbox('reply-to', $comment->id) ?>>
											<?php endif ?>
											<label for="comment-radio-<?= $comment->id ?>" id="comment-<?= $comment->id ?>"><?= esc($comment->comment) ?> / <?php if ($comment->registered_by === '0') : ?><?= esc($comment->user_name) ?><?php else : ?><a href="<?= site_url('user/' . $comment->registered_by) ?>"><?= esc($comment->user_name) ?></a><?php endif ?> <span class="comment-date">(<?= $comment->registered_at ?>)</span></label>
										</div>
										<?php if (! empty($loginUserId) && ((int) $comment->registered_by === $loginUserId || ($comment->registered_by !== '0' && (int) $prompt->user_id === $loginUserId))) : ?>
											<button type="button" class="btn btn-outline-danger btn-sm comment-delete" date-id="<?= $comment->id ?>">×</button>
										<?php endif ?>
										<?php if (! empty($comment->children)) : ?>
											<ul <?= empty($loginUserId) ? 'style="list-style: circle;"' : 'style="list-style: none;"' ?>>
												<?php foreach ($comment->children as $child) : ?>
													<li>
														<div class="form-check-inline">
															<?php if (! empty($loginUserId)) : ?>
																<input class="form-check-input" type="radio" name="reply-to" value="<?= $child->id ?>" id="comment-radio-<?= $child->id ?>" <?= isset($clearCommentInput) ? '' : set_checkbox('reply-to', (string) $child->id) ?>>
															<?php endif ?>
															<label for="comment-radio-<?= $child->id ?>" id="comment-<?= $child->id ?>"><?= esc($child->comment) ?> / <?php if ($child->registered_by === '0') : ?><?= esc($child->user_name) ?><?php else : ?><a href="<?= site_url('user/' . $child->registered_by) ?>"><?= esc($child->user_name) ?></a><?php endif ?> <span class="comment-date">(<?= $child->registered_at ?>)</span></label>
														</div>
														<?php if (! empty($loginUserId) && ((int) $child->registered_by === $loginUserId || ($child->registered_by !== '0' && (int) $prompt->user_id === $loginUserId))) : ?>
															<button type="button" class="btn btn-outline-danger btn-sm comment-delete" date-id="<?= $child->id ?>">×</button>
														<?php endif ?>
														<?php if (! empty($child->children)) : ?>
															<ul style="list-style: circle;">
																<?php foreach ($child->children as $grand_child) : ?>
																	<li>
																		<span id="comment-<?= $grand_child->id ?>"><?= esc($grand_child->comment) ?> / <?php if ($grand_child->registered_by === '0') : ?><?= esc($grand_child->user_name) ?><?php else : ?><a href="<?= site_url('user/' . $grand_child->registered_by) ?>"><?= esc($grand_child->user_name) ?></a><?php endif ?> <span class="comment-date">(<?= $grand_child->registered_at ?>)</span></span>
																		<?php if (! empty($loginUserId) && ((int) $grand_child->registered_by === $loginUserId || ($grand_child->registered_by !== '0' && (int) $prompt->user_id === $loginUserId))) : ?>
																			<button type="button" class="btn btn-outline-danger btn-sm comment-delete" date-id="<?= $grand_child->id ?>">×</button>
																		<?php endif ?>
																	</li>
																<?php endforeach ?>
															</ul>
														<?php endif ?>
													</li>
												<?php endforeach ?>
											</ul>
										<?php endif ?>
									</li>
								<?php endforeach ?>
							</ul>
						<?php endif ?>
						<?php if (! empty($loginUserId)) : ?>
							<div class="input-group mt-2">
								<div class="input-group-text">
									<input class="form-check-input mt-0" type="radio" value="0" aria-label="コメントリプライ先は無し" name="reply-to" <?= isset($clearCommentInput) ? ' checked="checked"' : set_checkbox('reply-to', '0', true) ?>>
								</div>
								<input type="text" class="form-control" aria-label="コメント入力欄" maxlength="2048" id="comment" name="comment" value="<?= isset($clearCommentInput) ? '' : set_value('comment', '') ?>" required>
								<button class="btn btn-outline-secondary" type="submit" id="comment-button">送信</button>
							</div>
							<?= $validation->showError('reply-to') ?>
							<?= $validation->showError('comment') ?>
							<?= form_close() ?>
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>
		<div style="display: none;">
			<?= form_open('prompt/' . $prompt->id, ['id' => 'comment-delete-form']) ?>
			<?= csrf_field() ?>
			<?= form_hidden('type', 'comment-delete') ?>
			<input type="hidden" name="comment_id" id="comment-delete-id" value="0">
			<?= form_close() ?>
		</div>
		<script>
			for (const btn of document.querySelectorAll('.comment-delete')){
				btn.addEventListener('click', function () {
					const id = this.getAttribute('date-id')
					const elem = document.getElementById('comment-' + id)
					if (window.confirm('以下のコメントを削除します。\n\n' + elem.textContent)) {
						document.getElementById('comment-delete-id').setAttribute('value', id)
						document.getElementById('comment-delete-form').submit()
						return
					}
			})
			}
		</script>
	<?php endif ?>
	<?php if ($prompt->r18 === '1' && ($_SESSION['nsfw_mode'] ?? 's') === 's') : ?>
		<?php
        $uri      = current_url(true);
	    $query       = preg_replace('/(^|&|\?)(p=\d+|[nl]mode=\w)/u', '', $uri->getQuery());
	    $current_url = str_replace(index_page(), '', implode('/', $uri->getSegments())) . '?' . ($query ? ($query . '&') : '');
	    ?>
		<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#nsfw-modal" style="display: none;" id="nsfw-button"></button>
		<div class="modal" id="nsfw-modal" tabindex="-1" aria-labelledby="nsfw-modal-label" aria-hidden="true">
			<div class="modal-dialog modal-fullscreen">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="nsfw-modal-label">R-18 (NSFW) 表示確認</h4>
					</div>
					<div class="modal-body text-center">
						<div class="fs-4">このページにはアダルト・グロテスクなどの成人向け(NSFW)の内容を含みます。<br>18歳未満の方、またはそのようなコンテンツの閲覧を望まない方は、「戻る」ボタンで元のページにお戻りください。<br><br>自己の責任において閲覧を望む方は、「続ける」ボタンで先に進んでください。</div>
						<hr>
						<div class="row">
							<div class="col">
								<a type="button" class="btn btn-secondary me-3" href="javascript:history.back()">戻る</a>
								<a class="btn btn-primary ms-3" href="<?= site_url($current_url . '?nmode=n') ?>">続ける</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			document.addEventListener('DOMContentLoaded', function () {
				const ev = new Event('click')
				document.getElementById('nsfw-button').dispatchEvent(ev)
			})
		</script>
	<?php endif ?>
	<div id="copy-area" style="display: none;"><textarea id="copy-area-text"></textarea></div>
	<script>
		for (const elem of document.querySelectorAll('.copy-btn')) {
			elem.addEventListener('click',function () {
				const target = this.getAttribute('data-target')
				if (navigator.clipboard) {
					navigator.clipboard.writeText(document.getElementById(target + '-text').innerText)
				} else {
					const copyArea = document.getElementById('copy-area'), textArea = document.getElementById('copy-area-text')
					copyArea.style.display = 'block'
					textArea.innerHtml = document.getElementById(target + '-text').innerHtml
					textArea.select()
					document.execCommand('copy')
					copyArea.style.display = 'none'
				}
			})
		}
	</script>
</main>
<?= $this->endSection() ?>