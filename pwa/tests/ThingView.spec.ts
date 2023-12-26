import { expect, test } from "./test";

test.describe("Thing view", () => {
  test.beforeEach(async ({ thingPage }) => {
    await thingPage.gotoDefaultThing();
  });

  test("I can see the thing details @read", async ({ page }) => {
    // test thing display
    await expect(page).toHaveTitle("Eiger");
    await expect(page.locator("h1")).toHaveText("Eiger");
    await expect(page.getByTestId("thing-cover")).toBeVisible();
    await expect(page.getByTestId("thing-description")).not.toBeEmpty();
  });

  test("I can go back to the things list through the breadcrumb @read", async ({ page }) => {
    await expect(page.getByTestId("thing-breadcrumb")).toContainText("Things/Eiger");
    await page.getByTestId("thing-breadcrumb").getByText("Things/Eiger").click();
    await expect(page).toHaveURL(/\/things$/);
  });

  test("I can go back to the things list filtered by name through the breadcrumb @read", async ({ page }) => {
    await expect(page.getByTestId("thing-breadcrumb")).toContainText("Eiger");
    await page.getByTestId("thing-breadcrumb").getByText("Eiger").click();
    await expect(page).toHaveURL(/\/things\?name=Eiger/);
  });
});
