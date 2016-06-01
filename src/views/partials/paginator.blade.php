@if ($paginator->count())
<div class="row">
	<div class="col-xs-6">
		<div class="dataTables_info" id="example1_info">
			Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->count() }} entries
		</div>
	</div>

	<div class="col-xs-6">
		<div class="dataTables_paginate paging_bootstrap">
			<ul class="pagination">
				{{--todo: style pagination--}}
				@if ($paginator->currentPage() > 1)
				<li class="prev"><a href="#">← Previous</a></li>
				@endif

				@for ($i = $paginator->currentPage(); $i < min($paginator->currentPage() + 5, $paginator->count()); $i++ )
				<li><a {{ ($i == $paginator->currentPage() ? 'class="active"' : '') }} href="#">2</a></li>
				@endfor

				@if ($paginator->hasMore())
				<li class="next"><a href="#">Next → </a></li>
				@endif;
			</ul>
		</div>
	</div>
</div>
@endif