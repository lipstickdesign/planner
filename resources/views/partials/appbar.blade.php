@php
  $vpUser = auth()->user();
  $vpCo = app()->bound('currentCompany') ? app('currentCompany') : null;
  $vpSub = $vpCo->name ?? 'Vivu Planner';
  $vpMark = $vpCo->logo_path ?? null;
  $active = $active ?? '';
@endphp
<style>
  .vpbar-top{background:linear-gradient(135deg,#1c3155,#12284c);color:#fff}
  .vpbar-in{max-width:1180px;margin:0 auto;padding:0 20px}
  .vpbar-top .vpbar-in{display:flex;align-items:center;gap:13px;padding-top:14px;padding-bottom:14px}
  .vpbar-logo{width:34px;height:38px;color:#fff;flex:none;display:block}
  .vpbar-logo svg,.vpbar-logo img{width:100%;height:100%;display:block;object-fit:contain}
  .vpbar-brand h1{font-family:'Ubuntu',system-ui,sans-serif;font-weight:700;font-size:19px;line-height:1.1;margin:0;color:#fff}
  .vpbar-sub{font-size:12.5px;color:rgba(255,255,255,.72)}
  .vpbar-spacer{flex:1}
  .vpbar-icon{background:rgba(255,255,255,.13);border:none;color:#fff;width:37px;height:37px;border-radius:50%;display:grid;place-items:center;cursor:pointer;text-decoration:none;flex:none}
  .vpbar-icon:hover{background:rgba(255,255,255,.2)}
  .vpbar-icon svg{width:20px;height:20px;fill:none;stroke:currentColor;stroke-width:1.8}
  .vpbar-userwrap{position:relative}
  .vpbar-chip{display:flex;align-items:center;gap:8px;background:rgba(255,255,255,.13);padding:7px 15px;border-radius:22px;font-size:13px;border:none;color:#fff;cursor:pointer;font-family:'Ubuntu',system-ui,sans-serif}
  .vpbar-chip:hover{background:rgba(255,255,255,.2)}
  .vpbar-dd{position:absolute;right:0;top:46px;background:#fff;color:#1a1f33;border-radius:12px;box-shadow:0 12px 40px rgba(20,40,80,.18);padding:6px;min-width:180px;display:none;z-index:60}
  .vpbar-dd.open{display:block}
  .vpbar-dd a,.vpbar-dd button{display:block;width:100%;text-align:left;background:none;border:none;font-family:'Ubuntu',system-ui,sans-serif;font-size:13.5px;color:#1a1f33;padding:9px 11px;border-radius:8px;cursor:pointer;text-decoration:none}
  .vpbar-dd a:hover,.vpbar-dd button:hover{background:#f1f6fc}
  .vpbar-tabs{background:#1a1e39}
  .vpbar-tabs .vpbar-in{display:flex;flex-wrap:wrap}
  .vpbar-tabs a{padding:15px 20px;color:rgba(255,255,255,.72);font-family:'Ubuntu',system-ui,sans-serif;font-weight:500;font-size:14px;text-decoration:none;border-bottom:3px solid transparent}
  .vpbar-tabs a:hover{color:#fff;text-decoration:none}
  .vpbar-tabs a.active{color:#fff;border-bottom-color:#fb471f}
</style>
<div class="vpbar-top"><div class="vpbar-in">
  <span class="vpbar-logo">
    @if($vpMark)
      <img src="{{ $vpMark }}" alt="">
    @else
      <svg viewBox="0 0 191.4 365" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M191.4,121.5V45l-11.7,3.6C95.2,74.9,43.6,106.1,26.6,141.5c-8,16.5-8.6,33.7-1.8,51.1c5.3,13.7,14.4,23.2,27.1,28.2c-9.8,65.7,7.5,134.3,8.3,137.4l1.7,6.8h65.7l-1.9-10.6c-1.1-6.1-2.3-12.1-3.5-17.9c-5.8-28.4-10.3-50.9,0.6-66.9c9-13.2,29-22.4,61.3-28.1l7.4-1.3l0-81.4l-13.9,9.2C162,178,124.8,198.7,92.8,205c-2.7,5.8-4.4,12.5-5.4,19.1c15.4-1.9,33.2-7.3,53.2-16.2c12.9-5.7,24.4-11.9,32.8-16.7v33.7c-33.4,6.9-54.4,17.8-65.6,34.3c-15.2,22.3-9.4,50.6-3.4,80.6c0.5,2.3,0.9,4.6,1.4,6.9H76c-3.7-17.2-12.4-64.3-7.8-111.3c0.6-9.7,1.9-20,4.9-29.5c17.1-58.2,110.6-82.7,111.5-83L191.4,121.5z M173.4,107.7c-9.9,3-29.2,9.7-49.6,20.7c-37,20-60.6,45.6-68.4,74.2c-6.3-3.4-10.9-8.8-13.8-16.5c-5-12.8-4.6-24.8,1.2-36.8c17.2-35.5,78.2-62.5,130.6-79.8V107.7z"/><path fill="currentColor" d="M41.8,83.6c-5.7,0-11.5-1.2-16.9-3.6C14.7,75.5,6.9,67.3,2.8,56.9s-3.8-21.8,0.7-32S16.3,6.9,26.7,2.8c21.5-8.3,45.7,2.4,54.1,23.9c8.3,21.5-2.4,45.7-23.9,54.1C52,82.7,46.9,83.6,41.8,83.6z M41.8,18c-2.9,0-5.8,0.5-8.6,1.6c-5.9,2.3-10.6,6.8-13.2,12.6c-2.6,5.8-2.7,12.3-0.4,18.2c2.3,5.9,6.8,10.6,12.6,13.2c5.8,2.6,12.3,2.7,18.2,0.4C62.6,59.2,68.7,45.4,64,33.2C60.3,23.8,51.3,18,41.8,18z"/></svg>
    @endif
  </span>
  <div class="vpbar-brand"><h1>Vivu Planner</h1><div class="vpbar-sub">{{ $vpSub }}</div></div>
  <span class="vpbar-spacer"></span>
  <a class="vpbar-icon" href="/dashboard#settings" title="Innstillinger">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"/><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"/></svg>
  </a>
  <div class="vpbar-userwrap">
    <button class="vpbar-chip" onclick="vpToggleUser(event)" title="Meny"><span>{{ $vpUser?->name }}</span></button>
    <div class="vpbar-dd" id="vpUserDD">
      <a href="/dashboard">Til Vivu Planner</a>
      <button type="button" onclick="document.getElementById('vpLogout').submit()">Logg ut</button>
    </div>
  </div>
</div></div>
<form method="POST" action="{{ route('logout') }}" id="vpLogout" style="display:none">@csrf</form>
<div class="vpbar-tabs"><div class="vpbar-in">
  <a href="/dashboard">Dashboard</a>
  <a href="/dashboard#wheel">Årshjul</a>
  <a href="/dashboard#list">Eventliste</a>
  <a href="/dashboard#tasks">Oppgaver</a>
  <a href="/dashboard#klubbliv">Klubbliv</a>
  <a href="/dashboard#team">Brukere</a>
  <a href="/treningstider" class="{{ $active === 'training' ? 'active' : '' }}">Treningstider</a>
</div></div>
<script>
  function vpToggleUser(e){e.stopPropagation();var d=document.getElementById('vpUserDD');if(d)d.classList.toggle('open');}
  document.addEventListener('click',function(){var d=document.getElementById('vpUserDD');if(d)d.classList.remove('open');});
</script>
