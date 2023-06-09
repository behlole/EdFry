@extends('user.layouts.app')
@section('panel')
     <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
    <div class="row mb-none-30">
        <div class="col-xl-8 col-lg-6 col-md-6 mb-30">
            <div class="row">
                <div class="col-lg-12 col-md-12 mb-30">
                    <div class="card b-radius--5 overflow-hidden">
                        <div class="card-body p-0">
                            <div class="d-flex p-3 bg--primary align-items-center">
                                <div class="avatar avatar--lg">
                                    <img
                                        src="{{ getImage(imagePath()['assistant']['path'].'/'. $user->image,imagePath()['assistant']['size'])}}"
                                        alt="@lang('profile-image')">
                                </div>
                                <div class="pl-3">
                                    <h4 class="text--white">{{$user->name}}</h4>
                                </div>
                            </div>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="list-left">@lang('First Name')</span>
                                    <!--<span-->
                                    <!--    class="font-weight-bold list-right">{{$user->firstname.' '.$user->lastname}}</span>-->
                                      <input  class="font-weight-bold list-right" name="firstname" value="{{$user->firstname}}">
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="list-left">@lang('Last Name')</span>
                                    <!--<span-->
                                    <!--    class="font-weight-bold list-right">{{$user->firstname.' '.$user->lastname}}</span>-->
                                      <input  class="font-weight-bold list-right" name="lastname" value="{{$user->lastname}}">
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="list-left">@lang('Username')</span>
                                    <input  class="font-weight-bold list-right" name="username" value="{{$user->username}}">
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="list-left">@lang('Email')</span>
                                    <span class="font-weight-bold list-right">{{$user->email}}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="list-left">@lang('Mobile')</span>
                                     <input  class="font-weight-bold list-right" name="mobile" value="{{$user->mobile}}">
                                  
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-6 col-md-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-50 border-bottom pb-2">@lang('Profile Information')</h5>

               

                        <div class="row">

                            <div class="col-md-12">

                                <div class="form-group">
                                    <div class="image-upload">
                                        <div class="thumb">
                                            <div class="avatar-preview">
                                                <div class="profilePicPreview"
                                                     style="background-image: url({{ getImage(imagePath()['assistant']['path'].'/'. $user->image,imagePath()['assistant']['size']) }})">
                                                    <button type="button" class="remove-image"><i
                                                            class="fa fa-times"></i></button>
                                                </div>
                                            </div>
                                            <div class="avatar-edit">
                                                <input type="file" class="profilePicUpload" name="image"
                                                       id="profilePicUpload1" accept=".png, .jpg, .jpeg">
                                                <label for="profilePicUpload1"
                                                       class="bg--success">@lang('Upload Profile Images')</label>
                                                <small class="mt-2 text-facebook">@lang('Supported files'):
                                                    <b>@lang('jpeg, jpg')</b>. @lang('Image will be resized into') {{imagePath()['assistant']['size']}} @lang('px')
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit"
                                    class="btn btn--primary btn-block btn-lg">@lang('Save Changes')</button>
                        </div>
                    
                </div>
            </div>
        </div>
    </div>
    </form>
@endsection

@push('breadcrumb-plugins')
    <a href="{{route('user.password')}}" class="btn btn-sm btn--primary box--shadow1 text--small"><i
            class="fa fa-key"></i>@lang('Password Setting')</a>
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
