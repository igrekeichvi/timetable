<p>
    {$ml->ahref($rq->href($router_action_parameter, 'm_category_add'), $lc->get('caption_for_add_new'))}
</p>
{if !empty($list_categories)}
    
    {$ml->table($list_categories, $lc, $rq, $router_action_parameter, null, 'm_category_')}

    <p>
        {$ml->ahref($rq->href($router_action_parameter, 'm_category_add'), $lc->get('caption_for_add_new'))}
    </p>
{else}
    <p>
        {$lc->get('caption_for_list_empty')}
    </p>
{/if}