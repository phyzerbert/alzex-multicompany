@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-8 ">
                <div class="card">
                    <div class="card-header">{{__('page.two_factor_authentication')}}</div>
                    <div class="card-body">
                        <p>{{__('page.2fa_title')}}</p>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{__('page.2fa_enter_pin')}}<br/><br/>
                        <form class="form-horizontal" action="{{ route('2faVerify') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('one_time_password-code') ? ' has-error' : '' }}">
                                <label for="one_time_password" class="control-label">{{__('page.one_time_pwd')}}</label>
                                <input id="one_time_password" name="one_time_password" class="form-control col-md-4"  type="text" required/>
                            </div>
                            <button class="btn btn-primary" type="submit">{{__('page.authenticate')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection