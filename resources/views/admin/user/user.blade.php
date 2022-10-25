@extends('admin.layouts.admin_base')
@section('content')
 <div class="main-right-wrapper">
                    <div class="dashboard-container">
                        <div class="user-management-header">
                            <div class="alert alert-success" role="alert">
                                <div class="alert-text">
                                    <span>
                                        <img src="./assets/images/svg/check.svg" alt="check icon" />
                                    </span> User has been Deactivated.
                                </div>
                                <div class="text-end">
                                    <img src="./assets/images/svg/alert-cross.svg" alt="alert icon" />
                                </div>
                            </div>
                            <div class="btn-group user-btn-group ms-auto">
                                <span>
                                    <img src="./assets/images/svg/user-icon.svg" alt="user-logo" /></span>
                                <button type="button" class="btn btn-secondary dropdown-toggle dropdown-bg-none" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><button class="dropdown-item" type="button"  data-bs-toggle="modal" data-bs-target="#modalLogout">Log Out</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <h1 class="section-title">All Users (<span>245</span>)</h1>
                        <!-- For no Users found -->
                        <div class="no-users d-none">No Users Yet</div>
                    </div>
                    <!--  Table start from here  -->
                    <div class="table-container table-container-user">
                        <div class="table-head table-head-user">
                            <div class="table-row table-row-user">
                                <div class="th">Name</div>
                                <div class="th">Phone</div>
                                <div class="th">Email Address</div>
                                <div class="th">Location</div>
                                <div class="th">User Type</div>
                                <div class="th">Status</div>
                                <div class="th">Action</div>
                            </div>
                        </div>
                        <div class="table-body table-body-user">
                            <!--  repeat this div  -->
                            <div class="table-row">
                                <div class="td">
                                    <div class="user-title">
                                        <div class="user-img">
                                            <div>
                                                <img src="./assets/images/svg/user-small-img.png" alt="user image" data-bs-toggle="modal" data-bs-target="#modalUserDetails" />
                                            </div>
                                        </div>
                                        <div class="user-title-info">
                                            <h5>Jeff Gregory</h5>
                                            <p>Joined: Oct 28, 2021</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="td">+1 049-948 (0383)</div>
                                <div class="td">test@gmail.com</div>
                                <div class="td">Kentucky, 60005</div>
                                <div class="td">Parent To Be</div>
                                <div class="td">Active</div>
                                <div class="td">
                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        <img src="assets/images/svg/3-dots-horizontal.svg" alt="" class="3-dots-icon"
                                            id="inactive-icon">
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="#">Send Message</a></li>
                                        <li><a class="dropdown-item" href="#">Activate User</a></li>
                                        <li><a class="dropdown-item" href="#" type="button"  data-bs-toggle="modal" data-bs-target="#modalDeactivated">Deactivate User</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="table-row">
                                <div class="td">
                                    <div class="user-title">
                                        <div class="user-img">
                                            <div>
                                                <img src="./assets/images/svg/user-small-img.png" alt="user image" />
                                            </div>
                                        </div>
                                        <div class="user-title-info">
                                            <h5>Jeff Gregory</h5>
                                            <p>Joined: Oct 28, 2021</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="td">+1 049-948 (0383)</div>
                                <div class="td">test@gmail.com</div>
                                <div class="td">Kentucky, 60005</div>
                                <div class="td">Parent To Be</div>
                                <div class="td">Active</div>
                                <div class="td">
                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        <img src="assets/images/svg/3-dots-horizontal.svg" alt="Dot Image" />
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="#">Send Message</a></li>
                                        <li><a class="dropdown-item" href="#">Activate User</a></li>
                                        <li><a class="dropdown-item" href="#">Deactivate User</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="table-row">
                                <div class="td">
                                    <div class="user-title">
                                        <div class="user-img">
                                            <div>
                                                <img src="./assets/images/svg/user-small-img.png" alt="user image" />
                                            </div>
                                        </div>
                                        <div class="user-title-info">
                                            <h5>Jeff Gregory</h5>
                                            <p>Joined: Oct 28, 2021</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="td">+1 049-948 (0383)</div>
                                <div class="td">test@gmail.com</div>
                                <div class="td">Kentucky, 60005</div>
                                <div class="td">Surrogate Mother<br />
                                    <span class="sm-code">SM03834</span>
                                </div>
                                <div class="td text-danger">Inactive</div>
                                <div class="td">
                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        <img src="assets/images/svg/3-dots-horizontal.svg" alt="Dot Image" />
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="#">Send Message</a></li>
                                        <li><a class="dropdown-item" href="#">Activate User</a></li>
                                        <li><a class="dropdown-item" href="#">Deactivate User</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="table-row">
                                <div class="td">
                                    <div class="user-title">
                                        <div class="user-img">
                                            <div>
                                                <img src="./assets/images/svg/user-small-img.png" alt="user image" />
                                            </div>
                                        </div>
                                        <div class="user-title-info">
                                            <h5>Jeff Gregory</h5>
                                            <p>Joined: Oct 28, 2021</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="td">+1 049-948 (0383)</div>
                                <div class="td">test@gmail.com</div>
                                <div class="td">Kentucky, 60005</div>
                                <div class="td">Parent To Be</div>
                                <div class="td">Active</div>
                                <div class="td">
                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        <img src="assets/images/svg/3-dots-horizontal.svg" alt="Dot Image" />
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="#">Send Message</a></li>
                                        <li><a class="dropdown-item" href="#">Activate User</a></li>
                                        <li><a class="dropdown-item" href="#">Deactivate User</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end table  -->
                    <div class="pagination-section">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-end">
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Previous">
                                        <span aria-hidden="true"><img
                                                src="./assets/images/svg/pagination-left-arrow.svg"
                                                alt="pagination left arrow" /></span>
                                    </a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">4</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Next">
                                        <span aria-hidden="true"><img
                                                src="./assets/images/svg/pagination-right-arrow.svg"
                                                alt="pagination right arrow" /></span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection