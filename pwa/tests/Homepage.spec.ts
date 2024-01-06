import { expect, test } from "@playwright/test";

test.describe("Homepage", () => {
  test.beforeEach(async ({ page }) => {
    await page.goto("/");
  });

  test("Check homepage @read", async ({ page }) => {
    await expect(page).toHaveTitle("OpenDataSpace - Things");
  });

  test("Go to API docs @read", async ({ page }) => {
    await page.getByTestId("cards").getByRole("link", { name: "API" }).click();
    await expect(page).toHaveURL(/\/docs$/);
  });

  test("Go to Admin @read", async ({ page }) => {
    await page.getByTestId("cards").getByRole("link", { name: "Admin" }).click();
    await expect(page).toHaveURL(/\/admin$/);
  });

  test("Go to Mercure Debugger @read", async ({ page }) => {
    await page.getByTestId("cards").getByRole("link", { name: "Mercure debugger" }).click();
    await expect(page).toHaveURL(/\/\.well-known\/mercure\/ui\/$/);
  });
})
