@extends('doctor.layouts.app')

@section('panel')
<div class="row mb-none-30 justify-content-center">
    <div class="col-lg-12 col-md-12 mb-30">
        <div class="card mt-5">
            <div class="card-header">
                <h5>@lang('Overviews')</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive table-responsive--lg">
                    <table class="default-data-table table ">
                        <thead>
                            <tr>
                                <th scope="col">@lang('SL')</th>
                                <th scope="col">@lang('Institution')</th>
                                <th scope="col">@lang('Discipline')</th>
                                <th scope="col">@lang('Period')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach ($experience_details as $item)
                            <tr>
                                <td data-label="@lang('SL')">{{ $loop->index+1 }}</td>
                                <td data-label="@lang('Institution')">{{ Str::limit(($item->institution),20) }}</td>
                                <td data-label="@lang('Discipline')">{{ Str::limit((strip_tags($item->discipline)),20) }}</td>
                                <td data-label="@lang('Period')">{{ Str::limit((strip_tags($item->period)),20) }}</td>
                                <td data-label="@lang('Action')">
                                    <a href="#" class="icon-btn  updateBtn" data-route="{{ route('doctor.experience.update',$item->id) }}" data-resourse="{{$item}}" data-toggle="modal" data-target="#updateBtn"><i class="la la-pencil-alt"></i></a>
                                    <a href="#" class="icon-btn btn--danger removeBtn" data-route="{{ route('doctor.experience.remove',$item->id) }}" data-toggle="tooltip" data-original-title="Remove"><i class="la la-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add METHOD MODAL --}}
<div id="addModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Add New Education Details')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('doctor.experience.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('Institution Name')</label>
                        <input type="text" name="institution" placeholder="@lang('Example : Glowing Smiles Family Dental Clinic')" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>@lang('Discipline')</label>
                        <input type="text" name="discipline" placeholder="@lang('Example : Medical Officer')" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>@lang('Period')</label>
                        <input type="text" name="period" placeholder="@lang('Example : 2010 - Present (5 years)')" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--primary">@lang('Save')</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- Update METHOD MODAL --}}
<div id="updateBtn" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> @lang('Update Education Details')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" class="edit-route" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('Institution Name')</label>
                        <input type="text" name="institution" placeholder="@lang('Example : Glowing Smiles Family Dental Clinic')" class="form-control institution" required>
                    </div>
                    <div class="form-group">
                        <label>@lang('Discipline')</label>
                        <input type="text" name="discipline" placeholder="@lang('Example : Medical Officer')" class="form-control discipline" required>
                    </div>
                    <div class="form-group">
                        <label>@lang('Period')</label>
                        <input type="text" name="period" placeholder="@lang('Example : 2010 - Present (5 years)')" class="form-control period" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--primary">@lang('Update')</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- REMOVE METHOD MODAL --}}
<div id="removeModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirmation')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST" class="remove-route">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p class="font-weight-bold">@lang('Are you sure you want to delete this item? Once deleted can not be undone this action.')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--danger">@lang('Remove')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('breadcrumb-plugins')
    <a href="#0" class="btn btn-sm btn--primary box--shadow1 text--small addBtn"><i class="fa fa-fw fa-plus"></i>@lang('Add New')</a>
@endpush
@endsection

@push('script')
    <script>
        'use strict';

        (function ($) {
            $('.addBtn').on('click', function () {
                var modal = $('#addModal');
                modal.modal('show');
            });

            $('.updateBtn').on('click', function () {
                var modal = $('#updateBtn');

                var resourse = $(this).data('resourse');
                var route = $(this).data('route');
                $('.institution').val(resourse.institution);
                $('.discipline').val(resourse.discipline);
                $('.period').val(resourse.period);
                $('.edit-route').attr('action',route);

            });
            
            $('.removeBtn').on('click', function() {
                var modal = $('#removeModal');
                var route = $(this).data('route');
                modal.modal('show');
                $('.remove-route').attr('action',route);
            });
        })(jQuery);
    </script>
@endpush
