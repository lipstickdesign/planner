<!DOCTYPE html>
<html lang="no">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700&display=swap" rel="stylesheet">
<title>Treningstider · Lag</title>
<style>
  :root{--bg:#f3f5fb;--card:#fff;--ink:#1a1f33;--ink-soft:#5b6b86;--line:#e6eaf2;--flik:#2f6fd6;--grey:#8795a3;--radius:16px;--accent:#fb471f;--flik-blue:#2f6fd6}
  *{box-sizing:border-box}
  body{margin:0;background:var(--bg);color:var(--ink);font-family:'Ubuntu',system-ui,sans-serif;font-size:14px;line-height:1.55}
  .wrap{max-width:1100px;margin:0 auto;padding:24px 20px 70px}
  a{color:var(--flik);text-decoration:none}a:hover{text-decoration:underline}
  h1{font-size:23px;font-weight:700;margin:6px 0 2px}
  .sub{color:var(--ink-soft);font-size:13px;margin:0 0 16px}
  .subnav{display:flex;gap:6px;margin:14px 0 20px}
  .subnav a{padding:7px 14px;border-radius:9px;border:1px solid var(--line);background:var(--card);color:var(--ink-soft);font-weight:500}
  .subnav a.active{border-color:var(--flik);color:var(--flik);background:#eef4fd}
  h2{font-size:15px;font-weight:700;margin:22px 0 8px;display:flex;align-items:center;gap:8px}
  .idchip{display:inline-block;width:11px;height:11px;border-radius:3px}
  .card{background:var(--card);border:1px solid var(--line);border-radius:var(--radius);overflow:hidden}
  table{border-collapse:collapse;width:100%;font-size:13px}
  th,td{text-align:left;padding:9px 12px;border-bottom:1px solid var(--line)}
  th{font-size:11px;text-transform:uppercase;letter-spacing:.04em;color:var(--grey);font-weight:600}
  tr:last-child td{border-bottom:none}
  tbody tr{cursor:pointer}
  tbody tr:hover{background:#f7f9fd}
  td.num,th.num{text-align:right;font-variant-numeric:tabular-nums}
  .name{font-weight:600}
  .muted{color:var(--grey)}
  .btn{font-family:inherit;font-size:13px;font-weight:500;padding:9px 15px;border-radius:10px;border:1px solid var(--line);background:#fff;color:var(--flik);cursor:pointer}
  .btn.solid{background:var(--flik);color:#fff;border-color:var(--flik)}
  .btn.sm{padding:6px 11px;font-size:12.5px}
  .btn.danger{color:#b23535;border-color:#f0d3d3}
  .overlay{position:fixed;inset:0;background:rgba(20,30,55,.42);display:none;align-items:flex-start;justify-content:center;padding:40px 16px;z-index:100;overflow:auto}
  .overlay.open{display:flex}
  .modal{background:#fff;border-radius:16px;max-width:560px;width:100%;box-shadow:0 24px 70px rgba(20,40,80,.28)}
  .mhead{padding:18px 22px;border-bottom:1px solid var(--line);display:flex;justify-content:space-between;align-items:center}
  .mhead h3{margin:0;font-size:16px;font-weight:700}
  .mhead .x{background:none;border:none;font-size:22px;color:var(--grey);cursor:pointer;line-height:1}
  .mbody{padding:18px 22px}
  .f label{display:block;font-size:12.5px;font-weight:600;color:var(--ink-soft);margin:12px 0 4px}
  .f input,.f select{width:100%;font-family:inherit;font-size:14px;padding:9px 11px;border:1px solid var(--line);border-radius:9px;background:#fbfcfe;color:var(--ink)}
  .f input:focus,.f select:focus{outline:none;border-color:var(--flik);box-shadow:0 0 0 3px rgba(47,111,214,.14)}
  .two{display:grid;grid-template-columns:1fr 1fr;gap:12px}
  .actions{display:flex;justify-content:space-between;gap:8px;margin-top:20px;flex-wrap:wrap}
  .empty{color:var(--ink-soft);padding:24px;text-align:center}
</style>
</head>
<body>
@include('partials.topbar', ['active' => 'training'])
@include('partials.topbar-js')
<div class="wrap">
  <h1>Treningstider</h1>
  <p class="sub">Lagene som skal fordeles treningstid. Klikk et lag for å redigere.</p>
  <nav class="subnav">
    <a href="/treningstider">Kontroll</a>
    <a href="/treningstider/lag" class="active">Lag</a>
  </nav>
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
    <span class="sub" id="ttCount"></span>
    <button class="btn solid sm" onclick="ttEdit(null)">+ Nytt lag</button>
  </div>
  <div id="lagHost"></div>
</div>

<div class="overlay" id="ttOverlay"><div class="modal" id="ttModal"></div></div>

<script>
  window.TT_TEAMS = @json($teams);
  window.TT_CATS = @json($cats);
  window.TT_CSRF = '{{ csrf_token() }}';
</script>
<script>
  var TEAMS = window.TT_TEAMS || [], CATS = window.TT_CATS || [], CSRF = window.TT_CSRF;
  function esc(s){return String(s==null?'':s).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;');}
  function val(id){var el=document.getElementById(id);return el?el.value.trim():'';}
  async function api(method,url,body){
    var r=await fetch(url,{method:method,headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF},body:body?JSON.stringify(body):undefined});
    if(r.status===422){var j=await r.json();throw new Error(Object.values(j.errors||{}).flat().join('\n')||'Ugyldige data');}
    if(!r.ok)throw new Error('Noe gikk galt ('+r.status+').');
    return r.status===204?null:r.json();
  }
  function render(){
    var host=document.getElementById('lagHost');
    document.getElementById('ttCount').textContent=TEAMS.length+' lag';
    if(!TEAMS.length){host.innerHTML='<div class="card empty">Ingen lag ennå. Legg til med «Nytt lag».</div>';return;}
    var groups={};TEAMS.forEach(function(t){var k=t.sport||'Uten idrett';(groups[k]=groups[k]||[]).push(t);});
    var html='';
    Object.keys(groups).sort(function(a,b){return a.localeCompare(b,'no');}).forEach(function(g){
      var lag=groups[g].slice().sort(function(a,b){return String(a.name).localeCompare(String(b.name),'no');});
      var color=(lag[0]&&lag[0].color)||'#8795a3';
      html+='<h2><span class="idchip" style="background:'+esc(color)+'"></span>'+esc(g)+' <span class="muted" style="font-weight:400">('+lag.length+')</span></h2>'+
        '<div class="card"><table><thead><tr><th>Lag</th><th>Årskull</th><th>Trinn</th><th class="num">Spillere</th><th class="num">Trenere</th><th class="num">Økter/uke</th><th>Areal inne</th><th>Areal ute</th></tr></thead><tbody>'+
        lag.map(function(t){return '<tr onclick="ttEdit('+t.id+')">'+
          '<td class="name">'+esc(t.name)+'</td>'+
          '<td class="muted">'+esc(t.birth_year||'–')+'</td>'+
          '<td class="muted">'+esc(t.grade||'–')+'</td>'+
          '<td class="num">'+(t.players==null?'–':t.players)+'</td>'+
          '<td class="num">'+(t.coaches==null?'–':t.coaches)+'</td>'+
          '<td class="num">'+esc(t.sessions_per_week||'–')+'</td>'+
          '<td class="muted">'+esc(t.area_indoor||'–')+'</td>'+
          '<td class="muted">'+esc(t.area_outdoor||'–')+'</td></tr>';}).join('')+
        '</tbody></table></div>';
    });
    host.innerHTML=html;
  }
  function closeModal(){document.getElementById('ttOverlay').classList.remove('open');}
  function ttEdit(id){
    var t=id?TEAMS.find(function(x){return x.id===id;}):{};
    if(!t)return;
    var catOpts='<option value="">– idrett –</option>'+CATS.map(function(c){return '<option value="'+c.id+'"'+(t.category_id===c.id?' selected':'')+'>'+esc(c.name)+'</option>';}).join('');
    document.getElementById('ttModal').innerHTML=
      '<div class="mhead"><h3>'+(id?'Rediger lag':'Nytt lag')+'</h3><button class="x" onclick="closeModal()">&times;</button></div>'+
      '<div class="mbody"><form class="f" onsubmit="ttSave(event,'+(id||'null')+')">'+
        '<div class="two"><div><label>Lagnavn *</label><input id="t_name" required></div><div><label>Idrett</label><select id="t_cat">'+catOpts+'</select></div></div>'+
        '<div class="two"><div><label>Årskull</label><input id="t_birth" placeholder="f.eks. 2015 eller 2014-2015"></div><div><label>Skoletrinn</label><input id="t_grade"></div></div>'+
        '<div class="two"><div><label>Antall spillere</label><input id="t_players" type="number" min="0"></div><div><label>Antall trenere</label><input id="t_coaches" type="number" min="0"></div></div>'+
        '<div class="two"><div><label>Økter per uke</label><input id="t_sessions" placeholder="f.eks. 2 eller 2-3"></div><div><label>&nbsp;</label><label style="display:flex;align-items:center;gap:8px;font-weight:400"><input id="t_indoor" type="checkbox" style="width:auto" '+(t.requires_indoor?'checked':'')+'>Krever innendørsøkt</label></div></div>'+
        '<div class="two"><div><label>Areal inne</label><input id="t_ai" placeholder="hel hall / halv hall"></div><div><label>Areal ute</label><input id="t_au" placeholder="hel bane / halv bane"></div></div>'+
        '<div class="actions">'+(id?'<button type="button" class="btn danger sm" onclick="ttDelete('+id+')">Slett lag</button>':'<span></span>')+
          '<span style="display:flex;gap:8px"><button type="button" class="btn" onclick="closeModal()">Avbryt</button><button class="btn solid" type="submit">Lagre</button></span></div>'+
      '</form></div>';
    document.getElementById('ttOverlay').classList.add('open');
    document.getElementById('t_name').value=t.name||'';
    document.getElementById('t_birth').value=t.birth_year||'';
    document.getElementById('t_grade').value=t.grade||'';
    document.getElementById('t_players').value=(t.players==null?'':t.players);
    document.getElementById('t_coaches').value=(t.coaches==null?'':t.coaches);
    document.getElementById('t_sessions').value=t.sessions_per_week||'';
    document.getElementById('t_ai').value=t.area_indoor||'';
    document.getElementById('t_au').value=t.area_outdoor||'';
  }
  async function ttSave(ev,id){
    ev.preventDefault();
    var body={
      name:val('t_name'),
      category_id:(+document.getElementById('t_cat').value||null),
      birth_year:val('t_birth')||null,
      grade:val('t_grade')||null,
      players:(val('t_players')===''?null:+val('t_players')),
      coaches:(val('t_coaches')===''?null:+val('t_coaches')),
      sessions_per_week:val('t_sessions')||null,
      area_indoor:val('t_ai')||null,
      area_outdoor:val('t_au')||null,
      requires_indoor:document.getElementById('t_indoor').checked
    };
    try{
      var card=id?await api('PUT','/treningstider/lag/'+id,body):await api('POST','/treningstider/lag',body);
      if(id){var i=TEAMS.findIndex(function(x){return x.id===id;});if(i>=0)TEAMS[i]=card;}else{TEAMS.push(card);}
      closeModal();render();
    }catch(err){alert(err.message);}
  }
  async function ttDelete(id){
    if(!confirm('Slette dette laget?'))return;
    try{await api('DELETE','/treningstider/lag/'+id);TEAMS=TEAMS.filter(function(x){return x.id!==id;});closeModal();render();}
    catch(err){alert(err.message);}
  }
  render();
</script>
</body>
</html>
