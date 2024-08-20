<ul class="nav nav-main">
  <li>
    <a class="nav-link" href="{{env('APP_URL')}}dashboard">
    <i class="bx bx-home-alt" aria-hidden="true"></i>
    <span>Dashboard</span>
    </a>                        
  </li>
  {{-- <li class="nav-parent">
    <a class="nav-link" href="#">
    <i class='bx bx-group' aria-hidden="true"></i>        
    <span>User Management</span>
    </a>
    <ul class="nav nav-children">
      @if(Auth::user()->getSec()->getCRUD('user_man_crud')['read']=='true'  || Auth::user()->getSec()->global_admin_value)
        <li><a class="nav-link" href="{{url('users')}}">Add/Manage Users</a></li>
      @endif
    </ul>
  </li> --}}
  {{-- <li class="nav-parent">
    <a class="nav-link" href="#">
    <i class="bx bx-user-pin" aria-hidden="true"></i>
      <span>Suppliers</span>
    </a>
    <ul class="nav nav-children">
      @if(Auth::user()->getSec()->getCRUD('supplier_crud')['read']=='true'  || Auth::user()->getSec()->global_admin_value)
        <li><a class="nav-link" href="{{url('suppliers')}}">Add/Manage Suppliers</a></li>
      @endif
    </ul>
  </li>
  <li class="nav-parent">
    <a class="nav-link" href="#">
    <i class="bx bx-user-pin" aria-hidden="true"></i>
      <span>Customers</span>
    </a>
    <ul class="nav nav-children">
      @if(Auth::user()->getSec()->getCRUD('customer_crud')['read']=='true'  || Auth::user()->getSec()->global_admin_value)
        <li><a class="nav-link" href="{{url('customers')}}">Add/Manage Customers</a></li>
      @endif
    </ul>
  </li>
  <li class="nav-parent">
    <a class="nav-link" href="#">
    <i class="bx bx-box" aria-hidden="true"></i>
    <span>Products</span>
    </a>
    <ul class="nav nav-children">
      @if(Auth::user()->getSec()->getCRUD('products_crud')['read']=='true' || Auth::user()->getSec()->getCRUD('recipes_crud')['read']=='true' || Auth::user()->getSec()->global_admin_value)
        <li><a class="nav-link" href="{{url('products')}}">Add / Edit Product & Recipes</a></li>
      @endif
    </ul>
  </li>
  <li class="nav-parent">
    <a class="nav-link" href="#">
    <i class="bx bx-spreadsheet" aria-hidden="true"></i>
    <span>Job Cards</span>
    </a>
    <ul class="nav nav-children">
      @if(Auth::user()->getSec()->getCRUD('jobcards_crud')['create']=='true'  || Auth::user()->getSec()->global_admin_value)
        <li><a class="nav-link" href="{{url('jobs/create')}}">Create New Job Card</a></li>
      @endif
      @if(Auth::user()->getSec()->getCRUD('jobcards_crud')['read']=='true'  || Auth::user()->getSec()->global_admin_value)
        <li><a class="nav-link" href="{{url('jobs')}}">Open Job Cards</a></li>
      @endif
    </ul>
  </li>
  <li class="nav-parent">
    <a class="nav-link" href="#">
    <i class="bx bx-cog" aria-hidden="true"></i>
    <span>Production</span>
    </a>
    <ul class="nav nav-children">
      @if(Auth::user()->getSec()->getCRUD('production_crud')['create']=='true'  || Auth::user()->getSec()->global_admin_value)
        <li><a class="nav-link" href="{{url('batches/create')}}">Create New Batch</a></li>
      @endif
      @if(Auth::user()->getSec()->getCRUD('production_crud')['read']=='true'  || Auth::user()->getSec()->global_admin_value)
        <li><a class="nav-link" href="{{url('batches')}}">Batches</a></li>
      @endif
    </ul>
  </li> --}}
  <li class="nav-parent">
    <a class="nav-link" href="#">
    <i class="bx bx-dna" aria-hidden="true"></i>
    <span>Labs</span>
    </a>
    <ul class="nav nav-children">
      @if(Auth::user()->getSec()->getCRUD('lab_tests_crud')['read']=='true'  || Auth::user()->getSec()->global_admin_value)
        <li><a class="nav-link" href="{{url('labs/batches')}}">Batches</a></li>
      @endif
    </ul>
  </li>
  <li class="nav-parent">
    <a class="nav-link" href="#">
    <i class="bx bxs-truck" aria-hidden="true"></i>
    <span>Dispatch</span>
    </a>
    <ul class="nav nav-children">
      @if(Auth::user()->getSec()->getCRUD('dispatch_crud')['read']=='true'  || Auth::user()->getSec()->global_admin_value)
        <li><a class="nav-link" href="{{url('dispatches/new')}}">Dispatches</a></li>
      @endif  
      @if(Auth::user()->getSec()->receive_stock_value  || Auth::user()->getSec()->global_admin_value)
        <li><a class="nav-link" href="{{url('goods-receive/new')}}">Goods Received</a></li>
      @endif
    {{-- <li><a class="nav-link" href="{{url('dispatches/archive')}}">Archive Dispatches</a></li> **Incorporated into Dispacthes with Tabs 2023-09-02 **--}}
    {{-- <li><a class="nav-link" href="#">Dispatched Orders</a></li> --}}
    </ul>
  </li>
  {{-- <li class="nav-parent">
    <a class="nav-link" href="#">
    <i class="bx bxs-report" aria-hidden="true"></i>
    <span>Reports</span>
    </a>
    <ul class="nav nav-children">
      @if(Auth::user()->getSec()->reports_stock  || Auth::user()->getSec()->global_admin_value)
        <li><a class="nav-link" href="{{url('report/stock-reports')}}">Stock Report</a></li>
      @endif      
      @if(Auth::user()->getSec()->reports_labs  || Auth::user()->getSec()->global_admin_value)
        <li><a class="nav-link" href="{{url('report/lab-reports')}}">Lab Reports</a></li>
      @endif
      @if(Auth::user()->getSec()->reports_dispatch  || Auth::user()->getSec()->global_admin_value)
        <li><a class="nav-link" href="{{url('report/dispatch-reports')}}">Dispatch Reports</a></li>
      @endif
    </ul> 
  </li>--}}
</ul>