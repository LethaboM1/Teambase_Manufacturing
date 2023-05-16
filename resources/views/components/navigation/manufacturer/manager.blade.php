<ul class="nav nav-main">
    <li>
        <a class="nav-link" href="dashboard.php">
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
        <li><a class="nav-link" href="{{env('APP_URL')}}users">Add/Manage Users</a></li>
        </ul>
      </li>
      <li class="nav-parent">
        <a class="nav-link" href="#">
        <i class="bx bx-box" aria-hidden="true"></i>
        <span>Products</span>
        </a>
        <ul class="nav nav-children">
        <li><a class="nav-link" href="dashboard.php?page=add-product">Add / Edit Product & Recipes</a></li>
        </ul>
      </li>
      <li class="nav-parent">
        <a class="nav-link" href="#">
        <i class="bx bx-spreadsheet" aria-hidden="true"></i>
        <span>Job Cards</span>
        </a>
        <ul class="nav nav-children">
        <li><a class="nav-link" href="dashboard.php?page=create-job-card">Create New Job Card</a></li>
        <li><a class="nav-link" href="dashboard.php?page=open-job-cards">Open Job Cards</a></li>
        </ul>
      </li>
      <li class="nav-parent">
        <a class="nav-link" href="#">
        <i class="bx bx-cog" aria-hidden="true"></i>
        <span>Production</span>
        </a>
        <ul class="nav nav-children">
        <li><a class="nav-link" href="dashboard.php?page=create-batch">Create New Batch</a></li>
        <li><a class="nav-link" href="dashboard.php?page=open-batch">Open Batchs</a></li>
        </ul>
      </li>
      <li class="nav-parent">
        <a class="nav-link" href="#">
        <i class="bx bx-dna" aria-hidden="true"></i>
        <span>Labs</span>
        </a>
        <ul class="nav nav-children">
        <li><a class="nav-link" href="dashboard.php?page=create-lab">Create Lab for Batch</a></li>
        </ul>
      </li>
      <li class="nav-parent">
        <a class="nav-link" href="#">
        <i class="bx bxs-truck" aria-hidden="true"></i>
        <span>Dispatch</span>
        </a>
        <ul class="nav nav-children">
        <li><a class="nav-link" href="">Orders Ready for Dispatch</a></li>
        <li><a class="nav-link" href="">Dispatched Orders</a></li>
        </ul>
      </li>
      <li class="nav-parent">
        <a class="nav-link" href="#">
        <i class="bx bxs-report" aria-hidden="true"></i>
        <span>Reports</span>
        </a>
        <ul class="nav nav-children">
          <li><a class="nav-link" href="">Stock Report</a></li>
          <li><a class="nav-link" href="">Order Reports</a></li>
          <li><a class="nav-link" href="">Lab Reports</a></li>
          <li><a class="nav-link" href="">Dispatch Reports</a></li>
        </ul>
      </li>
</ul>