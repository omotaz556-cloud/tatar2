/**
 * Quick responsive overflow check — run: node tests/responsive-check.js
 */
const { chromium } = require('playwright');

const BASE = process.env.BASE_URL || 'http://localhost:8080';
const PAGES = [
  { path: '/', name: 'index' },
  { path: '/login.php', name: 'login' },
  { path: '/spielregeln.php', name: 'spielregeln' },
  { path: '/anmelden.php', name: 'anmelden' },
];

const SIZES = [
  { name: 'desktop-xl', width: 1920, height: 1080 },
  { name: 'laptop', width: 1366, height: 768 },
  { name: 'ipad', width: 768, height: 1024 },
  { name: 'phone-md', width: 390, height: 844 },
  { name: 'phone-sm', width: 360, height: 640 },
];

(async () => {
  const browser = await chromium.launch();
  const results = [];

  for (const size of SIZES) {
    const context = await browser.newContext({
      viewport: { width: size.width, height: size.height },
    });
    const page = await context.newPage();

    for (const pg of PAGES) {
      const url = BASE + pg.path;
      try {
        await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 20000 });
        await page.waitForTimeout(800);
        const metrics = await page.evaluate(() => {
          const doc = document.documentElement;
          const overflow = doc.scrollWidth - doc.clientWidth;
          return {
            scrollWidth: doc.scrollWidth,
            clientWidth: doc.clientWidth,
            overflow,
            hasViewport: !!document.querySelector('meta[name="viewport"]'),
          };
        });
        results.push({
          page: pg.name,
          size: size.name,
          ...metrics,
          ok: metrics.overflow <= 2,
        });
      } catch (e) {
        results.push({ page: pg.name, size: size.name, error: e.message, ok: false });
      }
    }
    await context.close();
  }

  await browser.close();

  console.log('\n=== Responsive overflow check ===\n');
  for (const r of results) {
    if (r.error) {
      console.log(`FAIL ${r.size} / ${r.page}: ${r.error}`);
    } else {
      const status = r.ok ? 'OK' : 'OVERFLOW';
      console.log(
        `${status} ${r.size} / ${r.page}: overflow=${r.overflow}px (${r.scrollWidth}/${r.clientWidth}) viewport=${r.hasViewport}`
      );
    }
  }

  const fails = results.filter((r) => !r.ok);
  process.exit(fails.length ? 1 : 0);
})();
