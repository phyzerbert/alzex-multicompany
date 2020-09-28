@extends('layouts.master')
@section('style')
    <link href="{{asset('master/global_assets/js/plugins/daterangepicker/daterangepicker.min.css')}}" rel="stylesheet">
@endsection
@section('content')
    @php
        config(['site.page' => 'advanced_delete']);
    @endphp
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="pull-left page-title"><i class="fa fa-trash-o"></i> {{__('page.advanced_delete')}}</h3>
                </div>
            </div>    
        
            @php
                $role = Auth::user()->role->slug;
            @endphp
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-body">                        
                        <form class="form-layout form-layout-1" action="" method="POST" id="delete_form">
                            @csrf
                            <div class="form-group my-3">
                                <label class="form-control-label">{{__('page.date')}}</label>
                                <input class="form-control" id="period" type="text" name="date" placeholder="{{__('page.date')}}" autocomplete="off">
                            </div>
                            @php
                                $users = \App\User::orderBy('name')->get();
                            @endphp
                            <div class="form-group mb-2">
                                <label class="form-control-label">{{__('page.user')}}</label>
                                <select class="form-control select2" name="user" id="search_user" class="wd-100" multiple="multiple">
                                    @foreach ($users as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-layout-footer mt-3">
                                <button type="submit" class="btn btn-primary" id="btn_request"><i class="fa fa-paper-plane mr-2"></i> {{__('page.request')}}</button>
                            </div>
                            <div class="form-group mt-3 verify" style="display: none">
                                <label class="form-control-label">{{__('page.verification_code')}}</label>
                                <input class="form-control" type="text" name="verification_code" placeholder="{{__('page.input_verification_code')}}">
                            </div>
                            <div class="form-layout-footer mt-3 verify" style="display: none">
                                <button type="button" class="btn btn-primary" id="btn_verify"><i class="fa fa-check mr-2"></i> {{__('page.confirm')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>                
    </div>
@endsection

@section('script')
<script src="{{asset('master/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
<script src="{{asset('master/global_assets/js/plugins/daterangepicker/jquery.daterangepicker.min.js')}}"></script>
<script>
    $(document).ready(function () {
        $("#period").dateRangePicker({
            autoClose: false,
        });

        $('#search_user')
            .select2({
                width: 'resolve',
                multiple: true,
            });

        $("#delete_form").submit(function(e){
            e.preventDefault();
            let users = $("#search_user").val();

            let request_data = {
                period: $("#period").val(),
                user: $("#search_user").val().toString(),
            };
            $.ajax({
                url: "{{route('advanced_delete.request')}}",
                data: request_data,
                method: 'POST',
                dataType: 'json',
                success: function (data) {
                    if(data.status == 200) {                       
                        $("#btn_request").attr('disabled', 'true');
                        $(".verify").show();
                    } else if (data.status == 400) {
                        alert(data.message)
                    } else {
                        alert('Something went wrong!');
                    }
                }
            });
        });
        $("#btn_verify").click(function () {
            $.ajax({
                url: "{{route('advanced_delete.verify')}}",
                data: {verification_code: $("#verification_code").val()},
                method: 'POST',
                dataType: 'json',
                success: function (data) {
                    if(data.status == 200) {                       
                        alert(data.message);
                        window.location.reload();
                    } else if (data.status == 400) {
                        alert(data.message)
                    } else {
                        alert('Something went wrong!');
                    }
                }
            });
        })
    });
</script>
@endsection
