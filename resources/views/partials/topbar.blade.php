@php
  $vpU = auth()->user();
  $vpCo = app()->bound('currentCompany') ? app('currentCompany') : null;
  $vpSet = ($vpCo && is_array($vpCo->settings)) ? $vpCo->settings : [];
  $vpSub = ($vpSet['subtitle'] ?? null) ?: ($vpCo->name ?? 'Årshjul');
  $vpMark = $vpCo->logo_path ?? null;
  $vpCanEdit = $vpU?->isCompanyAdmin() ?? false;
  $vpSuper = (bool) ($vpU?->is_platform_admin);
  $vpParts = preg_split('/\s+/', trim($vpU?->name ?? '')) ?: [];
  $vpInit = mb_strtoupper(mb_substr($vpParts[0] ?? '', 0, 1).(count($vpParts) > 1 ? mb_substr(end($vpParts), 0, 1) : ''));
  $active = $active ?? 'home';
@endphp
<style>
  .topbar{background:var(--brand-grad,linear-gradient(125deg,#26406e,#1c3155 55%,#1a1e39));color:#fff}
  .vptop-in{display:flex;align-items:center;gap:14px;padding:16px 28px;max-width:1240px;margin:0 auto}
  .brand{display:flex;align-items:center;gap:12px}
  .logosvg{display:block}
  .logosvg svg,.logosvg img{height:38px;width:auto;color:#fff;display:block;border-radius:6px}
  .brand h1{font-size:17px;margin:0;font-weight:500;font-family:'Ubuntu',system-ui,sans-serif}
  .brand .sub{font-size:12px;opacity:.82;font-weight:300}
  .spacer{flex:1}
  .iconbtn{background:rgba(255,255,255,.16);border:none;color:#fff;width:36px;height:36px;border-radius:50%;cursor:pointer;display:grid;place-items:center;margin-right:4px}
  .iconbtn:hover{background:rgba(255,255,255,.3)}
  .iconbtn svg{width:19px;height:19px;stroke:currentColor;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
  .usermenu{position:relative}
  .userchip{display:flex;align-items:center;gap:9px;background:rgba(255,255,255,.13);padding:6px 13px;border-radius:24px;font-size:13px;border:none;color:#fff;cursor:pointer;font-family:'Ubuntu',system-ui,sans-serif}
  .userchip:hover{background:rgba(255,255,255,.22)}
  .userchip .av{width:28px;height:28px;border-radius:50%;background:var(--accent,#fb471f);color:#3a2c00;display:grid;place-items:center;font-weight:700;font-size:11px;margin-left:-6px}
  .usermenudd{position:absolute;top:46px;right:0;background:#fff;border-radius:12px;box-shadow:0 16px 44px rgba(20,40,80,.24);border:1px solid var(--line,#e6eaf2);min-width:236px;padding:6px;display:none;z-index:70}
  .usermenudd.open{display:block}
  .usermenudd .ddi{display:flex;align-items:center;gap:9px;width:100%;text-align:left;background:none;border:none;font-family:'Ubuntu',system-ui,sans-serif;font-size:13.5px;color:var(--ink,#1a1f33);padding:9px 11px;border-radius:8px;cursor:pointer;text-decoration:none}
  .usermenudd .ddi:hover{background:#f3f7fc}
  .usermenudd .ddi.cur{color:var(--flik-blue,#2f6fd6);font-weight:600}
  .usermenudd .ddsep{font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--ink-soft,#5b6b86);padding:9px 11px 4px}
  .usermenudd .ddsep2{border-top:1px solid var(--line,#e6eaf2);margin:6px 0}
  .tabbar{background:var(--flik-blue-deep,#1a1e39)}
  .vptop-tabs{display:flex;gap:4px;max-width:1240px;margin:0 auto;padding:0 20px;flex-wrap:wrap}
  .tab{padding:15px 20px;color:rgba(255,255,255,.72);font-family:'Ubuntu',system-ui,sans-serif;font-size:14px;font-weight:500;cursor:pointer;border:none;background:none;border-bottom:3px solid transparent;line-height:1.55}
  .tab:hover{color:#fff}.tab.active{color:#fff;border-bottom-color:var(--accent,#fb471f)}
</style>
<div class="topbar"><div class="vptop-in">
  <div class="brand">
    <span class="logosvg">
      @if($vpMark)
        <img src="{{ $vpMark }}" alt="">
      @else
        <svg class="brandmark" viewBox="0 0 191.4 365" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M191.4,121.5V45l-11.7,3.6C95.2,74.9,43.6,106.1,26.6,141.5c-8,16.5-8.6,33.7-1.8,51.1c5.3,13.7,14.4,23.2,27.1,28.2c-9.8,65.7,7.5,134.3,8.3,137.4l1.7,6.8h65.7l-1.9-10.6c-1.1-6.1-2.3-12.1-3.5-17.9c-5.8-28.4-10.3-50.9,0.6-66.9c9-13.2,29-22.4,61.3-28.1l7.4-1.3l0-81.4l-13.9,9.2C162,178,124.8,198.7,92.8,205c-2.7,5.8-4.4,12.5-5.4,19.1c15.4-1.9,33.2-7.3,53.2-16.2c12.9-5.7,24.4-11.9,32.8-16.7v33.7c-33.4,6.9-54.4,17.8-65.6,34.3c-15.2,22.3-9.4,50.6-3.4,80.6c0.5,2.3,0.9,4.6,1.4,6.9H76c-3.7-17.2-12.4-64.3-7.8-111.3c0.6-9.7,1.9-20,4.9-29.5c17.1-58.2,110.6-82.7,111.5-83L191.4,121.5z M173.4,107.7c-9.9,3-29.2,9.7-49.6,20.7c-37,20-60.6,45.6-68.4,74.2c-6.3-3.4-10.9-8.8-13.8-16.5c-5-12.8-4.6-24.8,1.2-36.8c17.2-35.5,78.2-62.5,130.6-79.8V107.7z"/><path fill="currentColor" d="M41.8,83.6c-5.7,0-11.5-1.2-16.9-3.6C14.7,75.5,6.9,67.3,2.8,56.9s-3.8-21.8,0.7-32S16.3,6.9,26.7,2.8c21.5-8.3,45.7,2.4,54.1,23.9c8.3,21.5-2.4,45.7-23.9,54.1C52,82.7,46.9,83.6,41.8,83.6z M41.8,18c-2.9,0-5.8,0.5-8.6,1.6c-5.9,2.3-10.6,6.8-13.2,12.6c-2.6,5.8-2.7,12.3-0.4,18.2c2.3,5.9,6.8,10.6,12.6,13.2c5.8,2.6,12.3,2.7,18.2,0.4C62.6,59.2,68.7,45.4,64,33.2C60.3,23.8,51.3,18,41.8,18z"/></svg>
      @endif
    </span>
    <div><h1>Vivu Planner</h1><div class="sub">{{ $vpSub }}</div></div>
  </div>
  <div class="spacer"></div>
  @if($vpCanEdit)<button class="iconbtn" title="Innstillinger" onclick="openSettings()"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"/><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"/></svg></button>@endif
  <div class="usermenu">
    <button class="userchip" onclick="toggleUserMenu(event)" title="Meny"><span>{{ $vpU?->name }}</span><span class="av">{{ $vpInit }}</span></button>
    <div class="usermenudd" id="userDD"></div>
  </div>
  <form method="POST" action="{{ route('logout') }}" id="logoutForm" style="display:none">@csrf</form>
</div></div>
<div class="tabbar"><div class="vptop-tabs">
  <button class="tab {{ $active === 'home' ? 'active' : '' }}" data-view="home">Dashboard</button>
  <button class="tab {{ $active === 'wheel' ? 'active' : '' }}" data-view="wheel">Årshjul</button>
  <button class="tab {{ $active === 'list' ? 'active' : '' }}" data-view="list">Eventliste</button>
  <button class="tab {{ $active === 'tasks' ? 'active' : '' }}" data-view="tasks">Oppgaver</button>
  <button class="tab {{ $active === 'klubbliv' ? 'active' : '' }}" data-view="klubbliv">Klubbliv</button>
  @if($vpCanEdit)<button class="tab {{ $active === 'team' ? 'active' : '' }}" data-view="team">Brukere</button>@endif
  @if($vpSuper)<a class="tab {{ $active === 'training' ? 'active' : '' }}" href="/treningstider" style="text-decoration:none">Treningstider</a>@endif
</div></div>
