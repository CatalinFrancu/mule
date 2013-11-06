<!DOCTYPE HTML>
<html>
  <head>
    <title>{$pageTitle} | Mule</title>
    <meta charset="utf-8">
    {foreach from=$cssFiles item=cssFile}
      <link type="text/css" href="{$wwwRoot}css/{$cssFile}" rel="stylesheet"/>
    {/foreach}
    {foreach from=$jsFiles item=jsFile}
      <script src="{$wwwRoot}js/{$jsFile}"></script>
    {/foreach}
  </head>

  <body>
    <div class="title">Mule</div>

    <div class="menu">
      <ul>
        <li><a href="{$wwwRoot}">{"home"|_}</a></li>
        {if $user}
          <li class="right"><a href="{$wwwRoot}auth/logout">{"logout"|_}</a></li>
          <li class="userName right">{$user->getDisplayName()}</li>
        {else}
          <li class="right"><a id="openidLink" href="{$wwwRoot}auth/login">{"OpenID login"|_}</a></li>
        {/if}
      </ul>
    </div>

    {if $flashMessage}
      <div class="flashMessage {$flashMessageType}Type">{$flashMessage}</div>
    {/if}

    <div id="template">
      {include file=$templateName}
    </div>

    <footer>
      <div id="license"></div>
    </footer>
  </body>

</html>
