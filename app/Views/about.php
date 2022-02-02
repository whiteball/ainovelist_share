<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<?= $this->include('_parts/header_nav') ?>
<main class="container">
	<h3>このサイトについて</h3>
	<div>
		<div class="lead mt-2 mb-2"><u><strong>現在仮運用中です！</strong></u></div>
		このサイトは、<a href="https://ai-novel.com/" target="_blank" rel="noopener noreferrer">AIのべりすと</a>で作成したプロンプトを共有するためのサイトです。
	</div>
	<hr>
	<h4>投稿する方へ</h4>
	<ul>
		<li>投稿には<a href="<?= site_url('register') ?>">アカウントの作成</a>が必要です。</li>
		<li>ID/パスワードを忘れた場合でも、アカウントの復旧はできないのでご注意ください。(メールアドレスなどの連絡先を登録内容に含んでいないため、本人確認が出来ません)</li>
		<li>R-18な内容でも投稿可能ですが、公序良俗に反するもの、個人情報を含むもの、広告やマルチ商法や金融商品(暗号通貨、NFT含む)の勧誘を目的とするもの、差別行為を目的とするものなどは、投稿やアカウント削除の対象となります。</li>
	</ul>
	<hr>
	<h4>閲覧する方へ</h4>
	<ul>
		<li>投稿されたプロンプトの中にはR-18な内容を含む場合があります。その他、閲覧者の望まないコンテンツが存在する可能性をご承知の上、自己の判断においてサイトを閲覧するようお願いします。</li>
	</ul>
	<h4>連絡先</h4>
	<ul>
		<li>管理人Twitter: <a href="https://twitter.com/whiteball" rel="noopener noreferrer" target="_blank">@whiteball</a></li>
	</ul>
</main>
<?= $this->endSection() ?>