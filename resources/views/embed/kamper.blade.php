<!DOCTYPE html>
<html lang="nb">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Kommende hjemmekamper – {{ $company->name }}</title>
<style>
  body{margin:0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;font-size:16px;background:transparent;padding:8px}
  .feed{max-width:640px;margin:0 auto}
</style>
</head>
<body>
<div class="feed">
  @include('embed.kamper_list')
</div>
<script>
  // Meld høyden til forelder-siden, så iframen kan tilpasse seg innholdet.
  function vkPostHeight(){
    var h = document.body.scrollHeight;
    if (window.parent) window.parent.postMessage({ vkFrame: true, vkHeight: h }, '*');
  }
  window.addEventListener('load', vkPostHeight);
  window.addEventListener('resize', vkPostHeight);
  if (window.ResizeObserver) { new ResizeObserver(vkPostHeight).observe(document.body); }
</script>
</body>
</html>
