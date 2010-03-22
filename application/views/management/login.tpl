<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="bg">
    <head>
        <title>{$lc->get('caption_for_login_title')}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <form method="POST" action="{$rq->href($cfg->get('router_action_parameter'), 'm_login')}" enctype="multipar/form-data">
            {$form->string('username')}
            {$form->string('password', '', null, true)}
            <p>
                <input type="submit" name="go_login" id="go_login" value="{$lc->get('caption_for_go_login')}" />
            </p>    
        </form>
    </body>
</html>