<?= $this->extend('template') ?>
<?= $this->section('title') ?> - ユーザースクリプト<?= $this->endSection() ?>

<?= $this->section('ogp') ?>
<?php $description = 'AIのべりすと用のユーザースクリプトの導入案内。' ?>
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
			最終更新日：2022/03/09 バージョン0.7
		</div>
		このスクリプトを導入することで次のことが出来るようになります。
		<ul>
			<li>編集ページの本文入力欄でテキストを選択しながら「Ctrl＋/」を押すと、選択テキストの上の行に「@/*」を、下の行に「@*/」を挿入する。</li>
			<li>「@/*」から「@*/」までを選択しながら「Ctrl＋/」を押すと、「@/*」と「@*/」を削除する。</li>
			<li>AIが生成した文章をリトライをするとき、AIが生成した後に文章を変更していた場合、本当にリトライしてもいいかの確認ダイアログを出す。同じくUndo・Redoするときも、文章を変更している場合に確認ダイアログを出す。</li>
			<li>本文入力欄を複数文コメントや最新の出力文(色の変わっている部分)の途中で分割されないようにする。(分割の回数が減るため、とても長い単一の複数文コメントがあると、環境によってはブラウザが重くなります。)</li>
			<li>@endpointがある場合は、「続きの文を書く」「リトライ」したときに@endpointの前に出力文を挿入するようにし、「続きの文を書く」「リトライ」「Undo」「Redo」したときに@endpointの前にスクロールするようにする。</li>
			<li>@endpointにもコメント色分けを適用する。(一番最後にある@endpointから下を色分け。)</li>
			<li>「Redo」が最新状態でさらに「Redo」を3回押すと、「Undo」と同じようにUndo履歴を挿入する。</li>
			<li>オプションの右から2番目のアイコン(クリップボード)を押すと、「続きの文を書く」や「リトライ」をしたときにサーバーに送信したテキスト内容を確認出来るエリアを表示する。</li>
			<li>同じく、オプションの右端のアイコン(本)を押すと、過去20回分までAIが出力したテキストの履歴を確認出来るエリアを表示する。履歴はファイルとしてダウンロードすることも出来る。</li>
			<li>上記2つのテキストエリアは、本文のフォントや文字サイズに従う。</li>
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