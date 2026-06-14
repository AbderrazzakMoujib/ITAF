<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: Arial, Helvetica, sans-serif; background-color: #F5F5F5; color: #1A1A1A; font-size: 15px; line-height: 1.6; }
.wrapper { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 4px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.10); }

/* Header */
.header { background: #E8002D; padding: 0; }
.header-top { padding: 28px 36px 20px; }
.header-flag { display: block; font-family: Arial Narrow, Arial, sans-serif; font-weight: 900; font-style: italic; font-size: 26px; color: #FFFFFF; text-transform: uppercase; letter-spacing: 0.02em; line-height: 1.1; }
.header-flag span { color: rgba(255,255,255,0.6); }
.header-sub { margin-top: 8px; font-family: Arial, sans-serif; font-size: 12px; color: rgba(255,255,255,0.7); letter-spacing: 0.1em; text-transform: uppercase; }
.header-sep { color: rgba(255,255,255,0.4); margin: 0 8px; }
.header-bar { background: #0A1628; height: 6px; }

/* Corps */
.body { padding: 32px 36px; }
.salutation { font-family: Arial Narrow, Arial, sans-serif; font-size: 19px; font-weight: 700; color: #0A1628; margin-bottom: 10px; }
.message { color: #444; margin-bottom: 24px; font-size: 14px; }

/* Badge recap */
.badge-recap { border-left: 5px solid #E8002D; background: #F5F5F5; padding: 14px 18px; margin-bottom: 24px; border-radius: 0 4px 4px 0; }
.badge-name { font-family: Arial Narrow, Arial, sans-serif; font-weight: 900; font-size: 20px; color: #0A1628; text-transform: uppercase; letter-spacing: 0.02em; }
.badge-company { font-family: Arial Narrow, Arial, sans-serif; font-weight: 700; font-size: 15px; color: #4A90C4; text-transform: uppercase; margin-top: 2px; }
.badge-detail { font-size: 13px; color: #666; margin-top: 3px; }

/* QR code */
.qr-section { text-align: center; background: #F5F5F5; border: 1px solid #DDDDDD; border-top: 4px solid #E8002D; border-radius: 4px; padding: 24px; margin: 20px 0; }
.qr-title { font-family: Arial Narrow, Arial, sans-serif; font-weight: 700; font-size: 13px; letter-spacing: 0.1em; text-transform: uppercase; color: #0A1628; margin-bottom: 14px; }
.qr-section img { width: 200px; height: 200px; display: block; margin: 0 auto; border: 3px solid #DDDDDD; padding: 6px; background: white; }
.qr-instruction { margin-top: 14px; font-size: 13px; color: #444; font-weight: 600; }

/* Infos événement */
.event-info { background: #0A1628; border-radius: 4px; padding: 16px 20px; margin: 20px 0; }
.event-info table { width: 100%; border-collapse: collapse; }
.event-info td { padding: 5px 0; font-size: 13px; color: rgba(255,255,255,0.85); }
.event-info td:first-child { font-family: Arial Narrow, Arial, sans-serif; font-weight: 700; text-transform: uppercase; font-size: 11px; letter-spacing: 0.1em; color: #E8002D; width: 90px; }

/* CTA */
.cta { text-align: center; margin: 24px 0; }
.cta a { display: inline-block; background: #E8002D; color: #FFFFFF; text-decoration: none; padding: 13px 30px; border-radius: 4px; font-family: Arial Narrow, Arial, sans-serif; font-weight: 700; font-size: 15px; letter-spacing: 0.06em; text-transform: uppercase; }

/* Footer */
.footer { background: #0A1628; color: rgba(255,255,255,0.4); text-align: center; padding: 18px 36px; font-size: 11px; letter-spacing: 0.06em; text-transform: uppercase; }
.footer .footer-brand { color: #E8002D; font-family: Arial Narrow, Arial, sans-serif; font-weight: 700; }

@media (max-width: 600px) {
    .body { padding: 24px 20px; }
    .header-top { padding: 24px 20px; }
}
</style>
