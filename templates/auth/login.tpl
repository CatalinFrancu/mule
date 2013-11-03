<h3>Autentificare cu OpenID</h3>

<form name="loginForm" method="post" action="{$wwwRoot}auth/login">
  OpenID:
  <input type="text" name="openid" value="{$openid}" size="50" autofocus="autofocus"/>
  <input type=submit id="login" name="submitButton" value="Autentificare"/>  
</form>
<br/>

Dacă ai un cont Google sau Yahoo, îl poți folosi ca OpenID:<br/><br/>

<div id="openidProviders">
  <a href="{$wwwRoot}auth/login?openid=google"><img src="{$wwwRoot}img/openid/google.png" alt="Autentificare cu un cont Google"/></a>
  <a href="{$wwwRoot}auth/login?openid=yahoo"><img src="{$wwwRoot}img/openid/yahoo.png" alt="Autentificare cu un cont Yahoo"/></a>
</div>

<h3>Ce este OpenID?</h3>

<div id="openidHeadline">
  <img src="{$wwwRoot}img/openid/openid.png" alt="Logo OpenID"/>

  <span>este o modalitate mai rapidă și mai ușoară de a te autentifica pe Internet.</span>
</div>

<ul>
  <li>Nu este nevoie să îți creezi un cont nou pe acest site;</li>
  <li>Nu este nevoie să memorezi o parolă în plus;</li>
  <li>Un cont OpenID, odată creat, poate fi refolosit pe orice site care admite OpenID, iar numărul acestora este în creștere;</li>
  <li>Sunt șanse mari să ai deja un OpenID, deoarece multe site-uri mari (Google, Yahoo și altele) servesc și ca furnizori de OpenID;</li>
</ul>

Poți citi mai multe informații pe <a href="http://openid.net/">site-ul OpenID</a> (în limba engleză).

<h3>Cum obțin un OpenID?</h3>

Vizitează <a href="http://openid.net/get-an-openid/">lista furnizorilor de OpenID</a>.
