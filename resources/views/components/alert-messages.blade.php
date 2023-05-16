<div id="alert-messages">
    @if(Session::get('alertMessage'))    
        <div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>Success!</strong>&nbsp;{{Session::get('alertMessage')}}
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-hidden='true' aria-label='Close'></button>
        </div>
    @endif

    @if(Session::get('alertError'))    
        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
            <strong>Error!</strong>&nbsp;{{Session::get('alertError')}}
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-hidden='true' aria-label='Close'></button>
        </div>
    @endif


    @if(Session::get('alertNotice'))    
        <div class='alert alert-info alert-dismissible fade show' role='alert'>
            <strong>Notice!</strong>&nbsp;{{Session::get('alertNotice')}}
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-hidden='true' aria-label='Close'></button>
        </div>
    @endif

    @if($errors->any())
        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
            <small>
                <strong>Error!</strong>&nbsp;
                @foreach($errors->all() as $error)
                    {{$error}}
                @endforeach
            </small>
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-hidden='true' aria-label='Close'></button>
        </div>        
    @endif
</div>