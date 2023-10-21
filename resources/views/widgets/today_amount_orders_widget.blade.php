<div style="display: flex; flex-direction: row; align-items: center; justify-content: space-around; flex-wrap: wrap;">
	<div class="alert" style="background: #363c46; width: 330px;">
		<div style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
			<div>
				<h1 class="h4" style="margin-top: 0px;">Total today's sales </h1>
				<h2 class="h4" style="margin: 0px;"><kbd>{{ $todaySales }}</kbd></h2>
			</div>
			<div>
				<h3 style="margin: 0px;"><i class="voyager-activity"></i></h3>
			</div>
		</div>
	</div>

	<div class="alert" style="background: #363c46; width: 330px;">
		<div style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
			<div>
				<h1 class="h4" style="margin-top: 0px;">Total today's orders </h1>
				<h2 class="h4" style="margin: 0px;"><kbd>{{ $countOrders }}</kbd></h2>
			</div>
			<div>
				<h3 style="margin: 0px;"><i class="voyager-dollar"></i></h3>
			</div>
		</div>
	</div>

	<div class="alert" style="background: #363c46; width: 330px;">
		<div style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
			<div>
				<h1 class="h4" style="margin-top: 0px;">All time sales </h1>
				<h2 class="h4" style="margin: 0px;"><kbd>{{ $sum }}</kbd></h2>
			</div>
			<div>
				<h3 style="margin: 0px;"><i class="voyager-shop"></i></h3>
			</div>
		</div>
	</div>

	<div class="alert" style="background: #363c46; width: 330px;">
		<div style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
			<div>
				<h1 class="h4" style="margin-top: 0px;">Completed/abandoned orders</h1>
				<h2 class="h4" style="margin: 0px;"><code>{{ $count }}</code> / <kbd>{{ $drops }}</kbd></h2>
			</div>
			<div>
				<h3 style="margin: 0px;"><i class="voyager-dashboard"></i></h3>
			</div>
		</div>
	</div>

</div>