<ul class="nav nav-main">
    <li>
        <a class="nav-link" href="{{env('APP_URL')}}dashboard">
        <i class="bx bx-home-alt" aria-hidden="true"></i>
        <span>Dashboard</span>
        </a>                        
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
</ul>