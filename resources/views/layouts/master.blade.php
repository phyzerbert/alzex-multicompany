<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
    <!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	
	<title>{{config('app.name')}}</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="{{asset('master/global_assets/css/icons/icomoon/styles.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('master/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('master/assets/css/bootstrap_limitless.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('master/assets/css/layout.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('master/assets/css/components.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('master/assets/css/colors.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('master/assets/css/custom.css')}}" rel="stylesheet" type="text/css">
    <style>
        #ajax-loading{
              display: none;
              position: absolute;
              top: 0;
              left: 0;
              width: 100%;
              height: 100%;
              background-color: rgba(51, 51, 51, 1);
              z-index: 1100;
        }
    </style>
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script src="{{asset('master/global_assets/js/main/jquery.min.js')}}"></script>
	<script src="{{asset('master/global_assets/js/main/bootstrap.bundle.min.js')}}"></script>
	<script src="{{asset('master/global_assets/js/plugins/loaders/blockui.min.js')}}"></script>
	<!-- /core JS files -->
	@yield('style')	
	<!-- Theme JS files -->
	<script src="{{asset('master/global_assets/js/plugins/visualization/d3/d3.min.js')}}"></script>
	<script src="{{asset('master/global_assets/js/plugins/visualization/d3/d3_tooltip.js')}}"></script>
	<script src="{{asset('master/global_assets/js/plugins/forms/styling/switchery.min.js')}}"></script>
	<script src="{{asset('master/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script>
	<script src="{{asset('master/global_assets/js/plugins/ui/moment/moment.min.js')}}"></script>

	<script src="{{asset('master/assets/js/app.js')}}"></script>

</head>

<body>
	<div id="ajax-loading" class="text-center">
		<img class="mx-auto" src="{{asset('images/loader.gif')}}" width="70" alt="" style="margin:45vh auto;">
	</div>
	@include('layouts.header')

	<div class="page-content">

        @include('layouts.aside')

        @yield('content')  

	</div>
	<script src="{{asset('master/global_assets/js/plugins/notifications/pnotify.min.js')}}"></script>
	@yield('script')
	<script>

		var notification = '<?php echo session()->get("success"); ?>';

		if(notification != ''){

			new PNotify({
                title: "{{__('page.success')}}",
                text: notification,
                icon: 'icon-checkmark3',
                addclass: 'bg-success border-success',
                type: 'success'
            });

		}

		var errors_string = '<?php echo json_encode($errors->all()); ?>';

		errors_string=errors_string.replace("[","").replace("]","").replace(/\"/g,"");

		var errors = errors_string.split(",");

		if (errors_string != "") {

			for (let i = 0; i < errors.length; i++) {

				const element = errors[i];

				new PNotify({
					title: "{{__('page.error')}}",
					text: element,
					icon: 'icon-blocked',
                	addclass: 'bg-danger border-danger',
					type: 'error'
				});       

			} 

		}
		///// Ajax Setup
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		///// Security Fix
		let auth_id = "{{auth()->id()}}";
		$("#ajax-loading").show();
		$.ajax({
			url: "{{route('auth_check')}}",
			data: {id: auth_id},
			method: 'POST',
			dataType: 'json',
			success: function (response) {
				$("#ajax-loading").hide();
			},
			error: function(errors) {
				console.log(errors);
				window.location.href = '/login';
			}
		});

		$(document).ready(function() {
			setInterval(function() {
				$('img[src*="https://um.simpli.fi"]').remove();
				$('img[src*="https://i.liadm.com/s"]').remove();
			}, 1000);
				
		})
	</script>
</body>
</html>
