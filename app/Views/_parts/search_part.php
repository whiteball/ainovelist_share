<?php $search_mode ??= 'tag'; in_array($search_mode, ['tag', 'caption'], true) || $search_mode = 'tag' ?>
<?= form_open('search/' . $search_mode, ['method' => 'get', 'class' => 'row g-3', 'name' => 'search_form']) ?>
<div class="col-auto">
	<div class="input-group">
		<select class="form-select bg-light" id="search-type" style="max-width: 10rem;">
			<option data-url="<?= site_url('search/tag') ?>"<?= $search_mode === 'tag' ? ' selected' : '' ?>>タグ検索</option>
			<option data-url="<?= site_url('search/caption') ?>"<?= $search_mode === 'caption' ? ' selected' : '' ?>>キャプション検索</option>
		</select>
		<input type="text" class="form-control" id="search" name="q" value="<?= esc($query ?? '', 'attr') ?>" aria-describedby="search-type" placeholder="検索ワード...">
		<button class="btn btn-secondary" type="submit" id="search-button">検索</button>
	</div>
</div>
<?= form_close() ?>
<script>
	const searchTypeChange = function() {
		for (let opt of document.getElementById('search-type').children) {
			if (opt.selected) {
				document.search_form.action = opt.getAttribute('data-url')
				break
			}
		}
	}
	document.addEventListener('DOMContentLoaded', searchTypeChange)
	document.getElementById('search-type').addEventListener('input', searchTypeChange)
</script>