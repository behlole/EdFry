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
                                <th scope="col">@lang('Title')</th>
                                <th scope="col">@lang('Icon')</th>
                                <th scope="col">@lang('Url')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach ($social_icons as $item)
                            <tr>
                                <td data-label="@lang('SL')">{{ $loop->index+1 }}</td>
                                <td data-label="@lang('Title')">{{ Str::limit(($item->title),20) }}</td>
                                <td data-label="@lang('Icon')">@php echo $item->icon @endphp</td>
                                <td data-label="@lang('Url')">{{ Str::limit((strip_tags($item->url)),20) }}</td>
                                <td data-label="@lang('Action')">
                                    <a href="#" class="icon-btn  updateBtn" data-route="{{ route('doctor.social.icon.update',$item->id) }}" data-resourse="{{$item}}" data-toggle="modal" data-target="#updateBtn"><i class="la la-pencil-alt"></i></a>
                                    <a href="#" class="icon-btn btn--danger removeBtn" data-route="{{ route('doctor.social.icon.remove',$item->id) }}" data-toggle="tooltip" data-original-title="Remove"><i class="la la-trash"></i></a>
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
                <h5 class="modal-title"> @lang('Add New Education Details')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('doctor.social.icon.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('Title')</label>
                        <input type="text" name="title" placeholder="@lang('Example : Facebook')" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>@lang('Icon')</label>
                        <div class="input-group has_append">
                            <input type="text" class="form-control icon" name="icon" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary iconPicker" data-icon="fas fa-home" role="iconpicker"></button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>@lang('Url')</label>
                        <input type="url" name="url" placeholder="@lang('Example') : https://www.google.com/" class="form-control" required>
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
                        <label>@lang('Title')</label>
                        <input type="text" id="title" name="title" placeholder="@lang('Example : Facebook')" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>@lang('Icon')</label>
                        <div class="input-group has_append">
                            <input type="text" id="icon-value" class="form-control icon" name="icon" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary iconPicker" data-icon="fas fa-home" role="iconpicker"></button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>@lang('Url')</label>
                        <input type="url" id="url" name="url" placeholder="@lang('Example') : https://www.google.com/" class="form-control" required>
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

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/doctor/css/bootstrap-iconpicker.min.css') }}">
@endpush
@push('script-lib')
    <script src="{{ asset('assets/doctor/js/bootstrap-iconpicker.bundle.min.js') }}"></script>
@endpush

@push('script')
    <script>

        (function ($) {
            'use strict';

        $('.addBtn').on('click', function () {
            var modal = $('#addModal');
            modal.modal('show');
        });

        $('.updateBtn').on('click', function () {
            var modal = $('#updateBtn');

            var resourse = $(this).data('resourse');
            var route = $(this).data('route');
            $('#title').val(resourse.title);
            $('#url').val(resourse.url);
            $('#icon-value').val(resourse.icon);
            $('.edit-route').attr('action',route);

        });

        $('.removeBtn').on('click', function() {
            var modal = $('#removeModal');
            var route = $(this).data('route');
            modal.modal('show');
            $('.remove-route').attr('action',route);
        });

        $('#addModal').on('shown.bs.modal', function (e) {
            $(document).off('focusin.modal');
        });

        $('#updateBtn').on('shown.bs.modal', function (e) {
            $(document).off('focusin.modal');
        });

        $('.iconPicker').iconpicker({
            align: 'center', // Only in div tag
            arrowClass: 'btn-danger',
            arrowPrevIconClass: 'fas fa-angle-left',
            arrowNextIconClass: 'fas fa-angle-right',
            cols: 10,
            footer: true,
            header: true,
            icon: 'fas fa-bomb',
            iconset: 'fontawesome5',
            labelHeader: '{0} of {1} pages',
            labelFooter: '{0} - {1} of {2} icons',
            placement: 'bottom', // Only in button tag
            rows: 5,
            search: false,
            searchText: 'Search icon',
            selectedClass: 'btn-success',
            unselectedClass: ''

            }).on('change', function (e) {
                $(this).parent().siblings('.icon').val(`<i class="${e.icon}"></i>`);
            });
        })(jQuery);
    </script>
@endpush
