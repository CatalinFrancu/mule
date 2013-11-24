{'Hi,'|_}

{'You are receiving this message because you requested a one-time login token. To complete your login, please visit this link within one hour:'|_}

{$homePage}auth/emailLogin?token={$token}&email={$email|escape:url}

{'If you did not request this token, please discard this message.'|_}

{'Thank you,'|_}
{$signature}
