<form class="add-user-form form-horizontal">

  <fieldset>

    <!-- Form Name -->
    <legend>Create new user</legend>

    <div class="message"></div>

    <!-- Text input-->
    <div class="form-group">
      <label class="col-md-4 control-label" for="name">Full Name</label>
      <div class="col-md-5">
        <input id="name" name="name" type="text" placeholder="Full Name" class="form-control input-md" required="">

      </div>
    </div>

    <!-- Password input-->
    <div class="form-group">
      <label class="col-md-4 control-label" for="email">Email</label>
      <div class="col-md-5">
        <input id="email" name="email" type="email" placeholder="Email" class="form-control input-md" required="">

      </div>
    </div>

    <!-- Multiple Radios -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="role">Role</label>
      <div class="col-md-4">
        <div class="radio">
          <label for="role-0">
            <input type="radio" name="role" id="role-0" value="user"  checked="checked"> User
          </label>
        </div>
        <div class="radio">
          <label for="role-1">
            <input type="radio" name="role" id="role-1" value="visitor"> Visitor
          </label>
        </div>
        <div class="radio">
          <label for="role-2">
            <input type="radio" name="role" id="role-2" value="admin"> Admin
          </label>
        </div>
      </div>
    </div>

    <!-- Button -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="create"></label>
      <div class="col-md-4">
        <button id="create" name="create" class="btn btn-primary">Create User</button>
      </div>
    </div>
    <input type="hidden" name="type" value="createUser">

  </fieldset>
</form>
