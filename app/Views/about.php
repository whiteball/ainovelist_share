<?= $this->extend('template') ?>
<?= $this->section('title') ?> - このサイトについて<?= $this->endSection() ?>

<?= $this->section('ogp') ?>
<?php $description = 'このサイトについての説明ページ。更新履歴も含む。' ?>
<meta property="og:image" content="<?= base_url('img/ogp.png') ?>" />
<meta name="twitter:image" content="<?= base_url('img/ogp.png') ?>" />
<meta property="og:type" content="website" />
<meta property="og:title" content="このサイトについて" />
<meta name="twitter:title" content="このサイトについて">
<meta property="og:description" content="<?= $description ?>" />
<meta name="twitter:description" content="<?= $description ?>">
<meta name="description" content="<?= $description ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<h3>このサイトについて</h3>
	<div>
		このサイトは、<a href="https://ai-novel.com/" target="_blank" rel="noopener noreferrer">AIのべりすと</a>で作成したプロンプトを共有するための非公式ファンサイトです。
	</div>
	<hr>
	<h4>投稿する方へ</h4>
	<ul>
		<li>投稿には<a href="<?= site_url('register') ?>">アカウントの作成</a>が必要です。</li>
		<li>ID/パスワードを忘れた場合でも、アカウントの復旧はできないのでご注意ください。(メールアドレスなどの連絡先を登録内容に含んでいないため、本人確認が出来ません)</li>
		<li>R-18な内容でも投稿可能ですが、公序良俗に反するもの、個人情報を含むもの、広告やマルチ商法や金融商品(暗号通貨、NFT含む)の勧誘を目的とするもの、差別行為を目的とするものなどは、投稿やアカウント削除の対象となります。</li>
		<li>可能な範囲でサービスの維持に努めますが、その継続性やデータの永続性は一切保証しません。必要なデータはnovelファイルのダウンロード等で各自保存してください。</li>
	</ul>
	<hr>
	<h4>閲覧する方へ</h4>
	<ul>
		<li>投稿されたプロンプトの中にはR-18な内容を含む場合があります。その他、閲覧者の望まないコンテンツが存在する可能性をご承知の上、自己の判断においてサイトを閲覧するようお願いします。</li>
		<li>各プロンプトの個別ページでは、プロンプト内容の確認のほか、そのプロンプトのnovelファイルのダウンロード、AIのべりすとへの直接読み込み(インポート)ができます。また本文、メモリなどの項目を、個別にクリップボードにコピーすることも可能です。必要に応じてご利用ください。</li>
		<li>投稿されたプロンプトにコメントをするには、<a href="<?= site_url('register') ?>">アカウントの作成</a>が必要です。ただし、コメント可能なのはプロンプト投稿者がコメント受け付け状態にしているものに限ります</li>
		<li>ID/パスワードを忘れた場合でも、アカウントの復旧はできないのでご注意ください。(メールアドレスなどの連絡先を登録内容に含んでいないため、本人確認が出来ません)</li>
		<li>コメント投稿も、プロンプト投稿と同様の方針で削除対象となりますが、加えてR-18でないプロンプトにR-18な内容を投稿することはご遠慮ください。</li>
	</ul>
	<hr>
	<h4>使用フォントの権利表示</h4>
	<div>
		本サイトではOGP画像のフォントに「源真ゴシック」(<a href="http://jikasei.me/font/genshin/" target="_blank" rel="noopener noreferrer">http://jikasei.me/font/genshin/</a>) を使用しています。<br>
		Licensed under SIL Open Font License 1.1 (<a href="http://scripts.sil.org/OFL" target="_blank" rel="noopener noreferrer">http://scripts.sil.org/OFL</a>)<br>
		© 2015 自家製フォント工房, © 2014, 2015 Adobe Systems Incorporated, © 2015 M+<br>
		FONTS PROJECT
	</div>
	<hr>
	<h4>連絡先</h4>
	<ul>
		<li>管理人Twitter: <a href="https://twitter.com/whiteball" rel="noopener noreferrer" target="_blank">@whiteball</a></li>
	</ul>
	<hr>
	<h4>更新履歴</h4>
	<table class="table">
		<tr>
			<th scope="col" width="130">
				日付
			</th>
			<th scope="col">
				内容
			</th>
		</tr>
		<tr>
			<th scope="row">
				2022/05/01
			</th>
			<td>
				<ul>
					<li>プロンプトの個別ページにコメントを投稿出来るようにした。ただし、プロンプトの投稿者がコメントを受け付ける設定にしているものに限る。</li>
					<li>マイページに投稿した/受け取ったコメントの一覧ページを追加。</li>
				</ul>
			</td>
		</tr>
		<tr>
			<th scope="row">
				2022/04/12
			</th>
			<td>
				<ul>
					<li>プロンプト編集時にもファイルからデータを読み込めるようにした。</li>
				</ul>
			</td>
		</tr>
		<tr>
			<th scope="row">
				2022/04/11
			</th>
			<td>
				<ul>
					<li>集計エラーのため、今週のランキングが更新出来ませんでした。申し訳ありません。</li>
					<li>ランキング集計期間内に削除または非公開になったプロンプトがあるとエラーが出る不具合を修正。</li>
					<li>プロンプト削除時にOGP画像が削除されなかった不具合を修正。</li>
				</ul>
			</td>
		</tr>
		<tr>
			<th scope="row">
				2022/04/04
			</th>
			<td>
				<ul>
					<li>ランキングページを追加。</li>
				</ul>
			</td>
		</tr>
		<tr>
			<th scope="row">
				2022/04/02
			</th>
			<td>
				<ul>
					<li>検索ページなどのソート順のリンク先が間違っていたのを修正。</li>
				</ul>
			</td>
		</tr>
		<tr>
			<th scope="row">
				2022/03/26
			</th>
			<td>
				<ul>
					<li>プロンプトの個別ページのOGP画像にタイトルが入るようにした。</li>
				</ul>
			</td>
		</tr>
		<tr>
			<th scope="row">
				2022/03/24
			</th>
			<td>
				<ul>
					<li>プロンプトの一覧のソート順を指定出来るようにした。デフォルトは投稿日順。</li>
				</ul>
			</td>
		</tr>
		<tr>
			<th scope="row">
				2022/02/20
			</th>
			<td>
				<ul>
					<li>スマートフォン環境で、novelファイルダウンロード/AIのべりすとインポートボタンの表示がずれていたのを修正。</li>
				</ul>
			</td>
		</tr>
		<tr>
			<th scope="row">
				2022/02/19
			</th>
			<td>
				<ul>
					<li>タイトルから(仮)を外して、正式公開開始。</li>
				</ul>
			</td>
		</tr>
		<tr>
			<th scope="row">
				2022/02/17
			</th>
			<td>
				<ul>
					<li>プロンプトの個別ページに投稿日/更新日の表示を追加。</li>
				</ul>
			</td>
		</tr>
		<tr>
			<th scope="row">
				2022/02/15
			</th>
			<td>
				<ul>
					<li>各ページOGPの設定を追加。</li>
				</ul>
			</td>
		</tr>
		<tr>
			<th scope="row">
				2022/02/06
			</th>
			<td>
				<ul>
					<li>マイページのURLとページ構成を変更。</li>
					<li>マイページに退会(ユーザー削除)ボタンを追加。</li>
					<li>投稿/編集完了ページに個別ページと編集ページへのリンクを追加。</li>
				</ul>
			</td>
		</tr>
		<tr>
			<th scope="row">
				2022/02/05
			</th>
			<td>
				<ul>
					<li>プロンプトの個別ページにカウンタを設置。</li>
					<li>プロンプトの個別ページの各テキスト欄にクリップボードへのコピーボタンを設置。</li>
				</ul>
			</td>
		</tr>
		<tr>
			<th scope="row">
				2022/02/03
			</th>
			<td>
				<ul>
					<li>編集フォーム以外ではタイトルに含まれたタグを表示しないように変更。</li>
					<li>プロンプトの個別ページで、メモリ・脚注・禁止ワード・キャラクターブック・スクリプトは折りたたんで表示されるように変更。</li>
					<li>初めてR-18/すべてのタブを表示する際は、クッションページを挟むように変更。一度クッションを見たあとは、セッション情報が消えるまでは再度表示されることはありません。</li>
					<li>プロンプトを投稿/編集の際に、非公開状態(ドラフト・書きかけ保存)を選択できるように変更。非公開状態ではマイページ以外の一覧に現れず、詳細ページも表示できなくなります。</li>
					<li>マイページにパスワード変更フォームを追加。</li>
				</ul>
			</td>
		</tr>
		<tr>
			<th scope="row">
				2022/02/02
			</th>
			<td>
				<ul>
					<li>仮公開開始。</li>
					<li>プロンプトの個別ページからAIのべりすとに直接読み込める機能を追加。</li>
					<li>Faviconを追加。</li>
					<li>キャプション(タイトルと説明)検索を追加。</li>
					<li>投稿内容確認ページから編集フォームへ戻るボタンを追加。</li>
					<li>正規表現のスクリプトがあるnovelファイルが読み込めなかったのを修正。</li>
				</ul>
			</td>
		</tr>
	</table>

</main>
<?= $this->endSection() ?>