<!DOCTYPE html>
<html lang="no">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700&display=swap" rel="stylesheet">
<title>Treningstider · Anlegg</title>
<style>
  :root{--bg:#f3f5fb;--card:#fff;--ink:#1a1f33;--ink-soft:#5b6b86;--line:#e6eaf2;--flik:#2f6fd6;--grey:#8795a3;--radius:16px;--accent:#fb471f}
  *{box-sizing:border-box}
  body{margin:0;background:var(--bg);color:var(--ink);font-family:'Ubuntu',system-ui,sans-serif;font-size:14px;line-height:1.55}
  .wrap{max-width:1000px;margin:0 auto;padding:24px 20px 70px}
  a{color:var(--flik);text-decoration:none}a:hover{text-decoration:underline}
  h1{font-size:23px;font-weight:700;margin:6px 0 2px}
  .sub{color:var(--ink-soft);font-size:13px;margin:0 0 16px}
  .subnav{display:flex;gap:6px;margin:14px 0 20px}
  .subnav a{padding:7px 14px;border-radius:9px;border:1px solid var(--line);background:var(--card);color:var(--ink-soft);font-weight:500}
  .subnav a.active{border-color:var(--flik);color:var(--flik);background:#eef4fd}
  .card{background:var(--card);border:1px solid var(--line);border-radius:var(--radius);overflow:hidden}
  table{border-collapse:collapse;width:100%;font-size:13px}
  th,td{text-align:left;padding:10px 13px;border-bottom:1px solid var(--line)}
  th{font-size:11px;text-transform:uppercase;letter-spacing:.04em;color:var(--grey);font-weight:600}
  tr:last-child td{border-bottom:none}
  tbody tr{cursor:pointer}tbody tr:hover{background:#f7f9fd}
  td.num,th.num{text-align:right;font-variant-numeric:tabular-nums}
  .name{font-weight:600}
  .muted{color:var(--grey)}
  .pill{display:inline-block;font-size:11px;font-weight:600;padding:2px 9px;border-radius:20px}
  .pill.aktiv{background:#e7f5ee;color:#1f7a46}
  .pill.kommende{background:#fdf1dc;color:#8a5a12}
  .tag{display:inline-block;font-size:11.5px;background:#eef2f9;color:var(--ink-soft);border-radius:6px;padding:2px 8px;margin:0 4px 4px 0}
  .btn{font-family:inherit;font-size:13px;font-weight:500;padding:9px 15px;border-radius:10px;border:1px solid var(--line);background:#fff;color:var(--flik);cursor:pointer}
  .btn.solid{background:var(--flik);color:#fff;border-color:var(--flik)}
  .btn.sm{padding:6px 11px;font-size:12.5px}
  .btn.danger{color:#b23535;border-color:#f0d3d3}
  .overlay{position:fixed;inset:0;background:rgba(20,30,55,.42);display:none;align-items:flex-start;justify-content:center;padding:40px 16px;z-index:100;overflow:auto}
  .overlay.open{display:flex}
  .modal{background:#fff;border-radius:16px;max-width:520px;width:100%;box-shadow:0 24px 70px rgba(20,40,80,.28)}
  .mhead{padding:18px 22px;border-bottom:1px solid var(--line);display:flex;justify-content:space-between;align-items:center}
  .mhead h3{margin:0;font-size:16px;font-weight:700}
  .mhead .x{background:none;border:none;font-size:22px;color:var(--grey);cursor:pointer;line-height:1}
  .mbody{padding:18px 22px}
  .f label{display:block;font-size:12.5px;font-weight:600;color:var(--ink-soft);margin:12px 0 4px}
  .f input,.f select{width:100%;font-family:inherit;font-size:14px;padding:9px 11px;border:1px solid var(--line);border-radius:9px;background:#fbfcfe;color:var(--ink)}
  .f input:focus,.f select:focus{outline:none;border-color:var(--flik);box-shadow:0 0 0 3px rgba(47,111,214,.14)}
  .two{display:grid;grid-template-columns:1fr 1fr;gap:12px}
  .sports{display:flex;flex-wrap:wrap;gap:8px;margin-top:6px}
  .sports label{display:flex;align-items:center;gap:6px;font-weight:400;font-size:13px;background:#f5f7fb;border:1px solid var(--line);border-radius:8px;padding:6px 10px;margin:0;color:var(--ink);cursor:pointer}
  .sports label input{width:auto}
  .actions{display:flex;justify-content:space-between;gap:8px;margin-top:20px;flex-wrap:wrap}
  .empty{color:var(--ink-soft);padding:24px;text-align:center}
</style>
</head>
<body>
@include('partials.topbar', ['active' => 'training'])
@include('partials.topbar-js')
<div class="wrap">
  <h1>Treningstider</h1>
  <p class="sub">Anleggene FLIK disponerer – haller og baner. Klikk et anlegg for å redigere.</p>
  <nav class="subnav">
    <a href="/treningstider">Kontroll</a>
    <a href="/treningstider/lag">Lag</a>
    <a href="/treningstider/anlegg" class="active">Anlegg</a>
  </nav>
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
    <span class="sub" id="tfCount"></span>
    <button class="btn solid sm" onclick="tfEdit(null)">+ Nytt anlegg</button>
  </div>
  <div id="anleggHost"></div>
</div>

<div class="overlay" id="tfOverlay"><div class="modal" id="tfModal"></div></div>

<script>
  window.TF_FAC = @json($facilities);
  window.TF_SPORTS = @json($sports);
  window.TF_CSRF = '{{ csrf_token() }}';
</script>
<script>
  var FAC = window.TF_FAC || [], SPORTS = window.TF_SPORTS || [], CSRF = window.TF_CSRF;
  var TYPES = ['hall','kunstgress','gress','friidrett','annet'];
  function esc(s){return String(s==null?'':s).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;');}
  function val(id){var el=document.getElementById(id);return el?el.value.trim():'';}
  async function api(method,url,body){
    var r=await fetch(url,{method:method,headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF},body:body?JSON.stringify(body):undefined});
    if(r.status===422){var j=await r.json();throw new Error(Object.values(j.errors||{}).flat().join('\n')||'Ugyldige data');}
    if(!r.ok)throw new Error('Noe gikk galt ('+r.status+').');
    return r.status===204?null:r.json();
  }
  function render(){
    var host=document.getElementById('anleggHost');
    document.getElementById('tfCount').textContent=FAC.length+' anlegg';
    if(!FAC.length){host.innerHTML='<div class="card empty">Ingen anlegg ennå. Legg til med «Nytt anlegg».</div>';return;}
    var rows=FAC.slice().sort(function(a,b){return String(a.name).localeCompare(String(b.name),'no');}).map(function(f){
      var sports=(f.allowed_sports||[]).map(function(s){return '<span class="tag">'+esc(s)+'</span>';}).join('');
      return '<tr onclick="tfEdit('+f.id+')"><td class="name">'+esc(f.name)+'</td>'+
        '<td class="muted">'+esc(f.type||'–')+'</td>'+
        '<td class="num">'+(f.zones||1)+'</td>'+
        '<td><span class="pill '+(f.status==='kommende'?'kommende':'aktiv')+'">'+esc(f.status||'aktiv')+'</span></td>'+
        '<td>'+(sports||'<span class="muted">–</span>')+'</td></tr>';
    }).join('');
    host.innerHTML='<div class="card"><table><thead><tr><th>Anlegg</th><th>Type</th><th class="num">Soner</th><th>Status</th><th>Tillatte idretter</th></tr></thead><tbody>'+rows+'</tbody></table></div>';
  }
  function closeModal(){document.getElementById('tfOverlay').classList.remove('open');}
  function tfEdit(id){
    var f=id?FAC.find(function(x){return x.id===id;}):{allowed_sports:[]};
    if(!f)return;
    var typeOpts=TYPES.map(function(t){return '<option value="'+t+'"'+(f.type===t?' selected':'')+'>'+t+'</option>';}).join('');
    var chosen=f.allowed_sports||[];
    var sportsBoxes=SPORTS.map(function(s){return '<label><input type="checkbox" class="tf_sport" value="'+esc(s)+'"'+(chosen.indexOf(s)>=0?' checked':'')+'>'+esc(s)+'</label>';}).join('');
    document.getElementById('tfModal').innerHTML=
      '<div class="mhead"><h3>'+(id?'Rediger anlegg':'Nytt anlegg')+'</h3><button class="x" onclick="closeModal()">&times;</button></div>'+
      '<div class="mbody"><form class="f" onsubmit="tfSave(event,'+(id||'null')+')">'+
        '<label>Navn *</label><input id="f_name" required>'+
        '<div class="two"><div><label>Type</label><select id="f_type">'+typeOpts+'</select></div>'+
          '<div><label>Antall soner</label><input id="f_zones" type="number" min="1" max="20"></div></div>'+
        '<label>Status</label><select id="f_status"><option value="aktiv">Aktiv</option><option value="kommende">Kommende (ikke i bruk ennå)</option></select>'+
        '<label>Tillatte idretter</label><div class="sports">'+(sportsBoxes||'<span class="muted">Ingen idretter registrert</span>')+'</div>'+
        '<div class="actions">'+(id?'<button type="button" class="btn danger sm" onclick="tfDelete('+id+')">Slett</button>':'<span></span>')+
          '<span style="display:flex;gap:8px"><button type="button" class="btn" onclick="closeModal()">Avbryt</button><button class="btn solid" type="submit">Lagre</button></span></div>'+
      '</form></div>';
    document.getElementById('tfOverlay').classList.add('open');
    document.getElementById('f_name').value=f.name||'';
    document.getElementById('f_zones').value=(f.zones||1);
    document.getElementById('f_status').value=f.status||'aktiv';
  }
  async function tfSave(ev,id){
    ev.preventDefault();
    var sports=[].slice.call(document.querySelectorAll('#tfModal .tf_sport:checked')).map(function(c){return c.value;});
    var body={name:val('f_name'),type:val('f_type'),zones:(+val('f_zones')||1),status:document.getElementById('f_status').value,allowed_sports:sports};
    try{
      var card=id?await api('PUT','/treningstider/anlegg/'+id,body):await api('POST','/treningstider/anlegg',body);
      if(id){var i=FAC.findIndex(function(x){return x.id===id;});if(i>=0)FAC[i]=card;}else{FAC.push(card);}
      closeModal();render();
    }catch(err){alert(err.message);}
  }
  async function tfDelete(id){
    if(!confirm('Slette dette anlegget?'))return;
    try{await api('DELETE','/treningstider/anlegg/'+id);FAC=FAC.filter(function(x){return x.id!==id;});closeModal();render();}
    catch(err){alert(err.message);}
  }
  render();
</script>
</body>
</html>
