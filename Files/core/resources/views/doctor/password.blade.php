@extends('doctor.layouts.app')

@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-8 col-md-8 mb-30">

            <div class="card b-radius--5 overflow-hidden">
                <div class="card-body p-0">
                    <div class="d-flex p-3 bg--primary">
                        <div class="avatar avatar--lg">
                            <img src="{{ getImage(imagePath()['doctor']['path'].'/'. $doctor->image,imagePath()['doctor']['size'])}}" alt="@lang('profile-image')">
                        </div>
                        <div class="pl-3">
                            <h4 class="text--white">{{$doctor->name}}</h4>
                        </div>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="list-left">@lang('Name')</span>
                            <span class="list-right font-weight-bold">{{$doctor->name}}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="list-left">@lang('Username')</span>
                            <span  class="font-weight-bold list-right">{{$doctor->username}}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="list-left">@lang('Email')</span>
                            <span  class="font-weight-bold list-right">{{$doctor->email}}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="list-left">@lang('Mobile')</span>
                            <span  class="font-weight-bold list-right">{{$doctor->mobile}}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="list-left">@lang('Sector')</span>
                            <span  class="font-weight-bold list-right">{{$doctor->sector->name}}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="list-left">@lang('Location')</span>
                            <span  class="font-weight-bold list-right">{{$doctor->location->name}}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="list-left">@lang('Address')</span>
                            <span  class="font-weight-bold list-right">{{$doctor->address}}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="list-left">@lang('Qualification')</span>
                            <span  class="font-weight-bold list-right">{{$doctor->qualification}}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="list-left">@lang('Fees')</span>
                            <span  class="font-weight-bold list-right">{{$doctor->fees}} {{$general->cur_text}}</span>
                        </li>

                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-50 border-bottom pb-2">@lang('Change Password')</h5>

                    <form action="{{ route('doctor.password.update') }}" method="POST">
                        @csrf

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">@lang('Password')</label>
                            <div class="col-lg-9">

                                <input class="form-control" type="password" name="old_password">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">@lang('New password')</label>
                            <div class="col-lg-9">
                                <input class="form-control" type="password" name="password">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">@lang('Confirm password')</label>
                            <div class="col-lg-9">
                                <input class="form-control" type="password" name="password_confirmation">
                            </div>
                        </div>


                        <div class="form-group row">

                            <label class="col-lg-3 col-form-label form-control-label"></label>
                            <div class="col-lg-9">
                            <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Save Changes')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{route('doctor.profile')}}" class="btn btn-sm btn--primary box--shadow1 text--small" ><i class="fa fa-user"></i>@lang('Profile Setting')</a>
@endpush

@push('style')
    <style>
        .list-left{
            width: 40%;
        }
        .list-right{
            width: calc(100% - 40%);
        }
    </style>
@endpush
