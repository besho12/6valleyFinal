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
        <form action="{{ route('admin.business-settings.specialads') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="name" class="title-color">{{translate('video_title')}}</label>
                        <input type="text" name="video_title" class="form-control" id="video_title"
                               placeholder="{{translate('enter_video_title')}}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="name" class="title-color">{{translate('video_points')}}</label>
                        <input type="text" name="video_points" class="form-control" id="video_points"
                               placeholder="{{translate('enter_video_points')}}" required>
                    </div>
                    <div class="col-md-12 mt-2">
                        <input type="hidden" id="id" name="id">
                        <label for="video_url" class="title-color">{{ translate('video_url')}}</label>
                        <input type="url" name="video_url" class="form-control" id="video_url"
                               placeholder="{{translate('enter_video_url')}}" required>
                    </div>
                    <div class="col-md-12 mt-2">
                        <label for="description" class="title-color">{{translate('description')}}</label>
                        <textarea type="text" name="video_description" class="form-control" id="video_description"
                               placeholder="{{translate('enter_video_description')}}" required></textarea>
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
