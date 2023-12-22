import { expect, test } from "./test";

test.describe("Thing view", () => {
  test.beforeEach(async ({ thingPage }) => {
    await thingPage.gotoDefaultThing();
  });

  test("I can see the thing details @read", async ({ page }) => {
    // test thing display
    await expect(page).toHaveTitle("Thing");
    await expect(page.locator("h1")).toHaveText("Thing");
    await expect(page.getByTestId("thing-cover")).toBeVisible();
    await expect(page.getByTestId("thing-description")).not.toBeEmpty();
  });

  test("I can go back to the things list through the breadcrumb @read", async ({ page }) => {
    await expect(page.getByTestId("thing-breadcrumb")).toContainText("Things Store");
    await page.getByTestId("thing-breadcrumb").getByText("Things Store").click();
    await expect(page).toHaveURL(/\/things$/);
  });
});
