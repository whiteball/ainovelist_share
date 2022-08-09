<?= $this->extend('template') ?>
<?= $this->section('title') ?> - ユーザースクリプト<?= $this->endSection() ?>

<?= $this->section('ogp') ?>
<?php $description = 'AIのべりすと用のユーザースクリプトの導入案内。' ?>
<meta property="og:image" content="<?= base_url('img/ogp.png') ?>" />
<meta name="twitter:image" content="<?= base_url('img/ogp.png') ?>" />
<meta property="og:type" content="website" />
<meta property="og:title" content="ユーザースクリプト" />
<meta name="twitter:title" content="ユーザースクリプト">
<meta property="og:description" content="<?= $description ?>" />
<meta name="twitter:description" content="<?= $description ?>">
<meta name="description" content="<?= $description ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<h3>ユーザースクリプト</h3>
	<div>
		AIのべりすとのサイトで使用できる<a href="https://www.tampermonkey.net/" target="_blank" rel="noopener noreferrer">Tampermonkey</a>用のユーザースクリプトを配布しています。<br>
		<div class="m-2">
			<a href="https://gist.github.com/whiteball/b2bf1b71e37a07c87bb3948ea6f0f0f8" target="_blank" rel="noopener noreferrer">ダウンロードページ</a><br>
			最終更新日：2022/08/09 バージョン0.16
		</div>
		<div class="m-2">
			<a href="https://www.nicovideo.jp/watch/sm40158166" target="_blank" rel="noopener noreferrer">導入手順と機能紹介の動画</a>を作成しました。(バージョン0.7時点の情報)
		</div>
		このスクリプトを導入することで次の機能を追加します。
		<ul>
			<li>
				GUIv2での機能
				<ul>
					<li>
						キーボードショートカットの追加
						<ul>
							<li>編集ページの本文入力欄でテキストを選択しながら「Ctrl＋/」を押すと、選択テキストの上の行に「@/*」を、下の行に「@*/」を挿入する。</li>
							<li>「@/*」から「@*/」までを選択しながら「Ctrl＋/」を押すと、「@/*」と「@*/」を削除する。</li>
						</ul>
					</li>
					<li>
						確認ダイアログの追加
						<ul>
							<li>AIが生成した文章をリトライをするとき、AIが生成した後に文章を変更していた場合、本当にリトライしてもいいかの確認ダイアログを出す。同じくUndo・Redoするときも、文章を変更している場合に確認ダイアログを出す。</li>
						</ul>
					</li>
					<li>
						シャーディング(本文入力欄の自動分割)の改良
						<ul>
							<li>@endpointにもコメント色分けを適用する。(一番最後にある@endpointから下を色分け。)</li>
							<li>
								本文入力欄を複数文コメントや最新の出力文(色の変わっている部分)の途中で分割されないようにする。
								<ul><li>分割の回数が減るため、とても長い単一の複数文コメントがあると、環境によってはブラウザが重くなります。</li></ul>
							</li>
							<li>本文末尾付近で枠の分割がされないようにする。</li>
						</ul>
					</li>
					<li>
						@endpoint直前への挿入とスクロール
						<ul>
							<li>「続きの文を書く」「リトライ」「Undo」「Redo」したときに@endpointの前にスクロールするようにする。</li>
							<li>Undo履歴を@endpoint直前に挿入するようにする。</li>
							<li>環境設定の一番下に「@endpointがあっても挿入位置を常に一番下にする」の設定を追加する。</li>
						</ul>
					</li>
					<li>
						情報表示エリアの追加
						<ul>
							<li>オプションの右から3番目のアイコン(クリップボード)を押すと、「続きの文を書く」や「リトライ」をしたときにサーバーに送信したテキスト内容を確認出来るエリアを表示する。</li>
							<li>同じく、オプションの右から2番目のアイコン(本)を押すと、過去20回分までAIが出力したテキストの履歴を確認出来るエリアを表示する。履歴はファイルとしてダウンロードすることも出来る。</li>
							<li>
								オプションの右端のアイコン(iのマーク)を押すと、編集ページを開いてからの出力回数などの統計情報や最終保存日時を確認出来るエリアを表示する。
								<ul><li>統計情報はセッションに保存され、ページを閉じるまでリセットされません。</li></ul>
							</li>
							<li>これらのテキストエリアは、本文のフォントや文字サイズに従う。</li>
						</ul>
					</li>
					<li>
						その他機能の追加
						<ul>
							<li>「Redo」が最新状態でさらに「Redo」を3回押すと、「Undo」と同じようにUndo履歴を挿入する。</li>
							<li>AIのべりすとが認識できない文字(とりんさま6.8B/7.3Bモデル、でりだ7Bモデル、やみおとめ20Bモデル)を囲み文字でハイライト表示する。</li>
							<li>環境設定の一番下に「オプションアイコンを横スクロール可能にする」の設定を追加する。</li>
							<li>禁止ワードの下に、対象の文字を含むトークンを検索できるフォームを追加する。(※この機能は検索文字列をこのサーバーに送信します。サーバーへ検索文字列を送りたくない場合は、この機能を使わないでください。)</li>
							<li>AI出力後にメモリまたは脚注のテキストを置換するスクリプトのオプションを追加する。
								<dl>
									<dt>使い方</dt>
									<dd>種別を「使用しない」に設定し置換対象がメモリなら「(?:M){0}」、脚注なら「(?:A){0}」をINの先頭に書く。<br>それに続けてAIの出力にマッチする正規表現を書く。<br>OUTには「メモリ/脚注にマッチする正規表現<|>置換後のテキスト」を書く。<br>OUTでは特殊な変数として「#数字#」が使える。これはOUTを正規表現として解釈する前に、INに書いた正規表現のキャプチャでその部分を置換する。</dd>
									<dt>例</dt>
									<dd>IN: (?:M){0}(.+)にいる。<br>OUT: \[場所：.+\]<|>[場所：#1#]<br>という設定で出力が「大きな広場にいる。」なら、メモリに書かれている「[場所：～～]」という部分が「[場所：大きな広場]」に置換される。</dd>
								</dl>
							</li>
						</ul>
					</li>
				</ul>
			</li>
			<li>
				GUIv3(現在ベータテスト中)での機能
				<ul>
					<li>
						情報表示ダイアログの追加
						<ul>
							<li>ページ左下の「送信テキスト」ボタンを押すと、「続きの文を書く」や「リトライ」をしたときにサーバーに送信したテキスト内容を確認出来るダイアログを表示する。</li>
							<li>ページ左下の「履歴の履歴」ボタンを押すと、過去20回分までAIが出力したテキストの履歴を確認出来るダイアログを表示する。履歴はファイルとしてダウンロードすることも出来る。</li>
							<li>
								ページ左下の「情報」ボタンを押すと、編集ページを開いてからの出力回数などの統計情報や最終保存日時を確認出来るエリアを表示する。
								<ul><li>統計情報はセッションに保存され、ページを閉じるまでリセットされません。</li></ul>
							</li>
							<li>各ダイアログはドラッグで移動させることが出来る。(PCのみ)</li>
							<li>各ダイアログはダイアログ左下の「＋」「－」ボタンで高さを、右下の「＋」「－」ボタンで幅を調整出来る。</li>
						</ul>
					</li>
				</ul>
			</li>
		</ul>
	</div>
	<hr>
	<h4>導入手順</h4>
	<ol>
		<li>ブラウザに<a href="https://www.tampermonkey.net/" target="_blank" rel="noopener noreferrer">Tampermonkey</a>をインストールする。</li>
		<li>AIのべりすと用ユーザースクリプトの<a href="https://gist.github.com/whiteball/b2bf1b71e37a07c87bb3948ea6f0f0f8" target="_blank" rel="noopener noreferrer">ダウンロードページ</a>を開く。</li>
		<li>ダウンロードページの右の方にある「Raw」リンクをクリックする。(<a href="https://gist.github.com/whiteball/b2bf1b71e37a07c87bb3948ea6f0f0f8/raw/ai_novelist_utility.user.js" target="_blank" rel="noopener noreferrer">直リンク</a>)</li>
		<li>Tampermonkeyのインストール確認ページが開くので、インストールボタンを押す。
		</li>
	</ol>
	<hr>
	<h4>注意</h4>
	<ul>
		<li>「Ctrl＋/」で追加した「@/*」と「@*/」は、「Ctrl＋z」で戻すことは出来ません。</li>
		<li>「Undo」や「Redo」を押して履歴を出した場合、そこから「リトライ」「Undo」「Redo」のどれを押した場合でも確認ダイアログが出ます。</li>
		<li>AIのべりすとのサイトの構成が変わると、ユーザースクリプトは動作しなくなる可能性があります。</li>
		<li>Chrome/Firefoxにて動作確認していますが、万が一編集中のテキストが消えてしまうなどの現象が発生しても、作者は責任を負いかねます。</li>
	</ul>
</main>
<?= $this->endSection() ?>