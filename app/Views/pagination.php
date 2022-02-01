<?php if ($count !== 0) : ?>
	<nav aria-label="Page navigation">
		<ul class="pagination justify-content-center">
			<li class="page-item<?= ($page === 1) ? ' disabled' : '' ?>">
				<?php if ($page === 1) : ?>
					<span class="page-link">前へ</span>
				<?php else : ?>
					<a class="page-link" href="<?= site_url(($page_base_url ?? '/') . '?p=' . ($page - 1)) ?>">前へ</a>
				<?php endif ?>
			</li>
			<?php for ($i = 1; $i <= $last_page; $i++) : ?>
				<?php if ($i === $page) : ?>
					<li class="page-item disabled"><span class="page-link"><?= $i ?></span></li>
				<?php else : ?>
					<li class="page-item"><a class="page-link" href="<?= site_url(($page_base_url ?? '/') . '/?p=' . $i) ?>"><?= $i ?></a></li>
				<?php endif ?>
			<?php endfor ?>
			<li class="page-item<?= ($page === $last_page) ? ' disabled' : '' ?>">
				<?php if (($page === 0)) : ?>
					<span class="page-link">次へ</span>
				<?php else : ?>
					<a class="page-link" href="<?= site_url(($page_base_url ?? '/') . '/?p=' . ($page + 1)) ?>">次へ</a>
				<?php endif ?>
			</li>
		</ul>
	</nav>
<?php endif ?>