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
    <footer>
      <div id="license">
      </div>
    </footer>
  </body>

</html>
