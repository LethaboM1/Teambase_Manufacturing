<ul class="nav nav-main">
    <li>
        <a class="nav-link" href="{{env('APP_URL')}}dashboard">
        <i class="bx bx-home-alt" aria-hidden="true"></i>
        <span>Dashboard</span>
        </a>                        
      </li>
      <li class="nav-parent">
        <a class="nav-link" href="#">
        <i class="bx bx-user-pin" aria-hidden="true"></i>
        <span>User Managment</span>
        </a>
        <ul class="nav nav-children">
        <li><a class="nav-link" href="{{url('users')}}">Add/Manage Users</a></li>
        </ul>
      </li>
      <li class="nav-parent">
        <a class="nav-link" href="#">
        <i class="bx bx-box" aria-hidden="true"></i>
        <span>Products</span>
        </a>
        <ul class="nav nav-children">
        <li><a class="nav-link" href="{{url('products')}}">Add / Edit Product & Recipes</a></li>
        </ul>
      </li>
      <li class="nav-parent">
        <a class="nav-link" href="#">
        <i class="bx bx-spreadsheet" aria-hidden="true"></i>
        <span>Job Cards</span>
        </a>
        <ul class="nav nav-children">
        <li><a class="nav-link" href="{{url('jobs/create')}}">Create New Job Card</a></li>
        <li><a class="nav-link" href="{{url('jobs')}}">Open Job Cards</a></li>
        </ul>
      </li>
      <li class="nav-parent">
        <a class="nav-link" href="#">
        <i class="bx bx-cog" aria-hidden="true"></i>
        <span>Production</span>
        </a>
        <ul class="nav nav-children">
        <li><a class="nav-link" href="{{url('batches/create')}}">Create New Batch</a></li>
        <li><a class="nav-link" href="{{url('batches')}}">Open Batchs</a></li>
        </ul>
      </li>
      <li class="nav-parent">
        <a class="nav-link" href="#">
        <i class="bx bx-dna" aria-hidden="true"></i>
        <span>Labs</span>
        </a>
        <ul class="nav nav-children">
        <li><a class="nav-link" href="{{url('labs/batches')}}">Batches</a></li>
        </ul>
      </li>
      <li class="nav-parent">
        <a class="nav-link" href="#">
        <i class="bx bxs-truck" aria-hidden="true"></i>
        <span>Dispatch</span>
        </a>
        <ul class="nav nav-children">
          <li><a class="nav-link" href="{{url('dispatchs/ready')}}">Batches Ready to Dispatch</a></li>  
        <li><a class="nav-link" href="{{url('dispatchs/orders')}}">Orders Ready for Dispatch</a></li>
        <li><a class="nav-link" href="#">Dispatched Orders</a></li>
        </ul>
      </li>
      <li class="nav-parent">
        <a class="nav-link" href="#">
        <i class="bx bxs-report" aria-hidden="true"></i>
        <span>Reports</span>
        </a>
        <ul class="nav nav-children">
          <li><a class="nav-link" href="{{url('report/stock-reports')}}">Stock Report</a></li>
          <li><a class="nav-link" href="{{url('report/order-reports')}}">Order Reports</a></li>
          <li><a class="nav-link" href="{{url('report/lab-reports')}}">Lab Reports</a></li>
          <li><a class="nav-link" href="{{url('report/dispatch-reports')}}">Dispatch Reports</a></li>
        </ul>
      </li>
</ul>