@extends('admin.layout.default')
@section('content')
    <div class="row justify-content-between align-items-center mb-3">
        <div class="col-12">
            <h5 class="pages-title fs-2">{{ trans('labels.add_new') }}</h5>
            <div class="d-flex">
                @include('admin.layout.breadcrumb')
            </div>
        </div>

    </div>
    <div class="row mb-7">
        <div class="col-12">
            <div class="card border-0 box-shadow">
                <div class="card-body">
                    <form action="{{ route('colors.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group row">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">{{ trans('labels.name') }}  ar
                                        <span class="text-danger"> * </span>
                                    </label>
                                    <input type="text" class="form-control" name="name[ar]"
                                        value="{{ old('name.ar') }}" placeholder="{{ trans('labels.name') }} ar " required>
                                    @error('name.en')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <label class="form-label">{{ trans('labels.name') }} en 
                                        <span class="text-danger"> * </span>
                                    </label>
                                    <input type="text" class="form-control" name="name[en]"
                                        value="{{ old('name.en') }}" placeholder="{{ trans('labels.name') }} en" required>
                                    @error('name.en')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12">
                                    <label class="form-label">{{ trans('labels.code') }}
                                        <span class="text-danger"> * </span>
                                    </label>
                                    <input type="color" class="form-control" name="code"
                                        value="{{ old('code') }}" placeholder="{{ trans('labels.code') }} ar " required>
                                    @error('code')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group text-end">
                                <a href="{{ URL::to('admin/colors') }}" class="btn btn-danger btn-cancel m-1">
                                    {{ trans('labels.cancel') }}
                                </a>
                                <button class="btn btn-success btn-succes m-1" type="submit" >
                                    {{ trans('labels.save') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection