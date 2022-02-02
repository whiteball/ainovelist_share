<!doctype html>
<html lang="ja">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link href="<?= base_url('css/default.css') ?>" rel="stylesheet">
	<title>AIのべりすと プロンプト共有(仮)</title>
</head>

<body>
	<?= $this->renderSection('content') ?>

	<div class="container">
		<footer class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mt-4 border-top">
			<div class="col-1"></div>
			<div class="col-10 col-md-auto mt-2 justify-content-center mt-md-0 text-center" style="font-size: 75%;color: gray;">
				AIのべりすと プロンプト共有(仮)
			</div>
			<div class="col-1"></div>
		</footer>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>