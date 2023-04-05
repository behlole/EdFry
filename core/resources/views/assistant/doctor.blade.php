@extends('assistant.layouts.app')

@section('panel')

    <div class="row">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive table-responsive--sm">
                        <table class="default-data-table table">
                            <thead>
                                <tr>
                                    <th scope="col">@lang('Doctor')</th>
                                    <th scope="col">@lang('Email')</th>
                                    <th scope="col">@lang('Joined At')</th>
                                    <th scope="col">@lang('Appointment')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($doctors as $item)
                            <tr>
                                <td data-label="@lang('Doctor')">
                                    <div class="user">
                                        <div class="thumb"><img src="{{ getImage('assets/doctor/images/profile/'. $item->image)}}" alt="@lang('image')"></div>
                                        <span class="name">{{$item->name}}</span>
                                    </div>
                                </td>
                                <td data-label="@lang('Email')">{{ $item->email }}</td>
                                <td data-label="@lang('Joined At')">{{ showDateTime($item->created_at) }}</td>
                                <td data-label="@lang('Action')">
                                    <a href="{{ route('assistant.doctor.appointment.view',$item->id) }}" class="icon-btn bg--primary ml-1" data-toggle="tooltip" data-placement="top" title="@lang('New Appointment')"><i class="fas fa-handshake"></i></a>

                                    <a href="{{ route('assistant.doctor.appointment.done',$item->id) }}" class="icon-btn bg--success ml-1" data-toggle="tooltip" data-placement="top" title="@lang('Done Appointment')"><i class="fas fa-check-square"></i></a>

                                    <a href="{{ route('assistant.doctor.appointment.trashed',$item->id) }}" class="icon-btn bg--warning ml-1" data-toggle="tooltip" data-placement="top" title="@lang('Trashed Appointment')"><i class="la la-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
            </div><!-- card end -->
        </div>
    </div>
@endsection
