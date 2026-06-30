<!DOCTYPE html>
<html lang="no">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Logg inn – Vivu Planner</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box}
body{margin:0;font-family:'Ubuntu',system-ui,sans-serif;background:linear-gradient(135deg,#26406e,#1c3155 55%,#1a1e39);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.card{background:#fff;border-radius:20px;max-width:400px;width:100%;padding:32px 30px 26px;box-shadow:0 30px 80px rgba(0,0,0,.4);text-align:center}
.mk{height:60px;color:#1c3155;margin-bottom:8px}
.mk svg{height:60px;width:auto}
h1{font-size:22px;margin:2px 0 2px;color:#1b2733}
.sub{font-size:13.5px;color:#5b6b7b;margin:0 0 20px}
.err{background:#fde3e3;color:#b23535;font-size:13px;border-radius:9px;padding:9px 12px;margin-bottom:14px;text-align:left}
input[type=email],input[type=password]{width:100%;font-family:inherit;font-size:15px;padding:11px 13px;border:1px solid #e3e8ef;border-radius:10px;margin-bottom:11px;text-align:center}
input:focus{outline:none;border-color:#2f6fd6;box-shadow:0 0 0 3px rgba(47,111,214,.14)}
.rem{display:flex;align-items:center;gap:7px;font-size:13px;color:#5b6b7b;justify-content:center;margin-bottom:14px}
.rem input{width:auto}
button{width:100%;font-family:inherit;font-size:15px;font-weight:500;padding:13px;border:none;border-radius:12px;background:#2f6fd6;color:#fff;cursor:pointer}
button:hover{background:#2557b8}
.hint{margin-top:16px;border-top:1px solid #e3e8ef;padding-top:13px;font-size:12px;color:#8795a3}
</style>
</head>
<body>
<div class="card">
  <div class="mk"><svg viewBox="0 0 191.4 365" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M191.4,121.5V45l-11.7,3.6C95.2,74.9,43.6,106.1,26.6,141.5c-8,16.5-8.6,33.7-1.8,51.1c5.3,13.7,14.4,23.2,27.1,28.2c-9.8,65.7,7.5,134.3,8.3,137.4l1.7,6.8h65.7l-1.9-10.6c-1.1-6.1-2.3-12.1-3.5-17.9c-5.8-28.4-10.3-50.9,0.6-66.9c9-13.2,29-22.4,61.3-28.1l7.4-1.3l0-81.4l-13.9,9.2C162,178,124.8,198.7,92.8,205c-2.7,5.8-4.4,12.5-5.4,19.1c15.4-1.9,33.2-7.3,53.2-16.2c12.9-5.7,24.4-11.9,32.8-16.7v33.7c-33.4,6.9-54.4,17.8-65.6,34.3c-15.2,22.3-9.4,50.6-3.4,80.6c0.5,2.3,0.9,4.6,1.4,6.9H76c-3.7-17.2-12.4-64.3-7.8-111.3c0.6-9.7,1.9-20,4.9-29.5c17.1-58.2,110.6-82.7,111.5-83L191.4,121.5z M173.4,107.7c-9.9,3-29.2,9.7-49.6,20.7c-37,20-60.6,45.6-68.4,74.2c-6.3-3.4-10.9-8.8-13.8-16.5c-5-12.8-4.6-24.8,1.2-36.8c17.2-35.5,78.2-62.5,130.6-79.8V107.7z"/><path fill="currentColor" d="M41.8,83.6c-5.7,0-11.5-1.2-16.9-3.6C14.7,75.5,6.9,67.3,2.8,56.9s-3.8-21.8,0.7-32S16.3,6.9,26.7,2.8c21.5-8.3,45.7,2.4,54.1,23.9c8.3,21.5-2.4,45.7-23.9,54.1C52,82.7,46.9,83.6,41.8,83.6z M41.8,18c-2.9,0-5.8,0.5-8.6,1.6c-5.9,2.3-10.6,6.8-13.2,12.6c-2.6,5.8-2.7,12.3-0.4,18.2c2.3,5.9,6.8,10.6,12.6,13.2c5.8,2.6,12.3,2.7,18.2,0.4C62.6,59.2,68.7,45.4,64,33.2C60.3,23.8,51.3,18,41.8,18z"/></svg></div>
  <h1>Vivu Planner</h1>
  <div class="sub">Årshjul – Farsund og Lista Idrettsklubb</div>

  @if ($errors->any())
    <div class="err">{{ $errors->first() }}</div>
  @endif

  <form method="POST" action="{{ route('login') }}">
    @csrf
    <input type="email" name="email" placeholder="E-post" value="{{ old('email') }}" required autofocus>
    <input type="password" name="password" placeholder="Passord" required>
    <label class="rem"><input type="checkbox" name="remember" value="1"> Husk meg</label>
    <button type="submit">Logg inn</button>
  </form>

  <div class="hint">Demo: roger@havdurdesign.no · passord: <b>password</b></div>
</div>
</body>
</html>
