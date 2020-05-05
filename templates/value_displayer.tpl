{if $type eq 'text'}
    {$value|truncate:{$truncate_value_after}}<br />
{/if}

{if $type eq 'mailto'}
    {mailto address="{$value|escape:"html"}" encode="javascript" text="{$value|truncate:{$truncate_value_after}}" extra='class="link-email" title="'|cat:$msg_tooltip_emailto:'"'}<br />
{/if}

{if $type eq 'tel'}
    <a href="tel:{$value}" rel="nofollow" class="link-phone" title="{$msg_tooltip_phoneto}">{$value|truncate:{$truncate_value_after}}</a><br />
{/if}

{if $type eq 'boolean'}
    {if $value=="TRUE"}{$msg_true|truncate:{$truncate_value_after}}<br />{/if}
    {if $value=="FALSE"}{$msg_false|truncate:{$truncate_value_after}}<br />{/if}
{/if}

{if $type eq 'date'}
    {convert_ldap_date($value)|date_format:{$date_specifiers}|truncate:{$truncate_value_after}}<br />
{/if}

{if $type eq 'list'}
    {$value|truncate:{$truncate_value_after}}<br />
{/if}

{if $type eq 'dn_link'}
    <a href="index.php?page=display&dn={$value|escape:'url'}&search={$search}">{$value|truncate:{$truncate_value_after}}</a><br />
{/if}

{if $type eq 'racfaccess'}
    <tr onclick='document.getElementById("racfid").value = "{$value.id}"; document.getElementById("accesslevel").value = "ACC({$value.acc})";'><td><i class="fa fa-fw fa-user-circle"></i>&nbsp;{$value.id}</td><td><i class="fa fa-fw fa-lock"></i>&nbsp;{$value.acc}</td><td><i name="resdel">&nbsp;</i></tr>
{/if}
