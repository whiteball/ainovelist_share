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
			<li><a href="#ai_novelist_inserting_images_for_reading">リーディングモードに画像挿入</a></li>
			<li><a href="#ai_novelist_voicevox">出力をVOICEVOX読み上げ</a></li>
			<li><a href="#ai_novelist_any_font">任意フォント指定</a></li>
			<li><a href="#ai_novelist_range2button">スライダーをボタンに置き換え</a></li>
			<li><a href="#ai_novelist_trinart_download">TrinArtで生成画像とパラメータをまとめてダウンロード</a></li>
			<li><a href="#ai_novelist_trinart_download_for_gallery">TrinArtのギャラリー個別ページで画像とパラメータをまとめてダウンロード</a></li>
			<li><a href="#ai_novelist_trinart_lumina_info">TrinArtでページを開いてからのルミナ消費を表示する</a></li>
		</ul>
		<hr>
		<h4 id="ai_novelist_utility">AIのべりすとユーティリティ</h4>
		<div>
			<div class="m-2">
				<a href="https://gist.github.com/whiteball/b2bf1b71e37a07c87bb3948ea6f0f0f8" target="_blank" rel="noopener noreferrer">ダウンロードページ</a><br>
				最終更新日：2022/12/15 バージョン0.19.0
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
								<li>テキストを選択中に「Ctrl+数字」を押すと、設定に従って選択中のテキストを辞書サイトで検索したページを新しいタブで表示する。左から4番目のアイコン(デスクランプ)の環境設定の下の「ユーザースクリプト設定」から可能。デフォルトでは「Ctrl+1」に「Weblio辞書」、「Ctrl+2」に「Weblio類語」、「Ctrl+3」に「goo辞書」となっている。</li>
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
								<li>左から4番目のアイコン(デスクランプ)の環境設定の下の「ユーザースクリプト設定」に、「@endpointがあっても挿入位置を常に一番下にする」の設定を追加する。</li>
							</ul>
						</li>
						<li>
							情報表示エリアの追加
							<ul>
								<li>オプションの右から3番目のアイコン(クリップボード)を押すと、「続きの文を書く」や「リトライ」をしたときにサーバーに送信したテキスト内容や禁止ワード、バイアスを確認出来るエリアを表示する。</li>
								<li>同じく、オプションの右から2番目のアイコン(本)を押すと、過去20回分までAIが出力したテキストの履歴を確認出来るエリアを表示する。履歴はファイルとしてダウンロードすることも出来る。</li>
								<li>
									オプションの右端のアイコン(iのマーク)を押すと、編集ページを開いてからの出力回数などの統計情報や最終保存日時を確認出来るエリアを表示する。
									<ul><li>統計情報はセッションに保存され、ページを閉じるまでリセットされません。</li></ul>
								</li>
								<li>これらのテキストエリアは、本文のフォントや文字サイズに従う。</li>
							</ul>
						</li>
						<li>
							画像の挿入機能
							<ul>
								<li>特定の文字列が行頭にあるときに、指定した画像を本文欄に挿入する。アイコントーク風SSのような見た目を再現可能。</li>
								<li>設定は左から4番目のアイコン(デスクランプ)の環境設定の下の「ユーザースクリプト設定」から行う。この設定はブラウザに保存されないが、zipファイルとして設定のエクスポート/インポートが可能。</li>
								<li><a href="https://twitter.com/whiteball/status/1587032534281777152" target="_blank" rel="noopener noreferrer">リリース時のツイート</a></li>
							</ul>
						</li>
						<li>
							その他機能の追加
							<ul>
								<li>「Redo」が最新状態でさらに「Redo」を3回押すと、「Undo」と同じようにUndo履歴を挿入する。</li>
								<li>AIのべりすとが認識できない文字(とりんさま6.8B/7.3Bモデル、でりだ7Bモデル、やみおとめ20Bモデル)を囲み文字でハイライト表示する。</li>
								<li>左から4番目のアイコン(デスクランプ)の環境設定の下の「ユーザースクリプト設定」に、「オプションアイコンを横スクロール可能にする」の設定を追加する。</li>
								<li>禁止ワードの下に、対象の文字を含むトークンを検索できるフォームを追加する。検索結果は禁止ワードの形式、または@biasの形式で表示する。(※この機能は検索文字列をこのサーバーに送信します。サーバーへ検索文字列を送りたくない場合は、この機能を使わないでください。)</li>
								<li>設定された禁止ワードを除外する設定を追加する。設定は左から4番目のアイコン(デスクランプ)の環境設定の下の「ユーザースクリプト設定」から行う。この設定はブラウザに保存されるが、すべての作品で共通の設定となる。</li>
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
				<li>「AIのべりすとユーティリティ」の<a href="https://gist.github.com/whiteball/b2bf1b71e37a07c87bb3948ea6f0f0f8" target="_blank" rel="noopener noreferrer">ダウンロードページ</a>を開く。</li>
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
		<h4 id="ai_novelist_inserting_images_for_reading">リーディングモードに画像挿入</h4>
		<div>
			<div class="m-2">
				<a href="https://gist.github.com/whiteball/4e5075cbdecdf8e521acf8ba51c61478" target="_blank" rel="noopener noreferrer">ダウンロードページ</a><br>
				最終更新日：2022/11/05 バージョン0.1.0
			</div>
			このスクリプトを導入することで次の機能を追加します。
			<ul>
				<li>リーディングモード(作品リストのリーディングボタンで表示されるページ)に画像を挿入する設定を追加。リッチテキストとしてのコピーも可能。</li>
				<li>設定zipファイルは、AIのべりすとユーティリティのものと互換。</li>
			</ul>
			<h5>導入手順</h5>
			<ol>
				<li>ブラウザに<a href="https://www.tampermonkey.net/" target="_blank" rel="noopener noreferrer">Tampermonkey</a>をインストールする。</li>
				<li>「リーディングモードに画像挿入」の<a href="https://gist.github.com/whiteball/4e5075cbdecdf8e521acf8ba51c61478" target="_blank" rel="noopener noreferrer">ダウンロードページ</a>を開く。</li>
				<li>ダウンロードページの右の方にある「Raw」リンクをクリックする。(<a href="https://gist.github.com/whiteball/4e5075cbdecdf8e521acf8ba51c61478/raw/ai_novelist_inserting_images_for_reading.user.js" target="_blank" rel="noopener noreferrer">直リンク</a>)</li>
				<li>Tampermonkeyのインストール確認ページが開くので、インストールボタンを押す。</li>
			</ol>
			<h5>注意</h5>
			<ul>
				<li>リッチテキストコピーはスマホでの動作が未確認です。</li>
				<li>AIのべりすとのサイトの構成が変わると、ユーザースクリプトは動作しなくなる可能性があります。</li>
				<li>Chrome/Firefoxにて動作確認していますが、万が一テキストや画像が消えてしまうなどの現象が発生しても、作者は責任を負いかねます。</li>
			</ul>
		</div>
		<hr>
		<h4 id="ai_novelist_voicevox">出力をVOICEVOX読み上げ</h4>
		<div>
			<div class="m-2">
				<a href="https://gist.github.com/whiteball/f5d700c831a45252b046d2cb1f599a7f" target="_blank" rel="noopener noreferrer">ダウンロードページ</a><br>
				最終更新日：2022/12/04 バージョン0.1.5
			</div>
			このスクリプトを導入することで次の機能を追加します。
			<ul>
				<li>別途インストールした<a href="https://voicevox.hiroshiba.jp/" target="_blank" rel="noopener noreferrer">VOICEVOX</a>を起動しておくと、AIのべりすとの出力文をブラウザ上で音声再生する。</li>
				<li>本文欄のテキストを選択して「Ctrl+Alt+v」を押すことで、選択テキストを音声再生する。</li>
				<li>設定を行うことで、読み上げに含めないパターンの指定や、出力文を読み上げる際にその直前の句読点やかぎ括弧までの文章も含めることが可能。</li>
				<li>スクリプトに設定を行うことで、特定の部分だけ設定のボイスとは異なるボイスで読み上げることが可能。
					<dl>
						<dt>使い方</dt>
						<dd>種別を「使用しない」に設定し、「(?:VOICEVOX:ID){0}」をINの先頭に書く。「ID」の部分は使用したいボイスに合わせて、数字、または、頭に「p」がついた数字を記入する。(ボイス選択のメニューから確認可能)<br>それに続けて、INにはボイスを変更させたい部分にマッチする正規表現を書く。<br>OUTには読み上げさせたいテキストを書く。</dd>
						<dt>例</dt>
						<dd>IN: (?:VOICEVOX:3){0}ずんだもん「([^」]+?)」<br>OUT: $1<br>という設定で、読み上げ対象のテキストが『めたん「セリフ1」 ずんだもん「セリフ2」 めたん「セリフ3」』なら、『めたん「セリフ1」』を設定欄での指定のボイス、『セリフ2』をスクリプトで指定のID:3のボイス、 『めたん「セリフ3」』を設定欄での指定のボイスで読み上げる。<br>『ずんだもん』の部分は正規表現の置換で消されるため、読み上げには含まれなくなる。</dd>
					</dl>
				</li>
				<li>設定欄はオプションの左から4番目のアイコン(デスクランプ)の一番下。</li>
			</ul>
			<h5>導入手順</h5>
			<ol>
				<li>ブラウザに<a href="https://www.tampermonkey.net/" target="_blank" rel="noopener noreferrer">Tampermonkey</a>をインストールする。</li>
				<li>「出力をVOICEVOX読み上げ」の<a href="https://gist.github.com/whiteball/f5d700c831a45252b046d2cb1f599a7f" target="_blank" rel="noopener noreferrer">ダウンロードページ</a>を開く。</li>
				<li>ダウンロードページの右の方にある「Raw」リンクをクリックする。(<a href="https://gist.github.com/whiteball/f5d700c831a45252b046d2cb1f599a7f/raw/ai_novelist_voicevox.user.js" target="_blank" rel="noopener noreferrer">直リンク</a>)</li>
				<li>Tampermonkeyのインストール確認ページが開くので、インストールボタンを押す。</li>
			</ol>
			<h5>VOICEVOX導入手順</h5>
			<ol>
				<li><a href="https://voicevox.hiroshiba.jp/" target="_blank" rel="noopener noreferrer">VOICEVOX公式サイト</a>からインストーラをダウンロードしてインストールする。CPU版/GPU版どちらでも可。</li>
				<li>インストールしたVOICEVOXを起動する。もしくはVOICEVOXインストールディレクトリで「run.exe」を実行する。「run.exe」はコマンドライン引数「--host」で待ち受けホスト名を、「--port」で待ち受けポートを指定できる。VOICEVOXを起動した場合も含め、デフォルトは「localhost:50021」。</li>
				<li>ブラウザで「http://localhost:50021」(ホスト/ポートを変更した場合はそのアドレス)にアクセスし、「VOICEVOX ENGINE」のページが表示されれば導入完了。</li>
			</ol>
			<h5>注意</h5>
			<ul>
				<li>ボイス一覧にはプリセットも含まれますが、VOICEVOXのアプリ上から設定できるプリセットではありません。VOICEVOXをインストールしたディレクトリにある<a href="https://github.com/VOICEVOX/voicevox_engine#%E3%83%97%E3%83%AA%E3%82%BB%E3%83%83%E3%83%88%E6%A9%9F%E8%83%BD%E3%81%AB%E3%81%A4%E3%81%84%E3%81%A6" target="_blank" rel="noopener noreferrer">presets.yaml</a>で設定した内容が一覧に現れます。プリセットを使う場合は、このファイルを直接編集して追加してください。</li>
				<li>AIのべりすとのサイトの構成が変わると、ユーザースクリプトは動作しなくなる可能性があります。</li>
				<li>Chrome/Firefoxにて動作確認していますが、万が一テキストが消えてしまうなどの現象が発生しても、作者は責任を負いかねます。</li>
			</ul>
		</div>
		<hr>
		<h4 id="ai_novelist_any_font">任意フォント指定</h4>
		<div>
			<div class="m-2">
				<a href="https://gist.github.com/whiteball/cf068c67041d5538914182d4e00d03ea" target="_blank" rel="noopener noreferrer">ダウンロードページ</a><br>
				最終更新日：2022/11/20 バージョン0.1.0
			</div>
			このスクリプトを導入することで次の機能を追加します。
			<ul>
				<li>環境設定の「本文のフォント」で「拡張」を選択して表示されるリストの下に、任意のフォント名を指定できる入力欄と、その指定したフォントを適用するボタンを追加。</li>
			</ul>
			<h5>導入手順</h5>
			<ol>
				<li>ブラウザに<a href="https://www.tampermonkey.net/" target="_blank" rel="noopener noreferrer">Tampermonkey</a>をインストールする。</li>
				<li>「任意フォント指定」の<a href="https://gist.github.com/whiteball/cf068c67041d5538914182d4e00d03ea" target="_blank" rel="noopener noreferrer">ダウンロードページ</a>を開く。</li>
				<li>ダウンロードページの右の方にある「Raw」リンクをクリックする。(<a href="https://gist.github.com/whiteball/cf068c67041d5538914182d4e00d03ea/raw/ai_novelist_any_font.user.js" target="_blank" rel="noopener noreferrer">直リンク</a>)</li>
				<li>Tampermonkeyのインストール確認ページが開くので、インストールボタンを押す。</li>
			</ol>
			<h5>注意</h5>
			<ul>
				<li>AIのべりすとのサイトの構成が変わると、ユーザースクリプトは動作しなくなる可能性があります。</li>
				<li>Chrome/Firefoxにて動作確認していますが、万が一テキストが消えてしまうなどの現象が発生しても、作者は責任を負いかねます。</li>
			</ul>
		</div>
		<hr>
		<h4 id="ai_novelist_range2button">スライダーをボタンに置き換え</h4>
		<div>
			<div class="m-2">
				<a href="https://gist.github.com/whiteball/fb39e820addeaa8468762f5f29878868" target="_blank" rel="noopener noreferrer">ダウンロードページ</a><br>
				最終更新日：2022/11/23 バージョン0.1.2
			</div>
			このスクリプトを導入することで次の機能を追加します。
			<ul>
				<li>詳細オプションでのパラメータを調整するスライダーを、増減ボタンに置き換える設定を追加する。</li>
				<li>「環境設定＞その他」の「文字数などの情報を表示」の下のラジオボタンで、「スライダー」「スライダー＆ボタン」「ボタン」を切り替えることができる。デフォルトは「スライダー」。</li>
			</ul>
			<h5>導入手順</h5>
			<ol>
				<li>ブラウザに<a href="https://www.tampermonkey.net/" target="_blank" rel="noopener noreferrer">Tampermonkey</a>をインストールする。</li>
				<li>「スライダーをボタンに置き換え」の<a href="https://gist.github.com/whiteball/fb39e820addeaa8468762f5f29878868" target="_blank" rel="noopener noreferrer">ダウンロードページ</a>を開く。</li>
				<li>ダウンロードページの右の方にある「Raw」リンクをクリックする。(<a href="https://gist.github.com/whiteball/fb39e820addeaa8468762f5f29878868/raw/ai_novelist_range2button.user.js" target="_blank" rel="noopener noreferrer">直リンク</a>)</li>
				<li>Tampermonkeyのインストール確認ページが開くので、インストールボタンを押す。</li>
			</ol>
			<h5>注意</h5>
			<ul>
				<li>AIのべりすとのサイトの構成が変わると、ユーザースクリプトは動作しなくなる可能性があります。</li>
				<li>Chrome/Firefoxにて動作確認していますが、万が一テキストが消えてしまうなどの現象が発生しても、作者は責任を負いかねます。</li>
			</ul>
		</div>
		<hr>
		<h4 id="ai_novelist_trinart_download">TrinArtで生成画像とパラメータをまとめてダウンロード</h4>
		<div>
			<div class="m-2">
				<a href="https://gist.github.com/whiteball/03c4953d7f547187d979267f5ef36c59" target="_blank" rel="noopener noreferrer">ダウンロードページ</a><br>
				最終更新日：2022/09/25 バージョン0.3.1
			</div>
			このスクリプトを導入することで次の機能を追加します。
			<ul>
				<li><del>TrinArtの画像生成ページの下部に、画像を生成した時にその画像をjpgとして、またパラメータをtxtとしていっぺんにダウンロードするボタンを表示する。(スマホの場合は個別のダウンロードボタンを表示)</del></li>
				<li><del>ダウンロードボタンの下に、ファイル名を指定する入力欄を追加。デフォルト値はプロンプトのスペースなどを_に置換したものを使う。</del></li>
				<li><del>UndoやRedoをした場合は、その時に表示している画像をダウンロードする。パラメータも生成当時のものをダウンロードする。</del></li>
				<li>上記機能はTrinArt本体に取り込まれました。現在以下の機能を提供しています。</li>
				<li>コンテンツフィルタの設定の下に、自動保存オプションを追加。これがチェックされている場合は、画像の生成完了と同時にダウンロードを開始する。この設定はページをリロードするとオフになる。</li>
			</ul>
			<h5>導入手順</h5>
			<ol>
				<li>ブラウザに<a href="https://www.tampermonkey.net/" target="_blank" rel="noopener noreferrer">Tampermonkey</a>をインストールする。</li>
				<li>「TrinArtで生成画像とパラメータをまとめてダウンロード」の<a href="https://gist.github.com/whiteball/03c4953d7f547187d979267f5ef36c59" target="_blank" rel="noopener noreferrer">ダウンロードページ</a>を開く。</li>
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
				<li>「TrinArtのギャラリー個別ページで画像とパラメータをまとめてダウンロード」の<a href="https://gist.github.com/whiteball/3676fd7a3f58e947a864d2c8ef312024" target="_blank" rel="noopener noreferrer">ダウンロードページ</a>を開く。</li>
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
				最終更新日：2022/09/24 バージョン0.1.1
			</div>
			このスクリプトを導入することで次の機能を追加します。
			<ul>
				<li>TrinArtの画像生成ページの下部に、現在のパラメータで消費するルミナ、現在の残りルミナ、ページを開いたときの残りルミナ、ページを開いてから消費したルミナを表示する。</li>
				<li>※あくまでも取得できる範囲の情報からルミナを表示しているため、実際のルミナ消費と確実に一致しているかは保証しかねます。</li>
				<li>※ルミナの割引価格表示には非対応です。</li>
			</ul>
			<h5>導入手順</h5>
			<ol>
				<li>ブラウザに<a href="https://www.tampermonkey.net/" target="_blank" rel="noopener noreferrer">Tampermonkey</a>をインストールする。</li>
				<li>「TrinArtでページを開いてからのルミナ消費を表示する」の<a href="https://gist.github.com/whiteball/a2a3af48b3132c00231bf1d77673dddb" target="_blank" rel="noopener noreferrer">ダウンロードページ</a>を開く。</li>
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