@extends('layouts.back-end.app')

@section('title', 'Special ADS Setup')

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2 text-capitalize">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/announcement.png')}}" alt="">
                {{translate('special_ads_setup')}}
            </h2>
        </div>
        <form action="{{ route('admin.business-settings.announcement') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <label for="name" class="title-color">{{translate('name')}}</label>
                        <select class="form-control w-100" name="name" id="name" required>
                            <option value="">{{'---'.translate('select').'---'}}</option>
                            <option value="instagram">{{translate('instagram')}}</option>
                            <option value="facebook">{{translate('facebook')}}</option>
                            <option value="twitter">{{translate('twitter')}}</option>
                            <option value="linkedin">{{translate('linkedIn')}}</option>
                            <option value="pinterest">{{translate('pinterest')}}</option>
                            <option value="google-plus">{{translate('google_plus')}}</option>
                        </select>
                    </div>
                    <div class="col-md-12 mt-2">
                        <input type="hidden" id="id" name="id">
                        <label for="link" class="title-color">{{ translate('social_media_link')}}</label>
                        <input type="url" name="link" class="form-control" id="link"
                               placeholder="{{translate('enter_Social_Media_Link')}}" required>
                    </div>
                    <div class="col-md-12">
                        <input type="hidden" id="id">
                    </div>
                </div>
            </div>
            <div class="d-flex gap-10 justify-content-end flex-wrap">
                <button type="submit" id="actionBtn" class="btn btn--primary px-4">{{ translate('save')}}</button>
                <a id="update" class="btn btn--primary px-4 d--none">{{ translate('update')}}</a>
            </div>
        </form>
    </div>
@endsection

@push('script_2')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/business-setting/business-setting.js')}}"></script>
@endpush
