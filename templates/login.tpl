<div class="row">
    <div class="display col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading text-center">
                <p class="panel-title">
                <i class="fa fa-fw fa-database"></i>&nbsp;RACF Manager</p>
            </div>

            <div class="panel-body">
	        Please log in to continue.
	    </div>
	</div>
    </div>

    <div class="row">
      <div class="display col-md-3">
      </div>
      <div class="display col-md-6">
        <div class="panel panel-info">
            <div class="panel-heading text-center">
                <p class="panel-title">
                    <i class="fa fa-fw fa-check-circle"></i>
                    Login details:
                </p>
            </div>

             <div class="panel-body">

                 <form id="login" method="post" action="index.php?page=login&next={$next}">
                     <div class="form-group">
                         <div class="input-group">
                             <span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
                             <input type="text" name="username" id="username" class="form-control" placeholder="{$msg_racfid}" />
                         </div>
                     </div>
                     <div class="form-group">
                         <div class="input-group">
                             <span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
                             <input type="password" name="password" id="password" class="form-control" />
                         </div>
                     </div>
                     <div class="form-group">
                         <button type="submit" class="btn btn-success">
                             <i class="fa fa-fw fa-check-square-o"></i> {$msg_submit}
                         </button>
                     </div>
                </form>
            </div>
        </div>
      </div>
      <div class="display col-md-3">
      </div>
    </div>
</div>

<div class="alert alert-success">{$msg_welcome}</div>
