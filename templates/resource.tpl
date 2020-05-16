<div class="row">
    <div class="display col-md-7">

        <div class="panel panel-info">
            <div class="panel-heading text-center">
                <p class="panel-title">
                    <i class="fa fa-fw fa-{$attributes_map.{$card_title}.faclass}"></i>
                    {$entry.{$attributes_map.{$card_title}.attribute}.0}
                </p>
            </div>

            <div class="panel-body">

                <div class="table-responsive">
                <table class="table table-striped table-hover">
                {foreach $card_items as $item}
                {$attribute=$attributes_map.{$item}.attribute}
                {$type=$attributes_map.{$item}.type}
                {$faclass=$attributes_map.{$item}.faclass}

                {if !isset({$entry.$attribute.0}) && ! $show_undef}
                    {continue}
                {/if}
                    <tr>
                        <th class="text-center">
                            <i class="fa fa-fw fa-{$faclass}"></i>
                        </th>
                        <th class="hidden-xs">
                            {$msg_label_{$item}}
                        </th>
                        <td{if $type=="racfaccess"} style="padding: 0px"{/if}>
                        {if isset({$entry.{$attribute}.0})}
			    {if $type=="racfaccess"}<table class="table table-striped table-hover">{/if}
                            {foreach $entry.{$attribute} as $value}
                            {include 'value_displayer.tpl' value=$value type=$type truncate_value_after=10000}
                            {/foreach}
			    {if $type=="racfaccess"}</table>{/if}
                        {else}
                            <i>{$msg_notdefined}</i><br />
                        {/if}
                        </td>
                    </tr>
                {/foreach}
                </table>
                </div>

            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading text-center">
                <p class="panel-title">
                    <i class="fa fa-fw fa-info-circle"></i>
                    {$msg_accountstatus}
                </p>
            </div>

            <div class="panel-body">

                <div class="table-responsive">
                <table class="table table-striped table-hover">
                {foreach $password_items as $item}
                {$attribute=$attributes_map.{$item}.attribute}
                {$type=$attributes_map.{$item}.type}
                {$faclass=$attributes_map.{$item}.faclass}

                {if !({$entry.$attribute.0}) && ! $show_undef}
                    {continue}
                {/if}
                    <tr>
                        <th class="col-md-6">
                            {$msg_label_{$item}}
                        </th>
                        <td class="col-md-6">
                        {if ({$entry.$attribute.0})}
                            {foreach $entry.{$attribute} as $value}
                            {include 'value_displayer.tpl' value=$value type=$type truncate_value_after=10000}
                            {/foreach}
                        {else}
                            <i>{$msg_notdefined}</i><br />
                        {/if}
                        </td>
                    </tr>
                {/foreach}
                </table>
                </div>

            </div>
        </div>

    </div>
    <div class="col-md-5">

        <div class="panel panel-info">
            <div class="panel-heading text-center">
                <p class="panel-title">
                    <i class="fa fa-fw fa-lock"></i>
                    {$msg_resourceaddaccess}
                </p>
            </div>
    
             <div class="panel-body">
    
                 <form id="resourceaddaccess" method="post" action="index.php?page=resourceaddaccess">
                     {if $resourceaddaccessresult eq 'racfidrequired'}
                     <div class="alert alert-danger"><i class="fa fa-fw fa-exclamation-triangle"></i> {$msg_racfidrequired}</div>
                     {/if}
                     {if $resourceaddaccessresult eq 'addaccessfailed'}
                     <div class="alert alert-danger"><i class="fa fa-fw fa-exclamation-triangle"></i> {$msg_addaccessfailed}</div>
                     {/if}
                     {if $resourceaddaccessresult eq 'addaccessok'}
                     <div class="alert alert-success"><i class="fa fa-fw fa-check"></i> {$msg_addaccessok}</div>
                     {/if}
                     <input type="hidden" name="dn" value="{$dn}" />
                     <div class="form-group">
                         <div class="input-group">
                             <span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
                             <input type="text" name="racfid" id="racfid" class="form-control" placeholder="{$msg_racfid}" />
                         </div>
                         <div class="input-group">
                             <span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
                             <select name="accesslevel" id="accesslevel" class="form-control">
			       <option value="ACC(NONE)">None</option>
			       <option value="ACC(READ)">Read</option>
			       <option value="ACC(UPDATE)">Update</option>
			       <option value="ACC(ALTER)">Alter</option>
			       <option value="ACC(CONTROL)">Control</option>
			       <option value="DELETE">-- Delete ACL Entry --</option>
			     </select>
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

        {if $use_resetpassword}
        <div class="panel panel-info">
            <div class="panel-heading text-center">
                <p class="panel-title">
                    <i class="fa fa-fw fa-repeat"></i>
                    {$msg_resetpassword}
                </p>
            </div>

             <div class="panel-body">

                 <form id="resetpassword" method="post" action="index.php?page=resetpassword">
                     {if $resetpasswordresult eq 'passwordrequired'}
                     <div class="alert alert-warning"><i class="fa fa-fw fa-exclamation-triangle"></i> {$msg_passwordrequired}</div>
                     {/if}
                     {if $resetpasswordresult eq 'passwordrefused'}
                     <div class="alert alert-danger"><i class="fa fa-fw fa-exclamation-triangle"></i> {$msg_passwordrefused}</div>
                     {/if}
                     {if $resetpasswordresult eq 'passwordchanged'}
                     <div class="alert alert-success"><i class="fa fa-fw fa-check"></i> {$msg_passwordchanged}</div>
                     {/if}
                     <input type="hidden" name="dn" value="{$dn}" />
                     <div class="form-group">
                         <div class="input-group">
                             <span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
                             <input type="password" name="newpassword" id="newpassword" class="form-control" placeholder="{$msg_newpassword}" />
                         </div>
                     </div>
                     <div class="form-groupi row">
                         <div class="col-md-9"><p>{$msg_forcereset}</p></div>
                         <div class="col-md-3 text-right">
                             <div class="btn-group" data-toggle="buttons">
                                 <label class="btn btn-primary{if $resetpassword_reset_default} active{/if}">
                                 {if $resetpassword_reset_default}
                                     <input type="radio" name="pwdreset" id="true" value="true" checked /> {$msg_true}
                                 {else}
                                     <input type="radio" name="pwdreset" id="true" value="true" /> {$msg_true}
                                 {/if}
                                 </label>
                                 <label class="btn btn-primary{if !$resetpassword_reset_default} active{/if}">
                                 {if !$resetpassword_reset_default}
                                     <input type="radio" name="pwdreset" id="false" value="false" checked /> {$msg_false}
                                 {else}
                                     <input type="radio" name="pwdreset" id="false" value="false" /> {$msg_false}
                                 {/if}
                                 </label>
                             </div>
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
        {/if}

        {if $isLocked}
        <div class="panel panel-danger">
            <div class="panel-heading text-center">
                <p class="panel-title">
                    <i class="fa fa-fw fa-exclamation-triangle"></i>
                    {$msg_accountlocked}
                </p>
            </div>

             <div class="panel-body">
                 {if $unlockDate}
                 <p>{$msg_unlockdate} {$unlockDate|date_format:{$date_specifiers}}</p>
                 {/if}
                 {if $use_unlockaccount}
                 <form id="unlockaccount" method="post" action="index.php?page=unlockaccount">
                     {if $unlockaccountresult eq 'ldaperror'}
                     <div class="alert alert-danger"><i class="fa fa-fw fa-exclamation-triangle"></i> {$msg_accountnotunlocked}</div>
                     {/if}
                     <input type="hidden" name="dn" value="{$dn}" />
                     <div class="form-group">
                         <button type="submit" class="btn btn-success">
                             <i class="fa fa-fw fa-unlock"></i> {$msg_unlockaccount}
                         </button>
                     </div>
                 </form>
                 {/if}
            </div>
        </div>
        {/if}

        {if $isExpired}
        <div class="panel panel-danger">
            <div class="panel-heading text-center">
                <p class="panel-title">
                    <i class="fa fa-fw fa-exclamation-triangle"></i>
                    {$msg_passwordexpired}
                </p>
            </div>
        </div>
        {/if}
   </div>
</div>
