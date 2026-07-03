<!DOCTYPE html>
<html lang="no">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Glemt passord – Vivu Planner</title>
<link rel="icon" type="image/jpeg" href="/favicon.jpg">
<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box}
body{margin:0;font-family:"Ubuntu",system-ui,sans-serif;background:linear-gradient(135deg,#26406e,#1c3155 55%,#1a1e39);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.card{background:#fff;border-radius:20px;max-width:400px;width:100%;padding:32px 30px 26px;box-shadow:0 30px 80px rgba(0,0,0,.4);text-align:center}
h1{font-size:21px;margin:2px 0 6px;color:#1a1f33}
p{font-size:13.5px;color:#5b6b86;margin:0 0 18px;line-height:1.5}
.ok{background:#dff3e4;color:#1f7a42;font-size:13px;border-radius:9px;padding:10px 12px;margin-bottom:14px}
.err{background:#fde3e3;color:#b23535;font-size:13px;border-radius:9px;padding:10px 12px;margin-bottom:14px}
input{width:100%;font-family:inherit;font-size:15px;padding:12px 14px;border:1px solid #e6ebf2;border-radius:11px;margin-bottom:11px;text-align:center;background:#fbfcfe}
input:focus{outline:none;border-color:#2f6fd6;box-shadow:0 0 0 3px rgba(47,111,214,.14)}
button{width:100%;font-family:inherit;font-size:15px;font-weight:500;padding:13px;border:none;border-radius:12px;background:#2f6fd6;color:#fff;cursor:pointer}
button:hover{background:#2557b8}
.back{display:inline-block;margin-top:16px;font-size:13px;color:#5b6b86;text-decoration:none}
.back:hover{color:#2f6fd6}
</style>
</head>
<body>
<div class="card">
  <h1>Glemt passord?</h1>
  <p>Skriv inn e-posten din, så sender vi deg en lenke for å lage nytt passord.</p>
  @if (session('status'))<div class="ok">{{ session('status') }}</div>@endif
  <form method="POST" action="{{ route('password.email') }}">
    @csrf
    <input type="email" name="email" placeholder="E-post" value="{{ old('email') }}" required autofocus>
    <button type="submit">Send lenke</button>
  </form>
  <a class="back" href="{{ route('login') }}">← Tilbake til innlogging</a>
</div>
</body>
</html>
