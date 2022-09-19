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
		<p>AIのべりすとのサイトで使用できる<a href="https://www.tampermonkey.net/" target="_blank" rel="noopener noreferrer">Tampermonkey</a>用のユーザースクリプトを配布しています。</p>
		<h4>配布スクリプト一覧</h4>
		<ul>
			<li><a href="#ai_novelist_utility">AIのべりすとユーティリティ</a></li>
			<li><a href="#ai_novelist_trinart_download">TrinArtで生成画像とパラメータをまとめてダウンロード</a></li>
			<li><a href="#ai_novelist_trinart_download_for_gallery">TrinArtのギャラリー個別ページで画像とパラメータをまとめてダウンロード</a></li>
			<li><a href="#ai_novelist_trinart_lumina_info">TrinArtでページを開いてからのルミナ消費を表示する</a></li>
		</ul>
		<hr>
		<h4 id="ai_novelist_utility">AIのべりすとユーティリティ</h4>
		<div>
			<div class="m-2">
				<a href="https://gist.github.com/whiteball/b2bf1b71e37a07c87bb3948ea6f0f0f8" target="_blank" rel="noopener noreferrer">ダウンロードページ</a><br>
				最終更新日：2022/09/19 バージョン0.17.3
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
								<li>テキストを選択中に「Ctrl+数字」を押すと、設定に従って選択中のテキストを辞書サイトで検索したページを新しいタブで表示する。設定は環境設定の下の方から可能。デフォルトでは「Ctrl+1」に「Weblio辞書」、「Ctrl+2」に「Weblio類語」、「Ctrl+3」に「goo辞書」となっている。</li>
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
			<h5>導入手順</h5>
			<ol>
				<li>ブラウザに<a href="https://www.tampermonkey.net/" target="_blank" rel="noopener noreferrer">Tampermonkey</a>をインストールする。</li>
				<li>AIのべりすと用ユーザースクリプトの<a href="https://gist.github.com/whiteball/b2bf1b71e37a07c87bb3948ea6f0f0f8" target="_blank" rel="noopener noreferrer">ダウンロードページ</a>を開く。</li>
				<li>ダウンロードページの右の方にある「Raw」リンクをクリックする。(<a href="https://gist.github.com/whiteball/b2bf1b71e37a07c87bb3948ea6f0f0f8/raw/ai_novelist_utility.user.js" target="_blank" rel="noopener noreferrer">直リンク</a>)</li>
				<li>Tampermonkeyのインストール確認ページが開くので、インストールボタンを押す。
				</li>
			</ol>
			<h5>注意</h5>
			<ul>
				<li>「Ctrl＋/」で追加した「@/*」と「@*/」は、「Ctrl＋z」で戻すことは出来ません。</li>
				<li>「Undo」や「Redo」を押して履歴を出した場合、そこから「リトライ」「Undo」「Redo」のどれを押した場合でも確認ダイアログが出ます。</li>
				<li>AIのべりすとのサイトの構成が変わると、ユーザースクリプトは動作しなくなる可能性があります。</li>
				<li>Chrome/Firefoxにて動作確認していますが、万が一編集中のテキストが消えてしまうなどの現象が発生しても、作者は責任を負いかねます。</li>
			</ul>
		</div>
		<hr>
		<h4 id="ai_novelist_trinart_download">TrinArtで生成画像とパラメータをまとめてダウンロード</h4>
		<div>
			<div class="m-2">
				<a href="https://gist.github.com/whiteball/03c4953d7f547187d979267f5ef36c59" target="_blank" rel="noopener noreferrer">ダウンロードページ</a><br>
				最終更新日：2022/09/19 バージョン0.2.1
			</div>
			このスクリプトを導入することで次の機能を追加します。
			<ul>
				<li>TrinArtの画像生成ページの下部に、画像を生成した時にその画像をjpgとして、またパラメータをtxtとしていっぺんにダウンロードするボタンを表示する。(スマホの場合は個別のダウンロードボタンを表示)</li>
				<li>ダウンロードボタンの下に、ファイル名を指定する入力欄を追加。デフォルト値はプロンプトのスペースなどを_に置換したものを使う。</li>
				<li>UndoやRedoをした場合は、その時に表示している画像をダウンロードする。パラメータも生成当時のものをダウンロードする。</li>
				<li>コンテンツフィルタの設定の下に、自動保存オプションを追加。これがチェックされている場合は、画像の生成完了と同時にダウンロードを開始する。この設定はページをリロードするとオフになる。</li>
			</ul>
			<h5>導入手順</h5>
			<ol>
				<li>ブラウザに<a href="https://www.tampermonkey.net/" target="_blank" rel="noopener noreferrer">Tampermonkey</a>をインストールする。</li>
				<li>AIのべりすと用ユーザースクリプトの<a href="https://gist.github.com/whiteball/03c4953d7f547187d979267f5ef36c59" target="_blank" rel="noopener noreferrer">ダウンロードページ</a>を開く。</li>
				<li>ダウンロードページの右の方にある「Raw」リンクをクリックする。(<a href="https://gist.github.com/whiteball/03c4953d7f547187d979267f5ef36c59/raw/ai_novelist_trinart_download_button.user.js" target="_blank" rel="noopener noreferrer">直リンク</a>)</li>
				<li>Tampermonkeyのインストール確認ページが開くので、インストールボタンを押す。
				</li>
			</ol>
			<h5>注意</h5>
			<ul>
				<li>自動保存機能はスマホでは動作しません。</li>
				<li>AIのべりすとのサイトの構成が変わると、ユーザースクリプトは動作しなくなる可能性があります。</li>
				<li>Chrome/Firefoxにて動作確認していますが、万が一編集中のプロンプトや画像が消えてしまうなどの現象が発生しても、作者は責任を負いかねます。</li>
			</ul>
		</div>
		<hr>
		<h4 id="ai_novelist_trinart_download_for_gallery">TrinArtのギャラリー個別ページで画像とパラメータをまとめてダウンロード</h4>
		<div>
			<div class="m-2">
				<a href="https://gist.github.com/whiteball/3676fd7a3f58e947a864d2c8ef312024" target="_blank" rel="noopener noreferrer">ダウンロードページ</a><br>
				最終更新日：2022/09/12 バージョン0.1.1
			</div>
			このスクリプトを導入することで次の機能を追加します。
			<ul>
				<li>TrinArtのギャラリーの画像個別ページの下部に、その画像をjpgとして、またパラメータをtxtとしていっぺんにダウンロードするボタンを表示する。(スマホの場合は個別のダウンロードボタンを表示)</li>
				<li>ダウンロードボタンの下に、ファイル名を指定する入力欄を追加。デフォルト値はプロンプトのスペースなどを_に置換したものを使う。</li>
			</ul>
			<h5>導入手順</h5>
			<ol>
				<li>ブラウザに<a href="https://www.tampermonkey.net/" target="_blank" rel="noopener noreferrer">Tampermonkey</a>をインストールする。</li>
				<li>AIのべりすと用ユーザースクリプトの<a href="https://gist.github.com/whiteball/3676fd7a3f58e947a864d2c8ef312024" target="_blank" rel="noopener noreferrer">ダウンロードページ</a>を開く。</li>
				<li>ダウンロードページの右の方にある「Raw」リンクをクリックする。(<a href="https://gist.github.com/whiteball/3676fd7a3f58e947a864d2c8ef312024/raw/ai_novelist_trinart_download_button_for_gallery.user.js" target="_blank" rel="noopener noreferrer">直リンク</a>)</li>
				<li>Tampermonkeyのインストール確認ページが開くので、インストールボタンを押す。
				</li>
			</ol>
			<h5>注意</h5>
			<ul>
				<li>AIのべりすとのサイトの構成が変わると、ユーザースクリプトは動作しなくなる可能性があります。</li>
				<li>Chrome/Firefoxにて動作確認していますが、万が一閲覧中のプロンプトや画像が消えてしまうなどの現象が発生しても、作者は責任を負いかねます。</li>
			</ul>
		</div>
		<hr>
		<h4 id="ai_novelist_trinart_lumina_info">TrinArtでページを開いてからのルミナ消費を表示する</h4>
		<div>
			<div class="m-2">
				<a href="https://gist.github.com/whiteball/a2a3af48b3132c00231bf1d77673dddb" target="_blank" rel="noopener noreferrer">ダウンロードページ</a><br>
				最終更新日：2022/09/19 バージョン0.1
			</div>
			このスクリプトを導入することで次の機能を追加します。
			<ul>
				<li>TrinArtの画像生成ページの下部に、現在のパラメータで消費するルミナ、現在の残りルミナ、ページを開いたときの残りルミナ、ページを開いてから消費したルミナを表示する。</li>
				<li>※あくまでも取得できる範囲の情報からルミナを表示しているため、実際のルミナ消費と確実に一致しているかは保証しかねます。</li>
			</ul>
			<h5>導入手順</h5>
			<ol>
				<li>ブラウザに<a href="https://www.tampermonkey.net/" target="_blank" rel="noopener noreferrer">Tampermonkey</a>をインストールする。</li>
				<li>AIのべりすと用ユーザースクリプトの<a href="https://gist.github.com/whiteball/a2a3af48b3132c00231bf1d77673dddb" target="_blank" rel="noopener noreferrer">ダウンロードページ</a>を開く。</li>
				<li>ダウンロードページの右の方にある「Raw」リンクをクリックする。(<a href="https://gist.github.com/whiteball/a2a3af48b3132c00231bf1d77673dddb/raw/ai_novelist_trinart_lumina_info.user.js" target="_blank" rel="noopener noreferrer">直リンク</a>)</li>
				<li>Tampermonkeyのインストール確認ページが開くので、インストールボタンを押す。
				</li>
			</ol>
			<h5>注意</h5>
			<ul>
				<li>AIのべりすとのサイトの構成が変わると、ユーザースクリプトは動作しなくなる可能性があります。</li>
				<li>ルミナの消費量の正確性については作者は保証しません。</li>
				<li>Chrome/Firefoxにて動作確認していますが、万が一編集中のプロンプトや画像が消えてしまうなどの現象が発生しても、作者は責任を負いかねます。</li>
			</ul>
		</div>
	</div>
</main>
<?= $this->endSection() ?>