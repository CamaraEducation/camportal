@extends('layout.app')
@section('title', 'Camara EdPortal')
@section('header')

@endsection
@section('content')
	<div class="content-body">
		<div class="container-fluid">

			<a href="javascript:void" class="col-12 p-1">
				<div class="card bd-callout bd-callout-primary">
					<p class="text-bold">
						{{Utilities::quote()}}
					</p>
				</div>
			</a>			
			
			<div class="row">
				<div class="col-xl-3 col-sm-6 m-t35">
					<div class="widget-stat card bg-danger">
						<div class="card-body  p-4">
							<div class="media">
								<span class="mr-3">
									<i class="flaticon-381-video-clip"></i>
								</span>
								<div class="media-body text-white text-right">
									<p class="mb-1">Videos</p>
									<h3 class="text-white">{{getFileCount(upload.'video/')+StatsController::count_video()}}</h3>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-sm-6 m-t35">
					<div class="widget-stat card bg-success">
						<div class="card-body  p-4">
							<div class="media">
								<span class="mr-3">
									<i class="flaticon-381-notepad"></i>
								</span>
								<div class="media-body text-white text-right">
									<p class="mb-1">Documents</p>
									<h3 class="text-white">{{StatsController::count_document()}}</h3>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-sm-6 m-t35">
					<div class="widget-stat card bg-warning">
						<div class="card-body  p-4">
							<div class="media">
								<span class="mr-3">
									<i class="flaticon-381-cloud-computing"></i>
								</span>
								<div class="media-body text-white text-right">
									<p class="mb-1">Systems</p>
									<h3 class="text-white">{{StatsController::count_apps()}}</h3>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-sm-6 m-t35">
					<div class="widget-stat card bg-secondary">
						<div class="card-body  p-4">
							<div class="media">
								<span class="mr-3">
									<i class="flaticon-381-user"></i>
								</span>
								<div class="media-body text-white text-right">
									<p class="mb-1">Users</p>
									<h3 class="text-white">{{StatsController::count_user()}}</h3>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12 col-xl-9 col-xxl-8">
					<div class="card">
						<div class="card-header border-0 flex-wrap pb-0">
							<div class="mb-3">
								<h2 class="card-title mb-2 text-primary pad-2">Usage Activity</h2>
							</div>
						</div>
						<div class="card-body">
							<canvas id="areaChart_2"></canvas>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-xxl-4">
					<div class="row">
						<div class="card col-sm-12">
							<div class="card-header border-0 pb-0">
								<h2 class="card-title mb-2 text-primary pad-2">Camara Education Portal</h2>
							</div>
							<div class="card-body pb-2 px-3">
								<p>Camara Education portal is an offline educational tools container that aims to provide ease of access to different educational resources and tools</p>
							</div>
						</div>
						<div class="card col-sm-12">
							<div class="card-header border-0">
								<h2 class="card-title mb-2 text-primary pad-2">School Details</h2>
							</div>
							<div class="card-body pb-2 px-3">
								<h6>School: {{ConfigsController::get('school')}}</h6>
								<h6>Region: {{ConfigsController::get('region')}}</h6>
								<h6>Country: {{ConfigsController::get('country')}}</h6>
							</div>
						</div>
						<div class="card col-sm-12">
							<div class="card-header border-0 pb-0">
								<h2 class="card-title mb-2 text-primary pad-2">System Live Time</h2>
							</div>
							<div class="card-body pb-2 px-3">
								@if(viewer() == 1)
									@php $livetime = StatsController::count_live_time();
									$user_activity = StatsController::count_users_activity();
									$live_activity = StatsController::sum_users_activity(); 
									@endphp
								@else
									@php $livetime = StatsController::count_user_time($_SESSION['id']);
									$user_activity = StatsController::count_user_activity($_SESSION['id']); 
									@endphp
								@endif
								<h6>Today 		: {{TimeController::convert_sec_min_hrs($livetime['today']/1000)}}</h6>
								<h6>This Month  : {{TimeController::convert_sec_min_hrs($livetime['monthly']/1000)}}</h6>
								<h6>This Year 	: {{TimeController::convert_sec_min_hrs($livetime['all_time']/1000)}}</h6>
								<div class="space"></div>
							</div>
						</div>
						
						<div class="card col-sm-12">
							<div class="card-header border-0 pb-0">
								<h2 class="card-title mb-2 text-primary pad-2">Synchronization Unit</h2>
							</div>
							<div class="card-body pb-2 px-3">
								<h6>Number of Clients: {{StatsController::count_clients()->getRowCount()}}</h6>

								<a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal" data-target="#clientsModal" class="text-primary"> View All</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection	
@section('footer') 
@js('/assets/vendor/chart.js/Chart.bundle.min.js')


<script>
(function($) {
    "use strict" 

 var dzSparkLine = function(){
	let draw = Chart.controllers.line.__super__.draw; //draw shadow
	
	var screenWidth = $(window).width();
	var visitor_activity = function(){	
		//gradient area chart
		if(jQuery('#areaChart_2').length > 0 ){
			const areaChart_2 = document.getElementById("areaChart_2").getContext('2d');
			//generate gradient
			const areaChart_2gradientStroke = areaChart_2.createLinearGradient(1, 1, 0, 500);
			
			areaChart_2.height = 100;

			new Chart(areaChart_2, {
				type: 'line',
				data: {
					defaultFontFamily: 'Poppins',
					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					datasets: [
						{
							label: "User Activities",
							data: [ @foreach($user_activity as $key => $value) {{$value}}, @endforeach ],
							borderColor: "#6418C3",
							borderWidth: "4",
							backgroundColor: "rgb(100, 24, 195, 0.1)"
						},
						{
							label: "Total Live Time",
							data: [ @foreach($live_activity as $key => $value) {{round($value)}}, @endforeach ],
							borderColor: "rgb(85, 139, 47)",
							borderWidth: "4",
							backgroundColor: "rgb(85, 139, 47, 0.1)"
						}

					]
				},
				options: {
					legend: false, 
					scales: {
						yAxes: [{ 
							ticks: { beginAtZero: true, padding: 5 },
							title: { display: true, text: 'Sessions v Usage Time'}
						}],
						xAxes: [{ 
							ticks: { padding: 5 },
							title: { display: true, text: 'Months'}
						}]
					}
				}
			});
		}    
	}



	/* Function ============ */
	return {
		init:function(){
		},
		
		
		load:function(){
			visitor_activity();
		},
		
		resize:function(){
		}
	}

}();


	
jQuery(window).on('load',function(){
	dzSparkLine.load();
});

jQuery(window).on('resize',function(){
	//dzSparkLine.resize();
	setTimeout(function(){ dzSparkLine.resize(); }, 1000);
});
	
})(jQuery);
</script>
@endsection
