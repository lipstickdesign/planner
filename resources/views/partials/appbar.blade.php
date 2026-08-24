@php
  $vpUser = auth()->user();
  $vpSub = app()->bound('currentCompany') ? (app('currentCompany')->name ?? 'Vivu Planner') : 'Vivu Planner';
  $active = $active ?? '';
@endphp
<style>
  .vpbar-top{background:linear-gradient(135deg,#1c3155,#12284c);color:#fff}
  .vpbar-in{max-width:1180px;margin:0 auto;padding:0 20px}
  .vpbar-top .vpbar-in{display:flex;align-items:center;gap:14px;padding-top:15px;padding-bottom:15px}
  .vpbar-brand{font-weight:700;font-size:19px;line-height:1.15;font-family:'Ubuntu',system-ui,sans-serif}
  .vpbar-sub{font-size:12.5px;color:rgba(255,255,255,.72)}
  .vpbar-user{margin-left:auto;font-size:13px;color:rgba(255,255,255,.9)}
  .vpbar-tabs{background:#1a1e39}
  .vpbar-tabs .vpbar-in{display:flex;flex-wrap:wrap;padding-left:20px;padding-right:20px}
  .vpbar-tabs a{padding:15px 20px;color:rgba(255,255,255,.72);font-family:'Ubuntu',system-ui,sans-serif;font-weight:500;font-size:14px;text-decoration:none;border-bottom:3px solid transparent}
  .vpbar-tabs a:hover,.vpbar-user a:hover{color:#fff;text-decoration:none}
  .vpbar-tabs a.active{color:#fff;border-bottom-color:#fb471f}
</style>
<div class="vpbar-top"><div class="vpbar-in">
  <div>
    <div class="vpbar-brand">Vivu Planner</div>
    <div class="vpbar-sub">{{ $vpSub }}</div>
  </div>
  <div class="vpbar-user">{{ $vpUser?->name }}</div>
</div></div>
<div class="vpbar-tabs"><div class="vpbar-in">
  <a href="/dashboard">Dashboard</a>
  <a href="/dashboard#wheel">Årshjul</a>
  <a href="/dashboard#list">Eventliste</a>
  <a href="/dashboard#tasks">Oppgaver</a>
  <a href="/dashboard#klubbliv">Klubbliv</a>
  <a href="/dashboard#team">Brukere</a>
  <a href="/treningstider" class="{{ $active === 'training' ? 'active' : '' }}">Treningstider</a>
</div></div>
