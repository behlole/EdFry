@extends('doctor.layouts.app')

@section('panel')
<div class="row mb-none-30">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('doctor.about.update')}}" method="post">
                    @csrf
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold">@lang('About You')</label>
                            <textarea name="about" rows="5" placeholder="@lang('Example: Responsible physician with 9 years of experience maximizing patient wellness and facility profitability. Seeking to deliver healthcare excellence at Mercy Hospital. At CRMC, maintained 5-star healthgrades score for 112 reviews and 85% patient success')" required>{{ $doctor->about }}</textarea>
                        </div>
                        <div class="col-md-12 mt-5">
                            <div class="form-group">
                                <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Submit')</button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
