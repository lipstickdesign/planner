<!DOCTYPE html>
<html lang="no">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Vivu Planner – {{ $company->name ?? 'Årshjul' }}</title>
<link rel="icon" type="image/jpeg" href="/favicon.jpg">
<link rel="apple-touch-icon" href="/favicon.jpg">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700&display=swap" rel="stylesheet">
@verbatim
<style>
:root{--flik-blue:#2f6fd6;--flik-blue-dark:#1c3155;--flik-blue-deep:#1a1e39;--navy:#1c3155;--sky:#6aaae4;--orange:#fb471f;--magenta:#cc2170;--accent:#fb471f;--bg:#f3f5fb;--card:#fff;--ink:#1a1f33;--ink-soft:#5b6b86;--line:#e6eaf2;--green:#2e9e5b;--amber:#e8a200;--red:#d64545;--teal:#1a9aa0;--grey:#8795a3;--radius:22px;--shadow:0 2px 5px rgba(20,40,80,.05),0 14px 40px rgba(20,40,80,.07)}
*{box-sizing:border-box}
body{margin:0;background:var(--bg);color:var(--ink);font-family:'Ubuntu',system-ui,sans-serif;line-height:1.55}
a{color:var(--flik-blue);text-decoration:none}a:hover{text-decoration:underline}
.wrap{max-width:1240px;margin:0 auto;padding:0 28px}
.topbar{background:var(--brand-grad,linear-gradient(125deg,#26406e,#1c3155 55%,#1a1e39));color:#fff}
.topbar .wrap{display:flex;align-items:center;gap:14px;padding:16px 28px}
.brand{display:flex;align-items:center;gap:12px}
.brandmark{height:38px;width:auto;color:#fff;display:block}
.brand h1{font-size:17px;margin:0;font-weight:500}
.brand .sub{font-size:12px;opacity:.82;font-weight:300}
.spacer{flex:1}
.usermenu{position:relative}
.userchip{display:flex;align-items:center;gap:9px;background:rgba(255,255,255,.13);padding:6px 13px;border-radius:24px;font-size:13px;border:none;color:#fff;cursor:pointer;font-family:inherit}
.userchip:hover{background:rgba(255,255,255,.22)}
.usermenudd{position:absolute;top:46px;right:0;background:#fff;border-radius:12px;box-shadow:0 16px 44px rgba(20,40,80,.24);border:1px solid var(--line);min-width:236px;padding:6px;display:none;z-index:70}
.usermenudd.open{display:block}
.usermenudd .ddi{display:flex;align-items:center;gap:9px;width:100%;text-align:left;background:none;border:none;font-family:inherit;font-size:13.5px;color:var(--ink);padding:9px 11px;border-radius:8px;cursor:pointer}
.usermenudd .ddi:hover{background:#f3f7fc}
.usermenudd .ddi.cur{color:var(--flik-blue);font-weight:600}
.usermenudd .ddsep{font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--ink-soft);padding:9px 11px 4px}
.usermenudd .ddsep2{border-top:1px solid var(--line);margin:6px 0}
.userchip .av{width:28px;height:28px;border-radius:50%;background:var(--accent);color:#3a2c00;display:grid;place-items:center;font-weight:700;font-size:11px;margin-left:-6px}
.logoutbtn{background:rgba(255,255,255,.16);border:none;color:#fff;border-radius:20px;padding:7px 13px;cursor:pointer;font-family:inherit;font-size:13px}
.logoutbtn:hover{background:rgba(255,255,255,.3)}
.tabbar{background:var(--flik-blue-deep)}
.tabs{display:flex;gap:4px;max-width:1240px;margin:0 auto;padding:0 20px;flex-wrap:wrap}
.tab{padding:15px 20px;color:rgba(255,255,255,.72);font-size:14px;font-weight:500;cursor:pointer;border:none;background:none;border-bottom:3px solid transparent}
.tab:hover{color:#fff}.tab.active{color:#fff;border-bottom-color:var(--accent)}
main.wrap{padding:44px 28px 90px}.view{display:none}.view.active{display:block}
.ico{width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;flex:none;vertical-align:-3px}
.ico.chk{width:12px;height:12px;stroke-width:2.5;vertical-align:0}
.viewhead{display:flex;align-items:center;justify-content:space-between;margin-bottom:26px;gap:16px;flex-wrap:wrap}
.viewhead h2{font-size:19px;margin:0}
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-bottom:34px}
.stat{background:var(--card);border-radius:var(--radius);padding:24px 22px;box-shadow:var(--shadow);border:1px solid var(--line);position:relative;overflow:hidden}
.stat .n{font-size:30px;font-weight:700;line-height:1}.stat .l{font-size:13px;color:var(--ink-soft);margin-top:6px}
.stat .bar{position:absolute;left:0;top:0;bottom:0;width:5px}
.stat.b1{background:#eef3fd}.stat.b2{background:#fdf1ea}.stat.b3{background:#fcebf1}.stat.b4{background:#ecf5fa}
.stat.b1 .bar{background:var(--flik-blue)}.stat.b2 .bar{background:var(--orange)}.stat.b3 .bar{background:var(--magenta)}.stat.b4 .bar{background:var(--sky)}
.grid2{display:grid;grid-template-columns:1.35fr 1fr;gap:24px}
.panel{background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow);border:1px solid var(--line);overflow:hidden}
.panel h3{font-size:15px;margin:0;padding:18px 22px;border-bottom:1px solid var(--line);font-weight:700;display:flex;align-items:center;gap:9px}
.panel h3 .tag{font-size:11px;font-weight:500;color:#fff;background:var(--red);padding:2px 9px;border-radius:20px}
.panel h3 .tag.cool{background:var(--flik-blue)}
.panel .body{padding:8px 0}
.row{display:flex;align-items:center;gap:14px;padding:15px 22px;border-bottom:1px solid var(--line);cursor:pointer}
.row:last-child{border-bottom:none}.row:hover{background:#f7faff}
.dot{width:11px;height:11px;border-radius:50%;flex:none}
.row .meta{flex:1;min-width:0}
.row .t{font-size:14px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.row .s{font-size:12px;color:var(--ink-soft);margin-top:1px}
.when{font-size:12px;font-weight:500;text-align:right;white-space:nowrap}
.when .d{font-size:11px;color:var(--ink-soft);font-weight:400}.urgent{color:var(--red)}.soon{color:var(--amber)}
.pill{font-size:11px;font-weight:500;padding:3px 9px;border-radius:20px;white-space:nowrap;display:inline-block}
.st-planlagt{background:#e7f0fb;color:#1c5fa8}.st-arbeid{background:#fdf0d6;color:#9a6b00}
.st-klar{background:#dcf3ee;color:#137a6e}.st-publisert{background:#dff3e4;color:#1f7a42}
.st-avlyst{background:#eceff2;color:#69788a}.st-mangler{background:#fde3e3;color:#b23535}
.st-godkjent{background:#dff3e4;color:#1f7a42}.st-tilgodkj{background:#fdf0d6;color:#9a6b00}
.wheelwrap{display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start}
.wheelcard{background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow);border:1px solid var(--line);padding:16px;position:relative}
.wheeltip{position:absolute;pointer-events:none;background:#1c3155;color:#fff;font-size:12px;padding:7px 11px;border-radius:9px;box-shadow:0 8px 22px rgba(20,40,80,.28);opacity:0;transition:opacity .12s;white-space:nowrap;z-index:5;max-width:240px}
.wheeltip.on{opacity:1}
.wheeltip .tt{font-weight:600}
.wheeltip .td{color:#cfe0f2;font-size:11px;margin-top:1px}
svg.wheel{width:100%;height:auto;display:block}
.legend{background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow);border:1px solid var(--line);padding:22px 22px}
.legend h4{margin:0 0 14px;font-size:14px}
.legendhead{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:14px}
.legendhead h4{margin:0}
.toggleall{font-family:inherit;font-size:12px;font-weight:500;color:var(--flik-blue);background:#f1f6fc;border:1px solid #dbe7f5;border-radius:8px;padding:5px 10px;cursor:pointer;white-space:nowrap}
.toggleall:hover{background:#e7f0fb}
/* Denne uka + Klubbliv */
.weekcard{background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow);border:1px solid var(--line);padding:20px 22px;margin-bottom:20px}
.weekcard h3{margin:0 0 14px;font-size:15px;display:flex;align-items:center;gap:8px}
.weekcard h3 .ct{font-size:11px;font-weight:600;color:#fff;background:var(--flik-blue);border-radius:20px;padding:1px 9px}
.weekcard h3 .ct.warn{background:var(--amber)}.weekcard h3 .ct.red{background:var(--red)}.weekcard h3 .ct.gap{background:var(--teal)}
.weekcols{display:grid;grid-template-columns:2fr 1fr;gap:20px;align-items:start}
.weekcard .lbl{font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--ink-soft)}
@media(max-width:880px){.weekcols{grid-template-columns:1fr}}
.weatherstrip{display:flex;gap:10px;flex-wrap:wrap}
.wday{flex:1;min-width:76px;background:#f6f9fd;border:1px solid var(--line);border-radius:12px;padding:10px 8px;text-align:center}
.wday .wd{font-size:11px;color:var(--ink-soft);text-transform:uppercase;letter-spacing:.4px}
.wday .wt{font-size:18px;font-weight:700;margin:3px 0 1px}
.wday .wl{font-size:10.5px;color:var(--ink-soft);line-height:1.25}
.wday.today{border-color:var(--flik-blue);background:#eef3fd}
.todayrow{display:flex;gap:24px;flex-wrap:wrap}
.todaycol{flex:1;min-width:220px}
.todaycol .lbl{font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--ink-soft);margin-bottom:6px}
.trainline{display:flex;align-items:center;gap:8px;font-size:13px;padding:4px 0}
.trainline .sw{width:10px;height:10px;border-radius:3px;flex:none}
.rrow{display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid var(--line)}
.rrow:last-child{border-bottom:none}
.rrow .rd{font-size:12px;font-weight:600;color:var(--flik-blue);white-space:nowrap;min-width:58px}
.rrow .rt{flex:1;min-width:0;font-size:13.5px;font-weight:500}
.rrow .rt small{display:block;font-weight:400;color:var(--ink-soft);font-size:12px;margin-top:1px}
.rrow .ra{flex:none}
.emptyrec{font-size:13px;color:var(--ink-soft);padding:4px 0}
.tiphint{width:100%;font-family:inherit;font-size:13px;padding:8px 11px;border:1px solid #e6ebf2;border-radius:9px;background:#fbfcfe;color:var(--ink)}
.tiphint:focus{outline:none;border-color:var(--flik-blue);box-shadow:0 0 0 3px rgba(47,111,214,.14)}
.kpost{display:flex;align-items:center;gap:12px;padding:13px 16px;border:1px solid var(--line);border-radius:12px;background:#fff;margin-bottom:10px}
.kpost .kd{font-size:12px;font-weight:600;color:var(--flik-blue);min-width:58px;white-space:nowrap}
.kpost .kt{flex:1;min-width:0}
.kpost .kt .knm{font-weight:500;font-size:14px}
.kpost .kt .kmeta{font-size:12px;color:var(--ink-soft);margin-top:1px}
.gpill{display:inline-block;font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:.3px;padding:2px 8px;border-radius:20px;background:#eef2f7;color:#5b6b86}
.gpill.verving{background:#fdeee7;color:#b4531f}.gpill.engasjement{background:#e9f2fb;color:#1c5fa8}.gpill.praktisk{background:#e7f3ef;color:#177a5f}
.gpill.motivasjon{background:#f3ecfb;color:#7b3fb0}.gpill.sesong{background:#fdf3d8;color:#8a6300}.gpill.medlem{background:#fbe9f1;color:#a41f5f}
.idealib{margin-top:18px;background:#f7f9fc;border:1px solid var(--line);border-radius:14px;padding:16px 18px}
.idealib h4{margin:0 0 10px;font-size:14px;overflow:hidden}
.idearow{display:flex;align-items:center;gap:10px;padding:7px 0;border-bottom:1px solid #eef2f7;font-size:13px}
.idearow:last-child{border-bottom:none}.idearow .it{flex:1;min-width:0}
.idearow .gpill{width:104px;text-align:center;box-sizing:border-box;flex:none}
.idearow .it small{color:var(--ink-soft);display:block;font-size:12px}
.dchks{display:flex;flex-wrap:wrap;gap:8px 16px;margin-top:4px}
.dchk{display:flex;align-items:center;gap:6px;font-size:13px;color:var(--ink)}
.dchk input{width:auto}
/* Tannhjul i topp */
.iconbtn{background:rgba(255,255,255,.16);border:none;color:#fff;width:36px;height:36px;border-radius:50%;cursor:pointer;display:grid;place-items:center;margin-right:4px}
.iconbtn:hover{background:rgba(255,255,255,.3)}
.iconbtn svg{width:19px;height:19px;stroke:currentColor;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
/* Konfigurerbart dashboard */
.homebar{display:flex;gap:8px;flex-wrap:wrap}
.modcol{display:flex;flex-direction:column}
.modwrap{position:relative}
.modbar{display:flex;align-items:center;gap:7px;background:#eef3fd;border:1px dashed #b9d0ef;border-radius:10px;padding:7px 10px;margin-bottom:8px;font-size:12.5px;color:#1c3155}
.modbar .mh{cursor:grab;color:#6a86ad;font-size:14px;line-height:1}
.modbar .mt{font-weight:600;flex:1;min-width:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.modbar button{background:#fff;border:1px solid #cddcf0;border-radius:7px;min-width:26px;height:26px;cursor:pointer;color:#2f6fd6;font-size:13px;flex:none}
.modbar button:hover{background:#e0ecfb}.modbar button.rem{color:#b23535}
.modbar select{font-family:inherit;font-size:12px;padding:3px 6px;border:1px solid #cddcf0;border-radius:7px;background:#fff}
.homeedit .modwrap .weekcard{pointer-events:none;opacity:.97}
.homeedit .modcol{min-height:64px;border:2px dashed #e0e6f0;border-radius:16px;padding:8px;transition:border-color .12s,background .12s}
.homeedit .modcol.dragover{border-color:var(--flik-blue);background:#f2f7ff}
.modtray{grid-column:1/-1;margin-top:4px;background:#f7f9fc;border:1px solid var(--line);border-radius:14px;padding:14px 16px}
.modtray .lbl{margin-bottom:10px}
.traychip{display:inline-flex;align-items:center;gap:6px;background:#fff;border:1px solid #cddcf0;border-radius:20px;padding:7px 13px;font-size:13px;color:var(--flik-blue);cursor:grab;margin:0 8px 8px 0}
.traychip:hover{background:#eef3fd}
.ministats{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}
.ministat{background:#f6f9fd;border:1px solid var(--line);border-radius:12px;padding:12px 10px;text-align:center}
.ministat .mn{font-size:24px;font-weight:700;line-height:1}
.ministat .ml{font-size:11px;color:var(--ink-soft);margin-top:4px}
.weathergrid{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
.rrow.click{cursor:pointer}.rrow.click:hover{background:#f7faff}
.travday{padding:4px 0 6px;border-bottom:1px solid var(--line)}.travday:last-child{border-bottom:none;padding-bottom:0}
.travhead{font-size:12.5px;font-weight:600;color:#c25a00;margin:6px 0 2px}
.rchev{color:var(--grey);display:flex;flex:none;align-items:center}
.pubbtn{background:#eef6ef;border:1px solid #cfe6d4;color:#1f7a42;border-radius:8px;width:32px;height:28px;cursor:pointer;display:grid;place-items:center;flex:none;padding:0}
.pubbtn:hover{background:#d9f0de}
.rrow.done{opacity:.75}
.donetag{display:inline-flex;align-items:center;gap:6px;font-size:12px;font-weight:500;color:#1f7a42;flex:none;white-space:nowrap}
.btnlink{background:none;border:none;color:var(--flik-blue);cursor:pointer;font-size:12px;font-family:inherit;padding:0;text-decoration:underline}
@media(max-width:880px){.ministats{grid-template-columns:repeat(2,1fr)}.weathergrid{grid-template-columns:repeat(3,1fr)}.modtray{grid-column:auto}}
.legend .item{display:flex;align-items:center;gap:9px;font-size:13px;padding:6px 0;cursor:pointer;border-radius:6px}
.legend .item:hover{background:#f5f8fc}.legend .item.off{opacity:.32}
.legend .sw{width:12px;height:12px;border-radius:3px;flex:none}.legend .c{margin-left:auto;font-size:12px;color:var(--ink-soft)}
.wheel-hint{font-size:12px;color:var(--ink-soft);margin-top:10px;text-align:center}
.note{background:#fff8e6;border:1px solid #f3e2af;color:#7a5b00;font-size:12.5px;padding:10px 14px;border-radius:10px;margin-bottom:18px}
.brief{background:#eef3fd;border:1px solid #d7e4f8;color:#26344d;font-size:13px;padding:12px 15px;border-radius:12px;margin:0 0 18px;white-space:pre-wrap;line-height:1.55}
.brief b{color:var(--flik-blue-dark)}
.teamgrid{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:20px}
.person{background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow);border:1px solid var(--line);padding:22px;display:flex;gap:16px}
.person .pav{width:48px;height:48px;border-radius:50%;background:var(--flik-blue);color:#fff;display:grid;place-items:center;font-weight:700;flex:none}
.person .pnm{font-weight:700;font-size:15px}
.person .pdet{font-size:12.5px;color:var(--ink-soft);margin-top:8px;line-height:1.7}
.person .pact{margin-top:16px;display:flex;gap:8px;flex-wrap:wrap}
.rbadge{font-size:10.5px;font-weight:700;padding:2px 7px;border-radius:5px;text-transform:uppercase;letter-spacing:.4px;background:#eef2f7;color:#5b6b86;margin-left:4px}
.rbadge.admin{background:#e7f0fb;color:#1c5fa8}
.rbadge.super{background:#1c3155;color:#fff}
.toolbar{display:flex;gap:12px;flex-wrap:wrap;margin-bottom:24px;align-items:center}
.toolbar select,.toolbar input{font-family:inherit;font-size:13px;padding:10px 13px;border:1px solid var(--line);border-radius:10px;background:#fff;color:var(--ink)}
.toolbar input{flex:1;min-width:160px}
.monthgroup{margin-bottom:28px}.monthgroup h3{font-size:13px;text-transform:uppercase;letter-spacing:.7px;color:var(--ink-soft);margin:0 0 12px;font-weight:500}
.elist{background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow);border:1px solid var(--line);overflow:hidden}
.erow{display:grid;grid-template-columns:70px 1fr auto auto;gap:15px;align-items:center;padding:16px 22px;border-bottom:1px solid var(--line);cursor:pointer}
.erow:last-child{border-bottom:none}.erow:hover{background:#f7faff}
.erow .date{font-size:12px;color:var(--ink-soft);font-weight:500;text-align:center;line-height:1.2}
.erow .date .day{font-size:19px;color:var(--ink);display:block;font-weight:700}
.erow .ti{display:flex;align-items:center;gap:9px;min-width:0}
.erow .ti .nm{font-weight:500;font-size:14.5px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.sport{font-size:12px;color:#fff;padding:2px 9px;border-radius:20px;font-weight:500;white-space:nowrap}
.muted{font-size:12px;color:var(--ink-soft);white-space:nowrap}
.recur{font-size:11px;color:#1c5fa8}
.overlay{position:fixed;inset:0;background:rgba(10,25,45,.55);backdrop-filter:blur(2px);display:none;align-items:flex-start;justify-content:center;padding:36px 16px;z-index:60;overflow:auto}
.overlay.open{display:flex}
.modal{background:var(--card);border-radius:22px;max-width:840px;width:100%;box-shadow:0 30px 80px rgba(0,0,0,.32);overflow:hidden;animation:pop .2s cubic-bezier(.2,.8,.2,1)}
@keyframes pop{from{transform:translateY(12px) scale(.99);opacity:0}to{transform:none;opacity:1}}
.modal .head{padding:28px 32px;color:#fff;position:relative}
.modal .head .idtag{color:rgba(255,255,255,.85);font-size:11px}
.modal .head h2{margin:6px 0 4px;font-size:22px;font-weight:700}
.modal .head .sub{font-size:13.5px;opacity:.92}
.modal .close{position:absolute;top:16px;right:18px;background:rgba(255,255,255,.2);border:none;color:#fff;width:32px;height:32px;border-radius:50%;font-size:18px;cursor:pointer}
.modal .close:hover{background:rgba(255,255,255,.34)}
.mbody{padding:28px 32px 32px}
.fieldgrid{display:grid;grid-template-columns:1fr 1fr;gap:20px 32px;margin-bottom:24px;background:#f9fbfd;border:1px solid var(--line);border-radius:14px;padding:18px 20px}
.field .k{color:var(--ink-soft);font-size:11.5px;text-transform:uppercase;letter-spacing:.4px;margin-bottom:2px}
.field .v{font-weight:500;font-size:13.5px}.field .v.empty{color:var(--red);font-weight:400;font-style:italic}
.sectionlabel{font-size:13px;font-weight:700;margin:16px 0 10px;display:flex;align-items:center;gap:8px}
.sectionlabel .count{font-size:11px;font-weight:500;color:var(--ink-soft);background:#eef2f7;padding:1px 8px;border-radius:20px}
.timeline{position:relative;margin-left:6px;padding-left:20px;border-left:2px dashed var(--line)}
.post{position:relative;padding:0 0 15px}.post:last-child{padding-bottom:2px}
.post .pin{position:absolute;left:-27px;top:2px;width:13px;height:13px;border-radius:50%;background:#fff;border:3px solid var(--flik-blue)}
.post .ph{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.post .pd{font-size:12.5px;font-weight:700}.post .pt{font-size:13.5px;font-weight:500}
.post .pmeta{font-size:12px;color:var(--ink-soft);margin-top:3px;display:flex;gap:9px;flex-wrap:wrap;align-items:center}
.chan{font-size:11px;font-weight:500;padding:2px 8px;border-radius:6px;background:#eef3f9;color:#34536f}
.nopost{padding:14px 16px;font-size:13px;color:var(--ink-soft);background:#f7f9fc;border-radius:10px;border:1px dashed var(--line)}
.checklist{margin-top:16px;background:#f7f9fc;border:1px solid var(--line);border-radius:12px;padding:14px 16px}
.checklist h4{margin:0 0 9px;font-size:13px}
.check{display:flex;align-items:center;gap:9px;font-size:13px;padding:3px 0}
.check .box{width:16px;height:16px;border-radius:5px;border:2px solid var(--grey);flex:none;display:grid;place-items:center;font-size:11px;color:#fff}
.check.done .box{background:var(--green);border-color:var(--green)}.check.done .lbl{color:var(--ink-soft);text-decoration:line-through}
.check.todo .box{border-color:var(--amber)}
.links{display:flex;gap:12px;flex-wrap:wrap;margin-top:20px}
.btn{font-family:inherit;font-size:13px;font-weight:500;padding:10px 16px;border-radius:10px;border:1px solid var(--line);background:#fff;color:var(--flik-blue);cursor:pointer;display:inline-flex;align-items:center;gap:7px}
.btn:hover{background:#f1f6fc;text-decoration:none}.btn[disabled]{opacity:.45;cursor:not-allowed}
footer{color:var(--ink-soft);font-size:12.5px;text-align:center;padding:24px 20px 40px}
.btn.solid{background:var(--flik-blue);color:#fff;border-color:var(--flik-blue)}
.btn.solid:hover{background:var(--flik-blue-dark)}
.btn.sm{padding:8px 13px;font-size:12px}
.headbtn{position:absolute;top:20px;right:60px;background:rgba(255,255,255,.18);border:none;color:#fff;font-family:inherit;font-size:12.5px;font-weight:500;padding:7px 13px;border-radius:9px;cursor:pointer}
.headbtn:hover{background:rgba(255,255,255,.32)}
.linkchips{display:flex;gap:12px;flex-wrap:wrap;margin:0 0 24px}
.linkchip{display:inline-flex;align-items:center;gap:8px;background:#f1f6fc;border:1px solid #dbe7f5;border-radius:12px;padding:12px 16px;font-size:13px;color:var(--ink)}
.linkchip b{color:var(--flik-blue);font-weight:600}
.linkchip:hover{background:#e7f0fb;text-decoration:none}
.planhead{display:flex;align-items:center;justify-content:space-between;gap:10px;margin:6px 0 12px;flex-wrap:wrap}
.planttl{font-size:15.5px;font-weight:700}
.planbtns{display:flex;gap:8px}
.tasklist{display:flex;flex-direction:column;gap:11px;margin-bottom:28px}
.taskitem{border:1px solid var(--line);border-radius:13px;background:#fff;overflow:hidden;transition:box-shadow .15s,border-color .15s}
.taskitem.open{box-shadow:var(--shadow);border-color:#dbe7f5}
.taskrow{display:grid;grid-template-columns:16px 62px minmax(0,1fr) auto auto;gap:12px;align-items:center;padding:15px 17px;cursor:pointer}
.taskrow:hover{background:#f7faff}
.caret{color:var(--grey);display:inline-flex;align-items:center;transition:transform .15s}
.caret .ico{width:16px;height:16px;vertical-align:0}
.taskitem.open .caret{transform:rotate(90deg)}
.tdate{font-size:12px;color:var(--ink-soft);font-weight:500;white-space:nowrap}
.tlabel{display:flex;align-items:center;gap:8px;min-width:0;font-weight:500;font-size:14px}
.tl-txt{white-space:nowrap;overflow:hidden;text-overflow:ellipsis;min-width:0}
.platchip{flex:none;font-size:10.5px;font-weight:600;padding:2px 8px;border-radius:20px;white-space:nowrap}
.statussel{font-family:inherit;font-size:11px;font-weight:500;border:none;border-radius:20px;padding:4px 10px;cursor:pointer;-webkit-appearance:none;appearance:none;max-width:150px}
.statussel.st-planlagt{background:#e7f0fb;color:#1c5fa8}.statussel.st-arbeid{background:#fdf0d6;color:#9a6b00}
.statussel.st-klar{background:#dcf3ee;color:#137a6e}.statussel.st-publisert{background:#dff3e4;color:#1f7a42}
.chk{display:flex;align-items:center;gap:6px;font-size:13px;color:var(--ink-soft)}.chk input{width:auto}
.trowf{display:grid;grid-template-columns:64px 1fr auto;gap:14px;align-items:center;padding:13px 18px;border-bottom:1px solid var(--line);cursor:pointer}
.elist .trowf:last-child{border-bottom:none}.trowf:hover{background:#f7faff}
.trd{font-size:12px;color:var(--ink-soft);font-weight:600;text-align:center}
.trmeta{min-width:0}
.trt{font-weight:500;font-size:14px;display:flex;align-items:center;gap:8px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.trs{font-size:12px;color:var(--ink-soft);margin-top:2px;display:flex;align-items:center;gap:7px;flex-wrap:wrap}
.tflag{font-size:10.5px;font-weight:600;padding:1px 7px;border-radius:20px;flex:none}
.tflag.red{background:#fde3e3;color:#b23535}.tflag.amber{background:#fdf0d6;color:#9a6b00}
.segbar{display:flex;gap:8px;flex-wrap:wrap;align-items:center;margin-bottom:18px}
.seg{font-family:inherit;font-size:13px;font-weight:500;padding:8px 14px;border-radius:20px;border:1px solid var(--line);background:#fff;color:var(--ink-soft);cursor:pointer;display:inline-flex;align-items:center;gap:7px}
.seg:hover{background:#f3f7fc}
.seg.active{background:var(--flik-blue);color:#fff;border-color:var(--flik-blue)}
.segc{font-size:11px;font-weight:700;background:rgba(20,40,80,.09);border-radius:20px;padding:0 7px;min-width:18px;text-align:center}
.seg.active .segc{background:rgba(255,255,255,.28)}
.segtoggle{font-family:inherit;font-size:13px;font-weight:500;padding:8px 14px;border-radius:20px;border:1px solid var(--line);background:#fff;color:var(--ink-soft);cursor:pointer;margin-left:auto;display:inline-flex;align-items:center;gap:6px}
.segtoggle.on{background:#eef3fd;border-color:#cddcf0;color:var(--flik-blue)}
@media(max-width:880px){.segtoggle{margin-left:0}}
@media(max-width:880px){.trowf{grid-template-columns:56px 1fr auto;gap:10px}}
.tchan{font-size:11px;color:var(--ink-soft);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:170px;text-align:right}
.taskdetail{display:none;padding:2px 15px 15px}
.taskitem.open .taskdetail{display:block}
.taskactions{display:flex;gap:7px;align-items:center;margin-top:12px;flex-wrap:wrap}
@media(max-width:880px){.taskrow{grid-template-columns:16px 52px 1fr auto;gap:9px}.tchan{display:none}}
form.f label{display:block;font-size:12.5px;color:var(--ink-soft);margin:18px 0 6px;font-weight:500}
form.f input,form.f select,form.f textarea{width:100%;font-family:inherit;font-size:15px;padding:12px 14px;border:1px solid #e6ebf2;border-radius:11px;background:#fbfcfe;color:var(--ink);transition:border-color .12s,box-shadow .12s,background .12s}
form.f textarea{resize:vertical;min-height:150px;line-height:1.6}
.postbody{margin-top:8px;background:#f7f9fc;border:1px solid var(--line);border-radius:8px;padding:11px 13px;font-size:13.5px;line-height:1.55;white-space:pre-wrap}
form.f input:focus,form.f select:focus,form.f textarea:focus{outline:none;border-color:var(--flik-blue);background:#fff;box-shadow:0 0 0 3px rgba(0,82,155,.12)}
form.f .two{display:grid;grid-template-columns:1fr 1fr;gap:16px 24px}
form.f .actions{margin-top:24px;padding-top:18px;border-top:1px solid var(--line);display:flex;gap:10px;justify-content:flex-end}
form.f .actions .btn{padding:11px 22px;font-size:14px}
@media(max-width:880px){form.f .two{grid-template-columns:1fr}}
@media(max-width:880px){.stats{grid-template-columns:repeat(2,1fr)}.grid2,.wheelwrap{grid-template-columns:1fr}.fieldgrid{grid-template-columns:1fr}}
</style>
@endverbatim
@php
$themes = [
  'blue'  => ['blue'=>'2f6fd6','dark'=>'1c3155','deep'=>'1a1e39','navy'=>'1c3155','sky'=>'6aaae4','accent'=>'fb471f','grad'=>'linear-gradient(125deg,#26406e,#1c3155 55%,#1a1e39)'],
  'red'   => ['blue'=>'c0392b','dark'=>'4a121c','deep'=>'350c14','navy'=>'4a121c','sky'=>'dd9a9a','accent'=>'e8a200','grad'=>'linear-gradient(125deg,#6e2634,#4a121c 55%,#350c14)'],
  'green' => ['blue'=>'2e9e5b','dark'=>'15412b','deep'=>'0e2f1e','navy'=>'15412b','sky'=>'8fcfa6','accent'=>'e8a200','grad'=>'linear-gradient(125deg,#245c3d,#15412b 55%,#0e2f1e)'],
];
$th = $themes[$brand['theme'] ?? 'blue'] ?? $themes['blue'];
@endphp
<style>:root{--flik-blue:#{{ $th['blue'] }};--flik-blue-dark:#{{ $th['dark'] }};--flik-blue-deep:#{{ $th['deep'] }};--navy:#{{ $th['navy'] }};--sky:#{{ $th['sky'] }};--accent:#{{ $th['accent'] }};--brand-grad:{{ $th['grad'] }}}</style>
</head>
<body>
<div class="topbar">
  <div class="wrap">
    <div class="brand">
      <span class="logosvg"></span>
      <div>
        <h1>Vivu Planner</h1>
        <div class="sub">{{ $brand['subtitle'] ?? 'Årshjul' }}</div>
      </div>
    </div>
    <div class="spacer"></div>
    @if($canEdit)<button class="iconbtn" title="Innstillinger" onclick="openSettings()"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"/><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"/></svg></button>@endif
    <div class="usermenu">
      <button class="userchip" onclick="toggleUserMenu(event)" title="Meny"><span>{{ $user->name }}</span></button>
      <div class="usermenudd" id="userDD"></div>
    </div>
    <form method="POST" action="{{ route('logout') }}" id="logoutForm" style="display:none">@csrf</form>
  </div>
</div>
<div class="tabbar">
  <div class="tabs">
    <button class="tab active" data-view="home">Dashboard</button>
    <button class="tab" data-view="wheel">Årshjul</button>
    <button class="tab" data-view="list">Eventliste</button>
    <button class="tab" data-view="tasks">Oppgaver</button>
    <button class="tab" data-view="klubbliv">Klubbliv</button>
    @if($canEdit)<button class="tab" data-view="team">Brukere</button>@endif
  </div>
</div>

<main class="wrap">
  <section class="view active" id="view-home">
    <div class="viewhead"><h2>Dashboard</h2><div id="homeActions"></div></div>
    <div id="homeHost"></div>
  </section>
  <section class="view" id="view-wheel">
    <div class="viewhead"><h2>Årshjul 2026</h2>@if($canEdit)<button class="btn solid" onclick="openEventForm()">＋ Nytt arrangement</button>@endif</div>
    <div class="note">Klikk en prikk i hjulet eller en idrett i forklaringen for å filtrere. Årlige, gjentakende arrangement er merket med et eget ikon.</div>
    <div class="wheelwrap">
      <div class="wheelcard"><div id="wheelHost"></div><div class="wheel-hint" id="wheelHint"></div><div id="wheelTip" class="wheeltip"></div></div>
      <div class="legend" id="legend"></div>
    </div>
  </section>
  <section class="view" id="view-list">
    <div class="viewhead"><h2>Eventliste</h2>@if($canEdit)<button class="btn solid" onclick="openEventForm()">＋ Nytt arrangement</button>@endif</div>
    <div class="toolbar">
      <input type="text" id="search" placeholder="Søk etter arrangement…">
      <select id="fSport"><option value="">Alle idretter</option></select>
      <select id="fStatus"><option value="">Alle statuser</option></select>
      <button class="btn" id="archiveBtn" onclick="toggleArchive()"><svg class="ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2" /><path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-10" /><path d="M10 12l4 0" /></svg> Vis gjennomførte</button>
    </div>
    <div id="listHost"></div>
  </section>
  <section class="view" id="view-tasks">
    <div class="viewhead"><h2>Oppgaver</h2></div>
    <div class="toolbar">
      <input type="text" id="tsearch" placeholder="Søk i oppgaver…">
      <select id="tSport"><option value="">Alle idretter</option></select>
    </div>
    <div class="segbar" id="tScope"></div>
    <div id="taskListHost"></div>
  </section>
  <section class="view" id="view-klubbliv">
    <div class="viewhead"><h2>Klubbliv</h2>@if($canEdit)<button class="btn solid" onclick="openKlubblivForm(null)"><svg class="ico" viewBox="0 0 24 24"><path d="M12 5l0 14M5 12l14 0"/></svg> Ny klubbliv-post</button>@endif</div>
    <div class="note">Innhold mellom arrangementene – verving, engasjement og praktisk info. Nederst kan du redigere idé-biblioteket.</div>
    <div id="klubblivHost"></div>
  </section>
  @if($canEdit)
  <section class="view" id="view-team">
    <div class="viewhead"><h2>Brukere &amp; tilgang</h2><button class="btn solid" onclick="openUserForm()">＋ Legg til bruker</button></div>
    <div class="note">Admin kan opprette/endre alt. Medlem kan kun se og kopiere tekster. Bruk «Nullstill passord» når noen skal få nytt passord.</div>
    <div class="teamgrid" id="teamHost"></div>
  </section>
  @endif
</main>

<footer>Vivu Planner · Farsund og Lista Idrettsklubb · Laget av <a href="https://havdurdesign.no" target="_blank" rel="nofollow noopener sponsored">Havdur Design</a></footer>
<div class="overlay" id="overlay"><div class="modal" id="modal"></div></div>

<script>
window.DATA = @json($events);
window.ME = @json(['name' => $user->name, 'id' => $user->id, 'email' => $user->email]);
window.COMPANIES = @json($companies);
window.CURRENT_COMPANY_ID = @json($currentCompanyId);
window.BRAND = @json($brand);
window.CATS = @json($categories);
window.CAT_MANAGE = @json($categoriesManage);
window.CAT_LABEL = @json($catLabel);
window.GOALS = @json($goals);
window.MEMBERS = @json($members);
window.DESTS = @json($destinations);
window.CANEDIT = @json($canEdit);
window.TEAM = @json($teamUsers);
window.KLUBBLIV = @json($klubbliv);
window.IDEAS = @json($ideas);
window.TRAINING = @json($training);
window.KAMPER = @json($kamper);
window.WEATHER = @json($weather);
window.LAYOUT = @json($layout);
window.LAYOUT_DEFAULT = @json($layoutDefault);
window.HAS_USER_LAYOUT = @json($hasUserLayout);
window.IS_SUPERADMIN = @json($isSuperadmin);
window.COMPANY_SLUG = @json($company->slug ?? 'flik');
window.CSRF = '{{ csrf_token() }}';
</script>
@verbatim
<script>
const LOGO='<svg class="brandmark" viewBox="0 0 191.4 365" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M191.4,121.5V45l-11.7,3.6C95.2,74.9,43.6,106.1,26.6,141.5c-8,16.5-8.6,33.7-1.8,51.1c5.3,13.7,14.4,23.2,27.1,28.2c-9.8,65.7,7.5,134.3,8.3,137.4l1.7,6.8h65.7l-1.9-10.6c-1.1-6.1-2.3-12.1-3.5-17.9c-5.8-28.4-10.3-50.9,0.6-66.9c9-13.2,29-22.4,61.3-28.1l7.4-1.3l0-81.4l-13.9,9.2C162,178,124.8,198.7,92.8,205c-2.7,5.8-4.4,12.5-5.4,19.1c15.4-1.9,33.2-7.3,53.2-16.2c12.9-5.7,24.4-11.9,32.8-16.7v33.7c-33.4,6.9-54.4,17.8-65.6,34.3c-15.2,22.3-9.4,50.6-3.4,80.6c0.5,2.3,0.9,4.6,1.4,6.9H76c-3.7-17.2-12.4-64.3-7.8-111.3c0.6-9.7,1.9-20,4.9-29.5c17.1-58.2,110.6-82.7,111.5-83L191.4,121.5z M173.4,107.7c-9.9,3-29.2,9.7-49.6,20.7c-37,20-60.6,45.6-68.4,74.2c-6.3-3.4-10.9-8.8-13.8-16.5c-5-12.8-4.6-24.8,1.2-36.8c17.2-35.5,78.2-62.5,130.6-79.8V107.7z"/><path fill="currentColor" d="M41.8,83.6c-5.7,0-11.5-1.2-16.9-3.6C14.7,75.5,6.9,67.3,2.8,56.9s-3.8-21.8,0.7-32S16.3,6.9,26.7,2.8c21.5-8.3,45.7,2.4,54.1,23.9c8.3,21.5-2.4,45.7-23.9,54.1C52,82.7,46.9,83.6,41.8,83.6z M41.8,18c-2.9,0-5.8,0.5-8.6,1.6c-5.9,2.3-10.6,6.8-13.2,12.6c-2.6,5.8-2.7,12.3-0.4,18.2c2.3,5.9,6.8,10.6,12.6,13.2c5.8,2.6,12.3,2.7,18.2,0.4C62.6,59.2,68.7,45.4,64,33.2C60.3,23.8,51.3,18,41.8,18z"/></svg>';
document.querySelector('.logosvg').innerHTML=(window.BRAND&&window.BRAND.mark)?'<img src="'+window.BRAND.mark+'" alt="" style="height:38px;width:auto;display:block;border-radius:6px">':LOGO;

const DATA=window.DATA||[];
const TODAY=new Date();
const MONTHS=['Januar','Februar','Mars','April','Mai','Juni','Juli','August','September','Oktober','November','Desember'];
const MS=['jan','feb','mar','apr','mai','jun','jul','aug','sep','okt','nov','des'];
const col=e=>e.color||'#8795a3';
const fmt=d=>{if(!d)return '';const x=new Date(d);return x.getDate()+'. '+MS[x.getMonth()];};
const daysTo=d=>Math.round((new Date(d)-TODAY)/86400000);
const initials=n=>(n||'?').split(' ').map(w=>w[0]).join('').slice(0,2).toUpperCase();

function eventState(e){
  const dt=new Date(e.date);
  if(e.approval==='Internt'||/ikke markedsf|ikke skal publis/i.test(e.notat||''))return{key:'Internt',cls:'st-avlyst'};
  if(dt<TODAY)return{key:'Gjennomført',cls:'st-publisert'};
  if((!e.posts||!e.posts.length)&&e.type!=='Administrasjon')return{key:'Mangler innhold',cls:'st-mangler'};
  if((e.posts||[]).some(p=>p.status==='Under arbeid'))return{key:'Under arbeid',cls:'st-arbeid'};
  return{key:'Planlagt',cls:'st-planlagt'};
}
function approvalPill(e){
  if(e.approval==='Godkjent')return '<span class="pill st-godkjent">✓ Godkjent</span>';
  if(e.approval==='Til godkjenning')return '<span class="pill st-tilgodkj">⏳ Til godkjenning</span>';
  return '';
}

/* user chip */
document.querySelector('.userchip').insertAdjacentHTML('beforeend','<span class="av">'+initials(window.ME.name)+'</span>');

/* DASHBOARD */
function renderStats(){
  const totalPosts=DATA.reduce((a,e)=>a+(e.posts?e.posts.length:0),0);
  const upcoming=DATA.filter(e=>new Date(e.date)>=TODAY);
  const needsWork=upcoming.filter(e=>eventState(e).key==='Mangler innhold').length;
  const toApprove=DATA.filter(e=>e.approval==='Til godkjenning').length;
  document.getElementById('statRow').innerHTML=[
    {c:'b1',n:DATA.length,l:'Arrangement i årshjulet'},
    {c:'b2',n:totalPosts,l:'Planlagte oppgaver'},
    {c:'b3',n:needsWork,l:'Kommende uten innhold'},
    {c:'b4',n:toApprove,l:'Venter på godkjenning'}
  ].map(s=>'<div class="stat '+s.c+'"><div class="bar"></div><div class="n">'+s.n+'</div><div class="l">'+s.l+'</div></div>').join('');
}
function renderUrgent(){
  let items=[];
  DATA.forEach(e=>(e.posts||[]).forEach(p=>{if(!p.date)return;const dd=daysTo(p.date);if(dd>=-3&&dd<=60&&!p.published)items.push({e,p,dd});}));
  DATA.filter(e=>{const dd=daysTo(e.date);return dd>=0&&dd<=70&&(!e.posts||!e.posts.length)&&e.type!=='Administrasjon'&&!/ikke markedsf|ikke skal/i.test(e.notat||'');})
    .forEach(e=>items.push({e,p:null,dd:daysTo(e.date),missing:true}));
  items.sort((a,b)=>a.dd-b.dd);
  document.getElementById('urgCount').textContent=items.length;
  if(!items.length){document.getElementById('urgentList').innerHTML='<div class="row" style="cursor:default"><div class="meta"><div class="s">Ingenting forfaller de neste ukene.</div></div></div>';return;}
  document.getElementById('urgentList').innerHTML=items.map(i=>{
    const when=i.dd<0?'forsinket':(i.dd===0?'i dag':'om '+i.dd+' d'),cls=i.dd<=7?'urgent':(i.dd<=21?'soon':'');
    const label=i.missing?'Mangler oppgaver & tekst':(i.p.label||'Innlegg');
    return '<div class="row" onclick="openEvent('+i.e.id+')"><span class="dot" style="background:'+col(i.e)+'"></span>'+
      '<div class="meta"><div class="t">'+i.e.title+'</div><div class="s">'+label+' · '+i.e.sport+'</div></div>'+
      '<div class="when"><span class="pill '+(i.missing?'st-mangler':'st-arbeid')+'">'+(i.missing?'mangler':i.p.status)+'</span><div class="d '+cls+'">'+(i.p?fmt(i.p.date):fmt(i.e.date))+' · '+when+'</div></div></div>';
  }).join('');
}
function renderUpcoming(){
  const up=DATA.filter(e=>new Date(e.date)>=TODAY).sort((a,b)=>new Date(a.date)-new Date(b.date)).slice(0,8);
  document.getElementById('upcomingList').innerHTML=up.map(e=>{
    const st=eventState(e),dd=daysTo(e.date);
    return '<div class="row" onclick="openEvent('+e.id+')"><span class="dot" style="background:'+col(e)+'"></span>'+
      '<div class="meta"><div class="t">'+e.title+' '+(e.recur==='yearly'?'<span class="recur">'+ic('refresh')+'</span>':'')+'</div><div class="s">'+e.sport+' · '+(e.posts?e.posts.length:0)+' oppgaver</div></div>'+
      '<div class="when"><span class="pill '+st.cls+'">'+st.key+'</span><div class="d">'+fmt(e.date)+' · om '+dd+' d</div></div></div>';
  }).join('')||'<div class="row" style="cursor:default"><div class="meta"><div class="s">Ingen flere arrangement i 2026.</div></div></div>';
}

/* WHEEL */
let hidden=new Set();
function renderWheel(){
  const evs=DATA;
  document.getElementById('wheelHint').textContent='FLIK Årshjul 2026 · '+evs.length+' arrangement';
  const size=560,cx=280,cy=280,rO=250,rI=120;
  let svg='<svg class="wheel" viewBox="0 0 '+size+' '+size+'" xmlns="http://www.w3.org/2000/svg">';
  for(let m=0;m<12;m++){
    const a0=(m*30-90)*Math.PI/180,a1=((m+1)*30-90)*Math.PI/180;
    const x0=cx+rO*Math.cos(a0),y0=cy+rO*Math.sin(a0),x1=cx+rO*Math.cos(a1),y1=cy+rO*Math.sin(a1);
    const xi0=cx+rI*Math.cos(a0),yi0=cy+rI*Math.sin(a0),xi1=cx+rI*Math.cos(a1),yi1=cy+rI*Math.sin(a1);
    svg+='<path d="M'+xi0+' '+yi0+' L'+x0+' '+y0+' A'+rO+' '+rO+' 0 0 1 '+x1+' '+y1+' L'+xi1+' '+yi1+' A'+rI+' '+rI+' 0 0 0 '+xi0+' '+yi0+' Z" fill="'+(m%2?'#eef3f9':'#f7fafd')+'" stroke="#dde5ee"/>';
    const am=((m+0.5)*30-90)*Math.PI/180;
    svg+='<text x="'+(cx+(rO+18)*Math.cos(am))+'" y="'+(cy+(rO+18)*Math.sin(am))+'" font-size="12" font-weight="500" fill="#5b6b7b" text-anchor="middle" dominant-baseline="middle" font-family="Ubuntu">'+MS[m].toUpperCase()+'</text>';
  }
  svg+='<circle cx="'+cx+'" cy="'+cy+'" r="'+(rI-6)+'" fill="#1c3155"/>';
  svg+='<text x="'+cx+'" y="'+(cy-10)+'" font-size="30" font-weight="700" fill="#fff" text-anchor="middle" font-family="Ubuntu">FLIK</text>';
  svg+='<text x="'+cx+'" y="'+(cy+14)+'" font-size="13" fill="#cfe0f2" text-anchor="middle" font-family="Ubuntu">Årshjul 2026</text>';
  const byMonth={};evs.forEach(e=>{const m=new Date(e.date).getMonth();(byMonth[m]=byMonth[m]||[]).push(e);});
  Object.keys(byMonth).forEach(m=>{
    const list=byMonth[m].filter(e=>!hidden.has(e.sport));
    list.forEach((e,i)=>{
      const n=list.length;
      const am=((+m+(n===1?0.5:(0.18+0.64*i/Math.max(1,n-1))))*30-90)*Math.PI/180;
      const rr=rI+30+(i%3)*30;
      const px=cx+rr*Math.cos(am),py=cy+rr*Math.sin(am);
      svg+='<circle cx="'+px+'" cy="'+py+'" r="8.5" fill="'+col(e)+'" stroke="#fff" stroke-width="2" style="cursor:pointer" onclick="openEvent('+e.id+')" onmousemove="wheelTip(event,'+e.id+')" onmouseleave="hideWheelTip()"></circle>';
    });
  });
  svg+='</svg>';
  document.getElementById('wheelHost').innerHTML=svg;
}
function wheelTip(ev,id){
  const e=DATA.find(x=>x.id===id);if(!e)return;
  const tip=document.getElementById('wheelTip');if(!tip)return;
  const box=tip.parentElement.getBoundingClientRect();
  tip.innerHTML='<div class="tt">'+esc(e.title)+'</div><div class="td">'+e.sport+' · '+fmt(e.date)+'</div>';
  tip.classList.add('on');
  let x=ev.clientX-box.left+14, y=ev.clientY-box.top+14;
  if(x+tip.offsetWidth>box.width-6)x=ev.clientX-box.left-tip.offsetWidth-12;
  tip.style.left=x+'px';tip.style.top=y+'px';
}
function hideWheelTip(){const t=document.getElementById('wheelTip');if(t)t.classList.remove('on');}
function renderLegend(){
  const counts={},colors={};
  DATA.forEach(e=>{counts[e.sport]=(counts[e.sport]||0)+1;colors[e.sport]=col(e);});
  const keys=Object.keys(counts).sort();
  const allHidden=keys.length>0&&keys.every(s=>hidden.has(s));
  document.getElementById('legend').innerHTML=
    '<div class="legendhead"><h4>Idretter / grupper</h4><button class="toggleall" onclick="toggleAllSports()">'+(allHidden?'Vis alle':'Vis ingen')+'</button></div>'+
    keys.map(s=>
      '<div class="item '+(hidden.has(s)?'off':'')+'" onclick="toggleSport(\''+s.replace(/'/g,"\\'")+'\')"><span class="sw" style="background:'+colors[s]+'"></span>'+s+'<span class="c">'+counts[s]+'</span></div>').join('');
}
function toggleSport(s){hidden.has(s)?hidden.delete(s):hidden.add(s);renderWheel();renderLegend();}
function toggleAllSports(){
  const keys=Object.keys(DATA.reduce((a,e)=>{a[e.sport]=1;return a;},{}));
  const allHidden=keys.length>0&&keys.every(s=>hidden.has(s));
  if(allHidden){hidden.clear();}else{keys.forEach(s=>hidden.add(s));}
  renderWheel();renderLegend();
}

/* LIST */
function populateFilters(){
  const sp=document.getElementById('fSport');
  [...new Set(DATA.map(e=>e.sport))].sort().forEach(s=>sp.insertAdjacentHTML('beforeend','<option>'+s+'</option>'));
  ['Mangler innhold','Under arbeid','Planlagt','Gjennomført','Internt'].forEach(s=>document.getElementById('fStatus').insertAdjacentHTML('beforeend','<option>'+s+'</option>'));
}
function renderList(){
  const q=document.getElementById('search').value.toLowerCase(),fs=document.getElementById('fSport').value,fst=document.getElementById('fStatus').value;
  const fom=new Date(TODAY.getFullYear(),TODAY.getMonth(),1);
  let evs=DATA.filter(e=>{
    if(!showArchive&&new Date(e.date)<fom)return false;
    if(fs&&e.sport!==fs)return false;if(fst&&eventState(e).key!==fst)return false;
    if(q&&!((e.title+' '+(e.desc||'')+' '+e.sport).toLowerCase().includes(q)))return false;return true;
  }).slice().sort((a,b)=>new Date(a.date)-new Date(b.date));
  const groups={};evs.forEach(e=>{const m=new Date(e.date).getMonth();(groups[m]=groups[m]||[]).push(e);});
  let html='';
  Object.keys(groups).sort((a,b)=>a-b).forEach(m=>{
    html+='<div class="monthgroup"><h3>'+MONTHS[m]+' 2026</h3><div class="elist">';
    groups[m].forEach(e=>{
      const st=eventState(e),d=new Date(e.date);
      html+='<div class="erow" onclick="openEvent('+e.id+')">'+
        '<div class="date"><span class="day">'+d.getDate()+'</span>'+MS[m]+'</div>'+
        '<div class="ti"><span class="sport" style="background:'+col(e)+'">'+e.sport+'</span><span class="nm">'+e.title+'</span> '+(e.recur==='yearly'?'<span class="recur">'+ic('refresh')+'</span>':'')+'</div>'+
        '<div class="muted">'+((e.posts&&e.posts.length)?e.posts.length+' oppgaver':'—')+'</div>'+
        '<span class="pill '+st.cls+'">'+st.key+'</span></div>';
    });
    html+='</div></div>';
  });
  document.getElementById('listHost').innerHTML=html||'<div class="nopost" style="margin:0">Ingen treff.</div>';
}

/* OUTLINE-IKONER */
const ICONS={
  globe:'<path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M3.6 9h16.8" /><path d="M3.6 15h16.8" /><path d="M11.5 3a17 17 0 0 0 0 18" /><path d="M12.5 3a17 17 0 0 1 0 18" />',
  doc:'<path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2" /><path d="M9 9l1 0" /><path d="M9 13l6 0" /><path d="M9 17l6 0" />',
  info:'<path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 9h.01" /><path d="M11 12h1v4h1" />',
  calendar:'<path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2l0 -12" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2l0 -2" />',
  plus:'<path d="M12 5l0 14" /><path d="M5 12l14 0" />',
  edit:'<path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" />',
  copy:'<path d="M7 9.667a2.667 2.667 0 0 1 2.667 -2.667h8.666a2.667 2.667 0 0 1 2.667 2.667v8.666a2.667 2.667 0 0 1 -2.667 2.667h-8.666a2.667 2.667 0 0 1 -2.667 -2.667l0 -8.666" /><path d="M4.012 16.737a2.005 2.005 0 0 1 -1.012 -1.737v-10c0 -1.1 .9 -2 2 -2h10c.75 0 1.158 .385 1.5 1" />',
  trash:'<path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />',
  link:'<path d="M9 15l6 -6" /><path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" /><path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" />',
  refresh:'<path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />',
  mail:'<path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10" /><path d="M3 7l9 6l9 -6" />',
  tag:'<path d="M6.5 7.5a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M3 6v5.172a2 2 0 0 0 .586 1.414l7.71 7.71a2.41 2.41 0 0 0 3.408 0l5.592 -5.592a2.41 2.41 0 0 0 0 -3.408l-7.71 -7.71a2 2 0 0 0 -1.414 -.586h-5.172a3 3 0 0 0 -3 3" />',
  folder:'<path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2" />',
  sparkle:'<path d="M16 18a2 2 0 0 1 2 2a2 2 0 0 1 2 -2a2 2 0 0 1 -2 -2a2 2 0 0 1 -2 2m0 -12a2 2 0 0 1 2 2a2 2 0 0 1 2 -2a2 2 0 0 1 -2 -2a2 2 0 0 1 -2 2m-7 12a6 6 0 0 1 6 -6a6 6 0 0 1 -6 -6a6 6 0 0 1 -6 6a6 6 0 0 1 6 6" />',
  key:'<path d="M16.555 3.843l3.602 3.602a2.877 2.877 0 0 1 0 4.069l-2.643 2.643a2.877 2.877 0 0 1 -4.069 0l-.301 -.301l-6.558 6.558a2 2 0 0 1 -1.239 .578l-.175 .008h-1.172a1 1 0 0 1 -.993 -.883l-.007 -.117v-1.172a2 2 0 0 1 .467 -1.284l.119 -.13l.414 -.414h2v-2h2v-2l2.144 -2.144l-.301 -.301a2.877 2.877 0 0 1 0 -4.069l2.643 -2.643a2.877 2.877 0 0 1 4.069 0" /><path d="M15 9h.01" />',
  archive:'<path d="M3 6a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2" /><path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-10" /><path d="M10 12l4 0" />',
  check:'<path d="M5 12l5 5l10 -10" />',
  chevron:'<path d="M9 6l6 6l-6 6" />'
};
function ic(n,cls){return '<svg class="ico'+(cls?' '+cls:'')+'" viewBox="0 0 24 24" aria-hidden="true">'+(ICONS[n]||'')+'</svg>';}
/* Oppgave-status og plattform */
const TSTATUS={planlagt:['Planlagt','st-planlagt'],under_arbeid:['Under arbeid','st-arbeid'],klar:['Klar for publisering','st-klar'],publisert:['Publisert','st-publisert']};
const PLATFORMS={facebook:['Facebook','1877F2'],instagram:['Instagram','C13584'],tiktok:['TikTok','111111'],snapchat:['Snapchat','9A8600'],linkedin:['LinkedIn','0A66C2'],youtube:['YouTube','C4302B']};
function statusSel(p,eid){
  const cur=p.status_raw||'planlagt';const cls=(TSTATUS[cur]||TSTATUS.planlagt)[1];
  return '<select class="statussel '+cls+'" onclick="event.stopPropagation()" onchange="setTaskStatus('+eid+','+p.id+',this.value)">'+
    Object.keys(TSTATUS).map(k=>'<option value="'+k+'"'+(k===cur?' selected':'')+'>'+TSTATUS[k][0]+'</option>').join('')+'</select>';
}
function platChip(p){
  if(!p.platform||!PLATFORMS[p.platform])return '';
  const pl=PLATFORMS[p.platform];const fmt=p.format==='story'?' · Story':'';
  return '<span class="platchip" style="background:#'+pl[1]+'22;color:#'+pl[1]+'">'+pl[0]+fmt+'</span>';
}
async function setTaskStatus(eventId,taskId,status){
  try{const card=await api('PUT','/tasks/'+taskId+'/status',{status});upsert(card);
    if(document.getElementById('overlay').classList.contains('open'))openEvent(eventId);
    rerender();
  }catch(err){alert(err.message);}
}

/* EVENT CARD */
function openEvent(id){
  const e=DATA.find(x=>x.id===id);if(!e)return;
  const c=col(e);
  const f=(k,v)=>'<div class="field"><div class="k">'+k+'</div><div class="v '+(v?'':'empty')+'">'+(v||'mangler')+'</div></div>';
  const checks=[['Dato satt',!!e.date],['Ansvarlig tildelt',!!e.ansvarlig],['Landingsside',!!e.landing],['Påmelding (Hoopit)',!!e.hoopit],['Oppgaver planlagt',!!(e.posts&&e.posts.length)]];
  let postsHtml;
  if(e.posts&&e.posts.length){
    postsHtml='<div class="tasklist">'+e.posts.map(p=>{
      const pages=(p.pages||[]).join(' · ');
      const stCls=p.status==='Under arbeid'?'st-arbeid':(p.status==='Publisert'?'st-publisert':(p.status==='Klar for publisering'?'st-klar':'st-planlagt'));
      return '<div class="taskitem" id="ti'+p.id+'">'+
        '<div class="taskrow" onclick="toggleTask('+p.id+')">'+
          '<span class="caret">'+ic('chevron')+'</span>'+
          '<span class="tdate">'+(p.date?fmt(p.date):'—')+'</span>'+
          '<span class="tlabel"><span class="tl-txt">'+(p.label||'Innlegg')+'</span>'+platChip(p)+'</span>'+
          '<span class="tchan">'+pages+'</span>'+
          (CANEDIT?statusSel(p,e.id):'<span class="pill '+stCls+'">'+p.status+'</span>')+
        '</div>'+
        '<div class="taskdetail">'+
          (p.body?'<div class="postbody">'+esc(p.body)+'</div>':'<div class="muted" style="padding:6px 0 2px">Ingen tekst lagt inn ennå.</div>')+
          (p.text?'<div style="margin-top:8px;font-size:12.5px"><a href="'+p.text+'" target="_blank">'+ic('link')+' Lenke ↗</a></div>':'')+
          '<div class="taskactions">'+
            (CANEDIT?'<button class="btn sm" onclick="openTaskForm('+e.id+','+p.id+')">'+ic('edit')+' Rediger</button>':'')+
            (p.body?'<button class="btn sm" onclick="copyText(this,'+e.id+','+p.id+')">'+ic('copy')+' Kopier tekst</button>':'')+
            '<span style="flex:1"></span>'+
            (CANEDIT?'<button class="btn sm" title="Flytt opp" onclick="moveTask('+e.id+','+p.id+',-1)">↑</button><button class="btn sm" title="Flytt ned" onclick="moveTask('+e.id+','+p.id+',1)">↓</button><button class="btn sm" style="color:#b23535" title="Slett" onclick="deleteTask('+e.id+','+p.id+')">'+ic('trash')+'</button>':'')+
          '</div>'+
        '</div>'+
      '</div>';
    }).join('')+'</div>';
  }else{
    postsHtml='<div class="nopost">Ingen oppgaver planlagt ennå. Bruk «Foreslå plan» for et komplett forslag basert på datoen.</div>';
  }
  const chips=
    (e.landing?'<a class="linkchip" href="'+e.landing+'" target="_blank">'+ic('globe')+' Landingsside <b>Åpne ↗</b></a>':'')+
    (e.hoopit?'<a class="linkchip" href="'+e.hoopit+'" target="_blank">'+ic('doc')+' Hoopit påmelding <b>Åpne ↗</b></a>':'');
  document.getElementById('modal').innerHTML=
    '<div class="head" style="background:linear-gradient(135deg,'+c+','+c+' 55%,rgba(0,0,0,.35) 160%)">'+
      '<button class="close" onclick="closeModal()">×</button>'+
      (CANEDIT?'<button class="headbtn" onclick="openEventForm('+e.id+')">'+ic('edit')+' Rediger</button>':'')+
      '<div class="idtag">'+e.type+(e.recur==='yearly'?' · '+ic('refresh')+' årlig':'')+'</div>'+
      '<h2>'+e.title+'</h2>'+(e.desc?'<div class="sub">'+e.desc+'</div>':'')+
      '<div style="margin-top:10px">'+approvalPill(e)+'</div></div>'+
    '<div class="mbody"><div class="fieldgrid">'+
      f('Dato',e.date?fmt(e.date)+' 2026':'')+f('Idrett / gruppe',e.sport)+f('Hovedmål',e.mal)+f('Ansvarlig',e.ansvarlig)+'</div>'+
      (chips?'<div class="linkchips">'+chips+'</div>':'')+
      (e.notat?'<div class="note" style="margin:0 0 18px">'+ic('doc')+' '+e.notat+'</div>':'')+
      (e.brief?'<div class="brief">'+ic('info')+' <b>Praktisk info:</b> '+esc(e.brief)+'</div>':'')+
      '<div class="planhead"><div class="planttl">'+ic('calendar')+' Publiseringsplan <span class="count">'+(e.posts?e.posts.length:0)+' oppgaver</span></div>'+
        (CANEDIT?'<div class="planbtns"><button class="btn solid sm" onclick="openPlanReview('+e.id+')">'+ic('sparkle')+' Foreslå plan</button><button class="btn sm" onclick="openTaskForm('+e.id+',null)">'+ic('plus')+' Oppgave</button></div>':'')+'</div>'+
      postsHtml+
      '<div class="checklist"><h4>Sjekkliste – klar for publisering?</h4>'+checks.map(ch=>'<div class="check '+(ch[1]?'done':'todo')+'"><span class="box">'+(ch[1]?ic('check','chk'):'')+'</span><span class="lbl">'+ch[0]+'</span></div>').join('')+'</div>'+
      (CANEDIT?'<div class="links">'+
        '<button class="btn" onclick="duplicateNextYear('+e.id+')">'+ic('copy')+' Dupliser til neste år</button>'+
        '<button class="btn" style="color:#b23535" onclick="deleteEvent('+e.id+')">'+ic('trash')+' Slett</button>'+
      '</div>':'')+'</div>';
  document.getElementById('overlay').classList.add('open');
}
function toggleTask(id){const it=document.getElementById('ti'+id);if(it)it.classList.toggle('open');}
function closeModal(){document.getElementById('overlay').classList.remove('open');}
document.getElementById('overlay').addEventListener('click',e=>{if(e.target.id==='overlay')closeModal();});
document.addEventListener('click',e=>{const dd=document.getElementById('userDD');if(dd&&dd.classList.contains('open')&&!(e.target.closest&&e.target.closest('.usermenu')))dd.classList.remove('open');});
document.addEventListener('keydown',e=>{if(e.key==='Escape')closeModal();});

/* TABS */
document.querySelectorAll('.tab').forEach(t=>t.addEventListener('click',()=>{
  document.querySelectorAll('.tab').forEach(x=>x.classList.remove('active'));
  document.querySelectorAll('.view').forEach(x=>x.classList.remove('active'));
  t.classList.add('active');document.getElementById('view-'+t.dataset.view).classList.add('active');
  if(t.dataset.view==='home')renderHome();
  if(t.dataset.view==='wheel'){renderWheel();renderLegend();}
  if(t.dataset.view==='list')renderList();
  if(t.dataset.view==='tasks')renderTaskList();
  if(t.dataset.view==='klubbliv')renderKlubbliv();
  if(t.dataset.view==='team')renderTeam();
}));
['search','fSport','fStatus'].forEach(id=>document.getElementById(id).addEventListener('input',renderList));
['tsearch','tSport'].forEach(id=>{const el=document.getElementById(id);if(el){el.addEventListener('input',renderTaskList);el.addEventListener('change',renderTaskList);}});

/* ---- REDIGERING (CRUD via fetch) ---- */
const CSRF=window.CSRF, CATS=window.CATS||[], MEMBERS=window.MEMBERS||[], DESTS=window.DESTS||[];
const CANEDIT=!!window.CANEDIT, TEAM=window.TEAM||[];
function esc(s){return String(s==null?'':s).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;');}
function val(id){const el=document.getElementById(id);return el?el.value.trim():'';}
async function api(method,url,body){
  const r=await fetch(url,{method,headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF},body:body?JSON.stringify(body):undefined});
  if(r.status===422){const j=await r.json();throw new Error(Object.values(j.errors||{}).flat().join('\n')||'Ugyldige data');}
  if(!r.ok)throw new Error('Noe gikk galt ('+r.status+'). Er du logget inn?');
  return r.status===204?null:r.json();
}
function upsert(card){const i=DATA.findIndex(x=>x.id===card.id);if(i>=0)DATA[i]=card;else DATA.push(card);}
function rerender(){renderHome();const a=document.querySelector('.tab.active');if(a&&a.dataset.view==='wheel'){renderWheel();renderLegend();}if(a&&a.dataset.view==='list')renderList();if(a&&a.dataset.view==='tasks')renderTaskList();}

function openEventForm(id){
  const e=id?DATA.find(x=>x.id===id):{};
  const sel=(v,o)=>String(v)===String(o)?' selected':'';
  const catOpts='<option value="">– ingen –</option>'+CATS.map(c=>'<option value="'+c.id+'"'+sel(e.category_id,c.id)+'>'+c.name+'</option>').join('');
  const memOpts='<option value="">– velg –</option>'+MEMBERS.map(m=>'<option value="'+m.id+'"'+sel(e.responsible_user_id,m.id)+'>'+m.name+'</option>').join('');
  const typeOpts=['Event','Turnering','Rekruttering','Administrasjon'].map(t=>'<option'+sel(e.type,t)+'>'+t+'</option>').join('');
  const gl=(window.GOALS||[]).slice();if(e.mal&&gl.indexOf(e.mal)<0)gl.unshift(e.mal);
  const malOpts='<option value="">– velg –</option>'+gl.map(t=>'<option'+sel(e.mal||'',t)+'>'+esc(t)+'</option>').join('');
  const apprOpts=[['utkast','Utkast'],['til_godkjenning','Til godkjenning'],['godkjent','Godkjent'],['internt','Internt']].map(o=>'<option value="'+o[0]+'"'+sel(e.approval_status||'utkast',o[0])+'>'+o[1]+'</option>').join('');
  document.getElementById('modal').innerHTML=
    '<div class="head" style="background:linear-gradient(135deg,var(--flik-blue),var(--flik-blue-deep))"><button class="close" onclick="closeModal()">×</button><h2>'+(id?'Rediger arrangement':'Nytt arrangement')+'</h2><div class="sub">Plasseres automatisk i årshjul og liste</div></div>'+
    '<div class="mbody"><form class="f" onsubmit="saveEventForm(event,'+(id||'null')+')">'+
      '<label>Tittel *</label><input id="f_title" required value="'+esc(e.title)+'" placeholder="f.eks. Tine Fotballskole">'+
      '<div class="two"><div><label>Idrett / gruppe</label><select id="f_cat">'+catOpts+'</select></div>'+
      '<div><label>Dato *</label><input id="f_date" type="date" required value="'+(e.date||'2026-08-21')+'"></div></div>'+
      '<div class="two"><div><label>Type</label><select id="f_type">'+typeOpts+'</select></div>'+
      '<div><label>Hovedmål</label><select id="f_goal">'+malOpts+'</select></div></div>'+
      '<div class="two"><div><label>Ansvarlig</label><select id="f_resp">'+memOpts+'</select></div>'+
      '<div><label>Gjentakelse</label><select id="f_recur"><option value="yearly"'+sel(e.recur,'yearly')+'>Årlig</option><option value="none"'+sel(e.recur,'none')+'>Engangs</option></select></div></div>'+
      '<div class="two"><div><label>Status</label><select id="f_appr">'+apprOpts+'</select></div><div></div></div>'+
      '<label>Beskrivelse</label><input id="f_desc" value="'+esc(e.desc)+'">'+
      '<label>Notat (intern)</label><input id="f_notat" value="'+esc(e.notat)+'">'+
      '<label>Praktisk info / stikkord <span style="font-weight:400;color:var(--ink-soft)">– grunnlag for AI-tekst</span></label>'+
      '<textarea id="f_brief" placeholder="F.eks. datoer, hva barna får (t-skjorte, ball), hva de må ha med, pris, sted, tider… Kan være stikkord.">'+esc(e.brief)+'</textarea>'+
      '<div class="two"><div><label>Landingsside</label><input id="f_land" value="'+esc(e.landing)+'" placeholder="https://flik.no/…"></div>'+
      '<div><label>Påmelding (Hoopit)</label><input id="f_hoop" value="'+esc(e.hoopit)+'"></div></div>'+
      '<div class="actions"><button type="button" class="btn" onclick="'+(id?'openEvent('+id+')':'closeModal()')+'">Avbryt</button><button class="btn solid" type="submit">Lagre</button></div>'+
    '</form></div>';
  document.getElementById('overlay').classList.add('open');
}
async function saveEventForm(ev,id){ev.preventDefault();
  const body={title:val('f_title'),category_id:val('f_cat')||null,type:val('f_type'),goal:val('f_goal')||null,event_date:val('f_date'),recurrence:val('f_recur'),approval_status:val('f_appr'),description:val('f_desc')||null,internal_note:val('f_notat')||null,brief:val('f_brief')||null,landing_url:val('f_land')||null,signup_url:val('f_hoop')||null,responsible_user_id:val('f_resp')||null};
  try{const card=id?await api('PUT','/events/'+id,body):await api('POST','/events',body);upsert(card);rerender();openEvent(card.id);}catch(err){alert(err.message);}
}
async function deleteEvent(id){
  if(!confirm('Slette dette arrangementet? Dette kan ikke angres.'))return;
  try{await api('DELETE','/events/'+id);const i=DATA.findIndex(x=>x.id===id);if(i>=0)DATA.splice(i,1);rerender();closeModal();}catch(err){alert(err.message);}
}

function openTaskForm(eventId,taskId){
  const e=DATA.find(x=>x.id===eventId);const t=(taskId?(e.posts||[]).find(p=>p.id===taskId):{})||{};
  const sel=(v,o)=>v===o?' selected':'';
  const stOpts=[['planlagt','Planlagt'],['under_arbeid','Under arbeid'],['klar','Klar for publisering'],['publisert','Publisert']].map(o=>'<option value="'+o[0]+'"'+sel(t.status_raw||'planlagt',o[0])+'>'+o[1]+'</option>').join('');
  const dsel=(t.destination_ids||[]);
  const destOpts=DESTS.map(d=>'<option value="'+d.id+'"'+(dsel.indexOf(d.id)>=0?' selected':'')+'>'+d.name+'</option>').join('');
  document.getElementById('modal').innerHTML=
    '<div class="head" style="background:linear-gradient(135deg,var(--flik-blue),var(--flik-blue-deep))"><button class="close" onclick="closeModal()">×</button><h2>'+(taskId?'Rediger oppgave':'Ny oppgave')+'</h2><div class="sub">Innlegg i publiseringsplanen</div></div>'+
    '<div class="mbody"><form class="f" onsubmit="saveTaskForm(event,'+eventId+','+(taskId||'null')+')">'+
      '<div class="two"><div><label>Hva slags post *</label><input id="t_label" required value="'+esc(t.label)+'" placeholder="f.eks. Teaser – hold av datoen"></div>'+
      '<div><label>Publiseringsdato</label><input id="t_date" type="date" value="'+(t.date||'')+'"></div></div>'+
      '<div class="two"><div><label>Status</label><select id="t_status">'+stOpts+'</select></div><div><label>Ansvarlig</label><select id="t_resp"><option value="">– ingen –</option>'+MEMBERS.map(m=>'<option value="'+m.id+'"'+sel(t.responsible_user_id||'',m.id)+'>'+esc(m.name)+'</option>').join('')+'</select></div></div>'+
      '<div class="two"><div><label>Plattform</label><select id="t_platform"><option value="">– velg –</option>'+Object.keys(PLATFORMS).map(k=>'<option value="'+k+'"'+sel(t.platform||'',k)+'>'+PLATFORMS[k][0]+'</option>').join('')+'</select></div>'+
      '<div><label>Format</label><select id="t_format"><option value="">–</option><option value="post"'+sel(t.format||'','post')+'>Post</option><option value="story"'+sel(t.format||'','story')+'>Story</option></select></div></div>'+
      '<label>FLIK-side(r) / destinasjoner</label><select id="t_dests" multiple size="5" style="height:auto">'+destOpts+'</select>'+
      '<label style="display:flex;align-items:center;justify-content:space-between;gap:8px;flex-wrap:wrap">Tekst til innlegget<span style="display:flex;gap:6px">'+(t.body?'<button type="button" class="btn sm" id="aiReviseBtn" onclick="suggestText('+eventId+',true)">'+ic('refresh')+' Oppdater for nytt år</button>':'')+'<button type="button" class="btn sm" id="aiBtn" onclick="suggestText('+eventId+',false)">'+ic('sparkle')+' Foreslå tekst</button></span></label>'+
      '<textarea id="t_body" placeholder="Skriv eller la AI foreslå teksten. Denne kan kopieres rett ut til Facebook / Meta Planner.">'+esc(t.body)+'</textarea>'+
      '<label>Lenke (valgfritt – f.eks. Google Doc)</label><input id="t_text" value="'+esc(t.text)+'">'+
      '<div class="actions"><button type="button" class="btn" onclick="openEvent('+eventId+')">Avbryt</button><button class="btn solid" type="submit">Lagre</button></div>'+
    '</form></div>';
  document.getElementById('overlay').classList.add('open');
}
async function saveTaskForm(ev,eventId,taskId){ev.preventDefault();
  const dests=[...document.getElementById('t_dests').selectedOptions].map(o=>parseInt(o.value,10));
  const body={label:val('t_label'),publish_date:val('t_date')||null,status:val('t_status'),platform:val('t_platform')||null,format:val('t_format')||null,responsible_user_id:(+val('t_resp')||null),draft_url:val('t_text')||null,body_draft:val('t_body')||null,destination_ids:dests};
  try{const card=taskId?await api('PUT','/tasks/'+taskId,body):await api('POST','/events/'+eventId+'/tasks',body);upsert(card);rerender();openEvent(eventId);}catch(err){alert(err.message);}
}
async function deleteTask(eventId,taskId){
  if(!confirm('Slette denne oppgaven?'))return;
  try{const card=await api('DELETE','/tasks/'+taskId);upsert(card);rerender();openEvent(eventId);}catch(err){alert(err.message);}
}

/* arkiv-bryter for eventlisten */
let showArchive=false;
function toggleArchive(){showArchive=!showArchive;const b=document.getElementById('archiveBtn');if(b)b.innerHTML=ic('archive')+' '+(showArchive?'Skjul gjennomførte':'Vis gjennomførte');renderList();}

/* foreslå publiseringsplan ut fra eventdato */
async function generatePlan(eventId){
  const e=DATA.find(x=>x.id===eventId);
  const msg=(e.posts||[]).length?'Legge til en foreslått publiseringsplan i tillegg til de eksisterende oppgavene?':'Lage forslag til publiseringsplan basert på eventdatoen?';
  if(!confirm(msg))return;
  try{const card=await api('POST','/events/'+eventId+'/generate-plan');upsert(card);rerender();openEvent(eventId);}catch(err){alert(err.message);}
}

/* AI vurderer HELE planen: foreslår nye oppgaver, endringer og flagg – bruker godkjenner */
async function openPlanReview(eventId){
  const e=DATA.find(x=>x.id===eventId);if(!e)return;
  document.getElementById('modal').innerHTML=
    KHEAD+'<h2>Gjennomgang av planen</h2><div class="sub">'+esc(e.title)+'</div></div>'+
    '<div class="mbody"><div class="emptyrec" style="padding-top:14px">'+ic('sparkle')+' Vurderer planen …</div></div>';
  document.getElementById('overlay').classList.add('open');
  try{const r=await api('POST','/events/'+eventId+'/review-plan');renderPlanReview(eventId,r);}
  catch(err){const mb=document.querySelector('#modal .mbody');if(mb)mb.innerHTML='<div class="emptyrec" style="color:#b23535;padding-top:14px">'+esc(err.message)+'</div>';}
}
function renderPlanReview(eventId,r){
  const e=DATA.find(x=>x.id===eventId);
  const lbl=id=>{const p=(e.posts||[]).find(x=>x.id===id);return p?p.label:('#'+id);};
  const pdate=id=>{const p=(e.posts||[]).find(x=>x.id===id);return p&&p.date?fmt(p.date):'ingen dato';};
  window.__review={eventId:eventId,add:r.add||[],adjust:r.adjust||[],remove:r.remove||[]};
  const nAdd=(r.add||[]).length, nAdj=(r.adjust||[]).length, nRem=(r.remove||[]).length;
  let html='';
  if(!nAdd&&!nAdj&&!nRem){
    html='<div class="emptyrec" style="padding-top:14px">'+ic('check')+' Planen ser bra ut – ingen forslag akkurat nå.</div>';
  }else{
    html+='<div class="muted" style="font-size:12.5px;margin:4px 0 12px">Hak av det du vil gjennomføre. Ingenting endres før du trykker «Bruk valgte». Vil du beholde planen som den er, trykk «Avvis».</div>';
    if(nAdd){
      html+='<div class="sectionlabel" style="margin:6px 0 8px">Nye oppgaver <span class="count">'+nAdd+'</span></div>'+
        r.add.map((a,i)=>'<label class="idearow" style="cursor:pointer"><input type="checkbox" class="rv-add" data-i="'+i+'" checked style="width:auto;margin-right:6px"><span class="it">'+esc(a.label)+'<small>'+(a.date?fmt(a.date):'ingen dato')+(a.platform?' · '+esc(a.platform):'')+(a.format?' · '+esc(a.format):'')+(a.reason?' — '+esc(a.reason):'')+'</small></span></label>').join('');
    }
    if(nAdj){
      html+='<div class="sectionlabel" style="margin:16px 0 8px">Foreslåtte endringer <span class="count">'+nAdj+'</span></div>'+
        r.adjust.map((a,i)=>{const ch=[];if(a.date)ch.push('dato → '+fmt(a.date));if(a.platform)ch.push('kanal → '+esc(a.platform));if(a.format)ch.push('format → '+esc(a.format));return '<label class="idearow" style="cursor:pointer"><input type="checkbox" class="rv-adj" data-i="'+i+'" checked style="width:auto;margin-right:6px"><span class="it">'+esc(lbl(a.id))+'<small>'+esc(pdate(a.id))+' · '+(ch.join(', ')||'ingen endring')+(a.reason?' — '+esc(a.reason):'')+'</small></span></label>';}).join('');
    }
    if(nRem){
      html+='<div class="sectionlabel" style="margin:16px 0 8px">Foreslått fjernet <span class="count">'+nRem+'</span></div>'+
        r.remove.map((f,i)=>'<label class="idearow" style="cursor:pointer"><input type="checkbox" class="rv-rem" data-i="'+i+'" checked style="width:auto;margin-right:6px"><span class="it">'+ic('trash')+' '+esc(lbl(f.id))+'<small>'+esc(pdate(f.id))+(f.reason?' — '+esc(f.reason):'')+'</small></span></label>').join('')+
        '<div class="muted" style="font-size:12px;margin-top:4px">Fjerning skjer bare for de du haker av, og bekreftes før den utføres.</div>';
    }
  }
  const canApply=nAdd||nAdj||nRem;
  const mb=document.querySelector('#modal .mbody');
  mb.innerHTML=html+'<div class="actions"><button class="btn" onclick="closeModal()">Avvis</button>'+(canApply?'<button class="btn solid" onclick="applyPlanReview()">'+ic('check')+' Bruk valgte</button>':'')+'</div>';
}
async function applyPlanReview(){
  const rv=window.__review;if(!rv)return;
  const add=[...document.querySelectorAll('#modal .rv-add:checked')].map(c=>rv.add[+c.dataset.i]).filter(Boolean);
  const adjust=[...document.querySelectorAll('#modal .rv-adj:checked')].map(c=>rv.adjust[+c.dataset.i]).filter(Boolean);
  const remove=[...document.querySelectorAll('#modal .rv-rem:checked')].map(c=>(rv.remove[+c.dataset.i]||{}).id).filter(Boolean);
  if(!add.length&&!adjust.length&&!remove.length){alert('Velg minst ett forslag.');return;}
  if(remove.length&&!confirm('Dette sletter '+remove.length+' oppgave(r) permanent. Fortsette?'))return;
  try{const card=await api('POST','/events/'+rv.eventId+'/apply-plan',{add:add,adjust:adjust,remove:remove});upsert(card);rerender();openEvent(rv.eventId);}
  catch(err){alert(err.message);}
}

/* kopier event til neste år (datoer flyttet ett år frem) */
async function duplicateNextYear(eventId){
  const e=DATA.find(x=>x.id===eventId);
  const ny=(new Date(e.date)).getFullYear()+1;
  if(!confirm('Kopiere «'+e.title+'» til '+ny+'? Oppgaver og tekster kopieres, med alle datoer flyttet ett år frem.'))return;
  try{
    const card=await api('POST','/events/'+eventId+'/duplicate-next-year');
    upsert(card);rerender();openEvent(card.id);
    alert('Kopiert til '+ny+'! Åpne hver oppgave og bruk «Oppdater for nytt år», så retter AI årstall og årsklasser i teksten.');
  }catch(err){alert(err.message);}
}

/* flytt oppgave opp/ned (lagrer rekkefølge) */
async function moveTask(eventId,taskId,dir){
  const e=DATA.find(x=>x.id===eventId);const order=(e.posts||[]).map(p=>p.id);
  const i=order.indexOf(taskId);const j=i+dir;if(i<0||j<0||j>=order.length)return;
  const t=order[i];order[i]=order[j];order[j]=t;
  try{const card=await api('POST','/events/'+eventId+'/reorder-tasks',{order});upsert(card);rerender();openEvent(eventId);const it=document.getElementById('ti'+taskId);if(it)it.classList.add('open');}
  catch(err){alert(err.message);}
}

/* AI-tekstforslag for en oppgave */
async function suggestText(eventId,revise){
  const e=DATA.find(x=>x.id===eventId);
  const btn=document.getElementById(revise?'aiReviseBtn':'aiBtn');const orig=btn?btn.textContent:'';
  if(btn){btn.disabled=true;btn.innerHTML=ic('sparkle')+' Skriver …';}
  try{
    const payload={title:e.title,sport:e.sport,label:val('t_label'),date:val('t_date'),goal:e.mal,extra:e.desc,brief:e.brief||''};
    if(revise){payload.existing=val('t_body');payload.year=String((new Date(e.date)).getFullYear());}
    else{const d=val('t_body');if(d)payload.draft=d;}
    const res=await fetch('/ai/suggest',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF},body:JSON.stringify(payload)});
    const j=await res.json();
    if(!res.ok)throw new Error(j.error||('Feil '+res.status));
    document.getElementById('t_body').value=j.text||'';
  }catch(err){alert('Kunne ikke lage tekst: '+err.message);}
  if(btn){btn.disabled=false;btn.textContent=orig;}
}

/* kopier oppgavetekst til utklippstavle */
function copyText(btn,eventId,taskId){
  const e=DATA.find(x=>x.id===eventId);const p=(e.posts||[]).find(x=>x.id===taskId);if(!p)return;
  const t=p.body||'';
  const done=()=>{const o=btn.textContent;btn.textContent='✓ Kopiert!';setTimeout(()=>{btn.textContent=o;},1500);};
  if(navigator.clipboard&&navigator.clipboard.writeText){navigator.clipboard.writeText(t).then(done).catch(()=>fallbackCopy(t,done));}
  else fallbackCopy(t,done);
}
function fallbackCopy(t,done){const ta=document.createElement('textarea');ta.value=t;document.body.appendChild(ta);ta.select();try{document.execCommand('copy');}catch(e){}document.body.removeChild(ta);if(done)done();}

/* ---- BRUKERE & TILGANG ---- */
function renderTeam(){
  const host=document.getElementById('teamHost');if(!host)return;
  host.innerHTML=TEAM.map(u=>{
    const roleBadge=u.is_platform_admin?'<span class="rbadge super">superadmin</span>':(u.role==='admin'?'<span class="rbadge admin">admin</span>':'<span class="rbadge">medlem</span>');
    return '<div class="person">'+
      '<div class="pav">'+initials(u.name)+'</div>'+
      '<div style="flex:1;min-width:0">'+
        '<div class="pnm">'+u.name+' '+roleBadge+'</div>'+
        '<div class="pdet">'+ic('mail')+' '+u.email+(u.title?'<br>'+ic('tag')+' '+u.title:'')+(u.area?'<br>'+ic('folder')+' '+u.area:'')+'</div>'+
        '<div class="pact">'+
          '<button class="btn sm" onclick="openUserEdit('+u.id+')">'+ic('edit')+' Rediger</button>'+
          '<button class="btn sm" onclick="resetUserPassword('+u.id+',\''+esc(u.name).replace(/\x27/g,"")+'\')">'+ic('key')+' Nullstill passord</button>'+
          (u.is_platform_admin?'':'<button class="btn sm" onclick="toggleRole('+u.id+',\''+(u.role==='admin'?'medlem':'admin')+'\')">'+(u.role==='admin'?'↓ Gjør til medlem':'↑ Gjør til admin')+'</button>')+
          (u.is_platform_admin?'':'<button class="btn sm" style="color:#b23535" onclick="removeUser('+u.id+',\''+esc(u.name).replace(/\x27/g,"")+'\')">Fjern</button>')+
        '</div>'+
      '</div></div>';
  }).join('')||'<div class="nopost" style="margin:0">Ingen brukere.</div>';
}
function openUserForm(){
  const roleOpts='<option value="medlem">Medlem (kun se & kopiere)</option><option value="admin">Admin (kan redigere alt)</option>';
  document.getElementById('modal').innerHTML=
    '<div class="head" style="background:linear-gradient(135deg,var(--flik-blue),var(--flik-blue-deep))"><button class="close" onclick="closeModal()">×</button><h2>Legg til bruker</h2><div class="sub">Brukeren logger inn med e-post og passordet du setter</div></div>'+
    '<div class="mbody"><form class="f" onsubmit="saveUserForm(event)">'+
      '<div class="two"><div><label>Navn *</label><input id="u_name" required></div><div><label>E-post *</label><input id="u_email" type="email" required></div></div>'+
      '<div class="two"><div><label>Rolle</label><select id="u_role">'+roleOpts+'</select></div><div><label>Tittel</label><input id="u_title" placeholder="f.eks. Daglig leder"></div></div>'+
      '<label>Ansvarsområde</label><input id="u_area" placeholder="f.eks. Fotball">'+
      '<label>Midlertidig passord * (minst 8 tegn)</label><input id="u_pass" required minlength="8">'+
      '<div class="actions"><button type="button" class="btn" onclick="closeModal()">Avbryt</button><button class="btn solid" type="submit">Opprett bruker</button></div>'+
    '</form></div>';
  document.getElementById('overlay').classList.add('open');
}
async function saveUserForm(ev){ev.preventDefault();
  const body={name:val('u_name'),email:val('u_email'),role:val('u_role'),title:val('u_title')||null,area:val('u_area')||null,password:document.getElementById('u_pass').value};
  try{await api('POST','/users',body);alert('Bruker opprettet. Last siden på nytt for å se den i lista.');closeModal();}catch(err){alert(err.message);}
}
function openUserEdit(id){
  const u=TEAM.find(x=>x.id===id);if(!u)return;
  const isSuper=u.is_platform_admin;
  const roleField=isSuper
    ?'<input value="Superadmin" disabled>'
    :'<select id="e_role"><option value="medlem">Medlem (kun se & kopiere)</option><option value="admin">Admin (kan redigere alt)</option></select>';
  document.getElementById('modal').innerHTML=
    '<div class="head" style="background:linear-gradient(135deg,var(--flik-blue),var(--flik-blue-deep))"><button class="close" onclick="closeModal()">×</button><h2>Rediger bruker</h2><div class="sub">Endre navn, e-post, rolle og ansvar</div></div>'+
    '<div class="mbody"><form class="f" onsubmit="saveUserEdit(event,'+id+')">'+
      '<div class="two"><div><label>Navn *</label><input id="e_name" required></div><div><label>E-post *</label><input id="e_email" type="email" required></div></div>'+
      '<div class="two"><div><label>Rolle</label>'+roleField+'</div><div><label>Tittel</label><input id="e_title" placeholder="f.eks. Daglig leder"></div></div>'+
      '<label>Ansvarsområde</label><input id="e_area" placeholder="f.eks. Fotball">'+
      '<div class="actions"><button type="button" class="btn" onclick="closeModal()">Avbryt</button><button class="btn solid" type="submit">Lagre endringer</button></div>'+
    '</form></div>';
  document.getElementById('overlay').classList.add('open');
  document.getElementById('e_name').value=u.name;
  document.getElementById('e_email').value=u.email;
  document.getElementById('e_title').value=u.title||'';
  document.getElementById('e_area').value=u.area||'';
  if(!isSuper)document.getElementById('e_role').value=(u.role==='admin'?'admin':'medlem');
}
async function saveUserEdit(ev,id){ev.preventDefault();
  const u=TEAM.find(x=>x.id===id);
  const body={name:val('e_name'),email:val('e_email'),title:val('e_title')||null,area:val('e_area')||null};
  if(u&&!u.is_platform_admin)body.role=val('e_role');
  try{await api('PUT','/users/'+id,body);
    if(u){u.name=body.name;u.email=body.email;u.title=body.title;u.area=body.area;if(body.role)u.role=body.role;}
    closeModal();renderTeam();
  }catch(err){alert(err.message);}
}
async function resetUserPassword(id,name){
  const pw=prompt('Nytt passord for '+name+' (minst 8 tegn):');
  if(!pw)return;
  if(pw.length<8){alert('Passordet må være minst 8 tegn.');return;}
  try{await api('POST','/users/'+id+'/reset-password',{password:pw});alert('Passord oppdatert for '+name+'.');}catch(err){alert(err.message);}
}
async function toggleRole(id,role){
  try{await api('PUT','/users/'+id+'/role',{role});const u=TEAM.find(x=>x.id===id);if(u)u.role=role;renderTeam();}catch(err){alert(err.message);}
}
async function removeUser(id,name){
  if(!confirm('Fjerne '+name+' fra klubben?'))return;
  try{await api('DELETE','/users/'+id);const i=TEAM.findIndex(x=>x.id===id);if(i>=0)TEAM.splice(i,1);renderTeam();}catch(err){alert(err.message);}
}

/* ===== DENNE UKA + KLUBBLIV + TRENINGSTIDER ===== */
let KLUBBLIV=window.KLUBBLIV||[], IDEAS=window.IDEAS||[], TRAINING=window.TRAINING||[], WEATHER=window.WEATHER||[], KAMPER=window.KAMPER||[];
const WD=['Mandag','Tirsdag','Onsdag','Torsdag','Fredag','Lørdag','Søndag'];
const isoDow=d=>{const g=new Date(d).getDay();return g===0?7:g;};
function ymd(d){const x=new Date(d);return x.getFullYear()+'-'+String(x.getMonth()+1).padStart(2,'0')+'-'+String(x.getDate()).padStart(2,'0');}
function addDays(d,n){const x=new Date(d);x.setHours(0,0,0,0);x.setDate(x.getDate()+n);return x;}
function startOfWeek(d){const x=new Date(d);x.setHours(0,0,0,0);const g=(x.getDay()+6)%7;x.setDate(x.getDate()-g);return x;}
function isoWeek(d){const x=new Date(d);x.setHours(0,0,0,0);x.setDate(x.getDate()+3-((x.getDay()+6)%7));const w1=new Date(x.getFullYear(),0,4);return 1+Math.round(((x-w1)/86400000-3+((w1.getDay()+6)%7))/7);}
function allPosts(){
  const arr=[];
  DATA.forEach(e=>(e.posts||[]).forEach(p=>{if(p.date)arr.push({date:p.date,kind:'event',id:p.id,eventId:e.id,title:p.label||'Innlegg',ctx:e.title,channels:(p.pages||[]).join(' · '),platform:p.platform,format:p.format,status:p.status,published:p.status==='Publisert',raw:p.status_raw||'planlagt'});}));
  KLUBBLIV.forEach(k=>{if(k.date)arr.push({date:k.date,kind:'klubbliv',id:k.id,title:k.title,ctx:'Klubbliv',channels:(k.channels||[]).join(' · '),status:k.status,published:k.status==='publisert',raw:k.status||'planlagt'});});
  return arr;
}
function wc(icon,title,ctCls,ct,inner){return '<div class="weekcard"><h3>'+ic(icon)+' '+title+(ct!=null?' <span class="ct '+(ctCls||'')+'">'+ct+'</span>':'')+'</h3>'+inner+'</div>';}
function chev(){return '<span class="rchev">'+ic('chevron')+'</span>';}
function cd(dd){return dd===0?'i dag':(dd>0?'om '+dd+' dg':Math.abs(dd)+' dg siden');}
function dayOfYear(d){const x=new Date(d);const s=new Date(x.getFullYear(),0,0);return Math.floor((x-s)/86400000);}
function ideaOfDay(){const act=IDEAS.filter(i=>i.is_active!==false);return act.length?act[dayOfYear(new Date())%act.length]:null;}
function convRow(p){
  const open=p.kind==='event'?'openEvent('+p.eventId+')':'openKlubblivForm('+p.id+')';
  return '<div class="rrow click" style="padding:8px 0" onclick="'+open+'"><span class="rt">'+esc(p.title)+platChip(p)+'<small>'+esc(p.ctx)+(p.channels?' · '+esc(p.channels):'')+'</small></span>'+chev()+'</div>';
}
function rrow(p){
  const open=p.kind==='event'?'openEvent('+p.eventId+')':'openKlubblivForm('+p.id+')';
  return '<div class="rrow click" onclick="'+open+'"><span class="rd">'+fmt(p.date)+'</span><span class="rt">'+esc(p.title)+platChip(p)+'<small>'+esc(p.ctx)+(p.channels?' · '+esc(p.channels):'')+' · '+cd(daysTo(p.date))+'</small></span>'+chev()+'</div>';
}
function eventRow(e){
  const posts=e.posts?e.posts.length:0;
  return '<div class="rrow click" onclick="openEvent('+e.id+')"><span class="rd">'+fmt(e.date)+'</span><span class="rt">'+esc(e.title)+'<small>'+esc(e.sport||'')+' · '+posts+' poster · '+cd(daysTo(e.date))+'</small></span>'+chev()+'</div>';
}
/* ---- Moduler ---- */
function mod_publiser(){
  const today=new Date();today.setHours(0,0,0,0);const in7=addDays(today,7);const posts=allPosts();
  const due=posts.filter(p=>{const d=addDays(p.date,0);return d>=addDays(today,-2)&&d<=in7&&!p.published;}).sort((x,y)=>new Date(x.date)-new Date(y.date));
  if(due.length)return wc('sparkle','Publiser nå','',due.length,due.map(rrow).join(''));
  // Dynamisk fallback – hold brukeren i forkant selv når ingenting haster
  const next=posts.filter(p=>{const d=addDays(p.date,0);return d>addDays(today,7)&&!p.published;}).sort((x,y)=>new Date(x.date)-new Date(y.date)).slice(0,3);
  const idea=ideaOfDay();
  let inner='<div class="emptyrec" style="padding:2px 0 6px">Ingenting som haster de neste dagene – godt jobba! Her er det som holder deg i forkant:</div>';
  if(next.length)inner+='<div class="lbl" style="margin:4px 0 2px">Neste på tur</div>'+next.map(rrow).join('');
  if(CANEDIT&&idea)inner+='<div class="lbl" style="margin:12px 0 2px">Fyll en rolig dag</div><div class="rrow"><span class="rt">'+esc(idea.title)+'<small>Klubbliv · '+esc(idea.group)+'</small></span><span class="ra"><button class="btn sm" onclick="klubblivFromIdea('+idea.id+',\''+ymd(today)+'\')">Lag post</button></span></div>';
  if(!next.length&&!(CANEDIT&&idea))inner+='<div class="emptyrec">Ingen planlagte poster ennå. Lag en Klubbliv-post for å komme i gang.</div>';
  return wc('sparkle','Publiser nå',null,null,inner);
}
function mod_utenplan(){
  const noplan=DATA.filter(e=>{const dd=daysTo(e.date);return dd>=0&&dd<=90&&(!e.posts||!e.posts.length)&&e.type!=='Administrasjon'&&!/ikke markedsf|ikke skal/i.test(e.notat||'');}).sort((x,y)=>new Date(x.date)-new Date(y.date));
  return wc('calendar','Arrangement uten plan','warn',noplan.length, noplan.length?noplan.map(eventRow).join(''):'<div class="emptyrec">Alle kommende arrangement har en plan.</div>');
}
function mod_travle(){
  const today=new Date();today.setHours(0,0,0,0);const posts=allPosts();
  const byDay={};posts.forEach(p=>{const d=addDays(p.date,0);if(d>=today&&d<=addDays(today,28)&&!p.published){const k=ymd(p.date);(byDay[k]=byDay[k]||[]).push(p);}});
  const platLbl=pl=>{const m=PLATFORMS[pl];return m?m[0]:'uten kanal';};
  // Kun reelt travelt når SAMME kanal har flere poster samme dag.
  // To poster samme dag på ulike kanaler (f.eks. Facebook + Instagram) er helt greit.
  const days=[];
  Object.keys(byDay).sort().forEach(k=>{
    const grp={};byDay[k].forEach(p=>{const key=p.platform||'_';(grp[key]=grp[key]||[]).push(p);});
    const busy=Object.keys(grp).filter(g=>grp[g].length>=2);
    if(busy.length)days.push({k:k,grp:grp,busy:busy});
  });
  if(!days.length)return wc('info','Travle dager','red',0,'<div class="emptyrec">Ingen kanaler med flere poster samme dag. To poster samme dag på ulike kanaler er helt greit.</div>');
  const inner=days.map(d=>{
    const label=d.busy.map(bk=>platLbl(bk)+': '+d.grp[bk].length+' poster').join(' · ');
    const rows=d.busy.reduce((a,bk)=>a.concat(d.grp[bk]),[]).map(convRow).join('');
    return '<div class="travday"><div class="travhead">'+fmt(d.k)+' · '+label+' samme dag – vurder å flytte en</div>'+rows+'</div>';
  }).join('');
  return wc('info','Travle dager','red',days.length,inner);
}
function mod_tomrom(){
  const today=new Date();today.setHours(0,0,0,0);const posts=allPosts();
  const gaps=[];const actIdeas=IDEAS.filter(i=>i.is_active!==false);
  for(let wk=0;wk<6;wk++){const ws=addDays(startOfWeek(today),wk*7);const we=addDays(ws,6);const has=posts.some(p=>{const d=addDays(p.date,0);return d>=ws&&d<=we;});if(!has){const idea=actIdeas.length?actIdeas[gaps.length%actIdeas.length]:null;gaps.push({ws,we,idea});}}
  return wc('doc','Tomrom – fyll med Klubbliv','gap',gaps.length, gaps.length?gaps.map(g=>'<div class="rrow"><span class="rd">Uke '+isoWeek(g.ws)+'</span><span class="rt">'+fmt(ymd(g.ws))+'–'+fmt(ymd(g.we))+': ingen innhold planlagt'+(g.idea?'<small>Forslag: '+esc(g.idea.title)+'</small>':'')+'</span>'+(CANEDIT&&g.idea?'<span class="ra"><button class="btn sm" onclick="klubblivFromIdea('+g.idea.id+',\''+ymd(addDays(g.ws,2))+'\')">Lag post</button></span>':'')+'</div>').join(''):'<div class="emptyrec">Ingen tomme uker de neste seks ukene.</div>');
}
function mod_idag(){
  const today=new Date();today.setHours(0,0,0,0);const dow=isoDow(today);
  const todayTrain=TRAINING.filter(t=>t.weekday===dow);
  const wToday=(WEATHER||[]).find(w=>ymd(w.date)===ymd(today));
  return wc('calendar','I dag – '+WD[dow-1],null,null,
    '<div class="lbl" style="margin-bottom:6px">Trener i dag</div>'+
    (todayTrain.length?todayTrain.map(t=>'<div class="trainline"><span class="sw" style="background:'+(t.color||'#8795a3')+'"></span>'+esc(t.category||t.group||'Trening')+(t.start?' · '+t.start+(t.end?'–'+t.end:''):'')+(t.location?' · '+esc(t.location):'')+'</div>').join(''):'<div class="emptyrec">Ingen trening registrert i dag.</div>')+
    (function(){const kt=KAMPER.filter(k=>ymd(k.date)===ymd(today));return kt.length?'<div class="lbl" style="margin:12px 0 4px">Kamp i dag</div>'+kt.map(k=>'<div class="trainline"><span class="sw" style="background:'+(k.color||'#8795a3')+'"></span>'+esc(k.title)+(k.time?' · '+k.time:'')+' · '+(k.home?'Hjemme':'Borte')+(k.location?' · '+esc(k.location):'')+'</div>').join(''):'';})()+
    (wToday?'<div class="lbl" style="margin:12px 0 4px">Vær i dag</div><div class="trainline">'+(wToday.temp!=null?wToday.temp+'° · ':'')+esc(wToday.label||'')+'</div>':''));
}
function mod_vaer(opts){
  const mode=(opts&&opts.mode)||'week';
  const today=new Date();today.setHours(0,0,0,0);
  if(!WEATHER||!WEATHER.length)return wc('info','Vær i Farsund',null,null,'<div class="emptyrec">Ingen værdata akkurat nå.</div>');
  if(mode==='today'){const w=WEATHER.find(x=>ymd(x.date)===ymd(today))||WEATHER[0];return wc('info','Vær i dag',null,null,'<div class="trainline" style="font-size:15px">'+(w.temp!=null?w.temp+'° · ':'')+esc(w.label||'')+'</div>');}
  return wc('info','Været i Farsund',null,null,'<div class="weathergrid">'+WEATHER.slice(0,8).map(w=>'<div class="wday'+(ymd(w.date)===ymd(today)?' today':'')+'"><div class="wd">'+WD[isoDow(w.date)-1].slice(0,3)+'</div><div class="wt">'+(w.temp!=null?w.temp+'°':'–')+'</div><div class="wl">'+esc(w.label||'')+'</div></div>').join('')+'</div>');
}
function mod_tips(){
  return wc('sparkle','Dagens tips',null,null,
    '<div class="emptyrec" style="padding:0 0 8px">La Vivu foreslå noe å publisere i dag – ut fra vær, trening og planen. Skriv gjerne et tema for et enda mer treffsikkert forslag.</div>'+
    (CANEDIT?'<input id="tipHint" class="tiphint" placeholder="Valgfritt: tema eller stikkord …"><button class="btn solid sm" style="margin-top:8px" onclick="dagensTips()">'+ic('sparkle')+' Foreslå noe å publisere</button>':'')+
    '<div id="tipResult"></div>');
}
function mod_neste(){
  const up=DATA.filter(e=>daysTo(e.date)>=0).sort((x,y)=>new Date(x.date)-new Date(y.date)).slice(0,5);
  return wc('calendar','Neste arrangement',null,null, up.length?up.map(eventRow).join(''):'<div class="emptyrec">Ingen kommende arrangement.</div>');
}
function mod_tellere(){
  const totalPosts=DATA.reduce((s,e)=>s+(e.posts?e.posts.length:0),0);
  const upcoming=DATA.filter(e=>new Date(e.date)>=TODAY);
  const needsWork=upcoming.filter(e=>eventState(e).key==='Mangler innhold').length;
  const toApprove=DATA.filter(e=>e.approval==='Til godkjenning').length;
  const cells=[[DATA.length,'Arrangement'],[totalPosts,'Oppgaver'],[needsWork,'Uten innhold'],[toApprove,'Til godkjenning']];
  return wc('info','Nøkkeltall',null,null,'<div class="ministats">'+cells.map(c=>'<div class="ministat"><div class="mn">'+c[0]+'</div><div class="ml">'+c[1]+'</div></div>').join('')+'</div>');
}
function mod_kamper(){
  const today=new Date();today.setHours(0,0,0,0);
  const up=KAMPER.filter(k=>k.date&&addDays(k.date,0)>=today).sort((a,b)=>((a.date+(a.time||''))<(b.date+(b.time||''))?-1:1)).slice(0,8);
  const inner=up.length?up.map(k=>'<div class="rrow"><span class="rd">'+fmt(k.date)+'</span><span class="rt"><span style="display:inline-flex;align-items:center;gap:7px;min-width:0"><span style="width:9px;height:9px;border-radius:3px;flex:none;background:'+(k.color||'#8795a3')+'"></span>'+esc(k.title)+'</span><small>'+(k.category?esc(k.category)+' · ':'')+(k.home?'Hjemme':'Borte')+(k.time?' · '+k.time:'')+(k.location?' · '+esc(k.location):'')+'</small></span></div>').join(''):'<div class="emptyrec">Ingen kommende kamper registrert.</div>';
  return wc('calendar','Kommende kamper',null,null,inner);
}
const MODREG={
  tellere:{t:'Nøkkeltall',render:mod_tellere},
  publiser:{t:'Publiser nå',render:mod_publiser},
  utenplan:{t:'Arrangement uten plan',render:mod_utenplan},
  travle:{t:'Travle dager',render:mod_travle},
  tomrom:{t:'Tomrom',render:mod_tomrom},
  idag:{t:'I dag',render:mod_idag},
  vaer:{t:'Vær',render:mod_vaer},
  tips:{t:'Dagens tips',render:mod_tips},
  kamper:{t:'Kamper',render:mod_kamper},
  neste:{t:'Neste arrangement',render:mod_neste}
};
function normLayout(l){
  const norm=arr=>(Array.isArray(arr)?arr:[]).map(x=>typeof x==='string'?{id:x,opts:{}}:{id:x.id,opts:x.opts||{}}).filter(x=>MODREG[x.id]);
  l=l||{};return {a:norm(l.a),b:norm(l.b)};
}
let LAYOUT=normLayout(window.LAYOUT), LAYOUT_DEFAULT=normLayout(window.LAYOUT_DEFAULT), LAYOUT_SAVED=JSON.parse(JSON.stringify(LAYOUT));
let editMode=false; const IS_SUPERADMIN=!!window.IS_SUPERADMIN; let _drag=null;
function findItem(id){for(const c of ['a','b']){const i=LAYOUT[c].findIndex(x=>x.id===id);if(i>=0)return{col:c,idx:i,item:LAYOUT[c][i]};}return null;}
function layoutRemove(id){const f=findItem(id);if(f){LAYOUT[f.col].splice(f.idx,1);renderHome();}}
function layoutAdd(id,col){if(findItem(id))return;LAYOUT[col].push({id,opts:{}});renderHome();}
function layoutMoveDir(id,dir){const f=findItem(id);if(!f)return;const arr=LAYOUT[f.col];const j=f.idx+dir;if(j<0||j>=arr.length)return;const tmp=arr[f.idx];arr[f.idx]=arr[j];arr[j]=tmp;renderHome();}
function layoutSwitchCol(id){const f=findItem(id);if(!f)return;const it=LAYOUT[f.col].splice(f.idx,1)[0];LAYOUT[f.col==='a'?'b':'a'].push(it);renderHome();}
function layoutSetOpt(id,k,v){const f=findItem(id);if(f){f.item.opts=f.item.opts||{};f.item.opts[k]=v;renderHome();}}
function modBar(it){
  const m=MODREG[it.id];
  let opt='';
  if(it.id==='vaer'){const mode=(it.opts&&it.opts.mode)||'week';opt='<select onchange="layoutSetOpt(\'vaer\',\'mode\',this.value)"><option value="today"'+(mode==='today'?' selected':'')+'>I dag</option><option value="week"'+(mode==='week'?' selected':'')+'>Hele uka</option></select>';}
  return '<div class="modbar"><span class="mh">⠿</span><span class="mt">'+m.t+'</span>'+opt+
    '<button title="Flytt opp" onclick="layoutMoveDir(\''+it.id+'\',-1)">↑</button>'+
    '<button title="Flytt ned" onclick="layoutMoveDir(\''+it.id+'\',1)">↓</button>'+
    '<button title="Bytt kolonne" onclick="layoutSwitchCol(\''+it.id+'\')">⇄</button>'+
    '<button class="rem" title="Fjern" onclick="layoutRemove(\''+it.id+'\')">✕</button></div>';
}
function modTray(){
  const used=new Set([...LAYOUT.a,...LAYOUT.b].map(x=>x.id));
  const hidden=Object.keys(MODREG).filter(id=>!used.has(id));
  return '<div class="modtray"><div class="lbl">Skjulte moduler – dra inn, eller klikk for å legge til</div>'+(hidden.length?hidden.map(id=>'<button class="traychip" draggable="true" data-id="'+id+'" onclick="layoutAdd(\''+id+'\',\'a\')">+ '+MODREG[id].t+'</button>').join(''):'<span class="emptyrec">Alle moduler er i bruk.</span>')+'</div>';
}
function renderHome(){
  const host=document.getElementById('homeHost');if(!host)return;
  renderHomeActions();
  const colHtml=(items,col)=>items.map(it=>{
    const m=MODREG[it.id];if(!m)return '';
    let inner='';try{inner=m.render(it.opts||{});}catch(e){inner='<div class="weekcard"><div class="emptyrec">Kunne ikke vise modulen.</div></div>';}
    return '<div class="modwrap" data-id="'+it.id+'" data-col="'+col+'"'+(editMode?' draggable="true"':'')+'>'+(editMode?modBar(it):'')+inner+'</div>';
  }).join('');
  let html='<div class="weekcols'+(editMode?' homeedit':'')+'" id="homeCols"><div class="modcol" data-col="a">'+colHtml(LAYOUT.a,'a')+'</div><div class="modcol" data-col="b">'+colHtml(LAYOUT.b,'b')+'</div>';
  if(editMode)html+=modTray();
  html+='</div>';
  host.innerHTML=html;
  if(editMode)bindDnD();
}
function renderHomeActions(){
  const el=document.getElementById('homeActions');if(!el)return;
  if(!CANEDIT){el.innerHTML='';return;}
  if(!editMode){el.innerHTML='<div class="homebar"><button class="btn solid" onclick="openEventForm()">'+ic('plus')+' Nytt arrangement</button><button class="btn" onclick="toggleConfig()">'+ic('edit')+' Konfigurer</button></div>';return;}
  el.innerHTML='<div class="homebar"><button class="btn solid" onclick="saveConfig()">'+ic('check')+' Lagre</button><button class="btn" onclick="cancelConfig()">Avbryt</button><button class="btn" onclick="resetConfig()">'+ic('refresh')+' Nullstill til standard</button>'+(IS_SUPERADMIN?'<button class="btn" onclick="saveDefaultConfig()">'+ic('archive')+' Lagre som klubbstandard</button>':'')+'</div>';
}
function bindDnD(){
  const host=document.getElementById('homeHost');
  host.querySelectorAll('[draggable="true"]').forEach(el=>{el.addEventListener('dragstart',e=>{_drag=el.dataset.id;e.dataTransfer.effectAllowed='move';try{e.dataTransfer.setData('text/plain',el.dataset.id);}catch(_){}});});
  host.querySelectorAll('.modcol').forEach(col=>{
    col.addEventListener('dragover',e=>{e.preventDefault();col.classList.add('dragover');});
    col.addEventListener('dragleave',()=>col.classList.remove('dragover'));
    col.addEventListener('drop',e=>{e.preventDefault();col.classList.remove('dragover');if(!_drag)return;
      const c=col.dataset.col;const wraps=[...col.querySelectorAll('.modwrap')];let idx=wraps.length;
      for(let i=0;i<wraps.length;i++){const r=wraps[i].getBoundingClientRect();if(e.clientY<r.top+r.height/2){idx=i;break;}}
      const f=findItem(_drag);const it=f?f.item:{id:_drag,opts:{}};
      if(f){LAYOUT[f.col].splice(f.idx,1);if(f.col===c&&f.idx<idx)idx--;}
      if(idx>LAYOUT[c].length)idx=LAYOUT[c].length;LAYOUT[c].splice(idx,0,it);_drag=null;renderHome();
    });
  });
  const tr=host.querySelector('.modtray');
  if(tr){tr.addEventListener('dragover',e=>e.preventDefault());tr.addEventListener('drop',e=>{e.preventDefault();if(_drag){layoutRemove(_drag);_drag=null;}});}
}
function toggleConfig(){editMode=true;renderHome();}
function cancelConfig(){LAYOUT=JSON.parse(JSON.stringify(LAYOUT_SAVED));editMode=false;renderHome();}
async function saveConfig(){try{await api('POST','/dashboard-layout',{layout:LAYOUT});LAYOUT_SAVED=JSON.parse(JSON.stringify(LAYOUT));editMode=false;renderHome();}catch(err){alert(err.message);}}
async function resetConfig(){if(!confirm('Nullstille til standardoppsettet?'))return;try{await api('DELETE','/dashboard-layout');}catch(err){}LAYOUT=JSON.parse(JSON.stringify(LAYOUT_DEFAULT));LAYOUT_SAVED=JSON.parse(JSON.stringify(LAYOUT));editMode=false;renderHome();}
async function saveDefaultConfig(){if(!confirm('Lagre dette som standardoppsett for hele klubben?'))return;try{await api('POST','/dashboard-layout/default',{layout:LAYOUT});LAYOUT_DEFAULT=JSON.parse(JSON.stringify(LAYOUT));alert('Lagret som standard for klubben.');}catch(err){alert(err.message);}}
/* ---- NAVN-NEDTREKK / EGEN PROFIL / SELSKAPSBYTTE ---- */
function toggleUserMenu(e){e.stopPropagation();const dd=document.getElementById('userDD');if(!dd)return;if(dd.classList.contains('open')){dd.classList.remove('open');return;}renderUserMenu();dd.classList.add('open');}
function renderUserMenu(){
  const dd=document.getElementById('userDD');if(!dd)return;
  const comps=window.COMPANIES||[];const cur=window.CURRENT_COMPANY_ID;
  let html='<button class="ddi" onclick="openMyProfile()">'+ic('edit')+' Personlige innstillinger</button>';
  if(comps.length>1){html+='<div class="ddsep">Bytt konto</div>'+comps.map(c=>'<button class="ddi'+(c.id===cur?' cur':'')+'" onclick="switchCompany('+c.id+')">'+esc(c.name)+(c.id===cur?' '+ic('check'):'')+'</button>').join('');}
  html+='<div class="ddsep2"></div><button class="ddi" onclick="doLogout()">Logg ut</button>';
  dd.innerHTML=html;
}
function doLogout(){const f=document.getElementById('logoutForm');if(f)f.submit();}
function switchCompany(id){
  const f=document.createElement('form');f.method='POST';f.action='/switch-company/'+id;
  const c=document.createElement('input');c.type='hidden';c.name='_token';c.value=CSRF;f.appendChild(c);
  document.body.appendChild(f);f.submit();
}
function openMyProfile(){
  const dd=document.getElementById('userDD');if(dd)dd.classList.remove('open');
  document.getElementById('modal').innerHTML=
    KHEAD+'<h2>Personlige innstillinger</h2><div class="sub">Endre din egen bruker</div></div>'+
    '<div class="mbody"><form class="f" onsubmit="saveMyProfile(event)">'+
      '<div class="two"><div><label>Navn *</label><input id="me_name" required></div><div><label>E-post *</label><input id="me_email" type="email" required></div></div>'+
      '<label>Nytt passord <span style="color:var(--ink-soft);font-weight:400">(valgfritt, minst 8 tegn)</span></label><input id="me_pass" type="password" minlength="8" placeholder="La stå tomt for å beholde passordet">'+
      '<div class="actions"><button type="button" class="btn" onclick="closeModal()">Avbryt</button><button class="btn solid" type="submit">Lagre</button></div>'+
    '</form></div>';
  document.getElementById('overlay').classList.add('open');
  document.getElementById('me_name').value=(window.ME&&window.ME.name)||'';
  document.getElementById('me_email').value=(window.ME&&window.ME.email)||'';
}
async function saveMyProfile(ev){ev.preventDefault();
  const body={name:val('me_name'),email:val('me_email')};const pw=document.getElementById('me_pass').value;if(pw)body.password=pw;
  try{const r=await api('PUT','/me',body);if(window.ME){window.ME.name=r.name;window.ME.email=r.email;}
    const chip=document.querySelector('.usermenu .userchip span');if(chip)chip.textContent=r.name;
    closeModal();
  }catch(err){alert(err.message);}
}
function openSettings(){
  document.getElementById('modal').innerHTML=
    KHEAD+'<h2>Innstillinger</h2><div class="sub">Klubbens oppsett</div></div>'+
    '<div class="mbody">'+
      (IS_SUPERADMIN?'<div class="idearow" style="border:1px solid var(--accent,#00529b);background:rgba(0,82,155,.05)"><span class="it">Kunder<small>Superadmin: opprett nytt selskap + første admin</small></span><button class="btn solid sm" onclick="openCustomerManager()">Åpne</button></div>':'')+
      '<div class="idearow"><span class="it">Klubb & abonnement<small>Navn, undertekst, fargetema og logo</small></span><button class="btn sm" onclick="openCompanySettings()">Åpne</button></div>'+
      '<div class="idearow"><span class="it">'+(window.CAT_LABEL||'Kategorier')+'<small>Rediger '+(window.CAT_LABEL||'Kategorier').toLowerCase()+' – navn, farge, arkiver</small></span><button class="btn sm" onclick="openCatManager()">Åpne</button></div>'+
      '<div class="idearow"><span class="it">Hovedmål<small>Målene arrangementene kan merkes med</small></span><button class="btn sm" onclick="openGoalManager()">Åpne</button></div>'+
      '<div class="idearow"><span class="it">Kom i gang – importer<small>Lim inn tekst eller regneark → AI foreslår arrangement</small></span><button class="btn sm" onclick="openOnboarding()">Åpne</button></div>'+
      '<div class="idearow"><span class="it">Treningstider<small>Hvem trener når – brukes på dashbordet</small></span><button class="btn sm" onclick="openTrainingMgr()">Åpne</button></div>'+
      '<div class="idearow"><span class="it">Kamper<small>Kommende kamper – vises på dashbordet</small></span><button class="btn sm" onclick="openKampMgr()">Åpne</button></div>'+
      '<div class="idearow"><span class="it">Del årshjulet<small>Innebygdbart årshjul til nettsiden (uten Administrasjon)</small></span><button class="btn sm" onclick="openShareWheel()">Åpne</button></div>'+
    '</div>';
  document.getElementById('overlay').classList.add('open');
}
function openCompanySettings(){
  const b=window.BRAND||{};
  const dd=document.getElementById('userDD');if(dd)dd.classList.remove('open');
  const themes=[['blue','Blå'],['red','Rød'],['green','Grønn']];const cur=b.theme||'blue';
  document.getElementById('modal').innerHTML=
    KHEAD+'<h2>Klubb & abonnement</h2><div class="sub">Tilpass hvordan løsningen ser ut for dere</div></div>'+
    '<div class="mbody"><form class="f" onsubmit="saveCompanySettings(event)">'+
      '<div class="two"><div><label>Navn</label><input id="co_name"></div><div><label>Fargetema</label><select id="co_theme">'+themes.map(t=>'<option value="'+t[0]+'"'+(cur===t[0]?' selected':'')+'>'+t[1]+'</option>').join('')+'</select></div></div>'+
      '<label>Undertekst <span style="color:var(--ink-soft);font-weight:400">(står under «Vivu Planner» øverst)</span></label><input id="co_sub" placeholder="f.eks. Farsund og Lista Idrettsklubb">'+
      '<label>Logo / ikon <span style="color:var(--ink-soft);font-weight:400">(øverst til venstre – PNG/SVG)</span></label><input id="co_logo" type="file" accept="image/png,image/jpeg,image/svg+xml">'+
      (b.mark?'<div class="muted" style="font-size:12.5px;margin-top:6px">Logo er satt. <button type="button" class="btnlink" onclick="removeCompanyLogo()">Fjern logo</button></div>':'')+
      '<div class="actions"><button type="button" class="btn" onclick="closeModal()">Avbryt</button><button class="btn solid" type="submit">Lagre</button></div>'+
    '</form></div>';
  document.getElementById('overlay').classList.add('open');
  document.getElementById('co_name').value=b.name||'';
  document.getElementById('co_sub').value=b.subtitle||'';
}
async function saveCompanySettings(ev){ev.preventDefault();
  const id=window.CURRENT_COMPANY_ID;if(!id)return;
  try{
    await api('PUT','/company/'+id,{name:val('co_name'),subtitle:val('co_sub')||null,theme:val('co_theme')});
    const f=document.getElementById('co_logo');
    if(f&&f.files&&f.files[0]){
      const fd=new FormData();fd.append('logo',f.files[0]);
      const r=await fetch('/company/'+id+'/logo',{method:'POST',headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'},body:fd});
      if(!r.ok){const j=await r.json().catch(()=>({}));throw new Error((j.errors&&Object.values(j.errors).flat().join('\n'))||'Kunne ikke laste opp logo');}
    }
    location.reload();
  }catch(err){alert(err.message);}
}
async function removeCompanyLogo(){
  const id=window.CURRENT_COMPANY_ID;if(!id)return;
  if(!confirm('Fjerne logoen og bruke standard?'))return;
  try{await api('DELETE','/company/'+id+'/logo');location.reload();}catch(err){alert(err.message);}
}

/* ---- KATEGORIER (idretter / avdelinger) ---- */
function openCatManager(){
  const dd=document.getElementById('userDD');if(dd)dd.classList.remove('open');
  const L=window.CAT_LABEL||'Kategorier';const one=L.toLowerCase().replace(/er$/,'');
  document.getElementById('modal').innerHTML=
    KHEAD+'<h2>'+esc(L)+'</h2><div class="sub">Rediger '+esc(L.toLowerCase())+' klubben bruker. Arkiverte skjules fra nedtrekk, men beholder historikken.</div></div>'+
    '<div class="mbody">'+
      '<div id="catList"></div>'+
      '<div style="display:flex;gap:8px;align-items:center;margin-top:14px;padding-top:14px;border-top:1px solid var(--line)">'+
        '<input type="color" id="newcat_color" value="#5a7184" style="width:42px;height:38px;padding:2px">'+
        '<input id="newcat_name" placeholder="Ny '+esc(one)+' …" style="flex:1" onkeydown="if(event.key===\'Enter\'){event.preventDefault();addCategory();}">'+
        '<button class="btn solid sm" onclick="addCategory()">'+ic('plus')+' Legg til</button>'+
      '</div>'+
    '</div>';
  renderCatList();
  document.getElementById('overlay').classList.add('open');
}
function renderCatList(){
  const host=document.getElementById('catList');if(!host)return;
  const cats=window.CAT_MANAGE||[];
  if(!cats.length){host.innerHTML='<div class="emptyrec">Ingen kategorier ennå. Legg til under.</div>';return;}
  host.innerHTML=cats.map(catManageRow).join('');
}
function catManageRow(c){
  const badge=c.events?'<span class="count" title="arrangement bruker denne">'+c.events+'</span>':'';
  return '<div class="catrow" data-id="'+c.id+'" style="display:flex;gap:8px;align-items:center;padding:7px 0;border-bottom:1px solid var(--line)'+(c.archived?';opacity:.55':'')+'">'+
    '<input type="color" value="'+esc(c.color||'#5a7184')+'" onchange="saveCategory('+c.id+')" class="cat_color" style="width:36px;height:34px;padding:2px"'+(c.archived?' disabled':'')+'>'+
    '<input value="'+esc(c.name)+'" onchange="saveCategory('+c.id+')" class="cat_name" style="flex:1"'+(c.archived?' disabled':'')+'>'+
    badge+
    '<button class="btn sm" onclick="toggleArchiveCategory('+c.id+')" title="'+(c.archived?'Gjenopprett':'Arkiver')+'">'+ic(c.archived?'refresh':'archive')+'</button>'+
    (c.events?'':'<button class="btn sm" onclick="deleteCategory('+c.id+')" title="Slett">'+ic('trash')+'</button>')+
  '</div>';
}
async function addCategory(){
  const el=document.getElementById('newcat_name');const name=(el.value||'').trim();
  if(!name){el.focus();return;}
  try{
    const r=await api('POST','/categories',{name:name,color:val('newcat_color')});
    (window.CAT_MANAGE=window.CAT_MANAGE||[]).push(r.category);
    CATS.push({id:r.category.id,name:r.category.name,color:r.category.color});
    el.value='';
    renderCatList();
  }catch(err){alert(err.message);}
}
async function saveCategory(id){
  const row=document.querySelector('.catrow[data-id="'+id+'"]');if(!row)return;
  const name=(row.querySelector('.cat_name').value||'').trim();
  const color=row.querySelector('.cat_color').value;
  if(!name){alert('Navn kan ikke være tomt.');return;}
  try{
    await api('PUT','/categories/'+id,{name:name,color:color});
    const m=(window.CAT_MANAGE||[]).find(x=>x.id===id);if(m){m.name=name;m.color=color;}
    const d=CATS.find(x=>x.id===id);if(d){d.name=name;d.color=color;}
  }catch(err){alert(err.message);}
}
async function toggleArchiveCategory(id){
  try{
    const r=await api('PUT','/categories/'+id+'/archive',{});
    const m=(window.CAT_MANAGE||[]).find(x=>x.id===id);if(m)m.archived=r.archived;
    const idx=CATS.findIndex(x=>x.id===id);
    if(r.archived){if(idx>=0)CATS.splice(idx,1);}
    else if(idx<0&&m){CATS.push({id:m.id,name:m.name,color:m.color});}
    renderCatList();
  }catch(err){alert(err.message);}
}
async function deleteCategory(id){
  if(!confirm('Slette kategorien helt? Dette kan ikke angres.'))return;
  try{
    await api('DELETE','/categories/'+id,{});
    window.CAT_MANAGE=(window.CAT_MANAGE||[]).filter(x=>x.id!==id);
    const idx=CATS.findIndex(x=>x.id===id);if(idx>=0)CATS.splice(idx,1);
    renderCatList();
  }catch(err){alert(err.message);}
}

/* ---- HOVEDMÅL ---- */
function openGoalManager(){
  const dd=document.getElementById('userDD');if(dd)dd.classList.remove('open');
  document.getElementById('modal').innerHTML=
    KHEAD+'<h2>Hovedmål</h2><div class="sub">Målene arrangementene kan merkes med. Endres fritt – ingen logikk henger på disse.</div></div>'+
    '<div class="mbody"><div id="goalList"></div>'+
      '<div style="display:flex;gap:8px;margin-top:14px;padding-top:14px;border-top:1px solid var(--line)">'+
        '<input id="newgoal" placeholder="Nytt hovedmål …" style="flex:1" onkeydown="if(event.key===\'Enter\'){event.preventDefault();addGoal();}">'+
        '<button class="btn solid sm" onclick="addGoal()">'+ic('plus')+' Legg til</button>'+
      '</div></div>';
  renderGoalList();
  document.getElementById('overlay').classList.add('open');
}
function renderGoalList(){
  const host=document.getElementById('goalList');if(!host)return;
  const g=window.GOALS||[];
  if(!g.length){host.innerHTML='<div class="emptyrec">Ingen hovedmål ennå. Legg til under.</div>';return;}
  host.innerHTML=g.map((name,i)=>'<div style="display:flex;gap:8px;align-items:center;padding:7px 0;border-bottom:1px solid var(--line)">'+
    '<input value="'+esc(name)+'" class="goal_name" onchange="renameGoal('+i+',this.value)" style="flex:1">'+
    '<button class="btn sm" onclick="removeGoal('+i+')" title="Fjern">'+ic('trash')+'</button></div>').join('');
}
async function saveGoals(){
  const id=window.CURRENT_COMPANY_ID;if(!id)return;
  await api('PUT','/company/'+id+'/goals',{goals:window.GOALS||[]});
}
async function addGoal(){
  const el=document.getElementById('newgoal');const v=(el.value||'').trim();if(!v)return;
  if((window.GOALS||[]).some(x=>x.toLowerCase()===v.toLowerCase())){el.value='';return;}
  (window.GOALS=window.GOALS||[]).push(v);el.value='';renderGoalList();
  try{await saveGoals();}catch(err){alert(err.message);}
}
async function renameGoal(i,v){
  v=(v||'').trim();if(!v){renderGoalList();return;}
  window.GOALS[i]=v;
  try{await saveGoals();}catch(err){alert(err.message);}
}
async function removeGoal(i){
  window.GOALS.splice(i,1);renderGoalList();
  try{await saveGoals();}catch(err){alert(err.message);}
}

/* ---- KUNDER (superadmin) ---- */
async function openCustomerManager(){
  const dd=document.getElementById('userDD');if(dd)dd.classList.remove('open');
  document.getElementById('modal').innerHTML=
    KHEAD+'<h2>Kunder</h2><div class="sub">Alle selskaper i systemet. Opprett nye og bytt mellom dem.</div></div>'+
    '<div class="mbody"><div id="custList"><div class="emptyrec">'+ic('refresh')+' Henter …</div></div>'+
      '<div style="margin-top:16px;padding-top:14px;border-top:1px solid var(--line)">'+
      '<div class="sectionlabel" style="margin-bottom:10px">Nytt selskap</div>'+
      '<form class="f" onsubmit="createCustomer(event)">'+
        '<div class="two"><div><label>Selskapsnavn</label><input id="cu_name" required></div>'+
          '<div><label>Type</label><select id="cu_type">'+
            ['idrettsklubb|Idrettsklubb','bedrift|Bedrift','forening|Forening','annet|Annet'].map(o=>{const p=o.split("|");return '<option value="'+p[0]+'">'+p[1]+'</option>';}).join('')+
          '</select></div></div>'+
        '<div class="two"><div><label>Fargetema</label><select id="cu_theme"><option value="blue">Blå</option><option value="red">Rød</option><option value="green">Grønn</option></select></div>'+
          '<div><label>Undertekst <span style="color:var(--ink-soft);font-weight:400">(valgfri)</span></label><input id="cu_sub" placeholder="f.eks. fullt navn"></div></div>'+
        '<div class="sectionlabel" style="margin:12px 0 4px">Første administrator</div>'+
        '<div class="two"><div><label>Navn</label><input id="cu_aname" required></div><div><label>E-post</label><input id="cu_aemail" type="email" required></div></div>'+
        '<label>Midlertidig passord <span style="color:var(--ink-soft);font-weight:400">(minst 8 tegn – del med admin, som kan endre det selv)</span></label><input id="cu_apass" type="text" minlength="8" required>'+
        '<div class="actions"><button type="button" class="btn" onclick="closeModal()">Lukk</button><button class="btn solid" type="submit">'+ic('plus')+' Opprett selskap</button></div>'+
      '</form></div></div>';
  document.getElementById('overlay').classList.add('open');
  try{const r=await api('GET','/customers');window.__customers=r.customers||[];renderCustList();}
  catch(err){document.getElementById('custList').innerHTML='<div class="emptyrec" style="color:#b23535">'+esc(err.message)+'</div>';}
}
function renderCustList(){
  const host=document.getElementById('custList');if(!host)return;
  const cs=window.__customers||[];const cur=window.CURRENT_COMPANY_ID;
  if(!cs.length){host.innerHTML='<div class="emptyrec">Ingen selskaper ennå.</div>';return;}
  host.innerHTML=cs.map(c=>'<div class="idearow"><span class="it">'+esc(c.name)+(c.id===cur?' <span class="count">aktiv</span>':'')+
    '<small>'+esc(c.org_type||'')+' · '+c.users+' brukere · '+c.events+' arrangement</small></span>'+
    (c.id===cur?'':'<button class="btn sm" onclick="switchCompany('+c.id+')">Bytt til</button>')+'</div>').join('');
}
async function createCustomer(ev){ev.preventDefault();
  const body={name:val('cu_name'),org_type:val('cu_type'),theme:val('cu_theme'),subtitle:val('cu_sub')||null,
    admin_name:val('cu_aname'),admin_email:val('cu_aemail'),admin_password:val('cu_apass')};
  try{
    const r=await api('POST','/customers',body);
    (window.__customers=window.__customers||[]).push(r.customer);
    window.__customers.sort((a,b)=>a.name.localeCompare(b.name,'no'));
    renderCustList();
    ['cu_name','cu_sub','cu_aname','cu_aemail','cu_apass'].forEach(id=>{const el=document.getElementById(id);if(el)el.value='';});
    alert('Selskapet «'+r.customer.name+'» er opprettet med admin '+body.admin_email+'. Bytt til det for å sette opp innhold.');
  }catch(err){alert(err.message);}
}

/* ---- ONBOARDING (AI tolker tekst/regneark) ---- */
function ensureXLSX(){return new Promise(res=>{if(typeof XLSX!=='undefined'){res(true);return;}const s=document.createElement('script');s.src='https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js';s.onload=()=>res(true);s.onerror=()=>res(false);document.head.appendChild(s);});}
function fileToText(file){return new Promise((res,rej)=>{const r=new FileReader();r.onload=e=>{try{const wb=XLSX.read(e.target.result,{type:'binary'});let out='';wb.SheetNames.forEach(n=>{out+=XLSX.utils.sheet_to_csv(wb.Sheets[n])+'\n';});res(out);}catch(err){rej(new Error('Kunne ikke lese regnearket.'));}};r.onerror=()=>rej(new Error('Kunne ikke lese fila.'));r.readAsBinaryString(file);});}
function openOnboarding(){
  const dd=document.getElementById('userDD');if(dd)dd.classList.remove('open');
  ensureXLSX();
  document.getElementById('modal').innerHTML=
    KHEAD+'<h2>Kom i gang – importer</h2><div class="sub">Lim inn tekst eller last opp et regneark, så foreslår AI arrangement</div></div>'+
    '<div class="mbody"><form class="f" onsubmit="onboardingParse(event)">'+
      '<label>Beskriv arrangementene (eller lim inn fra et dokument)</label><textarea id="ob_text" placeholder="F.eks.: Lions Cup i mars, fotballskole i august, håndballskole 11.–13. september, årsmøte i mars …"></textarea>'+
      '<label>… eller last opp regneark (xlsx / csv)</label><input id="ob_file" type="file" accept=".xlsx,.xls,.csv,.txt">'+
      '<div class="actions"><button type="button" class="btn" onclick="closeModal()">Avbryt</button><button class="btn solid" type="submit">'+ic('sparkle')+' Tolk med AI</button></div>'+
    '</form><div id="ob_result"></div></div>';
  document.getElementById('overlay').classList.add('open');
}
async function onboardingParse(ev){ev.preventDefault();
  let text=document.getElementById('ob_text').value.trim();
  const f=document.getElementById('ob_file');const box=document.getElementById('ob_result');
  try{
    if(f&&f.files&&f.files[0]){const ok=await ensureXLSX();if(!ok)throw new Error('Kunne ikke laste regneark-leseren – lim inn tekst i stedet.');const ft=await fileToText(f.files[0]);text=(text?text+'\n':'')+ft;}
    if(!text){alert('Skriv litt tekst eller last opp en fil først.');return;}
    box.innerHTML='<div class="emptyrec" style="padding-top:12px">'+ic('sparkle')+' Tolker …</div>';
    const r=await api('POST','/onboarding/parse',{text:text});
    renderOnboardingPreview(r.events||[]);
  }catch(err){box.innerHTML='<div class="emptyrec" style="color:#b23535;padding-top:12px">'+esc(err.message)+'</div>';}
}
function renderOnboardingPreview(events){
  const box=document.getElementById('ob_result');
  if(!events.length){box.innerHTML='<div class="emptyrec" style="padding-top:12px">Fant ingen arrangement i teksten. Prøv å beskrive tydeligere.</div>';return;}
  window.__obEvents=events;
  var warn = events.length>12 ? '<div style="background:#fff4d6;border:1px solid #e8c96a;border-radius:10px;padding:10px 12px;margin:14px 0 8px;font-size:13px;color:#6b5410">'+ic('info')+' AI-en fant <b>'+events.length+'</b> arrangement. Er dette egentlig treningstider eller en fast ukeplan? Da bør det heller være <b>ett</b> arrangement (oppstart) + påminnelser i Klubbliv – ikke ett per økt. Gå nøye gjennom før du oppretter.</div>' : '';
  box.innerHTML=warn+'<div class="sectionlabel" style="margin:16px 0 8px">Forslag <span class="count">'+events.length+'</span> <span style="font-weight:400;color:var(--ink-soft)">– hak av det du vil opprette</span></div>'+
    events.map((e,i)=>'<label class="idearow" style="cursor:pointer"><input type="checkbox" class="obchk" data-i="'+i+'" checked style="width:auto;margin-right:6px"><span class="it">'+esc(e.title)+'<small>'+(e.date?fmt(e.date):'dato mangler')+(e.sport?' · '+esc(e.sport):'')+(e.goal?' · '+esc(e.goal):'')+'</small></span></label>').join('')+
    '<div class="actions"><button class="btn" onclick="closeModal()">Avbryt</button><button class="btn solid" onclick="onboardingImport()">'+ic('plus')+' Opprett valgte</button></div>';
}
async function onboardingImport(){
  const chks=[...document.querySelectorAll('#modal .obchk:checked')].map(c=>window.__obEvents[+c.dataset.i]).filter(Boolean);
  if(!chks.length){alert('Velg minst ett arrangement.');return;}
  try{const r=await api('POST','/onboarding/import',{events:chks});alert('Opprettet '+r.created+' arrangement. Siden lastes på nytt.');location.reload();}catch(err){alert(err.message);}
}
function openKampMgr(){renderKampModal();document.getElementById('overlay').classList.add('open');}
function renderKampModal(){
  const rows=[...KAMPER].sort((a,b)=>((a.date+(a.time||''))<(b.date+(b.time||''))?-1:1));
  const list=rows.length?rows.map(k=>'<div class="idearow"><span class="gpill" style="background:'+(k.color||'#8795a3')+';color:#fff">'+(k.date?fmt(k.date):'—')+'</span><span class="it">'+esc(k.title)+'<small>'+(k.category?esc(k.category)+' · ':'')+(k.home?'Hjemme':'Borte')+(k.time?' · '+k.time:'')+(k.location?' · '+esc(k.location):'')+'</small></span><button class="btn sm" onclick="openKampForm('+k.id+')">'+ic('edit')+'</button><button class="btn sm" style="color:#b23535" onclick="deleteKamp('+k.id+')">'+ic('trash')+'</button></div>').join(''):'<div class="emptyrec">Ingen kamper lagt inn ennå.</div>';
  document.getElementById('modal').innerHTML=
    KHEAD+'<h2>Kamper</h2><div class="sub">Kommende hjemmekamper – vises på dashbordet</div></div>'+
    '<div class="mbody"><div style="display:flex;justify-content:flex-end;gap:8px;margin-bottom:10px;flex-wrap:wrap"><button class="btn sm" onclick="openShareKamper()">'+ic('link')+' Del til nettside</button><button class="btn sm" onclick="importKamper()">'+ic('refresh')+' Importer fra fotball.no</button><button class="btn solid sm" onclick="openKampForm(null)">'+ic('plus')+' Ny kamp</button></div>'+list+'</div>';
}
async function importKamper(){
  if(!confirm('Synkronisere hjemmekamper fra fotball.no? Tidligere importerte kamper erstattes med ferske (flyttede/avlyste oppdateres). Manuelt lagt inn kamper røres ikke.'))return;
  const mb=document.querySelector('#modal .mbody');if(mb)mb.innerHTML='<div class="emptyrec" style="padding-top:14px">'+ic('refresh')+' Synkroniserer kamper fra fotball.no …</div>';
  try{
    const r=await api('POST','/kamper/import');
    KAMPER=r.kamper||[];
    renderKampModal();renderHome();
    alert('Ferdig: '+r.result.synced+' hjemmekamper synkronisert (av '+r.result.total+' i feeden).');
  }catch(err){renderKampModal();alert(err.message);}
}
function openKampForm(id){
  const k=id?KAMPER.find(x=>x.id===id):null;
  const catOpts='<option value="">– idrett –</option>'+CATS.map(c=>'<option value="'+c.id+'">'+esc(c.name)+'</option>').join('');
  document.getElementById('modal').innerHTML=
    KHEAD+'<h2>'+(k?'Rediger kamp':'Ny kamp')+'</h2><div class="sub">Kamp</div></div>'+
    '<div class="mbody"><form class="f" onsubmit="saveKamp(event,'+(id||'null')+')">'+
      '<div class="two"><div><label>Motstander / navn *</label><input id="ka_title" required placeholder="f.eks. mot Vanse IL"></div><div><label>Idrett</label><select id="ka_cat">'+catOpts+'</select></div></div>'+
      '<div class="two"><div><label>Dato *</label><input id="ka_date" type="date" required></div><div><label>Tid</label><input id="ka_time" type="time"></div></div>'+
      '<div class="two"><div><label>Sted</label><input id="ka_loc"></div><div><label>Hjemme/borte</label><select id="ka_home"><option value="1">Hjemme</option><option value="0">Borte</option></select></div></div>'+
      '<div class="actions"><button type="button" class="btn" onclick="renderKampModal()">Tilbake</button><button class="btn solid" type="submit">Lagre</button></div>'+
    '</form></div>';
  if(k){document.getElementById('ka_title').value=k.title;if(k.category_id)document.getElementById('ka_cat').value=k.category_id;if(k.date)document.getElementById('ka_date').value=k.date;if(k.time)document.getElementById('ka_time').value=k.time;if(k.location)document.getElementById('ka_loc').value=k.location;document.getElementById('ka_home').value=k.home?'1':'0';}
}
async function saveKamp(ev,id){ev.preventDefault();
  const body={title:val('ka_title'),category_id:(+document.getElementById('ka_cat').value||null),match_date:val('ka_date'),match_time:val('ka_time')||null,location:val('ka_loc')||null,home:document.getElementById('ka_home').value==='1'};
  try{let card;if(id){card=await api('PUT','/kamper/'+id,body);const i=KAMPER.findIndex(x=>x.id===id);if(i>=0)KAMPER[i]=card;}else{card=await api('POST','/kamper',body);KAMPER.push(card);}renderKampModal();renderHome();}catch(err){alert(err.message);}
}
async function deleteKamp(id){if(!confirm('Slette denne kampen?'))return;try{await api('DELETE','/kamper/'+id);KAMPER=KAMPER.filter(x=>x.id!==id);renderKampModal();renderHome();}catch(err){alert(err.message);}}
function openShareWheel(){
  const url=window.location.origin+'/embed/'+(window.COMPANY_SLUG||'flik')+'/arshjul';
  const iframe='<iframe src="'+url+'" width="640" height="740" style="border:0;width:100%;max-width:660px" title="Årshjul"></iframe>';
  document.getElementById('modal').innerHTML=
    KHEAD+'<h2>Del årshjulet</h2><div class="sub">Prikkene er ikke klikkbare, og Administrasjon vises ikke</div></div>'+
    '<div class="mbody">'+
      '<label>Direkte lenke</label><div style="display:flex;gap:8px;align-items:center"><input class="tiphint" id="shareUrl" readonly value="'+esc(url)+'"><button class="btn sm" style="flex:none" onclick="copyField(\'shareUrl\')">'+ic('copy')+' Kopier</button></div>'+
      '<label style="margin-top:14px">Innbyggingskode (iframe)</label><textarea id="shareEmbed" readonly style="width:100%;min-height:92px;font-family:inherit;font-size:12.5px;padding:10px 12px;border:1px solid #e6ebf2;border-radius:10px;background:#fbfcfe;color:var(--ink)">'+esc(iframe)+'</textarea>'+
      '<div style="display:flex;gap:8px;margin-top:10px;flex-wrap:wrap"><button class="btn sm" onclick="copyField(\'shareEmbed\')">'+ic('copy')+' Kopier innbyggingskode</button><a class="btn sm" href="'+url+'" target="_blank">'+ic('link')+' Åpne forhåndsvisning</a></div>'+
    '</div>';
  document.getElementById('overlay').classList.add('open');
}
function openShareKamper(){
  const sports=[...new Set((KAMPER||[]).map(k=>k.category).filter(Boolean))].sort();
  const opts='<option value="">Alle idretter (kombinert)</option>'+sports.map(s=>'<option value="'+esc(s)+'">Bare '+esc(s)+'</option>').join('');
  document.getElementById('modal').innerHTML=
    KHEAD+'<h2>Del kampoversikt</h2><div class="sub">Viser hjemmekamper i dag + 7 dager – oppdateres automatisk</div></div>'+
    '<div class="mbody">'+
      '<div class="two"><div><label>Hva skal feeden vise?</label><select id="kShareSport" onchange="rebuildKampShare()">'+opts+'</select></div>'+
      '<div><label>Innbyggingsmåte</label><select id="kShareType" onchange="rebuildKampShare()"><option value="script">Uten iframe (arver font, auto-høyde)</option><option value="iframe">Med iframe</option></select></div></div>'+
      '<label style="margin-top:14px">Direkte lenke (forhåndsvisning)</label><div style="display:flex;gap:8px;align-items:center"><input class="tiphint" id="kShareUrl" readonly><button class="btn sm" style="flex:none" onclick="copyField(\'kShareUrl\')">'+ic('copy')+' Kopier</button></div>'+
      '<label style="margin-top:14px">Innbyggingskode – lim inn på nettsiden</label><textarea id="kShareEmbed" readonly style="width:100%;min-height:92px;font-family:inherit;font-size:12.5px;padding:10px 12px;border:1px solid #e6ebf2;border-radius:10px;background:#fbfcfe;color:var(--ink)"></textarea>'+
      '<label style="margin-top:14px">Forslag til forklaringstekst over kampene</label><textarea id="kShareIntro" readonly style="width:100%;min-height:58px;font-family:inherit;font-size:12.5px;padding:10px 12px;border:1px solid #e6ebf2;border-radius:10px;background:#fbfcfe;color:var(--ink)"></textarea>'+
      '<div style="display:flex;gap:8px;margin-top:10px;flex-wrap:wrap"><button class="btn sm" onclick="copyField(\'kShareEmbed\')">'+ic('copy')+' Kopier innbyggingskode</button><button class="btn sm" onclick="copyField(\'kShareIntro\')">'+ic('copy')+' Kopier forklaringstekst</button><button class="btn sm" id="kSharePrev">'+ic('link')+' Forhåndsvis</button><button class="btn sm" onclick="renderKampModal()">Tilbake</button></div>'+
    '</div>';
  rebuildKampShare();
}
function rebuildKampShare(){
  const sport=document.getElementById('kShareSport').value;
  const type=document.getElementById('kShareType').value;
  const origin=window.location.origin;const slug=(window.COMPANY_SLUG||'flik');
  const q=sport?'?sport='+encodeURIComponent(sport):'';
  const pageUrl=origin+'/embed/'+slug+'/kamper'+q;
  const jsUrl=origin+'/embed/'+slug+'/kamper.js'+q;
  let embed;
  if(type==='iframe'){
    embed='<iframe id="vk-frame" src="'+pageUrl+'" scrolling="no" style="border:0;width:100%" title="Kommende hjemmekamper"></iframe>\n'+
      '<script>window.addEventListener("message",function(e){if(e.data&&e.data.vkFrame){document.getElementById("vk-frame").style.height=e.data.vkHeight+"px";}});<\/script>';
  }else{
    embed='<script src="'+jsUrl+'"><\/script>';
  }
  const club=(window.BRAND&&window.BRAND.name)||'klubben';
  const intro=sport
    ? 'Her finner du '+club+' sine kommende '+sport.toLowerCase()+'kamper på hjemmebane. Oversikten oppdateres automatisk.'
    : 'Her finner du '+club+' sine kommende hjemmekamper den neste uka. Oversikten oppdateres automatisk.';
  document.getElementById('kShareUrl').value=pageUrl;
  document.getElementById('kShareEmbed').value=embed;
  document.getElementById('kShareIntro').value=intro;
  const prev=document.getElementById('kSharePrev');if(prev)prev.onclick=()=>window.open(pageUrl,'_blank');
}
function copyField(id){const el=document.getElementById(id);if(!el)return;el.focus();el.select();const t=el.value;if(navigator.clipboard&&navigator.clipboard.writeText)navigator.clipboard.writeText(t);else{try{document.execCommand('copy');}catch(e){}}}
async function dagensTips(){
  const box=document.getElementById('tipResult');if(!box)return;
  box.innerHTML='<div class="emptyrec" style="padding-top:10px">'+ic('sparkle')+' Skriver …</div>';
  const hint=document.getElementById('tipHint')?document.getElementById('tipHint').value.trim():'';
  const t0=new Date();t0.setHours(0,0,0,0);const dow=isoDow(t0);
  const tr=TRAINING.filter(t=>t.weekday===dow).map(t=>(t.category||t.group||'trening')+(t.start?' '+t.start:'')).join(', ');
  const w=(WEATHER||[]).find(x=>ymd(x.date)===ymd(t0));
  const next=DATA.filter(e=>daysTo(e.date)>=0).sort((x,y)=>new Date(x.date)-new Date(y.date))[0];
  let ctx='I dag er det '+WD[dow-1]+'.';
  if(w)ctx+=' Vær: '+(w.label||'')+(w.temp!=null?', '+w.temp+'°':'')+'.';
  if(tr)ctx+=' Trener i dag: '+tr+'.';
  if(next)ctx+=' Nærmeste arrangement: '+next.title+' ('+(next.sport||'')+') om '+daysTo(next.date)+' dager.';
  if(hint)ctx+=' Ønsket tema/vinkling fra brukeren (prioriter dette): '+hint+'.';
  try{
    const r=await api('POST','/ai/suggest',{title:'Dagens innlegg fra FLIK',label:'Klubbliv',extra:ctx});
    window.__tip=r.text||'';
    box.innerHTML='<div class="postbody" style="margin-top:10px">'+esc(window.__tip)+'</div><div style="display:flex;gap:8px;margin-top:10px;flex-wrap:wrap"><button class="btn sm" onclick="copyTip()">'+ic('copy')+' Kopier</button><button class="btn sm" onclick="tipToKlubbliv()">'+ic('plus')+' Lag Klubbliv-post</button><button class="btn sm" onclick="dagensTips()">'+ic('refresh')+' Nytt forslag</button></div>';
  }catch(err){box.innerHTML='<div class="emptyrec" style="color:#b23535;padding-top:10px">'+esc(err.message)+'</div>';}
}
function copyTip(){if(window.__tip&&navigator.clipboard&&navigator.clipboard.writeText)navigator.clipboard.writeText(window.__tip);}
function tipToKlubbliv(){openKlubblivForm(null);const t=document.getElementById('k_title');if(t)t.value='Dagens innlegg';const b=document.getElementById('k_body');if(b)b.value=window.__tip||'';const d=document.getElementById('k_date');if(d)d.value=ymd(new Date());}
/* ---- OPPGAVELISTE (alle tasks på tvers) ---- */
function taskRowFlat(p,e,today){
  const overdue=p.date&&addDays(p.date,0)<today&&p.status_raw!=='publisert';
  const dd=p.date?daysTo(p.date):null;
  const flag=overdue?'<span class="tflag red">Forfalt</span>':((dd!=null&&dd>=0&&dd<=3&&p.status_raw!=='publisert')?'<span class="tflag amber">Snart</span>':'');
  const resp=p.ansvarlig||e.ansvarlig||'';
  return '<div class="trowf" onclick="openEvent('+e.id+')">'+
    '<span class="trd">'+(p.date?fmt(p.date):'—')+'</span>'+
    '<span class="trmeta"><span class="trt">'+esc(p.label||'Innlegg')+flag+'</span><span class="trs"><span class="sport" style="background:'+col(e)+'">'+esc(e.sport||'')+'</span> '+esc(e.title)+(resp?' · '+esc(resp):'')+(p.platform&&PLATFORMS[p.platform]?' · '+PLATFORMS[p.platform][0]:'')+'</span></span>'+
    (CANEDIT?statusSel(p,e.id):'<span class="pill st-planlagt">'+esc(p.status)+'</span>')+
  '</div>';
}
let taskScope='kommende',taskMine=false;
function setTaskScope(s){taskScope=s;renderTaskList();}
function toggleTaskMine(){taskMine=!taskMine;renderTaskList();}
function renderTaskList(){
  const host=document.getElementById('taskListHost');if(!host)return;
  const today=new Date();today.setHours(0,0,0,0);
  const tsp=document.getElementById('tSport');
  if(tsp&&tsp.options.length<=1){[...new Set(DATA.map(e=>e.sport).filter(Boolean))].sort().forEach(s=>{const o=document.createElement('option');o.value=s;o.textContent=s;tsp.appendChild(o);});}
  const q=(document.getElementById('tsearch').value||'').toLowerCase();
  const fsp=tsp?tsp.value:'';
  const meId=(window.ME&&window.ME.id)||0;
  let base=[];
  DATA.forEach(e=>(e.posts||[]).forEach(p=>base.push({p,e})));
  base=base.filter(({p,e})=>{
    if(q&&!((p.label||'').toLowerCase().indexOf(q)>=0||(e.title||'').toLowerCase().indexOf(q)>=0))return false;
    if(fsp&&e.sport!==fsp)return false;
    if(taskMine){const r=p.responsible_user_id||e.responsible_user_id;if(r!==meId)return false;}
    return true;
  });
  const pub=x=>x.p.status_raw==='publisert';
  const dOf=x=>x.p.date?addDays(x.p.date,0):null;
  const kommende=base.filter(x=>!pub(x)&&dOf(x)&&dOf(x)>=today);
  const forfalt=base.filter(x=>!pub(x)&&dOf(x)&&dOf(x)<today);
  const publisert=base.filter(x=>pub(x));
  const scopes={kommende:kommende,forfalt:forfalt,publisert:publisert,alle:base};
  const seg=(id,label,arr)=>'<button class="seg'+(taskScope===id?' active':'')+'" onclick="setTaskScope(\''+id+'\')">'+label+' <span class="segc">'+arr.length+'</span></button>';
  const sc=document.getElementById('tScope');
  if(sc)sc.innerHTML=seg('kommende','Kommende',kommende)+seg('forfalt','Forfalt',forfalt)+seg('publisert','Publisert',publisert)+seg('alle','Alle',base)+'<button class="segtoggle'+(taskMine?' on':'')+'" onclick="toggleTaskMine()">'+ic('check')+' Mine oppgaver</button>';
  let rows=(scopes[taskScope]||base).slice();
  if(taskScope==='publisert'||taskScope==='forfalt')rows.sort((a,b)=>{const da=a.p.date||'',db=b.p.date||'';return da<db?1:(da>db?-1:0);});
  else rows.sort((a,b)=>{const da=a.p.date||'9999-99-99',db=b.p.date||'9999-99-99';return da<db?-1:(da>db?1:0);});
  host.innerHTML=rows.length?'<div class="elist">'+rows.map(({p,e})=>taskRowFlat(p,e,today)).join('')+'</div>':'<div class="nopost" style="margin:0">Ingen oppgaver i denne visningen.</div>';
}
/* ---- KLUBBLIV ---- */
function renderKlubbliv(){
  const host=document.getElementById('klubblivHost');if(!host)return;
  let html='';
  const posts=[...KLUBBLIV].sort((a,b)=>(a.date||'9999-99-99')>(b.date||'9999-99-99')?1:-1);
  html+='<div class="sectionlabel" style="margin:0 0 12px">Planlagte klubbliv-poster <span class="count">'+posts.length+'</span></div>';
  html+=posts.length?posts.map(k=>{
    const g=k.idea_group||'';
    return '<div class="kpost"><span class="kd">'+(k.date?fmt(k.date):'—')+'</span><span class="kt"><div class="knm">'+esc(k.title)+(g?' <span class="gpill '+g+'">'+g+'</span>':'')+'</div><div class="kmeta">'+((k.channels&&k.channels.length)?esc(k.channels.join(' · ')):'Ingen kanal valgt')+' · '+esc(k.status||'planlagt')+'</div></span>'+
      (CANEDIT?'<span style="display:flex;gap:6px">'+(k.body?'<button class="btn sm" onclick="copyKlubbliv('+k.id+')">'+ic('copy')+' Kopier</button>':'')+'<button class="btn sm" onclick="openKlubblivForm('+k.id+')">'+ic('edit')+' Rediger</button><button class="btn sm" style="color:#b23535" onclick="deleteKlubbliv('+k.id+')">'+ic('trash')+'</button></span>':'')+
    '</div>';
  }).join(''):'<div class="nopost">Ingen klubbliv-poster planlagt ennå.'+(CANEDIT?' Bruk «Ny klubbliv-post» eller forslagene i «Denne uka».':'')+'</div>';
  if(CANEDIT){
    html+='<div class="idealib"><h4>Idé-bibliotek <button class="btn sm" style="float:right" onclick="openIdeaForm(null)">'+ic('plus')+' Ny idé</button></h4>'+
      (IDEAS.length?IDEAS.map(i=>'<div class="idearow"><span class="gpill '+i.group+'">'+i.group+'</span><span class="it">'+esc(i.title)+(i.description?'<small>'+esc(i.description)+'</small>':'')+'</span><button class="btn sm" onclick="klubblivFromIdea('+i.id+',null)">Bruk</button><button class="btn sm" onclick="openIdeaForm('+i.id+')">'+ic('edit')+'</button><button class="btn sm" style="color:#b23535" onclick="deleteIdea('+i.id+')">'+ic('trash')+'</button></div>').join(''):'<div class="emptyrec">Ingen idéer ennå.</div>')+
    '</div>';
  }
  host.innerHTML=html;
}
const KHEAD='<div class="head" style="background:linear-gradient(135deg,var(--flik-blue),var(--flik-blue-deep))"><button class="close" onclick="closeModal()">×</button>';
function openKlubblivForm(id){
  const k=id?KLUBBLIV.find(x=>x.id===id):null;
  const ideaOpts='<option value="">– ingen –</option>'+IDEAS.map(i=>'<option value="'+i.id+'">'+esc(i.title)+'</option>').join('');
  const destChecks=DESTS.map(d=>'<label class="dchk"><input type="checkbox" value="'+d.id+'"> '+esc(d.name)+'</label>').join('');
  document.getElementById('modal').innerHTML=
    KHEAD+'<h2>'+(k?'Rediger klubbliv-post':'Ny klubbliv-post')+'</h2><div class="sub">Innhold mellom arrangementene</div></div>'+
    '<div class="mbody"><form class="f" onsubmit="saveKlubbliv(event,'+(id||'null')+')">'+
      '<div class="two"><div><label>Tittel *</label><input id="k_title" required></div><div><label>Fra idé (valgfritt)</label><select id="k_idea" onchange="klubblivIdeaPick()">'+ideaOpts+'</select></div></div>'+
      '<div class="two"><div><label>Publiseringsdato <span style="color:var(--ink-soft);font-weight:400">(tom = foreslå selv)</span></label><input id="k_date" type="date"></div><div><label>Status</label><select id="k_status"><option value="planlagt">Planlagt</option><option value="under_arbeid">Under arbeid</option><option value="klar">Klar for publisering</option><option value="publisert">Publisert</option></select></div></div>'+
      (k?'':'<div class="two"><div><label>Gjenta</label><select id="k_repeat" onchange="klubblivRepeatToggle()"><option value="">Ingen</option><option value="monthly">Månedlig</option><option value="quarterly">Kvartalsvis</option><option value="yearly">Årlig</option></select></div><div id="k_countwrap" style="display:none"><label>Antall ganger</label><input id="k_count" type="number" min="1" max="24" value="4"></div></div>')+
      '<label>Kanaler</label><div class="dchks">'+(destChecks||'<span class="emptyrec">Ingen kanaler.</span>')+'</div>'+
      '<label style="display:flex;align-items:center;justify-content:space-between;gap:8px">Tekst<span><button type="button" class="btn sm" id="kAiBtn" onclick="suggestKlubbliv()">'+ic('sparkle')+' Foreslå tekst</button></span></label><textarea id="k_body" placeholder="Skriv teksten, eller la AI foreslå ut fra idéen."></textarea>'+
      '<div class="actions"><button type="button" class="btn" onclick="closeModal()">Avbryt</button><button class="btn solid" type="submit">Lagre</button></div>'+
    '</form></div>';
  document.getElementById('overlay').classList.add('open');
  if(k){
    document.getElementById('k_title').value=k.title;
    document.getElementById('k_idea').value=k.content_idea_id||'';
    if(k.date)document.getElementById('k_date').value=k.date;
    if(k.status)document.getElementById('k_status').value=k.status;
    if(k.body)document.getElementById('k_body').value=k.body;
    (k.destination_ids||[]).forEach(did=>{const c=document.querySelector('#modal .dchks input[value="'+did+'"]');if(c)c.checked=true;});
  }
}
function klubblivIdeaPick(){const id=+document.getElementById('k_idea').value;const i=IDEAS.find(x=>x.id===id);if(i&&!val('k_title'))document.getElementById('k_title').value=i.title;}
function klubblivRepeatToggle(){const r=document.getElementById('k_repeat');const w=document.getElementById('k_countwrap');if(w)w.style.display=(r&&r.value)?'':'none';}
function klubblivFromIdea(ideaId,date){
  openKlubblivForm(null);
  const i=IDEAS.find(x=>x.id===ideaId);
  if(i){document.getElementById('k_idea').value=ideaId;document.getElementById('k_title').value=i.title;}
  if(date)document.getElementById('k_date').value=date;
}
async function suggestKlubbliv(){
  const btn=document.getElementById('kAiBtn');const title=val('k_title');
  if(!title){alert('Skriv en tittel først.');return;}
  const i=IDEAS.find(x=>x.id===(+document.getElementById('k_idea').value));
  const draft=document.getElementById('k_body').value.trim();
  if(btn){btn.disabled=true;btn.innerHTML=ic('sparkle')+' Skriver …';}
  try{
    const body={title:title,label:'Klubbliv',extra:(i&&i.description)?i.description:''};
    if(draft)body.draft=draft;
    const r=await api('POST','/ai/suggest',body);
    document.getElementById('k_body').value=r.text||'';
  }catch(err){alert(err.message);}
  if(btn){btn.disabled=false;btn.innerHTML=ic('sparkle')+' Foreslå tekst';}
}
async function saveKlubbliv(ev,id){ev.preventDefault();
  const dests=[...document.querySelectorAll('#modal .dchks input:checked')].map(c=>+c.value);
  const body={title:val('k_title'),content_idea_id:(+document.getElementById('k_idea').value||null),publish_date:val('k_date')||null,status:val('k_status')||'planlagt',destination_ids:dests,body_draft:document.getElementById('k_body').value.trim()||null};
  if(!body.publish_date)body.auto_date=true;
  try{
    let card;
    if(id){card=await api('PUT','/klubbliv/'+id,body);const i=KLUBBLIV.findIndex(x=>x.id===id);if(i>=0)KLUBBLIV[i]=card;}
    else{
      const rep=document.getElementById('k_repeat')?document.getElementById('k_repeat').value:'';
      if(rep){body.repeat=rep;body.repeat_count=parseInt((document.getElementById('k_count')||{}).value||'1',10)||1;}
      const r=await api('POST','/klubbliv',body);(r.posts||[r]).forEach(c=>KLUBBLIV.push(c));
    }
    closeModal();renderKlubbliv();renderHome();
  }catch(err){alert(err.message);}
}
async function deleteKlubbliv(id){if(!confirm('Slette denne klubbliv-posten?'))return;try{await api('DELETE','/klubbliv/'+id);KLUBBLIV=KLUBBLIV.filter(x=>x.id!==id);renderKlubbliv();renderHome();}catch(err){alert(err.message);}}
function copyKlubbliv(id){const k=KLUBBLIV.find(x=>x.id===id);if(!k||!k.body)return;if(navigator.clipboard&&navigator.clipboard.writeText)navigator.clipboard.writeText(k.body);}
function openIdeaForm(id){
  const i=id?IDEAS.find(x=>x.id===id):null;
  const groups=['verving','engasjement','praktisk','motivasjon','sesong','medlem'];
  const gOpts=groups.map(g=>'<option value="'+g+'">'+g+'</option>').join('');
  document.getElementById('modal').innerHTML=
    KHEAD+'<h2>'+(i?'Rediger idé':'Ny idé')+'</h2><div class="sub">Del av Klubbliv-biblioteket</div></div>'+
    '<div class="mbody"><form class="f" onsubmit="saveIdea(event,'+(id||'null')+')">'+
      '<div class="two"><div><label>Tittel *</label><input id="i_title" required></div><div><label>Gruppe</label><select id="i_group">'+gOpts+'</select></div></div>'+
      '<label>Stikkord / brief til AI</label><textarea id="i_desc" placeholder="Hva skal posten handle om?"></textarea>'+
      '<div class="actions"><button type="button" class="btn" onclick="closeModal()">Avbryt</button><button class="btn solid" type="submit">Lagre</button></div>'+
    '</form></div>';
  document.getElementById('overlay').classList.add('open');
  if(i){document.getElementById('i_title').value=i.title;document.getElementById('i_group').value=i.group;document.getElementById('i_desc').value=i.description||'';}
}
async function saveIdea(ev,id){ev.preventDefault();
  const body={group:val('i_group'),title:val('i_title'),description:document.getElementById('i_desc').value.trim()||null,is_active:true};
  try{let card;if(id){card=await api('PUT','/content-ideas/'+id,body);const i=IDEAS.findIndex(x=>x.id===id);if(i>=0)IDEAS[i]=card;}else{card=await api('POST','/content-ideas',body);IDEAS.push(card);}closeModal();renderKlubbliv();renderHome();}catch(err){alert(err.message);}
}
async function deleteIdea(id){if(!confirm('Slette denne idéen fra biblioteket?'))return;try{await api('DELETE','/content-ideas/'+id);IDEAS=IDEAS.filter(x=>x.id!==id);renderKlubbliv();}catch(err){alert(err.message);}}
/* ---- TRENINGSTIDER ---- */
function openTrainingMgr(){renderTrainingModal();document.getElementById('overlay').classList.add('open');}
function renderTrainingModal(){
  const rows=[...TRAINING].sort((a,b)=>a.weekday-b.weekday||String(a.start||'').localeCompare(String(b.start||'')));
  const list=rows.length?rows.map(t=>'<div class="idearow"><span class="gpill" style="background:'+(t.color||'#8795a3')+';color:#fff">'+WD[t.weekday-1].slice(0,3)+'</span><span class="it">'+esc(t.category||t.group||'Trening')+'<small>'+(t.start?t.start+(t.end?'–'+t.end:''):'')+(t.location?' · '+esc(t.location):'')+'</small></span><button class="btn sm" onclick="openTrainingForm('+t.id+')">'+ic('edit')+'</button><button class="btn sm" style="color:#b23535" onclick="deleteTraining('+t.id+')">'+ic('trash')+'</button></div>').join(''):'<div class="emptyrec">Ingen treningstider lagt inn ennå.</div>';
  document.getElementById('modal').innerHTML=
    KHEAD+'<h2>Treningstider</h2><div class="sub">Brukes i «Denne uka» for å vise hvem som trener</div></div>'+
    '<div class="mbody"><div style="text-align:right;margin-bottom:10px"><button class="btn solid sm" onclick="openTrainingForm(null)">'+ic('plus')+' Ny treningstid</button></div>'+list+'</div>';
}
function openTrainingForm(id){
  const t=id?TRAINING.find(x=>x.id===id):null;
  const catOpts='<option value="">– idrett –</option>'+CATS.map(c=>'<option value="'+c.id+'">'+esc(c.name)+'</option>').join('');
  const wdOpts=WD.map((w,idx)=>'<option value="'+(idx+1)+'">'+w+'</option>').join('');
  document.getElementById('modal').innerHTML=
    KHEAD+'<h2>'+(t?'Rediger treningstid':'Ny treningstid')+'</h2><div class="sub">Treningstider</div></div>'+
    '<div class="mbody"><form class="f" onsubmit="saveTraining(event,'+(id||'null')+')">'+
      '<div class="two"><div><label>Idrett</label><select id="t_cat">'+catOpts+'</select></div><div><label>Ukedag</label><select id="t_wd">'+wdOpts+'</select></div></div>'+
      '<div class="two"><div><label>Fra</label><input id="t_start" type="time"></div><div><label>Til</label><input id="t_end" type="time"></div></div>'+
      '<div class="two"><div><label>Sted</label><input id="t_loc"></div><div><label>Gruppe (valgfritt)</label><input id="t_group" placeholder="f.eks. G10"></div></div>'+
      '<div class="actions"><button type="button" class="btn" onclick="renderTrainingModal()">Tilbake</button><button class="btn solid" type="submit">Lagre</button></div>'+
    '</form></div>';
  if(t){if(t.category_id)document.getElementById('t_cat').value=t.category_id;document.getElementById('t_wd').value=t.weekday;if(t.start)document.getElementById('t_start').value=t.start;if(t.end)document.getElementById('t_end').value=t.end;if(t.location)document.getElementById('t_loc').value=t.location;if(t.group)document.getElementById('t_group').value=t.group;}
}
async function saveTraining(ev,id){ev.preventDefault();
  const body={category_id:(+document.getElementById('t_cat').value||null),weekday:+document.getElementById('t_wd').value,start_time:val('t_start')||null,end_time:val('t_end')||null,location:val('t_loc')||null,group_label:val('t_group')||null};
  try{let card;if(id){card=await api('PUT','/training-schedules/'+id,body);const i=TRAINING.findIndex(x=>x.id===id);if(i>=0)TRAINING[i]=card;}else{card=await api('POST','/training-schedules',body);TRAINING.push(card);}renderTrainingModal();renderHome();}catch(err){alert(err.message);}
}
async function deleteTraining(id){if(!confirm('Slette denne treningstiden?'))return;try{await api('DELETE','/training-schedules/'+id);TRAINING=TRAINING.filter(x=>x.id!==id);renderTrainingModal();renderHome();}catch(err){alert(err.message);}}

populateFilters();renderHome();
</script>
@endverbatim
</body>
</html>
