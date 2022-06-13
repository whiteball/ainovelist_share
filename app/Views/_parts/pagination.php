<?php if ($count !== 0) : ?>
	<?php $range       = 5 ?>
	<?php $range_lower = (int) ($page - floor($range / 2)) ?>
	<?php $range_upper = (int) ($page + floor($range / 2)) ?>

	<?php $page_url = ($page_base_url ?? '/') . (mb_strpos($page_base_url ?? '', '?') === false ? '?' : '&') . 'p=' ?>
	<nav aria-label="Page navigation">
		<ul class="pagination justify-content-center">
			<li class="page-item<?= ($page === 1) ? ' disabled' : '' ?>">
				<?php if ($page === 1) : ?>
					<span class="page-link">前へ</span>
				<?php else : ?>
					<a class="page-link" href="<?= site_url($page_url . ($page - 1)) ?>">前へ</a>
				<?php endif ?>
			</li>
			<?php if ($page === 1) : ?>
				<li class="page-item disabled"><span class="page-link">1</span></li>
			<?php else : ?>
				<li class="page-item"><a class="page-link" href="<?= site_url($page_url . '1') ?>">1</a></li>
			<?php endif ?>
			<?php if ($range <= $page) : ?>
				<li class="page-item disabled"><span class="page-link">…</span></li>
			<?php endif ?>
			<?php for ($i = $range_lower; $i <= $range_upper; $i++) : ?>
				<?php if ($i <= 1 || $i >= $last_page) :?>
					<?php continue ?>
				<?php endif ?>
				<?php if ($i === $page) : ?>
					<li class="page-item disabled"><span class="page-link"><?= $i ?></span></li>
				<?php else : ?>
					<li class="page-item"><a class="page-link" href="<?= site_url($page_url . $i) ?>"><?= $i ?></a></li>
				<?php endif ?>
			<?php endfor ?>
			<?php if ($range <= ($last_page - $page + 1)) : ?>
				<li class="page-item disabled"><span class="page-link">…</span></li>
			<?php endif ?>
			<?php if ($last_page !== 1) : ?>
				<?php if ($page === $last_page) : ?>
					<li class="page-item disabled"><span class="page-link"><?= $last_page ?></span></li>
				<?php else : ?>
					<li class="page-item"><a class="page-link" href="<?= site_url($page_url . $last_page) ?>"><?= $last_page ?></a></li>
				<?php endif ?>
			<?php endif ?>
			<li class="page-item<?= ($page === $last_page) ? ' disabled' : '' ?>">
				<?php if ($page === $last_page) : ?>
					<span class="page-link">次へ</span>
				<?php else : ?>
					<a class="page-link" href="<?= site_url($page_url . ($page + 1)) ?>">次へ</a>
				<?php endif ?>
			</li>
		</ul>
	</nav>
<?php endif ?>