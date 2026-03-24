@extends('layouts.admin')

@section('title')
    Dashboard
@endsection

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Dashboard</h1>
          </div>
          @if(Auth::user()->role == "super-admin")
          <div class="col-sm-6">
          <button class="btn btn-sm btn-success right" data-toggle="modal" data-target="#deleteModal">Clear Database</button>
          </div>
          @endif
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
      @if(Auth::user()->hasPermission('menu.dashboard'))
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @php
                    $user = Auth::user();
                    $userPermissions = $user->permissions->pluck('slug')->toArray();
                    $isSuperAdmin = $user->role === 'super-admin';
                @endphp
                <div class="col-lg-3 col-6">
                    <a href="{{ $user->hasPermission('menu.admins') ? url('/admins') : 'javascript:void(0);' }}" 
                        @unless($user->hasPermission('menu.admins')) data-toggle="modal" data-target="#accessDeniedModal" @endunless>
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ \App\Models\User::where('role', 'admin')->where('status', 'Active')->count() }}</h3>
                                <p>Total Admins</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-6">
                    <a href="{{ $user->hasPermission('menu.building.admins') ? url('/building-admins') : 'javascript:void(0);' }}"
                        @unless($user->hasPermission('menu.building.admins')) data-toggle="modal" data-target="#accessDeniedModal" @endunless>
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{\App\Models\User::where('role','BA')->count()}}</h3>
                                <p>Total BA</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-6">
                    <a href="{{ $user->hasPermission('menu.customers') ? url('/customers') : 'javascript:void(0);' }}"
                        @unless($user->hasPermission('menu.customers')) data-toggle="modal" data-target="#accessDeniedModal" @endunless>
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{\App\Models\User::where('role','customer')->count()}}</h3>
                                <p>Customers</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-6">
                    <a href="{{ $user->hasPermission('menu.builder') ? route('builder.index') : 'javascript:void(0);' }}"
                        @unless($user->hasPermission('menu.builder')) data-toggle="modal" data-target="#accessDeniedModal" @endunless>
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{\App\Models\Builder::count()}}</h3>
                                <p>Builders</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-6">
                    <a href="{{ $user->hasPermission('menu.buildings') ? url('buildings') : 'javascript:void(0);' }}"
                        @unless($user->hasPermission('menu.buildings')) data-toggle="modal" data-target="#accessDeniedModal" @endunless>
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{\App\Models\Building::count()}}</h3>
                                <p>Buildings</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-6">
                    <a href="{{ $user->hasPermission('menu.city') ? route('city.index') : 'javascript:void(0);' }}"
                        @unless($user->hasPermission('menu.city')) data-toggle="modal" data-target="#accessDeniedModal" @endunless>
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{\App\Models\City::count()}}</h3>
                                <p>City</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-6">
                    <a href="{{ $user->hasPermission('menu.ads') ? route('ads.index') : 'javascript:void(0);' }}"
                        @unless($user->hasPermission('menu.ads')) data-toggle="modal" data-target="#accessDeniedModal" @endunless>
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{\App\Models\Ad::count()}}</h3>
                                <p>Ads</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Access Denied Modal -->
                <div class="modal fade" id="accessDeniedModal" tabindex="-1" role="dialog" aria-labelledby="accessDeniedModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title" id="accessDeniedModalLabel"><i class="fa fa-ban"></i> Access Denied</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <p class="mb-0">You do not have permission to access this section.</p>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
               
                @if(!$isSuperAdmin && empty($userPermissions))
                <div class="col-12">
                    <div class="alert alert-warning mt-4">You do not have any permissions assigned. Please contact the super admin.</div>
                </div>
                @endif
            </div>
        </div>
    </section>
    
    @endif

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Are you sure ?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="text">You are going truncate all the databases</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" id="delete-button">Confirm Delete</button>
      </div>
    </div>
  </div>
</div>

@section('script')

<script>
    
    $(document).ready(function(){
        var id = '';
        var token = "{{csrf_token()}}";

        $(document).on('click','#delete-button',function(){
            var url = "{{url('clear-database')}}";
            $.ajax({
                url : url,
                type: "POST",
                data : {'_token':token},
                success: function(data)
                {
                    window.location.reload();
                }
            });
        });
    });
</script>
@endsection

@endsection