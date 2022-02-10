<?php use App\Enumeration\Role; ?> 
@extends('layouts.frontend')
@section('content')
<section class="register_area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="reset_password_wrapper clearfix">
                        <div class="reset_password_inner">
                            <div class="register_head">
                                <h2>Enter NEW PASSWORD</h2>
                            </div> 
                            @if(Session::has('flash_message_success'))
                                <div class="alert alert-success background-success">
                                    <button type="button" class="close" data-dismiss="alert">x</button>
                                    <strong>{!! session('flash_message_success')!!}</strong>
                                </div>
                            @endif
                            <form method="post" action="{{ route('new_password_post_buyer') }}">
                                @csrf 
                                <div class="form-group reset_email">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control {{ $errors->has('password') ? ' has-danger' : '' }}" name="password" id="password" placeholder="Password">
                                </div>
                                <div class="form-group reset_email">
                                    <label for="password_confirmation">Confirmed Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control {{ $errors->has('password') ? ' has-danger' : '' }}" name="password_confirmation" id="password_confirmation" placeholder="Re-enter Password"> 
                                    @if ($errors->has('password'))
                                        <p class="form-control-feedback mb-0">{{ $errors->first('password') }}</p>
                                    @endif
                                    <button type="submit" class="btn sign_in_btn mt-3">Reset Password</button>
                                </div>
                                <input type="hidden" name="token" value="{{ request()->get('token') }}"> 
                            </form> 
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </section> 
@stop