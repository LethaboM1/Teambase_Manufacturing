<div>
    <header class="card-header">
        <h2 class="card-title">Add New Supplier</h2>
    </header>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                @if($supplier_id>0)
                    <x-form.hidden wire=0 name="id" :value="$supplier_id" />
                @endif
                <x-form.input wire=0 :value="$supplier['name']" name="name" label="Supplier Name" />
            </div>
            <div class="col-md-6">
                <x-form.input wire=0 :value="$supplier['contact_name']" name="contact_name" label="Contact Name" />
            </div>
            <div class="col-md-6">
                <x-form.telephone wire=0 :value="$supplier['contact_number']" name="contact_number" label="Contact Number" />
            </div>
            <div class="col-md-6">
                <x-form.email wire=0 :value="$supplier['email']" name="email" label="E-mail" />
            </div>
            <div class="col-md-6">
                <x-form.input wire=0 :value="$supplier['vat_number']" name="vat_number" label="VAT Number" />
            </div>
            <div class="col-md-12">
                <x-form.textarea wire=0 :value="$supplier['address']" name="address" label="Address" />
            </div>
        </div>
    </div>
    <footer class="card-footer text-end">
        <button type="submit" name="add_user" class="btn btn-primary">{{($supplier_id>0?"Save":"Add")}} Supplier</button>
        <button class="btn btn-default modal-dismiss">Cancel</button>
    </footer>
</div>