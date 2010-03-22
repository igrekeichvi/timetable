<form method="post" action="{$rq->href($cfg->get('router_action_parameter'), 'm_service_add')}">
	{$form->enum('operator', $operators)}
	{$form->string('code')}
    {$form->string('name_bg')}
    {$form->string('name_en')}
    {$form->string('name_ru')}
    <p>
        <input type="submit" name="submit" value="{$lc->get('caption_for_create')}" />
    </p>    
</form>

{if !empty($services)}
    {$ml->table($services, $lc, $rq, $router_action_parameter, null, 'm_service_')}
{else}
    <p>
        {$lc->get('caption_for_list_empty')}
    </p>
{/if}