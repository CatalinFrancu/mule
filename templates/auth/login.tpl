<h3>{"OpenID login"|_}</h3>

<form method="post" action="login">
  OpenID:
  <input type="text" name="openid" value="{$openid}" size="50" autofocus="autofocus"/>
  <input type=submit id="login" name="submitButton" value="{'login'|_}"/>  
</form>
<br/>

{"If you have a Google or Yahoo account, you can use it as an OpenID:"|_}<br/><br/>

<div id="openidProviders">
  <a href="{$wwwRoot}auth/login?openid=google"><img src="{$wwwRoot}img/openid/google.png" alt="{'Google account login'|_}"/></a>
  <a href="{$wwwRoot}auth/login?openid=yahoo"><img src="{$wwwRoot}img/openid/yahoo.png" alt="{'Yahoo account login'|_}"/></a>
</div>

<h3>{"Email login"|_}</h3>

{"If your OpenID provider is currently down, you can %srequest a one-time token%s via email."|_|sprintf:'<a href="emailLogin">':'</a>'}

<h3>{"What is OpenID?"|_}</h3>

<div id="openidHeadline">
  <img src="{$wwwRoot}img/openid/openid.png" alt="{'OpenID logo'|_}"/>
  <span>{'is a faster, easier way to log in on the Internet.'|_}</span>
</div>

<ul>
  <li>{'No need to create a new account on this site;'|_}</li>
  <li>{'No need to remember yet another password;'|_}</li>
  <li>{'Once created, an OpenID account can be reused on any website that accepts OpenIDs;'|_}</li>
  <li>{'Chances are you already have an OpenID, because many popular sites (Google, Yahoo and others) act as OpenID providers.'|_}</li>
</ul>

{'You can read more on the %sOpenID website%s.'|_|sprintf:'<a href="http://openid.net/">':'</a>'}

<h3>{'How do I obtain an OpenID?'|_}</h3>

{'Check %sthe list of OpenID providers%s.'|_|sprintf:'<a href="http://openid.net/get-an-openid/">':'</a>'}
