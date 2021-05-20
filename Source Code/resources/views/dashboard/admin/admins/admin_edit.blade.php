@extends('layouts.admin-dashboard')

@section('title')
Admin | Edit {{ $admin->name}}
@endsection

@section("sub_header")
<div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
  <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
    <!--begin::Details-->
    <div class="d-flex align-items-center flex-wrap mr-2">
      <!--begin::Title-->
      <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Edit Admin</h5>
      <!--end::Title-->
      <!--begin::Separator-->
      <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-5 bg-gray-200"></div>
      <!--end::Separator-->
      <!--begin::Search Form-->
      <div class="d-flex align-items-center" id="kt_subheader_search">
        <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">{{ $admin->name }}</span>
      </div>
      <!--end::Search Form-->
    </div>
    <!--end::Details-->
    <!--begin::Toolbar-->
    <div class="d-flex align-items-center">
      <!--begin::Button-->
      <a href="{{ route('admins.index') }}" class="btn btn-default font-weight-bold btn-sm px-3 font-size-base">Back</a>
      <!--end::Button-->
      <!--begin::Dropdown-->
      <div class="btn-group ml-2">
        <button type="button" class="btn btn-primary font-weight-bold btn-sm px-3 font-size-base" id="save-changes">Save
          Changes</button>
      </div>
      <!--end::Dropdown-->
    </div>
    <!--end::Toolbar-->
  </div>
</div>
@endsection


@section('page_css')
<link href="{{asset('assets/css/pages/wizard/wizard-4.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<!--begin::Card-->
<div class="card card-custom">
  <div class="card-body p-0">
    <!--begin::Wizard-->
    <div class="wizard wizard-4" id="kt_wizard" data-wizard-state="step-first" data-wizard-clickable="true">
      <!--begin::Wizard Body-->
      <div class="row justify-content-center my-10 px-8 my-lg-15 px-lg-10">
        <div class="col-xl-12 col-xxl-7">
          <!--begin::Form-->

          <form action="{{ route('admins.update',$admin->id) }}" method="POST" class="form" id="kt_form"
            enctype="multipart/form-data">
            @csrf
            @method('put')
            <!--begin::Create Admin Form-->
            <div data-wizard-type="step-content" data-wizard-state="current">
              <h3 class="mb-10 font-weight-bold text-dark">Edit Admin ({{$admin->name}}):</h3>
              <div class="row">
                <div class="col-xl-12">
                  <div class="form-group row">
                    <label class="col-xl-3 col-lg-3 col-form-label text-left">Avatar</label>

                    <div class="col-lg-9 col-xl-9">
                      <div class="image-input image-input-outline" id="kt_user_add_avatar">
                        <div class="image-input-wrapper"
                          style="background-image: url({{asset ('admin/profile_images/'.$admin->image)}})">
                        </div>
                        <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                          data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                          <i class="fa fa-pen icon-sm text-muted"></i>
                          <input type="file" name="profile_avatar" accept=".png, .jpg, .jpeg" />
                          {{-- <input type="hidden" name="profile_avatar_remove" /> --}}
                        </label>
                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                          data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                          <i class="ki ki-bold-close icon-xs text-muted"></i>
                        </span>
                      </div>
                      @error('profile_avatar')
                      <div class="invalid-feedback image-input image-input-outline">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-xl-3 col-lg-3 col-form-label">Full Name</label>
                    <div class="col-lg-9 col-xl-9">
                      <input class="form-control form-control-lg  {{ $errors->has('name') ? 'is-invalid' :'' }}"
                        name="name" type="text" value="{{ $admin->name }}" placeholder="Full Name" />
                      @error('name')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-xl-3 col-lg-3 col-form-label">Email</label>
                    <div class="col-lg-9 col-xl-9">
                      <div class="input-group input-group-lg">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="la la-at"></i>
                          </span>
                        </div>
                        <input type="text"
                          class="form-control form-control-lg {{ $errors->has('email') ? 'is-invalid' :'' }}"
                          name="email" value="{{$admin->email }}" placeholder="Email Address" />
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-xl-3 col-lg-3 col-form-label">New Password</label>
                    <div class="col-lg-4 col-xl-4 px-0 mx-4">
                      <input class="form-control form-control-lg  {{ $errors->has('password') ? 'is-invalid' :'' }}"
                        name="password" type="password" placeholder="New Password" />
                      @error('password')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="col-lg-4 col-xl-4 px-0 mx-4">
                      <input class="form-control form-control-lg  {{ $errors->has('password') ? 'is-invalid' :'' }}"
                        name="password_confirmation" type="password" placeholder="Confirm password" />
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-xl-3 col-lg-3 col-form-label">Admin Role</label>
                <div class="col-xl-9 col-lg-9 col-form-label">
                  <div class="checkbox-inline">
                    <label class="checkbox">
                      <input name="isSuperAdmin" type="checkbox" {{$admin->isSuperAdmin ? 'checked' : ''}}>
                      <span></span>Super Admin</label>
                  </div>
                </div>
              </div>
            </div>
            <!--end::Create Admin Form-->
          </form>
          <!--end::Form-->
        </div>
      </div>
      <!--end::Wizard Body-->
    </div>
  </div>
</div>
<!--end::Card-->
@endsection

@section('page_scripts')
<script src="{{ asset('assets/js/pages/custom/user/add-user.js')}}"></script>
<script>
  var form = document.getElementById("kt_form");

document.getElementById("save-changes").addEventListener("click", function () {
  form.submit();
});
</script>
@endsection