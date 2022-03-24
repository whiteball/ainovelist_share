<!doctype html>
<html lang="ja">
<?php
$mode  = $_SESSION['list_mode'] ?? 's';
$uri   = current_url(true);
$path  = trim(str_replace('//', '/', str_replace(index_page(), '', $uri->getPath())));
$query = preg_replace('/(^|&|\?)([nl]mode=\w|sort=\w)/u', '', $uri->getQuery());
// home, tag, search, user
if ($mode !== 's' && ($path === '' || $path === '/' || $path === '/search/tag' || $path === '/search/caption' || mb_strpos($path, '/tag') === 0 || mb_strpos($path, '/user') === 0)) {
    $query = 'lmode=' . $mode . (empty($query) ? '' : '&') . $query;
}

$current_url = site_url(str_replace(index_page(), '', implode('/', $uri->getSegments())) . ($query ? ('?' . $query) : ''));
?>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link href="<?= base_url('css/default.css') ?>" rel="stylesheet">
	<link rel="canonical" href="<?= $current_url ?>">
	<title>AIのべりすと プロンプト共有<?= $this->renderSection('title') ?></title>
	<meta property="og:url" content="<?= $current_url ?>" />
	<meta property="og:site_name" content="AIのべりすと プロンプト共有" />
	<meta property="og:image" content="<?= base_url('img/ogp.png') ?>" />
	<meta property="og:image:type" content="image/png" />
	<meta property="og:image:width" content="1200" />
	<meta property="og:image:height" content="630" />
	<meta property="og:locale" content="ja_JP" />
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:image" content="<?= base_url('img/ogp.png') ?>" />
	<meta name="twitter:url" content="<?= $current_url ?>" />
	<?= $this->renderSection('ogp') ?>
</head>

<body>
	<?= $this->renderSection('content') ?>

	<div class="container">
		<footer class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mt-4 border-top">
			<div class="col-1"></div>
			<div class="col-10 col-md-auto mt-2 justify-content-center mt-md-0 text-center" style="font-size: 75%;color: gray;">
				AIのべりすと プロンプト共有
			</div>
			<div class="col-1"></div>
		</footer>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>