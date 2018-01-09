<?php if (is_array($status)) { ?>
<section>
	<h2>{{$label}}</h2>
	<div class="checks">
		@foreach ($status as $l => $s)@include('statuspage::check', ['label' => $l, 'status' => $s])@endforeach
	</div>
</section>
<?php } else { ?>
<div class="check">
	<span class="label">{{$label}}</span>
	<span class="status">{{$status}}</span>
</div>
<?php } ?>
