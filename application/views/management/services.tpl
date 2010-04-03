{if !empty($message_flash)}
	<p>
		{$message_flash}
	</p>
{/if}

<form method="post" action="{if empty($service)}{$rq->href($cfg->get('router_action_parameter'), 'm_service_add')}{else}{$rq->href($cfg->get('router_action_parameter'), 'm_service_edit', 'id', $service.id)}{/if}">
	{$form->enum('operator', $operators)}
	{$form->string('code')}
    {$form->string('name_bg')}
    {$form->string('name_en')}
    {$form->string('name_ru')}
    <p>
        <input type="submit" name="submit" value="{if empty($service)}{$lc->get('caption_for_create')}{else}{$lc->get('caption_for_edit')}{/if}" />
    </p> 
</form>

{if empty($service)}
	{if !empty($services)}
	    {$ml->table($services, $lc, $rq, $router_action_parameter, null, 'm_service_')}
	{else}
	    <p>
	        {$lc->get('caption_for_list_empty')}
	    </p>
	{/if}
{/if}