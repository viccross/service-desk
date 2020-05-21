<div class="row">
    <div class="display col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading text-center">
                <p class="panel-title">
                <i class="fa fa-fw fa-database"></i>&nbsp;RACF Manager</p>
            </div>

            <div class="panel-body">
	        Select the operation you want to perform.
	    </div>
	</div>
    </div>

    <div class="display col-md-6">
        <div class="panel panel-info">
            <div class="panel-heading text-center">
                <p class="panel-title">
                    <i class="fa fa-fw fa-check-circle"></i>
                    {$msg_racfuser}
                </p>
            </div>

             <div class="panel-body">

                 <form id="racfuser" method="post" action="index.php?page=search_racuser">
                     {if $checkpasswordresult eq 'passwordrequired'}
                     <div class="alert alert-warning"><i class="fa fa-fw fa-exclamation-triangle"></i> {$msg_passwordrequired}</div>
                     {/if}
                     {if $checkpasswordresult eq 'ldaperror'}
                     <div class="alert alert-danger"><i class="fa fa-fw fa-exclamation-triangle"></i> {$msg_passwordinvalid}</div>
                     {/if}
                     {if $checkpasswordresult eq 'passwordexpired'}
                     <div class="alert alert-warning"><i class="fa fa-fw fa-check"></i> {$msg_passwordexpired}</div>
                     {/if}
                     {if $checkpasswordresult eq 'passwordok'}
                     <div class="alert alert-success"><i class="fa fa-fw fa-check"></i> {$msg_passwordok}</div>
                     {/if}
                     <input type="hidden" name="dn" value="{$dn}" />
                     <div class="form-group">
                         <div class="input-group">
                             <span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
                             <input type="text" name="racfid" id="racfid" class="form-control" placeholder="{$msg_racfid}" />
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
	
        <div class="panel panel-info">
            <div class="panel-heading text-center">
                <p class="panel-title">
                    <i class="fa fa-fw fa-check-circle"></i>
                    {$msg_racfresource}
                </p>
            </div>

             <div class="panel-body">

                 <form id="racfresource" method="post" action="index.php?page=scanclass">
                     <div class="form-group">
                         <div class="input-group">
                             <span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
                             <select size="1" name="racfclass" id="racfclass" class="form-control">
			          <option value="VMMDISK">Minidisks (VMMDISK)</option>
			          <option value="SURROGAT">"Logon-By" definitions (SURROGAT)</option>
				  <option value="VMLAN">Virtual LANs (VMLAN)</option>
				  <option value="VMRDR">Unit-record devices (VMRDR)</option>
				  <option value="VMCMD">CP Commands (VMCMD)</option>
				  <option value="FACILITY">General Application Settings (FACILITY)</option>
                             </select>
                         </div>
                     </div>
                     <div class="form-group">
                         <div class="input-group">
                             <span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
                             <input type="text" name="resource" id="resource" class="form-control" placeholder="{$msg_resource}" />
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
</div>

<div class="alert alert-success">{$msg_welcome}</div>
