<form method="POST" id="formStylePick">
    @csrf

    <div class="row">
        <div class="col-md-1">
            <label>Vendor Name</label>
        </div>

        <div class="col-md-5">
            <input type="text" class="form-control" name="vendor_name" value="{{ $user->vendor->sp_vendor }}">
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-md-1">
            <label>Vendor Password</label>
        </div>

        <div class="col-md-5">
            <input type="password" class="form-control" name="vendor_password" value="{{ $user->vendor->sp_password }}">
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-md-1">
            <label>Vendor Category</label>
        </div>

        <div class="col-md-5">
            <input type="text" class="form-control" name="vendor_category" value="{{ $user->vendor->sp_category }}">
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-md-1">
            <label>Default Category</label>
        </div>

        <div class="col-md-5">
            <input type="text" class="form-control" name="default_category" value="{{ $user->vendor->sp_default_category }}">
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-md-12 text-right">
            <button class="btn btn-primary" id="btnStylePickSubmit">Save</button>
        </div>
    </div>
</form>