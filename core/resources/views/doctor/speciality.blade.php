@extends('doctor.layouts.app')

@section('panel')
    <div class="row">

        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('doctor.speciality.update') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-12">

                                <div class="payment-method-item">
                                    <div class="payment-method-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card border--primary">
                                                    <h5 class="card-header bg--primary  text-white">@lang('Speciality')
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-light float-right addUserData"><i
                                                                class="la la-fw la-plus"></i>@lang('Add New')
                                                        </button>
                                                    </h5>

                                                    <div class="card-body addedField">
                                                        @if ($speciality != null || !empty($speciality))
                                                            @foreach ($speciality as $item)
                                                                <div class="row align-items-center user-data">
                                                                    <div class="col-xl-11">
                                                                        <div class="form-group">
                                                                            <div class="input-group mb-md-0 mb-4">
                                                                                <div class="col-md-12 p-0">
                                                                                    <label> @lang('Enter Your Special Working Field')</label>
                                                                                    <input type="text" class="form-control" value="{{$item}}" name="speciality[]"  placeholder="@lang('Example : Dental Fillings')" required>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-1 mt-md-0 mt-2 text-right">
                                                                        <span class="input-group-btn">
                                                                            <button class="btn btn--danger btn-lg removeBtn removeBtn--style w-100" type="button">
                                                                                <i class="fa fa-times mr-0"></i>
                                                                            </button>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary btn-block">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


@push('breadcrumb-plugins')
    <a href="" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="la la-fw la-backward"></i> @lang('Go
        Back') </a>
@endpush

@push('style')
<style>
    .removeBtn--style{
        margin-top: 10px;
    }
    @media(max-width: 1199px){
        .removeBtn--style{
            margin-bottom: 15px;
        }
    }
</style>

@endpush

@push('script')
    <script>
        'use strict';

        (function ($) {
            $('.addUserData').on('click', function () {
                var html = `
                <div class="row align-items-center user-data">
                    <div class="col-md-11">
                        <div class="form-group">
                            <div class="input-group mb-md-0 mb-4">
                                <div class="col-md-12 p-0">
                                    <label>@lang('Enter Your Special Working Field')</label>
                                    <input name="speciality[]" class="form-control" type="text" placeholder="@lang('Example : Dental Fillings')" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1 mt-md-0 mt-2 text-right">
                        <span class="input-group-btn">
                            <button class="btn btn--danger btn-lg removeBtn removeBtn--style w-100" type="button">
                                <i class="fa fa-times"></i>
                            </button>
                        </span>
                    </div>
                </div>`;
                $('.addedField').append(html)
            });

            $(document).on('click', '.removeBtn', function () {
                $(this).closest('.user-data').remove();
            });
        })(jQuery);
    </script>
@endpush
