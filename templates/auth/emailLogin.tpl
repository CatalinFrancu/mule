<h3>{"Email login"|_}</h3>

<form method="post" action="emailLogin">
  {"email"|_}:
  <input type="text" name="email" value="{$email}" size="50" autofocus="autofocus"/>
  <input type=submit id="login" name="submitButton" value="{'login'|_}"/>  
</form>
<br/>

{'You can use this form to request a one-time token to login via email. This may be necessary in two cases:'|_}

<ul>
  <li>{'Your OpenID provider is currently down;'|_}</li>
  <li>{'Your account was migrated from another system and is not linked with any OpenID yet.'|_}</li>
</ul>
