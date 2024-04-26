@extends('admin.layout.default')
@section('content')
    <div class="row justify-content-between align-items-center mb-3">
        <div class="col-12">
            <h5 class="pages-title fs-2">
                {{ trans('labels.add_new') }}
            </h5>
            @include('admin.layout.breadcrumb')
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-lg-0">
            <div class="card border-0 box-shadow">
                <div class="card-body">
                    <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label class="form-label">
                                    {{ trans('labels.image') }} (250 x 250) 
                                </label>
                                <input type="file" class="form-control" name="profile">
                                @error('profile')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="name">
                                        {{ trans('labels.name') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control"
                                        placeholder="{{ trans('labels.name') }}" name="name" id="name" 
                                        value="{{old('name')}}" required>
                                    @error('name') <span class="text-danger" id="name">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="email">
                                        {{ trans('labels.email') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control"
                                        placeholder="{{ trans('labels.email') }}" name="email" id="email" 
                                        value="{{old('email')}}" required>
                                    @error('email') <span class="text-danger" id="email">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="mobile">
                                        {{ trans('labels.mobile') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="mobile" class="form-control"
                                        placeholder="{{ trans('labels.mobile') }}" name="mobile" id="mobile" 
                                        value="{{old('mobile')}}" required>
                                    @error('mobile') <span class="text-danger" id="mobile">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="password">
                                        {{ trans('labels.password') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" class="form-control"
                                        placeholder="{{ trans('labels.password') }}" name="password" id="password"
                                        value="{{old('password')}}" required>
                                    @error('password') <span class="text-danger" id="password">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="mobile">
                                        {{ trans('labels.password_confirmation') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="password_confirmation" class="form-control"
                                    placeholder="{{ trans('labels.password_confirmation') }}" name="password_confirmation" id="password_confirmation" 
                                    value="{{old('password_confirmation')}}" required>
                                    @error('password_confirmation') <span class="text-danger" id="password_confirmation">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <select name="Job_type_id" class="form-select" required>
                                    @foreach($JobTypeEnums as $JobTypeEnum)
                                    <option value="{{$JobTypeEnum['id']}}" @selected($JobTypeEnum['id'] == old('Job_type_id'))>
                                        {{$JobTypeEnum['name']}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group text-end">
                            <a type="button" class="btn btn-danger btn-cancel m-1" href="{{ route('employees.index') }}">
                                <i class="ft-x"></i> {{ trans('labels.cancel') }}
                            </a>
                            <button class="btn btn-success btn-succes m-1">
                                {{ trans('labels.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection