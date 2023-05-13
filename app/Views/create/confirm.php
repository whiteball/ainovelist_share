<?= $this->extend('template') ?>
<?= $this->section('title') ?> - プロンプト投稿 - 内容確認<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container" id="create-confirm">
	<h1 class="h3 mb-3 fw-normal">プロンプト投稿 - 内容確認</h1>
	<div class="mb-3 border rounded p-2">
		<div class="mb-3">
			<h5>タイトル</h5>
			<div class="wrap border rounded p-2"><?= esc($post_data['title']) ?></div>
		</div>
		<div class="mb-3">
			<h5>タグ</h5>
			<div class="wrap border rounded p-2">
				<?php foreach ($post_data['tags'] as $tag) : ?>
					<span class="btn rounded-pill btn-outline-secondary btn-sm tag-link"><?= esc($tag) ?></span>
				<?php endforeach ?>
			</div>
		</div>
		<div class="mb-3">
			<h5>説明</h5>
			<div class="wrap border rounded p-2"><?= nl2br(str_replace(' ', '&nbsp;', esc($post_data['description']))) ?></div>
		</div>
		<div class="mb-3">
			<h5>プロンプト(本文)</h5>
			<div class="wrap border rounded p-2"><?= nl2br(str_replace(' ', '&nbsp;', esc($post_data['prompt']))) ?></div>
		</div>
		<div class="mb-3">
			<h5>全年齢/R-18設定</h5>
			<div class="wrap border rounded p-2"><?= (! empty($post_data['r18']) && $post_data['r18'] === '1') ? 'R-18' : '全年齢' ?></div>
		</div>
		<div class="mb-3">
			<h5>公開/非公開設定</h5>
			<div class="wrap border rounded p-2"><?= (! empty($post_data['draft']) && $post_data['draft'] === '1') ? '非公開' : '公開' ?></div>
		</div>
		<div class="mb-3">
			<h5>コメント許可/不許可設定</h5>
			<div class="wrap border rounded p-2"><?= (! empty($post_data['comment']) && $post_data['comment'] === '1') ? '許可' : '不許可' ?></div>
		</div>
		<?php if ($return_url[0] === 'e'): ?>
			<div class="mb-3">
				<h5>更新順浮上あり/なし設定</h5>
				<div class="wrap border rounded p-2"><?= (! empty($post_data['updated_at_for_sort']) && $post_data['updated_at_for_sort'] === '1') ? '浮上なし' : '浮上あり' ?></div>
			</div>
		<?php endif ?>
		<div class="mb-3">
			<h5>プロンプトの改変可否</h5>
			<?php if ($post_data['license'] === '1') : ?>
				<div class="wrap border rounded p-2"><h5><span class="badge bg-danger text-white">禁止</span></h5>(公開せずに個人的に楽しむ範囲の改変は可能です)</div>
			<?php elseif ($post_data['license'] === '2') : ?>
				<div class="wrap border rounded p-2"><h5><span class="badge bg-success text-white">許可</span></h5>(許可条件は<a href="https://creativecommons.org/licenses/by-sa/4.0/deed.ja" target="_blank" rel="noopener noreferrer">クリエイティブコモンズ 表示-継承 4.0</a>を参照してください)</div>
			<?php else: ?>
				<div class="wrap border rounded p-2"><h5><span class="badge bg-secondary text-white">説明欄での条件による</span></h5>(未記入の場合、個別に許可を得られなければ禁止です)</div>
			<?php endif ?>
		</div>
	</div>
	<?php if (! (empty($post_data['memory']) && empty($post_data['authors_note']) && empty($post_data['ng_words']))) : ?>
		<div class="mb-3 border rounded p-2">
			<?php if (! empty($post_data['memory'])) : ?>
				<div class="mb-3">
					<h5>メモリ</h5>
					<div class="wrap border rounded p-2"><?= nl2br(str_replace(' ', '&nbsp;', esc($post_data['memory']))) ?></div>
				</div>
			<?php endif ?>
			<?php if (! empty($post_data['authors_note'])) : ?>
				<div class="mb-3">
					<h5>脚注</h5>
					<div class="wrap border rounded p-2"><?= nl2br(str_replace(' ', '&nbsp;', esc($post_data['authors_note']))) ?></div>
				</div>
			<?php endif ?>
			<?php if (! empty($post_data['ng_words'])) : ?>
				<div class="mb-3">
					<h5>禁止ワード</h5>
					<div class="wrap border rounded p-2"><?= nl2br(str_replace(' ', '&nbsp;', esc($post_data['ng_words']))) ?></div>
				</div>
			<?php endif ?>
		</div>
	<?php endif ?>
	<?php if (! empty($post_data['char_book'])) : ?>
		<div class="mb-3 border rounded p-2">
			<h5>キャラクターブック</h5>
			<?php foreach ($post_data['char_book'] as $char_book) : ?>
				<div>
					<h6 class="wrap">タグ: <?= esc($char_book['tag']) ?></h6>
					<div class="wrap border rounded p-2"><?= nl2br(str_replace(' ', '&nbsp;', esc($char_book['content']))) ?></div>
				</div>
			<?php endforeach ?>
		</div>
	<?php endif ?>
	<?php if (! empty($post_data['script'])) : ?>
		<div class="mb-3 border rounded p-2">
			<h5>スクリプト</h5>
			<?php foreach ($post_data['script'] as $script) : ?>
				<div class="border rounded p-2">
					<div class="border-bottom p-1">種類: <?= esc(SCRIPT_TYPE_LIST[$script['type']]) ?></div>
					<div class="wrap border-bottom p-1">IN: <?= esc($script['in']) ?></div>
					<div class="wrap border-bottom p-1">OUT: <?= esc($script['out']) ?></div>
				</div>
			<?php endforeach ?>
		</div>
	<?php endif ?>
	<div class="border rounded accordion-item mb-3">
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
									<td><?= esc((float) $post_data['temperature'] / 40) ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4 text-center">
						<table class="table table-bordered">
							<tbody>
								<tr>
									<th style="width: 50%;">トップP</th>
									<td><?= esc((float) $post_data['top_p'] / 40) ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4 text-center">
						<table class="table table-bordered">
							<tbody>
								<tr>
									<th style="width: 50%;">テイルフリー</th>
									<td><?= esc(0.75 + (float) $post_data['tfs'] / 160) ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4 text-center">
						<table class="table table-bordered">
							<tbody>
								<tr>
									<th style="width: 50%;">繰り返しペナルティ</th>
									<td><?= esc((float) $post_data['freq_p'] / 80) ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4 text-center">
						<table class="table table-bordered">
							<tbody>
								<tr>
									<th style="width: 50%;">出力の長さ</th>
									<td>約<?= esc(floor((float) $post_data['length'] * 2.5)) ?>文字</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4 text-center">
						<table class="table table-bordered">
							<tbody>
								<tr>
									<th style="width: 50%;">タイピカルP</th>
									<td><?= ((int) $post_data['typical_p'] <= 99) ? esc((float) $post_data['typical_p'] / 100) : '-' ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4 text-center">
						<table class="table table-bordered">
							<tbody>
								<tr>
									<th style="width: 50%;">繰り返しペナルティ（検索範囲）</th>
									<td><?= esc((int) $post_data['freq_p_range'] * 8) ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4 text-center">
						<table class="table table-bordered">
							<tbody>
								<tr>
									<th style="width: 50%;">繰り返しペナルティ（傾斜）</th>
									<td><?= esc((float) $post_data['freq_p_slope'] / 20) ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4 text-center">
						<table class="table table-bordered">
							<tbody>
								<tr>
									<th style="width: 50%;">AIが読み取るコンテキストの長さ</th>
									<td>約<?= esc(floor((float) $post_data['contextwindow'] * 20)) ?>文字</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4 text-center">
						<table class="table table-bordered">
							<tbody>
								<tr>
									<th style="width: 50%;">キャラクターブックの優先度</th>
									<td><?= ((int) $post_data['wiplacement'] >= 30) ? '本文の後ろ' : esc((int) $post_data['wiplacement'] * 2) ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4 text-center">
						<table class="table table-bordered">
							<tbody>
								<tr>
									<th style="width: 50%;">脚注の優先度</th>
									<td><?= esc($post_data['anplacement']) ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4 text-center">
						<table class="table table-bordered">
							<tbody>
								<tr>
									<th style="width: 50%;">キャラクターブックをスキャンする文字数</th>
									<td><?= esc((int) $post_data['wiscanrange'] * 8) ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4 text-center">
						<table class="table table-bordered">
							<tbody>
								<tr>
									<th style="width: 50%;">セリフの量</th>
									<td><?= esc((float) $post_data['dialogue_density'] / 0.2) ?>%</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4 text-center">
						<table class="table table-bordered">
							<tbody>
								<tr>
									<th style="width: 50%;">括弧書きの量</th>
									<td><?= esc((float) $post_data['parenthesis_density'] / 0.2) ?>%</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4 text-center">
						<table class="table table-bordered">
							<tbody>
								<tr>
									<th style="width: 50%;">3点リードの量</th>
									<td><?= esc((float) $post_data['periods_density'] / 0.2) ?>%</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4 text-center">
						<table class="table table-bordered">
							<tbody>
								<tr>
									<th style="width: 50%;">改行の量</th>
									<td><?= esc((float) $post_data['br_density'] / 0.2) ?>%</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4 text-center">
						<table class="table table-bordered">
							<tbody>
								<tr>
									<th style="width: 50%;">読点の量</th>
									<td><?= esc((float) $post_data['comma_density'] / 0.2) ?>%</td>
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
										<?= esc($long_term_label[$post_data['long_term_memory']] ?? $long_term_label[0]) ?>
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
										<?= (! empty($post_data['gui_mode']) && $post_data['gui_mode'] === '1') ? 'チャットモード' : 'ノベルモード' ?>
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
										<?= (! empty($post_data['chat_auto_enter']) && $post_data['chat_auto_enter'] === '1') ? '改行しない' : '改行する' ?>
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
										<?= (! empty($post_data['chat_auto_brackets']) && $post_data['chat_auto_brackets'] === '1') ? '括弧で囲む' : '括弧で囲まない' ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4 text-center">
						<table class="table table-bordered">
							<tbody>
								<tr>
									<th style="width: 50%;">改行/送信キー設定</th>
									<td>
										<?php $chat_enter_key_label = ['無効', 'Enterで改行、Shift+Enterで送信', 'Enterで改行、Ctrl+Enterで送信', 'Enterで改行、Alt+Enterで送信'] ?>
										<?= esc($chat_enter_key_label[$post_data['chat_enter_key']] ?? $chat_enter_key_label[1]) ?>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-12 col-lg-6 col-xxl-4 text-center">
						<table class="table table-bordered">
							<tbody>
								<tr>
									<th style="width: 50%;">改行/送信キー入替</th>
									<td>
										<?= (! empty($post_data['chat_change_enter_key']) && $post_data['chat_change_enter_key'] === '1') ? '入れ替えない' : '入れ替える' ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="mt-2">
					<h5>チャットテンプレート</h5>
					<?php if (empty($post_data['chat_template'])) : ?>
						<div class="text-secondary">入力無し</div>
					<?php else : ?>
						<div class="wrap border rounded p-2"><?= nl2br(str_replace(' ', '&nbsp;', esc($post_data['chat_template']))) ?></div>
					<?php endif ?>
				</div>
			</div>
		</div>
	</div>

	<?= form_open() ?>
	<?= csrf_field() ?>
	<?= form_hidden('send', 1) ?>
	<div class="text-center">
		<a href="<?= site_url($return_url . '?back=1') ?>" class="btn btn-outline-secondary me-5">戻る</a>
		<button type="submit" class="btn btn-primary">投稿する</button>
	</div>
	<?= form_close() ?>
</main>
<?= $this->endSection() ?>