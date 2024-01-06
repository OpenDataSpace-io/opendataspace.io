import { expect, test } from "@playwright/test";

test.describe("Homepage", () => {
  test.beforeEach(async ({ page }) => {
    await page.goto("/");
  });

  test("Check homepage @read", async ({ page }) => {
    await expect(page).toHaveTitle("OpenDataSpace - Things");
  });
})
