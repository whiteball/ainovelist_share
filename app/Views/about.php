<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<?= $this->include('header_nav') ?>
<main class="container">
	<h3>このサイトについて</h3>
	<div>
		このサイトは、<a href="https://ai-novel.com/" target="_blank" rel="noopener noreferrer">AIのべりすと</a>で使えるプロンプトを共有するためのサイトです。<br>
		ここで公開されているプロンプトをAIのべりすとに読み込ませて、続きを書いてみましょう。
	</div>
	<hr>
	<h4>投稿する方へ</h4>
	<ul>
		<li>投稿されたプロンプトは、他人に使ってもらうためのものです。そのため、プロンプトだけで完結しているものや、他人に続きを書かせたくないものは投稿しないようにしてください。</li>
		<li>投稿には<a href="<?= site_url('register') ?>">アカウントの作成</a>が必要です。</li>
		<li>ID/パスワードを忘れた場合でも、アカウントの復旧はできないので注意でしてください。(メールアドレスなどの連絡先が登録内容に含んでいないため)</li>
		<li>R-18な内容でも投稿可能ですが、公序良俗に反するもの、個人情報を含むもの、広告やマルチ商法や金融商品(暗号通貨、NFT含む)の勧誘を目的とするもの、差別行為を目的とするものなどは削除対象となります。</li>
	</ul>
	<hr>
	<h4>閲覧する方へ</h4>
	<ul>
		<li>投稿されたプロンプトの中にはR-18な内容を含む場合があります。その他、閲覧者の望まないコンテンツが存在する可能性を承知の上、自己の判断においてサイトを閲覧するようお願いします。</li>
	</ul>
	<h4>連絡先</h4>
	<ul>
		<li>管理人Twitter: <a href="https://twitter.com/whiteball" rel="noopener noreferrer" target="_blank">@whiteball</a></li>
	</ul>
</main>
<?= $this->endSection() ?>