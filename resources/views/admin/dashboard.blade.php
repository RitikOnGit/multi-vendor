@extends('admin.master')

@section('title', 'Dashboard')

@section('content')
<div class="">
    <div class="content-page">
     <div class="container-fluid">
        <div class="row">
            @if(Auth::user() && Auth::user()->role === 'admin')

            <div class="col-md-6 col-lg-3">
                <div class="card card-block card-stretch card-height">
                    <div class="card-body">
                        <div class="top-block d-flex align-items-center justify-content-between">
                            <h5>Products</h5>
                            <span class="badge badge-primary">Listed</span>
                        </div>
                        <h3><span class="counter">{{ $products }}</span></h3>
                        <!-- <div class="d-flex align-items-center justify-content-between mt-1">
                            <p class="mb-0">Total Revenue</p>
                            <span class="text-primary">65%</span>
                        </div> -->
                        <div class="iq-progress-bar bg-primary-light mt-2">
                            <span class="bg-primary iq-progress progress-1" data-percent="100"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card card-block card-stretch card-height">
                    <div class="card-body">
                        <div class="top-block d-flex align-items-center justify-content-between">
                            <h5>Customers</h5>
                            <!-- <span class="badge badge-success">Today</span> -->
                        </div>
                        <h3><span class="counter">{{ $users }}</span></h3>
                        <!-- <div class="d-flex align-items-center justify-content-between mt-1">
                            <p class="mb-0">Total Revenue</p>
                            <span class="text-success">85%</span>
                        </div> -->
                        <div class="iq-progress-bar bg-success-light mt-2">
                            <span class="bg-success iq-progress progress-1" data-percent="100"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card card-block card-stretch card-height">
                    <div class="card-body">
                        <div class="top-block d-flex align-items-center justify-content-between">
                            <h5>Vendors</h5>
                            <!-- <span class="badge badge-info">Weekly</span> -->
                        </div>
                        <h3><span class="counter">{{ $vendors }}</span></h3>
                        <!-- <div class="d-flex align-items-center justify-content-between mt-1">
                            <p class="mb-0">Total Revenue</p>
                            <span class="text-info">55%</span>
                        </div> -->
                        <div class="iq-progress-bar bg-info-light mt-2">
                            <span class="bg-info iq-progress progress-1" data-percent="100"></span>
                        </div>
                    </div>
                </div>
            </div>
            @elseif(Auth::user() && Auth::user()->role === 'vendor')
            <div class="col-md-6 col-lg-3">
                <div class="card card-block card-stretch card-height">
                    <div class="card-body">
                        <div class="top-block d-flex align-items-center justify-content-between">
                            <h5>Sales</h5>
                            <span class="badge badge-warning">Anual</span>
                        </div>
                        <h3>$<span class="counter">25100</span></h3>
                        <div class="d-flex align-items-center justify-content-between mt-1">
                            <p class="mb-0">Total Revenue</p>
                            <span class="text-warning">35%</span>
                        </div>
                        <div class="iq-progress-bar bg-warning-light mt-2">
                            <span class="bg-warning iq-progress progress-1" data-percent="35"></span>
                        </div>
                    </div>
                </div>
            </div>
            @else
                <div class="col-md-12">
                    <div class="alert alert-warning" role="alert">
                        You do not have permission to view this dashboard.
                    </div>
                </div>
            @endif

        </div>
        <!-- Page end  -->
    </div>
      </div>

</div>
@endsection
