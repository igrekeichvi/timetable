<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="bg">
    <head>
        <title>{$lc->get('caption_for_management_part_title')}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <ul>
            <li>
                {$ml->ahref($rq->href($router_action_parameter, 'm_operators'), $lc->get('caption_for_operators'))}
            </li>
            <li>
                {$ml->ahref($rq->href($router_action_parameter, 'm_services'), $lc->get('caption_for_services'))}
            </li>
        </ul>
        {$PAGE_CONTENTS}
    </body>
</html>