<div class="nano">
	<div class="nano-content">
	    <nav id="menu" class="nav-main" role="navigation">
			@if(auth()->user()->depart=='manufacture')
				@if(auth()->user()->role=='manager'||auth()->user()->role=='job admin')
					<x-navigation.manufacture.manager />
				@endif

				@if(auth()->user()->role=='recipe')
					<x-navigation.manufacture.recipe />
				@endif

				@if(auth()->user()->role=='clerk')
					<x-navigation.manufacture.clerk />
				@endif

				@if(auth()->user()->role=='dispatch')
					<x-navigation.manufacture.dispatch />
				@endif
				
			@endif
							
		</nav>
		<hr class="separator" /> 
    </div>
<script>
// Maintain Scroll Position
    if (typeof localStorage !== 'undefined') {
    if (localStorage.getItem('sidebar-left-position') !== null) {
        var initialPosition = localStorage.getItem('sidebar-left-position'),
            sidebarLeft = document.querySelector('#sidebar-left .nano-content');
            sidebarLeft.scrollTop = initialPosition;
        }
    }
</script>
</div>