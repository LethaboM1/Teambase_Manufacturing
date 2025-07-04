<ul class="nav nav-main">
    <li>
        <a class="nav-link" href="{{route('dashboard')}}">
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
          @if(Auth::user()->getSec()->getCRUD('products_crud')['read']=='true' || Auth::user()->getSec()->getCRUD('recipes_crud')['create']=='true' || Auth::user()->getSec()->global_admin_value)
            <li><a class="nav-link" href="{{url('products')}}">Add / Edit Product & Recipes</a></li>
          @endif
        </ul>
      </li>
</ul>