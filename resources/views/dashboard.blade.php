@extends('admin.layout.app')
@section('content')
    <main class="app-main">
       
    @include('components.alert')
</div>
        <div class="container-fluid">
            <div class="row">
                <!-- Summary Cards -->
                <div class="col-md-3">
                    <div class="dashboard-card p-4">
                        <div class="d-flex align-items-center">
                            <div class="card-icon me-3">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">TOTAL USERS</h6>
                                <h3 class="mb-0">1,254</h3>
                                <small class="text-success">+12% from last month</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="dashboard-card p-4">
                        <div class="d-flex align-items-center">
                            <div class="card-icon me-3">
                                <i class="bi bi-cart-check"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">TOTAL ORDERS</h6>
                                <h3 class="mb-0">542</h3>
                                <small class="text-success">+8% from last month</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="dashboard-card p-4">
                        <div class="d-flex align-items-center">
                            <div class="card-icon me-3">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">REVENUE</h6>
                                <h3 class="mb-0">$12,345</h3>
                                <small class="text-success">+15% from last month</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="dashboard-card p-4">
                        <div class="d-flex align-items-center">
                            <div class="card-icon me-3">
                                <i class="bi bi-activity"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">ACTIVITY</h6>
                                <h3 class="mb-0">86%</h3>
                                <small class="text-danger">-2% from last month</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-8">
                    <div class="dashboard-card p-4">
                        <h5 class="card-title">Sales Overview</h5>
                        <div class="chart-placeholder" style="height: 300px;">
                            <!-- Chart would go here -->
                            <div class="d-flex justify-content-center align-items-center h-100 text-muted">
                                <i class="bi bi-bar-chart-line" style="font-size: 3rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="dashboard-card p-4">
                        <h5 class="card-title">Recent Activities</h5>
                        <div class="activity-list">
                            <div class="activity-item d-flex mb-3">
                                <div class="avatar me-3">
                                    <i class="bi bi-person-circle" style="font-size: 1.5rem;"></i>
                                </div>
                                <div>
                                    <p class="mb-0"><strong>John Doe</strong> placed a new order</p>
                                    <small class="text-muted">2 minutes ago</small>
                                </div>
                            </div>
                            <div class="activity-item d-flex mb-3">
                                <div class="avatar me-3">
                                    <i class="bi bi-person-circle" style="font-size: 1.5rem;"></i>
                                </div>
                                <div>
                                    <p class="mb-0"><strong>Jane Smith</strong> updated profile</p>
                                    <small class="text-muted">15 minutes ago</small>
                                </div>
                            </div>
                            <div class="activity-item d-flex mb-3">
                                <div class="avatar me-3">
                                    <i class="bi bi-person-circle" style="font-size: 1.5rem;"></i>
                                </div>
                                <div>
                                    <p class="mb-0"><strong>Mike Johnson</strong> completed payment</p>
                                    <small class="text-muted">1 hour ago</small>
                                </div>
                            </div>
                            <div class="activity-item d-flex mb-3">
                                <div class="avatar me-3">
                                    <i class="bi bi-person-circle" style="font-size: 1.5rem;"></i>
                                </div>
                                <div>
                                    <p class="mb-0"><strong>Sarah Williams</strong> registered</p>
                                    <small class="text-muted">3 hours ago</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
