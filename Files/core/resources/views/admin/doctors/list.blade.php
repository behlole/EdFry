@extends('admin.layouts.app')

@section('panel')

    <div class="row">

        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th scope="col">@lang('Doctor')</th>
                                <th scope="col">@lang('Username')</th>
                                <th scope="col">@lang('Email')</th>
                                <th scope="col">@lang('Total Earn')</th>
                                <th scope="col">@lang('Joined At')</th>
                                <th scope="col">@lang('Featured')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($doctors as $doctor)
                                <tr>
                                    <td data-label="@lang('Doctor')">
                                        <div class="user">
                                            <div class="thumb"><img src="{{ getImage(imagePath()['doctor']['path'].'/'. $doctor->image,imagePath()['doctor']['size'])}}" alt="@lang('image')"></div>
                                            <span class="name">{{$doctor->name}}</span>
                                        </div>
                                    </td>
                                    <td data-label="@lang('Username')"><a href="{{ route('admin.doctors.detail', $doctor->id) }}">{{ $doctor->username }}</a></td>
                                    <td data-label="@lang('Email')">{{ $doctor->email }}</td>
                                    <td data-label="@lang('Total Earn')">{{ round($doctor->balance) }} {{ $general->cur_sym }}</td>
                                    <td data-label="@lang('Joined At')">{{ showDateTime($doctor->created_at) }}</td>
                                    <td data-label="@lang('Featured')">
                                        @if ($doctor->featured == 1)
                                            <span class="text--small badge font-weight-normal badge--success">@lang('Yes')</span>
                                        @else
                                            <span class="text--small badge font-weight-normal badge--warning">@lang('No')</span>
                                        @endif
                                    </td>
                                    <td data-label="@lang('Action')">
                                        <a href="{{ route('admin.doctors.detail', $doctor->id) }}" class="icon-btn" data-toggle="tooltip" title="" data-original-title="@lang('Details')">
                                            <i class="las la-desktop text--shadow"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{__($empty_message)}}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ $doctors->links('admin.partials.paginate') }}
                </div>
            </div><!-- card end -->
        </div>


    </div>
@endsection



@push('breadcrumb-plugins')
    <form action="{{ route('admin.doctors.search', $scope ?? str_replace('admin.doctors.', '', request()->route()->getName())) }}" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('Username or email')" value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush
