
    <ul class="notifications">
        <li>						            
            <ul class="notifications">
                <li>							
                    <a href="#" class="dropdown-toggle notification-icon" data-bs-toggle="dropdown">
                        <i class="bx bx-list-ol"></i>
                        @if($total_notifications > 0)
                            <span class="badge">{{$total_notifications}}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu notification-menu large">
                        <div class="notification-title">
                            @if($total_notifications > 0)
                                <span class="float-end badge badge-default">{{$total_notifications}}</span>
                                Requests
                            @endif
                        </div>
                        <div class="content">
                            <ul>
                                @if($total_notifications > 0)

                                    @foreach ($transfer_requests as $transfer)
                                        <li>
                                            <p class="clearfix mb-1">
                                                <span class="message float-start">Transfer Requested - {{$transfer['created_at']}} Dispatch No {{$transfer['dispatch_number']}}</span>													
                                            </p>												
                                        </li>											
                                    @endforeach

                                    @foreach ($adjustment_requests as $adjustment)
                                        <li>
                                            <p class="clearfix mb-1">
                                                <span class="message float-start">Adjustment Requested - {{$adjustment['created_at']}} Product {{$adjustment['description']}}</span>													
                                            </p>												
                                        </li>											
                                    @endforeach
                                
                                @else

                                    <li>
                                        <p class="clearfix mb-1">
                                            <span class="message float-start">Nothing for now...</span>													
                                        </p>												
                                    </li>
                                
                                @endif
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
            
        
    </li>
</ul>

