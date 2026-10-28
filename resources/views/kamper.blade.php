<!DOCTYPE html>
<html lang="no">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html-to-image/1.11.11/html-to-image.min.js"></script>
<title>Vivu Planner · Kamper</title>
@verbatim
<style>
  :root{--bg:#f3f5fb;--card:#fff;--ink:#1a1f33;--ink-soft:#5b6b86;--line:#e6eaf2;--flik:#2f6fd6;--flik-blue:#2f6fd6;--flik-blue-deep:#1a1e39;--accent:#fb471f;--grey:#8795a3;--navy:#12284c;--sky:#6aaae4;--brand-grad:linear-gradient(125deg,#26406e,#1c3155 55%,#1a1e39)}
  *{box-sizing:border-box}
  body{margin:0;background:var(--bg);color:var(--ink);font-family:'Ubuntu',system-ui,sans-serif;font-size:14px;line-height:1.55}
  .wrap{max-width:1240px;margin:0 auto;padding:26px 28px 70px}
  h1{font-size:23px;font-weight:700;margin:4px 0 2px}
  .sub{color:var(--ink-soft);font-size:13px;margin:0 0 14px;max-width:80ch}
  .modetabs{display:flex;gap:6px;margin:0 0 16px}
  .modetabs button{flex:1;font-family:inherit;font-size:13.5px;font-weight:600;padding:9px 12px;border:1px solid var(--line);border-radius:10px;background:#fff;color:var(--ink-soft);cursor:pointer}
  .modetabs button.on{background:var(--flik);border-color:var(--flik);color:#fff}
  .layout3{display:grid;grid-template-columns:238px 330px 1fr;gap:22px;align-items:start}
  @media(max-width:1120px){.layout3{grid-template-columns:1fr}}
  .pcol{background:var(--card);border:1px solid var(--line);border-radius:14px;padding:18px}
  .pcol.sticky{position:sticky;top:16px}
  .coltitle{font-size:15px;font-weight:700;margin:0 0 4px}
  .colnote{font-size:11.5px;color:var(--grey);margin:0 0 12px;line-height:1.5}
  .malhead{font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--ink-soft);margin:16px 0 8px;border-top:1px solid var(--line);padding-top:13px}
  .mallist{display:flex;flex-direction:column;gap:6px;max-height:420px;overflow:auto}
  .malitem{text-align:left;font-family:inherit;font-size:13px;font-weight:500;padding:9px 11px;border:1px solid var(--line);border-radius:9px;background:#fff;color:var(--ink);cursor:pointer;line-height:1.3}
  .malitem:hover{border-color:var(--flik)}
  .malitem.on{background:var(--flik);border-color:var(--flik);color:#fff}
  .malempty{font-size:12px;color:var(--grey);padding:12px 2px}
  .controls{padding:0}
  .controls label{display:block;font-size:12.5px;font-weight:600;color:var(--ink-soft);margin:13px 0 6px}
  .controls label:first-child{margin-top:0}
  select,input[type=text],input[type=date]{width:100%;font-family:inherit;font-size:14px;padding:10px 11px;border:1px solid var(--line);border-radius:9px;background:#fbfcfe;color:var(--ink)}
  input[type=range]{width:100%}
  input[type=color]{width:46px;height:40px;border:1px solid var(--line);border-radius:8px;background:#fff;padding:2px}
  .colorrow{display:flex;align-items:center;gap:10px}
  .two{display:flex;gap:10px}.two>*{flex:1}
  .seg{display:flex;gap:6px}
  .seg button{flex:1;font-family:inherit;font-size:13px;font-weight:600;padding:9px;border:1px solid var(--line);border-radius:9px;background:#fff;color:var(--ink-soft);cursor:pointer}
  .seg button.on{background:var(--flik);border-color:var(--flik);color:#fff}
  textarea{width:100%;height:150px;font-family:ui-monospace,Menlo,Consolas,monospace;font-size:11.5px;line-height:1.45;padding:9px;border:1px solid var(--line);border-radius:9px;background:#fbfcfe;color:var(--ink);resize:vertical}
  .ck{display:flex;align-items:center;gap:8px;font-weight:400;font-size:12.5px;margin-top:8px;color:var(--ink)}
  .ck input{width:auto}
  .btn{font-family:inherit;font-size:13.5px;font-weight:600;padding:11px 16px;border-radius:10px;border:1px solid var(--flik);background:var(--flik);color:#fff;cursor:pointer;width:100%;margin-top:12px}
  .btn.ghost{background:#fff;color:var(--flik)}
  .btn.soft{background:#fff;color:var(--ink-soft);border-color:var(--line)}
  .hint{font-size:11.5px;color:var(--grey);margin-top:8px;line-height:1.5}
  .seph{font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--ink-soft);margin:16px 0 2px;border-top:1px solid var(--line);padding-top:12px}
  .gallery{min-width:300px;display:flex;flex-wrap:wrap;gap:22px;align-content:flex-start}
  .item{display:flex;flex-direction:column;gap:8px}
  .frame{overflow:hidden;border-radius:10px;box-shadow:0 14px 40px rgba(16,27,51,.26)}
  .dlrow{display:flex;gap:8px;align-items:center}
  .dlrow .lbl{font-size:12px;color:var(--ink-soft);font-weight:600}
  .dlbtn{font-family:inherit;font-size:12px;font-weight:600;padding:7px 11px;border-radius:8px;border:1px solid var(--flik);background:#fff;color:var(--flik);cursor:pointer}
  .emptyc{color:var(--ink-soft);font-size:13px;padding:30px;background:var(--card);border:1px dashed var(--line);border-radius:12px}
  .hide{display:none}

  /* ---- Felles plakat ---- */
  .poster{width:1080px;color:#fff;position:relative;overflow:hidden;transform-origin:top left;font-family:'Ubuntu',system-ui,sans-serif}

  /* ---- Oversikt ---- */
  .poster.ov{background:var(--navy)}
  .poster.ov .bg{position:absolute;inset:0;background:radial-gradient(130% 70% at 50% -8%, rgba(47,111,214,.6), rgba(47,111,214,0) 58%),repeating-linear-gradient(135deg, rgba(255,255,255,.04) 0 3px, transparent 3px 42px),linear-gradient(180deg,#14264a 0%,#0f1f3d 55%,#0a1529 100%)}
  .poster.ov .inner{position:relative;padding:calc(62px*var(--sp,1)) 60px calc(48px*var(--sp,1));height:100%;display:flex;flex-direction:column}
  .poster.ov .top{display:block}
  .poster.ov .titlebar{background-repeat:no-repeat;background-position:left center;background-size:contain}
  .poster.ov .kicker{font-size:calc(32px*var(--ts,1));font-weight:700;letter-spacing:.22em;color:var(--sky);text-transform:uppercase;margin:0}
  .poster.ov .big{font-size:calc(128px*var(--ts,1));line-height:.9;font-weight:700;text-transform:uppercase;margin:8px 0 0;text-shadow:0 4px 26px rgba(0,0,0,.4)}
  .poster.ov .sub2{display:inline-block;font-size:calc(42px*var(--ts,1));font-weight:700;letter-spacing:.14em;text-transform:uppercase;background:var(--flik);border-radius:12px;padding:6px 22px;margin-top:18px}
  .poster.ov .heia{text-align:right;font-weight:700;font-size:calc(30px*var(--ts,1));letter-spacing:.1em;text-transform:uppercase;line-height:1.05}
  .poster.ov .heia .hrt{color:var(--sky)}
  .poster.ov .ball{width:168px;height:168px;margin:14px 0 0 auto;display:block}
  .poster.ov .pageno{position:absolute;top:66px;right:60px;font-size:24px;font-weight:700;color:rgba(255,255,255,.55)}
  .poster.ov .days{margin-top:calc(36px*var(--sp,1));display:flex;flex-direction:column;gap:calc(20px*var(--sp,1));flex:1}
  .poster.ov .dayhead{display:flex;align-items:center;gap:16px;font-weight:700;font-size:calc(40px*var(--ts,1));letter-spacing:.03em;text-transform:uppercase;background:linear-gradient(90deg,rgba(47,111,214,.95),rgba(28,49,85,.55));border-radius:16px;padding:calc(16px*var(--sp,1)) 24px}
  .poster.ov .dayhead svg{width:40px;height:40px;flex:none}
  .poster.ov .card{background:#fff;color:var(--ink);border-radius:18px;padding:calc(16px*var(--sp,1)) 26px;margin-top:calc(12px*var(--sp,1));box-shadow:0 7px 22px rgba(0,0,0,.16)}
  .poster.ov .card .r1{display:flex;align-items:center;gap:22px}
  .poster.ov .card .time{display:flex;align-items:center;gap:12px;font-weight:700;font-size:calc(34px*var(--ts,1));color:var(--navy);min-width:150px}
  .poster.ov .card .time svg{width:32px;height:32px;color:var(--flik)}
  .poster.ov .card .match{font-weight:700;font-size:calc(34px*var(--ts,1));color:var(--navy);flex:1;line-height:1.08}
  .poster.ov .card .match b{color:var(--flik)}
  .poster.ov .card .r2{display:flex;align-items:center;gap:14px;margin-top:6px;color:var(--ink-soft);font-size:calc(24px*var(--ts,1));flex-wrap:wrap}
  .poster.ov .card .r2 .team{font-weight:700;color:var(--navy)}
  .poster.ov .card .r2 .dot{opacity:.45}
  .poster.ov .card .r2 .hall{display:inline-flex;align-items:center;gap:8px}
  .poster.ov .card .r2 .hall svg{width:26px;height:26px;color:var(--flik)}
  .poster.ov .foot{display:flex;align-items:flex-end;justify-content:space-between;margin-top:calc(32px*var(--sp,1))}
  .poster.ov .slogan{font-weight:700;font-size:calc(40px*var(--ts,1));letter-spacing:.05em;text-transform:uppercase;line-height:1.08}
  .poster.ov .slogan .hrt{color:var(--sky)}
  .poster.ov .crest{height:calc(150px*var(--lo,1));width:auto;opacity:.98}
  .poster.ov .titleimg{display:block;height:auto;max-width:100%}

  /* ---- Enkeltkamp ---- */
  .poster.ek{display:flex;flex-direction:column;background:var(--navy)}
  .poster.ek .swirl{position:absolute;inset:0;background:radial-gradient(60% 50% at 80% 30%, rgba(60,110,190,.18), transparent 60%),radial-gradient(70% 60% at 15% 85%, rgba(50,95,170,.16), transparent 62%),radial-gradient(130% 80% at 50% -10%, rgba(47,111,214,.22), transparent 60%),linear-gradient(180deg,#15284c,#0e1d39)}
  .poster.ek .ek-top{flex:1;position:relative;z-index:2;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:calc(60px*var(--sp,1)) 70px calc(30px*var(--sp,1));gap:calc(6px*var(--sp,1))}
  .poster.ek .brand{width:150px;height:auto;margin-bottom:calc(26px*var(--sp,1))}
  .poster.ek .occ{font-size:calc(52px*var(--ts,1));font-weight:700;letter-spacing:.02em;line-height:1.05}
  .poster.ek .serie{font-size:calc(72px*var(--ts,1));font-weight:700;line-height:1.02;margin-top:6px}
  .poster.ek .ek-mid{position:relative;height:calc(430px*var(--sp,1));z-index:2}
  .poster.ek .half{position:absolute;top:0;bottom:0;width:50%;display:flex;align-items:center;justify-content:center}
  .poster.ek .half.left{left:0;background:var(--flik);clip-path:polygon(0 0,100% 0,84% 100%,0 100%)}
  .poster.ek .half.right{left:50%;clip-path:polygon(16% 0,100% 0,100% 100%,0 100%)}
  .poster.ek .half .inner{display:flex;flex-direction:column;align-items:center;gap:16px}
  .poster.ek .flogo{width:140px;height:auto}
  .poster.ek .fname{font-size:calc(52px*var(--ts,1));font-weight:700;letter-spacing:.06em}
  .poster.ek .oppimg{object-fit:contain;filter:drop-shadow(0 6px 16px rgba(0,0,0,.3))}
  .poster.ek .oppph{border:4px dashed rgba(255,255,255,.5);border-radius:18px;display:flex;align-items:center;justify-content:center;font-size:22px;color:rgba(255,255,255,.7);text-align:center;padding:16px}
  .poster.ek .ek-bot{position:relative;z-index:2;padding:calc(40px*var(--sp,1)) 80px 46px;text-align:center}
  .poster.ek .dt{display:flex;justify-content:space-between;max-width:760px;margin:0 auto}
  .poster.ek .dt .col{flex:1}
  .poster.ek .dt .lbl{font-size:calc(34px*var(--ts,1));font-weight:500;letter-spacing:.06em;text-transform:uppercase;opacity:.92}
  .poster.ek .dt .val{font-size:calc(54px*var(--ts,1));font-weight:700;letter-spacing:.02em;text-transform:uppercase;margin-top:4px}
  .poster.ek .sponsor{margin-top:calc(34px*var(--sp,1));min-height:70px;display:flex;align-items:center;justify-content:center}
  .poster.ek .sponsor img{max-width:66%;object-fit:contain}
</style>
@endverbatim
</head>
<body>
@include('partials.topbar', ['active' => 'kamper'])
@include('partials.topbar-js')
<div class="wrap">
  <h1>Kamper</h1>
  <p class="sub">Lag ferdige kampbilder til sosiale medier – on-brand, uten AI. Velg type, juster om du vil, og last ned som JPG (story eller post).</p>
  <div class="layout3">

    <!-- Kolonne 1: Maler -->
    <div class="pcol sticky">
      <div class="coltitle">Maler</div>
      <p class="colnote">Lagre et ferdig oppsett (tekst, bilder og design) som mal – delt med hele klubben.</p>
      <label style="display:block;font-size:12.5px;font-weight:600;color:var(--ink-soft);margin:0 0 6px">Idrett</label>
      <select id="sport" onchange="onSport()"></select>
      <div class="malhead" id="malhead">Lagrede maler</div>
      <div class="mallist" id="mallist"></div>
      <button class="btn" onclick="saveMal()">Lagre som mal</button>
      <button class="btn soft" id="delmalbtn" onclick="deleteMal()" disabled>Slett valgt mal</button>
    </div>

    <!-- Kolonne 2: Innstillinger -->
    <div class="pcol sticky">
      <div class="coltitle">Innstillinger</div>
      <div class="modetabs">
        <button id="md_ov" class="on" onclick="setMode('ov')">Kampoversikt</button>
        <button id="md_ek" onclick="setMode('ek')">Enkeltkamp</button>
      </div>

      <!-- Kontroller: Oversikt -->
      <div class="controls" id="ctrl-ov">
      <label>Kamper (hjemmekamper hentes automatisk – rediger ved behov)</label>
      <textarea id="editor" oninput="buildOv()"></textarea>
      <div class="hint">Dag-overskrift på egen linje. Kamp: <b>tid | lag | serie | hall | kamp</b>, skilt med loddrett strek.</div>
      <button class="btn soft" onclick="refreshMatches()">Hent kamper nå</button>
      <div class="seph">Format & design</div>
      <div class="seg"><button id="ovf_s" class="on" onclick="setFmt('story')">Story 1080×1920</button><button id="ovf_p" onclick="setFmt('post')">Post 1080×1350</button></div>
      <label>Tittel-bilde (valgfritt – erstatter «Denne ukens …»)</label>
      <input type="file" accept="image/png,image/jpeg" onchange="onOvTitle(event)" style="font-size:12px">
      <div class="hint">Bruk transparent <b>PNG</b> (høy oppløsning). SVG rendrer ikke riktig ved nedlasting.</div>
      <label>Tittel – størrelse</label><input id="ov_titlescale" type="range" min="0.5" max="1.4" step="0.05" value="1" oninput="ovTitleScale=+this.value;buildOv()">
      <button class="btn soft" onclick="ovTitle=null;buildOv()">Fjern tittel-bilde</button>
      <label>Tekststørrelse</label><input id="ov_ts" type="range" min="0.85" max="1.2" step="0.05" value="1" oninput="ovTs=+this.value;buildOv()">
      <label>Luft / tetthet</label><input id="ov_sp" type="range" min="0.8" max="1.3" step="0.05" value="1" oninput="ovSp=+this.value;buildOv()">
      <label>Egen bakgrunn (valgfritt)</label>
      <input type="file" accept="image/*" onchange="onBg(event,'ov')" style="font-size:12px">
      <label class="ck"><input id="ov_dark" type="checkbox" checked onchange="ovDark=this.checked;buildOv()">Mørkt filter</label>
      <button class="btn ghost" onclick="dlBg('ov')">Last ned bakgrunnsmal</button>
      <button class="btn soft" onclick="ovBG=null;buildOv()">Fjern bakgrunn</button>
      <button class="btn" onclick="downloadAllOv()">Last ned alle bilder (JPG)</button>
      </div>

      <!-- Kontroller: Enkeltkamp -->
      <div class="controls hide" id="ctrl-ek">
      <label>Velg kamp</label>
      <select id="ek_match" onchange="onPickMatch()"></select>
      <div id="ek_feednote" class="hint" style="margin-top:6px"></div>
      <label>Anledning / sted</label><input type="text" id="e_occ" value="Hjemmekamp Alcoaparken" oninput="buildEk()">
      <label>Serie / lag</label><input type="text" id="e_serie" value="A-lag" oninput="buildEk()">
      <div class="two"><div><label>Dato</label><input type="date" id="e_date" oninput="buildEk()"></div><div><label>Kampstart</label><input type="text" id="e_time" value="19:00" oninput="buildEk()"></div></div>
      <label>Motstander – logo</label><input type="file" accept="image/*" onchange="onOpp(event)" style="font-size:12px">
      <label>Motstander – farge</label>
      <div class="colorrow"><input type="color" id="e_color" value="#c8102e" oninput="buildEk()"><span class="hint" style="margin:0">Høyre side fylles i denne fargen.</span></div>
      <label>Sponsorlogo (valgfritt)</label><input type="file" accept="image/*" onchange="onSpon(event)" style="font-size:12px">
      <div class="seph">Format & design</div>
      <div class="seg"><button id="ekf_s" class="on" onclick="setFmt('story')">Story 1080×1920</button><button id="ekf_p" onclick="setFmt('post')">Post 1080×1350</button></div>
      <label>Motstanderlogo – størrelse</label><input id="ek_logo" type="range" min="0.6" max="1.6" step="0.05" value="1" oninput="ekLogo=+this.value;buildEk()">
      <label>Sponsorlogo – størrelse</label><input id="ek_spon" type="range" min="0.6" max="1.9" step="0.05" value="1" oninput="ekSpon=+this.value;buildEk()">
      <label>Tekststørrelse</label><input id="ek_ts" type="range" min="0.85" max="1.2" step="0.05" value="1" oninput="ekTs=+this.value;buildEk()">
      <label>Luft / avstand</label><input id="ek_sp" type="range" min="0.8" max="1.3" step="0.05" value="1" oninput="ekSp=+this.value;buildEk()">
      <label>Egen bakgrunn (valgfritt)</label>
      <input type="file" accept="image/*" onchange="onBg(event,'ek')" style="font-size:12px">
      <label class="ck"><input id="ek_dark" type="checkbox" checked onchange="ekDark=this.checked;buildEk()">Mørkt filter</label>
      <button class="btn soft" onclick="ekBG=null;buildEk()">Fjern bakgrunn</button>
      <button class="btn" onclick="dlEk()">Last ned som bilde (JPG)</button>
      </div>
    </div>

    <!-- Kolonne 3: Resultat -->
    <div class="pcol">
      <div class="coltitle">Resultat</div>
      <p class="colnote">Forhåndsvisning. Last ned som JPG med knappen i innstillinger.</p>
      <div class="gallery" id="gallery"></div>
    </div>
  </div>
</div>

<script>
  window.KAMPDATA = @json($kampdata);
  window.SPORTS = @json($sports);
  window.EK_MATCHES = @json($matches);
  window.TEMPLATES = @json($templates);
  window.CSRF = '{{ csrf_token() }}';
</script>
@verbatim
<script>
  var KAMPDATA=window.KAMPDATA||{}, SPORTS=window.SPORTS||[], MATCHES=window.EK_MATCHES||[];
  var TEMPLATES=window.TEMPLATES||[], CSRF=window.CSRF||'', selMal=null;
  var ICON_CLOCK='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>';
  var ICON_PIN='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>';
  var ICON_CAL='<svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4.5" width="18" height="16" rx="2.5"/><path d="M3 9h18M8 2.5v4M16 2.5v4"/></svg>';
  var LOGO='<svg class="crest" viewBox="0 0 191.4 365" xmlns="http://www.w3.org/2000/svg"><g fill="#fff"><path d="M191.4,121.5V45l-11.7,3.6C95.2,74.9,43.6,106.1,26.6,141.5c-8,16.5-8.6,33.7-1.8,51.1c5.3,13.7,14.4,23.2,27.1,28.2c-9.8,65.7,7.5,134.3,8.3,137.4l1.7,6.8h65.7l-1.9-10.6c-1.1-6.1-2.3-12.1-3.5-17.9c-5.8-28.4-10.3-50.9,0.6-66.9c9-13.2,29-22.4,61.3-28.1l7.4-1.3l0-81.4l-13.9,9.2C162,178,124.8,198.7,92.8,205c-2.7,5.8-4.4,12.5-5.4,19.1c15.4-1.9,33.2-7.3,53.2-16.2c12.9-5.7,24.4-11.9,32.8-16.7v33.7c-33.4,6.9-54.4,17.8-65.6,34.3c-15.2,22.3-9.4,50.6-3.4,80.6c0.5,2.3,0.9,4.6,1.4,6.9H76c-3.7-17.2-12.4-64.3-7.8-111.3c0.6-9.7,1.9-20,4.9-29.5c17.1-58.2,110.6-82.7,111.5-83L191.4,121.5z M173.4,107.7c-9.9,3-29.2,9.7-49.6,20.7c-37,20-60.6,45.6-68.4,74.2c-6.3-3.4-10.9-8.8-13.8-16.5c-5-12.8-4.6-24.8,1.2-36.8c17.2-35.5,78.2-62.5,130.6-79.8V107.7z"/><path d="M41.8,83.6c-5.7,0-11.5-1.2-16.9-3.6C14.7,75.5,6.9,67.3,2.8,56.9s-3.8-21.8,0.7-32S16.3,6.9,26.7,2.8c21.5-8.3,45.7,2.4,54.1,23.9c8.3,21.5-2.4,45.7-23.9,54.1C52,82.7,46.9,83.6,41.8,83.6z M41.8,18c-2.9,0-5.8,0.5-8.6,1.6c-5.9,2.3-10.6,6.8-13.2,12.6c-2.6,5.8-2.7,12.3-0.4,18.2c2.3,5.9,6.8,10.6,12.6,13.2c5.8,2.6,12.3,2.7,18.2,0.4C62.6,59.2,68.7,45.4,64,33.2C60.3,23.8,51.3,18,41.8,18z"/></g></svg>';
  var DAYS=['Søndag','Mandag','Tirsdag','Onsdag','Torsdag','Fredag','Lørdag'];
  var MONTHS=['januar','februar','mars','april','mai','juni','juli','august','september','oktober','november','desember'];
  var FORMATS={story:{w:1080,h:1920,budget:12},post:{w:1080,h:1350,budget:6.5}};
  var mode='ov', fmt='story';
  // oversikt-state
  var sport=null, ovTs=1, ovSp=1, ovLo=1, ovBG=null, ovDark=true, ovPages=[], ovTitle=null, ovTitleScale=1;
  // enkeltkamp-state
  var OPP=null, SPON=null, ekBG=null, ekDark=true, ekLogo=1, ekSpon=1, ekTs=1, ekSp=1;

  function esc(s){return String(s==null?'':s).replace(/&/g,'&amp;').replace(/</g,'&lt;');}
  function fmtMatch(m){return esc(m).replace(/\bFLIK\b/g,'<b>FLIK</b>');}
  function bgCss(img,dark){if(!img)return '';var ov=dark?'linear-gradient(180deg,rgba(9,18,38,.5),rgba(9,18,38,.8)),':'';return 'background:'+ov+'url('+img+') center/cover;';}
  // Rendrer plakat-markup i FULL størrelse utenfor skjermen og laster ned som JPG
  // (html-to-image = nettleserens egen rendring → riktig font, skygger og bakgrunner).
  function captureHtml(innerHtml,F,filename){
    var d=document.createElement('div');
    d.style.cssText='position:fixed;left:-99999px;top:0;width:'+F.w+'px;height:'+F.h+'px';
    d.innerHTML=innerHtml;document.body.appendChild(d);
    var node=d.querySelector('.poster');
    var ready=(document.fonts&&document.fonts.ready)?document.fonts.ready:Promise.resolve();
    return ready.then(function(){
      return htmlToImage.toJpeg(node,{width:F.w,height:F.h,quality:0.95,backgroundColor:'#12284c',pixelRatio:1,cacheBust:true});
    }).then(function(dataUrl){
      var a=document.createElement('a');a.download=filename;a.href=dataUrl;a.click();document.body.removeChild(d);
    }).catch(function(e){document.body.removeChild(d);alert('Klarte ikke lage bilde: '+((e&&e.message)||e));});
  }
  function onBg(ev,m){var f=ev.target.files[0];if(!f)return;var r=new FileReader();r.onload=function(e){if(m==='ov'){ovBG=e.target.result;buildOv();}else{ekBG=e.target.result;buildEk();}};r.readAsDataURL(f);}
  function onOvTitle(ev){var f=ev.target.files[0];if(!f)return;var r=new FileReader();r.onload=function(e){ovTitle=e.target.result;buildOv();};r.readAsDataURL(f);}
  function onOpp(ev){var f=ev.target.files[0];if(!f)return;var r=new FileReader();r.onload=function(e){OPP=e.target.result;buildEk();};r.readAsDataURL(f);}
  function onSpon(ev){var f=ev.target.files[0];if(!f)return;var r=new FileReader();r.onload=function(e){SPON=e.target.result;buildEk();};r.readAsDataURL(f);}

  function setMode(m){mode=m;document.getElementById('md_ov').classList.toggle('on',m==='ov');document.getElementById('md_ek').classList.toggle('on',m==='ek');
    document.getElementById('ctrl-ov').classList.toggle('hide',m!=='ov');document.getElementById('ctrl-ek').classList.toggle('hide',m!=='ek');renderMalList();if(m==='ek')renderEkMatches(true);build();}

  /* ---------- Maler ---------- */
  function renderMalList(){
    var box=document.getElementById('mallist');
    var list=TEMPLATES.filter(function(t){return t.sport===sport&&t.mode===mode;});
    document.getElementById('malhead').textContent='Maler · '+(mode==='ov'?'Kampoversikt':'Enkeltkamp');
    if(selMal&&!list.some(function(t){return t.id===selMal;}))selMal=null;
    document.getElementById('delmalbtn').disabled=!selMal;
    if(!list.length){box.innerHTML='<div class="malempty">Ingen maler for '+esc(sport||'')+' ennå. Lag et oppsett og trykk «Lagre som mal».</div>';return;}
    box.innerHTML=list.map(function(t){return '<button class="malitem'+(t.id===selMal?' on':'')+'" onclick="loadMal('+t.id+')">'+esc(t.name)+'</button>';}).join('');
  }
  function loadMal(id){
    var t=TEMPLATES.find(function(x){return x.id===id;});if(!t)return;selMal=id;
    var d=t.data||{};fmt=(d.fmt==='post')?'post':'story';
    if(t.mode==='ov'){
      document.getElementById('editor').value=d.text||'';
      ovTitle=d.titleUrl||null;ovBG=d.bgUrl||null;ovDark=d.dark!==false;
      ovTitleScale=+d.titleScale||1;ovTs=+d.ts||1;ovSp=+d.sp||1;
      setVal('ov_titlescale',ovTitleScale);setVal('ov_ts',ovTs);setVal('ov_sp',ovSp);setChk('ov_dark',ovDark);
      syncFmt();buildOv();
    }else{
      setTxt('e_occ',d.occ);setTxt('e_serie',d.serie);setTxt('e_date',d.date);setTxt('e_time',d.time);setTxt('e_color',d.color||'#c8102e');
      OPP=d.oppUrl||null;SPON=d.sponUrl||null;ekBG=d.bgUrl||null;ekDark=d.dark!==false;
      ekLogo=+d.logoScale||1;ekSpon=+d.sponScale||1;ekTs=+d.ts||1;ekSp=+d.sp||1;
      setVal('ek_logo',ekLogo);setVal('ek_spon',ekSpon);setVal('ek_ts',ekTs);setVal('ek_sp',ekSp);setChk('ek_dark',ekDark);
      renderEkMatches(false);document.getElementById('ek_match').value='';
      syncFmt();buildEk();
    }
    renderMalList();
  }
  function setVal(id,v){var e=document.getElementById(id);if(e)e.value=v;}
  function setChk(id,v){var e=document.getElementById(id);if(e)e.checked=!!v;}
  function setTxt(id,v){var e=document.getElementById(id);if(e)e.value=(v==null?'':v);}
  function syncFmt(){['ovf','ekf'].forEach(function(p){var s=document.getElementById(p+'_s'),q=document.getElementById(p+'_p');if(s)s.classList.toggle('on',fmt==='story');if(q)q.classList.toggle('on',fmt==='post');});}
  function saveMal(){
    var name=prompt('Navn på malen:', selMal?(TEMPLATES.find(function(x){return x.id===selMal;})||{}).name:'');
    if(!name||!name.trim())return;name=name.trim();
    var data,images;
    if(mode==='ov'){
      data={fmt:fmt,text:document.getElementById('editor').value,dark:ovDark,titleScale:ovTitleScale,ts:ovTs,sp:ovSp};
      images={titleUrl:ovTitle,bgUrl:ovBG};
    }else{
      data={fmt:fmt,occ:document.getElementById('e_occ').value,serie:document.getElementById('e_serie').value,date:document.getElementById('e_date').value,time:document.getElementById('e_time').value,color:document.getElementById('e_color').value,dark:ekDark,logoScale:ekLogo,sponScale:ekSpon,ts:ekTs,sp:ekSp};
      images={oppUrl:OPP,sponUrl:SPON,bgUrl:ekBG};
    }
    fetch('/kamper/mal',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},body:JSON.stringify({sport:sport,mode:mode,name:name,data:data,images:images})})
      .then(function(r){return r.json().then(function(j){if(!r.ok)throw new Error(j.error||'Feil ved lagring');return j;});})
      .then(function(j){var t=j.template;var i=TEMPLATES.findIndex(function(x){return x.id===t.id;});if(i>-1)TEMPLATES[i]=t;else TEMPLATES.push(t);selMal=t.id;renderMalList();})
      .catch(function(e){alert(e.message||e);});
  }
  function deleteMal(){
    if(!selMal)return;var t=TEMPLATES.find(function(x){return x.id===selMal;});if(!t)return;
    if(!confirm('Slette malen «'+t.name+'»?'))return;
    fetch('/kamper/mal/'+selMal,{method:'DELETE',headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'}})
      .then(function(r){if(!r.ok)throw new Error('Kunne ikke slette');TEMPLATES=TEMPLATES.filter(function(x){return x.id!==selMal;});selMal=null;renderMalList();})
      .catch(function(e){alert(e.message||e);});
  }
  function setFmt(f){fmt=f;['ovf','ekf'].forEach(function(p){document.getElementById(p+'_s').classList.toggle('on',f==='story');document.getElementById(p+'_p').classList.toggle('on',f==='post');});build();}
  function build(){mode==='ov'?buildOv():buildEk();}

  /* ---------- Oversikt ---------- */
  function parse(txt){var days=[],cur=null;(txt||'').split('\n').forEach(function(line){var l=line.trim();if(!l)return;if(l.indexOf('|')>-1){var p=l.split('|').map(function(x){return x.trim();});if(cur)cur.matches.push({time:p[0]||'',team:p[1]||'',serie:p[2]||'',hall:p[3]||'',match:p[4]||''});}else{cur={day:l,matches:[]};days.push(cur);}});return days;}
  function serialize(days){return (days||[]).map(function(d){return d.day+'\n'+d.matches.map(function(m){return [m.time,m.team,m.serie,m.hall,m.match].join(' | ');}).join('\n');}).join('\n');}
  function onSport(){sport=document.getElementById('sport').value;document.getElementById('editor').value=serialize(KAMPDATA[sport]||[]);renderMalList();if(mode==='ek'){renderEkMatches(true);buildEk();}else{buildOv();}}
  function refreshMatches(){document.getElementById('editor').value=serialize(KAMPDATA[sport]||[]);buildOv();}
  function ballOv(s){if(/fotball/i.test(s))return '<svg class="ball" viewBox="0 0 100 100"><circle cx="50" cy="50" r="46" fill="#fff"/><path d="M50 28l11 8-4 13H43l-4-13z" fill="#12284c"/><g fill="none" stroke="#12284c" stroke-width="3"><circle cx="50" cy="50" r="46"/><path d="M50 4v16M11 32l14 9M89 32l-14 9M26 86l6-15M74 86l-6-15"/></g></svg>';return '<svg class="ball" viewBox="0 0 100 100"><circle cx="50" cy="50" r="46" fill="#fff" stroke="#12284c" stroke-width="3"/><g fill="none" stroke="#12284c" stroke-width="3" stroke-linecap="round"><path d="M50 6C33 30 33 70 50 94M50 6C67 30 67 70 50 94M18 26C37 44 37 56 18 74M82 26C63 44 63 56 82 74"/></g></svg>';}
  function layoutPages(days,budget){var dayCost=0.75,matchCost=1,pages=[],page=[],used=0;days.forEach(function(d){var i=0;while(i<d.matches.length){if(used+dayCost>budget&&page.length){pages.push(page);page=[];used=0;}var grp={day:d.day,matches:[]};used+=dayCost;while(i<d.matches.length&&used+matchCost<=budget){grp.matches.push(d.matches[i]);i++;used+=matchCost;}page.push(grp);if(i<d.matches.length){pages.push(page);page=[];used=0;}}});if(page.length)pages.push(page);return pages;}
  function daysHtml(groups){return groups.map(function(d){return '<div><div class="dayhead">'+ICON_CAL+' '+esc(d.day)+'</div>'+d.matches.map(function(m){var r2=[];if(m.team)r2.push('<span class="team">'+esc(m.team)+'</span>');if(m.serie)r2.push('<span>'+esc(m.serie)+'</span>');if(m.hall)r2.push('<span class="hall">'+ICON_PIN+' '+esc(m.hall)+'</span>');return '<div class="card"><div class="r1"><div class="time">'+ICON_CLOCK+' '+esc(m.time)+'</div><div class="match">'+fmtMatch(m.match)+'</div></div>'+(r2.length?'<div class="r2">'+r2.join('<span class="dot">·</span>')+'</div>':'')+'</div>';}).join('')+'</div>';}).join('');}
  function ovPoster(groups,pageNo,pageTot,dispW){var F=FORMATS[fmt];dispW=dispW||330;return '<div class="poster ov" style="height:'+F.h+'px;transform:scale('+(dispW/F.w)+');--ts:'+ovTs+';--sp:'+ovSp+';--lo:'+ovLo+'">'+'<div class="bg" style="'+bgCss(ovBG,ovDark)+'"></div><div class="inner">'+(pageTot>1?'<div class="pageno">'+pageNo+' / '+pageTot+'</div>':'')+'<div class="top">'+(ovTitle?'<div class="titlebar" style="height:'+Math.round(240*ovTitleScale)+'px;background-image:url('+ovTitle+')"></div>':'<div class="kicker">Denne ukens '+esc(sport||'').toLowerCase()+'</div><div class="big">Kamper</div>')+'</div><div class="days">'+daysHtml(groups)+'</div></div></div>';}
  function buildOv(){var F=FORMATS[fmt],days=parse(document.getElementById('editor').value);var budget=Math.max(3,F.budget/ovSp);var pages=layoutPages(days,budget);ovPages=pages;var g=document.getElementById('gallery');if(!pages.length){g.innerHTML='<div class="emptyc">Ingen kamper lagt inn. Skriv inn kampene i feltet til venstre.</div>';return;}var frameH=Math.round(F.h*(330/F.w));g.innerHTML=pages.map(function(pg,i){return '<div class="item"><div class="frame" style="width:330px;height:'+frameH+'px">'+ovPoster(pg,i+1,pages.length)+'</div><div class="dlrow"><span class="lbl">Bilde '+(i+1)+' av '+pages.length+'</span><button class="dlbtn" onclick="dlOv('+i+')">Last ned JPG</button></div></div>';}).join('');}
  function dlOv(i){var F=FORMATS[fmt];if(!ovPages[i])return;captureHtml(ovPoster(ovPages[i],i+1,ovPages.length,F.w),F,'FLIK-'+(sport||'kamper').toLowerCase()+'-'+fmt+'-'+(i+1)+'.jpg');}
  function downloadAllOv(){document.querySelectorAll('#gallery .poster').forEach(function(_,i){setTimeout(function(){dlOv(i);},i*700);});}
  function dlBg(m){var F=FORMATS[fmt];captureHtml('<div class="poster ov" style="height:'+F.h+'px"><div class="bg" style="'+bgCss(ovBG,ovDark)+'"></div></div>',F,'FLIK-bakgrunn-'+fmt+'.jpg');}

  /* ---------- Enkeltkamp ---------- */
  function dateParts(v){if(!v)return{wd:'',dm:''};var d=new Date(v+'T12:00:00');if(isNaN(d))return{wd:'',dm:''};return{wd:DAYS[d.getDay()].toUpperCase(),dm:d.getDate()+'. '+MONTHS[d.getMonth()].toUpperCase()};}
  // Filtrer kampvelgeren på valgt idrett; autofyll fra feed hvis den finnes
  function renderEkMatches(autofill){
    var em=document.getElementById('ek_match'),note=document.getElementById('ek_feednote');if(!em)return;
    var items=[];MATCHES.forEach(function(m,i){if(m.sport===sport)items.push({m:m,i:i});});
    em.innerHTML='<option value="">– manuell innlegging –</option>'+items.map(function(x){return '<option value="'+x.i+'">'+esc(x.m.label)+'</option>';}).join('');
    if(!items.length){note.innerHTML='Ingen kommende kamper i feed for <b>'+esc(sport||'')+'</b> – legg inn manuelt under.';return;}
    note.innerHTML=items.length+' kommende '+esc(sport||'').toLowerCase()+'-kamp'+(items.length>1?'er':'')+' hentet fra feed.';
    if(autofill){em.value=String(items[0].i);onPickMatch();}
  }
  function onPickMatch(){var i=document.getElementById('ek_match').value;if(i===''||!MATCHES[i]){buildEk();return;}var m=MATCHES[i];
    document.getElementById('e_occ').value='Hjemmekamp'+(m.location?' '+m.location:'');
    document.getElementById('e_serie').value=m.tournament||'';
    document.getElementById('e_date').value=m.date||'';
    document.getElementById('e_time').value=m.time||'';
    buildEk();}
  function ekPoster(dispW){var F=FORMATS[fmt];dispW=dispW||360;var occ=esc(document.getElementById('e_occ').value),serie=esc(document.getElementById('e_serie').value),color=document.getElementById('e_color').value,time=esc(document.getElementById('e_time').value),dp=dateParts(document.getElementById('e_date').value);
    var ow=Math.round(230*ekLogo),pw=Math.round(210*ekLogo);
    var opp=OPP?'<img class="oppimg" style="width:'+ow+'px;height:'+ow+'px" src="'+OPP+'">':'<div class="oppph" style="width:'+pw+'px;height:'+pw+'px">Last opp<br>motstander-<br>logo</div>';
    var spon=SPON?'<div class="sponsor"><img style="max-height:'+Math.round(92*ekSpon)+'px" src="'+SPON+'"></div>':'<div class="sponsor"></div>';
    return '<div class="poster ek" style="height:'+F.h+'px;transform:scale('+(dispW/F.w)+');--ts:'+ekTs+';--sp:'+ekSp+'">'+'<div class="swirl" style="'+bgCss(ekBG,ekDark)+'"></div>'+'<div class="ek-top">'+LOGO.replace('class="crest"','class="brand"')+'<div class="occ">'+occ+'</div><div class="serie">'+serie+'</div></div>'+'<div class="ek-mid"><div class="half left"><div class="inner">'+LOGO.replace('class="crest"','class="flogo"')+'<div class="fname">FLIK</div></div></div><div class="half right" style="background:'+color+'"><div class="inner">'+opp+'</div></div></div>'+'<div class="ek-bot"><div class="dt"><div class="col"><div class="lbl">'+dp.wd+'</div><div class="val">'+dp.dm+'</div></div><div class="col"><div class="lbl">Kampstart</div><div class="val">Kl. '+time+'</div></div></div>'+spon+'</div></div>';}
  function buildEk(){var F=FORMATS[fmt];var frameH=Math.round(F.h*(360/F.w));document.getElementById('gallery').innerHTML='<div class="item"><div class="frame" style="width:360px;height:'+frameH+'px">'+ekPoster()+'</div></div>';}
  function dlEk(){var F=FORMATS[fmt];captureHtml(ekPoster(F.w),F,'FLIK-enkeltkamp-'+fmt+'.jpg');}

  // init
  (function(){
    var sel=document.getElementById('sport');
    sel.innerHTML=SPORTS.map(function(s){return '<option>'+esc(s)+'</option>';}).join('');
    var first=SPORTS.find(function(s){return (KAMPDATA[s]||[]).length;})||SPORTS[0]||'';
    sel.value=first;sport=first;document.getElementById('editor').value=serialize(KAMPDATA[first]||[]);
    if(!document.getElementById('e_date').value){var t=new Date();document.getElementById('e_date').value=t.toISOString().slice(0,10);}
    renderEkMatches(false);
    renderMalList();
    buildOv();
  })();
</script>
@endverbatim
</body>
</html>
