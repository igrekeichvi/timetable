<form method="post" action="{$rq->href($cfg->get('router_action_parameter'), 'm_operator_edit', 'id', $operator.id)}">
	{$form->string('name_bg', $operator.name_bg)}
    {$form->string('name_en', $operator.name_en)}
    {$form->string('name_ru', $operator.name_ru)}
    <p>
        <input type="submit" name="submit" value="{$lc->get('caption_for_edit')}" />
    </p>    
</form>