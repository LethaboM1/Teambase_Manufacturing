<div>
    <header class="card-header">
        <h2 class="card-title">Add New Customer</h2>
    </header>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                @if($customer_id>0)
                    <x-form.hidden wire=0 name="id" :value="$customer_id" />
                @endif
                <x-form.input wire=0 :value="$customer['name']" name="name" label="Customer Name" />
            </div>
            
            <div class="col-md-6">
                <x-form.checkbox name="credit_set" label="Has an account?" />                
                @if($credit_set)                
                    <x-form.input wire=0 :value="$customer['account_number']" name="account_number" label="Account No." />
                @endif
            </div>
            <div class="col-md-6">
                <x-form.hidden wire=0 name="credit" :value="$credit_set" />                
            </div>            
            <div class="col-md-6">
                <x-form.input wire=0 :value="$customer['contact_name']" name="contact_name" label="Contact Name" />
            </div>
            <div class="col-md-6">
                <x-form.telephone wire=0 :value="$customer['contact_number']" name="contact_number" label="Contact Number" />
            </div>
            <div class="col-md-6">
                <x-form.email wire=0 :value="$customer['email']" name="email" label="E-mail" />
            </div>
            <div class="col-md-6">
                <x-form.input wire=0 :value="$customer['vat_number']" name="vat_number" label="VAT Number" />
            </div>
            <div class="col-md-12">
                <x-form.textarea wire=0 :value="$customer['address']" name="address" label="Address" />
            </div>
        </div>
    </div>
    <footer class="card-footer text-end">
        @if (Auth::user()->getSec()->getCRUD('customer_crud')['update'] || Auth::user()->getSec()->getCRUD('customer_crud')['create'] || Auth::user()->getSec()->global_admin_value)
            <button type="submit" name="add_user" class="btn btn-primary">{{($customer_id>0?"Save":"Add")}} Customer</button>
        @endif
        <button class="btn btn-default modal-dismiss">Cancel</button>
    </footer>
</div>