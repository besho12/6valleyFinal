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

        <div class="card mt-3">
            <div class="card-body">
                <form action="{{ route('admin.business-settings.updatespecialads') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="name" class="title-color">{{translate('video_title')}}</label>
                                <input type="text" name="title" class="form-control" id="video_title"
                                    placeholder="{{translate('enter_video_title')}}" required value="{{ $single['title'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="title-color">{{translate('video_points')}}</label>
                                <input type="text" name="points" class="form-control" id="video_points"
                                    placeholder="{{translate('enter_video_points')}}" required value="{{ $single['points'] ?? '' }}">
                            </div>
                            <div class="col-md-12 mt-2">
                                <input type="hidden" id="id" name="id">
                                <label for="video_url" class="title-color">{{ translate('video_url')}}</label>
                                <input type="url" name="url" class="form-control" id="video_url"
                                    placeholder="{{translate('enter_video_url')}}" required value="{{ $single['url'] ?? '' }}">
                            </div>
                            <div class="col-md-12 mt-2">
                                <label for="description" class="title-color">{{translate('description')}}</label>
                                <textarea type="text" name="description" class="form-control" id="video_description"
                                    placeholder="{{translate('enter_video_description')}}" required> {{ $single['description'] ?? '' }}</textarea>
                            </div>
                            <div class="col-md-12">
                                <input type="hidden" id="id" name="id" value="{{ $single['id'] ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-10 justify-content-end flex-wrap">
                        <button type="submit" id="actionBtn" class="btn btn--primary px-4">{{ translate('save')}}</button>
                        {{-- <a href="{{ route('admin.business-settings.specialads') }}" class="btn btn-outline--primary">{{ translate('view_all')}}</a> --}}
                        <a id="update" class="btn btn--primary px-4 d--none">{{ translate('update')}}</a>
                    </div>
                </form>
            </div>
        </div>


        <form action="{{ route('admin.business-settings.deletespecialads') }}" method="post" class="delete-specialads-form-submit">
            @csrf
            <input name="id" hidden="" id="specialadsdeleteid">
        </form>

        <div class="card mt-3">
            <div class="card-body">
                <div class="table-responsive datatable-custom">
                    <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 text-start">
                        <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{translate('video_title')}}</th>
                                <th>{{translate('video_points')}}</th>
                                <th>{{translate('video_url')}}</th>
                                <th>{{translate('description')}}</th>                  
                                <th class="text-center">{{translate('action')}}</th>                  
                            </tr>
                        </thead>

                        <tbody>
                        @foreach($specialads as $key=>$order)

                            <tr class="status-{{$order['order_status']}} class-all">
                                <td>{{ $order['title'] }}</td>
                                <td>{{ $order['points'] }}</td>
                                <td>{{ $order['url'] }}</td>
                                <td>{{ $order['description'] }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-10">
                                        <a class="btn btn-outline-info btn-sm square-btn "
                                           title="{{ translate('edit') }}"
                                           href="{{ route('admin.business-settings.specialads') }}/{{ $order['id'] }}">
                                            <i class="tio-edit"></i>
                                        </a>
                                        <a class="btn btn-outline-danger btn-sm square-btn delete-specialads"
                                           title="{{ translate('delete') }}"                                           
                                           id="{{ $order['id'] }}">
                                            <i class="tio-delete"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive mt-4">
                    <div class="d-flex justify-content-lg-end">
                        {{-- {!! $specialads->links() !!} --}}
                    </div>
                </div>
                
                @if(count($specialads) == 0)
                    @include('layouts.back-end._empty-state',['text'=>'no_order_found'],['image'=>'default'])
                @endif
            </div>
        </div>

    </div>
@endsection

@push('script_2')
<script>
    console.log('Second script');
    $(document).ready(function(){
        $('.delete-specialads').on('click', function () {
            let specialadsId = $(this).attr("id");
            Swal.fire({
                title: 'Are you sure to delete this ?',
                text: 'You will not be able to revert this',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {           
                    $('#specialadsdeleteid').val(specialadsId);
                    $('.delete-specialads-form-submit').submit();            
                }
            })
        });
    })
</script>
@endpush

@push('script_2')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/business-setting/business-setting.js')}}"></script>
@endpush

