<div class="nano">
	<div class="nano-content">
	    <nav id="menu" class="nav-main" role="navigation">
			@if(auth()->user()->depart=='manufacture')
				@if(auth()->user()->role=='manager')
					<x-navigation.manufacture.manager />
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