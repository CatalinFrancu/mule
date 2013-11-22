{* Test TPL file for tplParser.php *}
<h3>{"OpenID login"|_}</h3>

{"Two strings"|_} some text here {'on the same row'|_}

<input type=submit id="login" name="submitButton" value="{'login'|_}"/>  
<br/>

{"This string has an 'apostrophe' and a back\slash in the middle"|_}<br/><br/>
{'Here is one with "double quotes"'|_}<br/><br/>

<img src="{$wwwRoot}img/openid/openid.png" alt="{'OpenID logo'|_}"/>

{'You can read more on the %sOpenID website%s.'|_|sprintf:'<a href="http://openid.net/">':'</a>'}
