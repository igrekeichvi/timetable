{if !empty($message_flash)}
	<p>
		{$message_flash}
	</p>
{/if}

<form method="post" action="{$rq->href($cfg->get('router_action_parameter'), 'm_operator_add')}">
    {$form->string('name_bg')}
    {$form->string('name_en')}
    {$form->string('name_ru')}
    <p>
        <input type="submit" name="submit" value="{$lc->get('caption_for_create')}" />
    </p>    
</form>

{if !empty($operators)}
    {$ml->table($operators, $lc, $rq, $router_action_parameter, null, 'm_operator_')}
{else}
    <p>
        {$lc->get('caption_for_list_empty')}
    </p>
{/if}