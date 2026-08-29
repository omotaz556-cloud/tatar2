/**
 * Verify stat shell layout (fixture + optional live page).
 */
import { chromium } from 'playwright';
import fs from 'fs';
import path from 'path';
import { pathToFileURL } from 'url';

const liveBase = process.argv[2] || '';
const outDir = path.join(process.cwd(), 'tools', '_render_out');
const fixtureUrl = pathToFileURL(path.join(process.cwd(), 'tools', 'stat_shell_fixture.html')).href;

async function audit(page, label) {
  return page.evaluate((lbl) => {
    const left = document.querySelector('.gk-td-left');
    const main = document.querySelector('.gk-td-main');
    const nav = document.querySelector('.gk-td-nav');
    const rlista = document.querySelector('.gk-rlista');
    const rect = (el) => {
      if (!el) return null;
      const r = el.getBoundingClientRect();
      return { x: Math.round(r.x), y: Math.round(r.y), w: Math.round(r.width), h: Math.round(r.height) };
    };
    const navLinks = rlista ? rlista.querySelectorAll('a').length : 0;
    const lr = left ? left.getBoundingClientRect() : null;
    const mr = main ? main.getBoundingClientRect() : null;
    const nr = nav ? nav.getBoundingClientRect() : null;
    return {
      label: lbl,
      left: rect(left),
      main: rect(main),
      nav: rect(nav),
      navLinks,
      orderOk: lr && mr && nr && lr.x < mr.x && mr.x < nr.x,
      navVisible: nr && nr.width >= 100 && nr.height >= 80 && navLinks >= 8,
      plusText: document.querySelector('.gk-rlista a.plus')?.textContent?.trim() || null,
    };
  }, label);
}

async function main() {
  fs.mkdirSync(outDir, { recursive: true });
  const browser = await chromium.launch({ headless: true });
  const page = await (await browser.newContext({ viewport: { width: 1280, height: 900 }, locale: 'ar' })).newPage();

  await page.goto(fixtureUrl, { waitUntil: 'networkidle', timeout: 30000 });
  const fixtureShot = path.join(outDir, 'stat_fixture.png');
  await page.screenshot({ path: fixtureShot, fullPage: true });
  const fixtureReport = await audit(page, 'fixture');
  console.log(JSON.stringify(fixtureReport, null, 2));

  let liveReport = null;
  if (liveBase) {
    await page.goto(`${liveBase.replace(/\/$/, '')}/statistiken.php`, { waitUntil: 'networkidle', timeout: 60000 });
    if ((await page.locator('body.pg-statistics').count()) === 0) {
      liveReport = { label: 'live', skipped: true, reason: 'not authenticated' };
    } else {
      const liveShot = path.join(outDir, 'stat_live.png');
      await page.screenshot({ path: liveShot, fullPage: true });
      liveReport = await audit(page, 'live');
      console.log('Live screenshot:', liveShot);
    }
    console.log(JSON.stringify(liveReport, null, 2));
  }

  await browser.close();

  const ok = fixtureReport.navVisible && fixtureReport.orderOk
    && (!liveReport || liveReport.skipped || (liveReport.navVisible && liveReport.orderOk));
  if (!ok) {
    console.error('FAIL: sidebar not visible');
    process.exit(1);
  }
  console.log('PASS: gk-shell 3 columns with right sidebar');
  console.log('Screenshot:', fixtureShot);
}

main().catch((e) => {
  console.error(e);
  process.exit(1);
});
