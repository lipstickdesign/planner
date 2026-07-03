<!DOCTYPE html>
<html lang="no">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Tilbakestill passordet ditt – Vivu Planner</title>
</head>
<body style="margin:0;padding:0;background:#eef1f6;font-family:'Ubuntu',Segoe UI,Helvetica,Arial,sans-serif;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef1f6;padding:28px 12px;">
    <tr>
      <td align="center">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:480px;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 10px 34px rgba(20,40,76,.14);">

          <!-- Topp / merke -->
          <tr>
            <td style="background:#1c3155;padding:26px 30px;text-align:center;">
              <div style="color:#ffffff;font-size:20px;font-weight:700;letter-spacing:.2px;">Vivu Planner</div>
              <div style="color:#9db6df;font-size:12.5px;margin-top:3px;">Farsund og Lista Idrettsklubb</div>
            </td>
          </tr>

          <!-- Innhold -->
          <tr>
            <td style="padding:30px 34px 26px;">
              <h1 style="margin:0 0 14px;font-size:20px;color:#1a1f33;font-weight:600;">Tilbakestill passordet ditt</h1>

              <p style="margin:0 0 16px;font-size:14.5px;line-height:1.6;color:#48566b;">
                @if(!empty($name))Hei {{ $name }}!@else Hei!@endif Vi har mottatt en forespørsel om å nullstille passordet ditt i Vivu Planner. Klikk på knappen under for å velge et nytt passord.
              </p>

              <!-- Knapp -->
              <table role="presentation" cellpadding="0" cellspacing="0" style="margin:22px 0 24px;">
                <tr>
                  <td align="center" style="border-radius:12px;background:#2f6fd6;">
                    <a href="{{ $url }}" style="display:inline-block;padding:13px 30px;font-size:15px;font-weight:500;color:#ffffff;text-decoration:none;border-radius:12px;">
                      Velg nytt passord
                    </a>
                  </td>
                </tr>
              </table>

              <p style="margin:0 0 16px;font-size:13px;line-height:1.6;color:#7a8798;">
                Lenken er gyldig i {{ $expire }} minutter. Hvis du ikke ba om å nullstille passordet, kan du trygt se bort fra denne e-posten – passordet ditt endres ikke.
              </p>

              <p style="margin:0 0 6px;font-size:12px;color:#98a4b5;">
                Fungerer ikke knappen? Kopier og lim inn denne adressen i nettleseren:
              </p>
              <p style="margin:0;font-size:12px;word-break:break-all;">
                <a href="{{ $url }}" style="color:#2f6fd6;text-decoration:none;">{{ $url }}</a>
              </p>
            </td>
          </tr>

          <!-- Bunn -->
          <tr>
            <td style="background:#f6f8fb;padding:18px 30px;text-align:center;border-top:1px solid #e6ebf2;">
              <div style="font-size:11.5px;color:#98a4b5;line-height:1.5;">
                Vivu Planner · Havdur Design<br>
                Denne e-posten ble sendt automatisk – du kan ikke svare på den.
              </div>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
