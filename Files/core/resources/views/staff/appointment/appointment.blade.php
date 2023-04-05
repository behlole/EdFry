@extends('staff.layouts.app')

@section('panel')

    <div class="row">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive table-responsive--sm">
                        <table class="default-data-table table">
                            <thead>
                            <tr>
                                <th scope="col">@lang('Name')</th>
                                <th scope="col">@lang('Email')</th>
                                <th scope="col">@lang('Booking Date')</th>
                                <th scope="col">@lang('Time Or Serial no')</th>
                                <th scope="col">@lang('Payment Status')</th>
                                <th scope="col">@lang('Doctor')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($appointments as $item)
                            <tr>
                                <td data-label="@lang('Name')">{{ $item->name }}</td>
                                <td data-label="@lang('Email')">{{ $item->email }}</td>
                                <td data-label="@lang('Booking Date')">{{ $item->booking_date }}</td>
                                <td data-label="@lang('Time Or Serial no')">{{ $item->time_serial }}</td>
                                @if ($item->p_status == 0)
                                    <td data-label="@lang('Payment Status')"><span class="text--small badge font-weight-normal badge--danger">@lang('Pay in cash')</span></td>
                                @elseif ($item->p_status == 1)
                                    <td data-label="@lang('Payment Status')"><span class="text--small badge font-weight-normal badge--success">@lang('Paid')</span></td>
                                @elseif ($item->p_status == 2)
                                    <td data-label="@lang('Payment Status')"><span class="text--small badge font-weight-normal badge--warning">@lang('Pending')</span></td>
                                @endif
                                <td data-label="@lang('Doctor')">{{ $item->doctor->name }}</td>
                                <td data-label="@lang('Action')">
                                    <a href="" data-route="{{ route('staff.appointments.view',$item->id) }}" data-resourse="{{ $item }}" data-toggle="modal" data-target="#viewModal"
                                        class="icon-btn bg--success ml-1 viewBtn">
                                    <i class="la la-eye"></i>
                                    </a>
                                    @if ($item->p_status == 0)
                                        <a href="" data-route="{{ route('staff.appointments.remove',$item->id) }}" data-toggle="modal" data-target="#removeModal"
                                            class="icon-btn bg--danger ml-1 removeBtn">
                                        <i class="la la-trash"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ $appointments->links('staff.partials.paginate') }}
                </div>
            </div><!-- card end -->
        </div>
    </div>

    {{-- View MODAL --}}
    <div id="viewModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Appointment')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <b>@lang('Name') :</b>
                        <p id="name_show" class="mb-2"></p>
                        <b>@lang('E-mail') :</b>
                        <p id="email_show" class="mb-2"></p>
                        <b>@lang('Contact No') :</b>
                        <p id="contact_show" class="mb-2"></p>
                        <b>@lang('Booking Date') :</b>
                        <p id="date_show" class="mb-2"></p>
                        <b>@lang('Booking Time or Serial no'):</b>
                        <p id="time_show" class="mb-2"></p>
                        <b>@lang('Age') :</b>
                        <p id="age_show" class="mb-2"></p>
                        <b>@lang('Disease') :</b>
                        <p id="disease_show" class="mb-2"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <form action="" class="edit-route" method="post">
                        @csrf
                        <input type="hidden" name="complete" value="0">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Cancle')</button>
                        <button type="submit" class="btn btn--warning serviceDoneBtn">@lang('Service Done')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- Remove MODAL --}}
    <div id="removeModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Remove Appointment')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>@lang('Are you sure You want to remove this appointment?')</h5>
                </div>
                <div class="modal-footer">
                    <form action="" class="remove-route" method="post">
                        @csrf
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Cancle')</button>
                        <button type="submit" class="btn btn--danger">@lang('Remove')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        'use strict';

        (function ($) {
            $('.viewBtn').on('click', function() {
                var modal = $('#viewModal');
                var resourse = $(this).data('resourse');
                var route = $(this).data('route');

                modal.find('.serviceDoneBtn').hide();
                modal.find('input[name=complete]').val(0);

                $('.edit-route').attr('action',route);

                $('#name_show').text(resourse.name);
                $('#email_show').text(resourse.email);
                $('#contact_show').text(resourse.mobile);
                $('#date_show').text(resourse.booking_date);
                $('#time_show').text(resourse.time_serial);
                $('#age_show').text(resourse.age);
                $('#disease_show').text(resourse.disease);

                if(resourse.is_complete) {

                    modal.find('.serviceDoneBtn').hide();

                }
                if(!resourse.is_complete) {

                    modal.find('.serviceDoneBtn').show();

                }

                modal.find('.serviceDoneBtn').on('click', function() {
                    modal.find('input[name=complete]').val(1);
                });
            });


            $('.removeBtn').on('click', function() {
                var modal = $('#removeModal');
                var route = $(this).data('route');

                $('.remove-route').attr('action',route);

            });
        })(jQuery);
    </script>
@endpush
