<form method="post" action="{$rq->href($cfg->get('router_action_parameter'), 'm_category_add')}">
    {$form->string('name')}
    {$form->text('description')}
    <p>
        <input type="submit" name="go_login" id="go_login" value="{$lc->get('caption_for_go_login')}" />
    </p>    
</form>