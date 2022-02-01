<?= $this->include('pagination') ?>
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-3">
	<?php foreach ($prompts as $prompt) : ?>
		<div class="col">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title" style="word-break: break-all;overflow-wrap: break-word;"><?= esc($prompt->title) ?></h5>
					<h6 class="card-subtitle mb-2 text-muted"><?= esc(mb_substr($prompt->registered_at, 0, 10)) ?></h6>
					<p class="card-text" style="word-break: break-all;overflow-wrap: break-word;"><?= str_replace(' ', '&nbsp;', esc(mb_strimwidth(preg_replace('/[\r\n]/u', ' ', trim($prompt->description)), 0, 128, '...'))) ?></p>
					<a href="<?= site_url('prompt/' . $prompt->id) ?>" class="btn btn-primary">詳細</a>
					<hr>
					<h6 class="card-subtitle">タグ:
						<?php if ($prompt->r18 === '1') : ?>
							<a class="btn rounded-pill btn-danger btn-sm tag-link" href="<?= site_url('tag/R-18') ?>">R-18</a>
						<?php endif ?>
						<?php foreach ($tags[$prompt->id] as $tag) : ?>
							<a class="btn rounded-pill btn-outline-secondary btn-sm tag-link" href="<?= site_url('tag/' . $tag->tag_name) ?>"><?= esc($tag->tag_name) ?></a>
						<?php endforeach ?>
					</h6>
				</div>
			</div>
		</div>
	<?php endforeach ?>
</div>
<?= $this->include('pagination') ?>