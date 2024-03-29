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
    // TODO: fix this test
    //await expect(page).toHaveURL(/\/things\/*$/);
  });

  test("I can go back to the things list filtered by name through the breadcrumb @read", async ({ page }) => {
    await expect(page.getByTestId("thing-breadcrumb")).toContainText("Eiger");
    await page.getByTestId("thing-breadcrumb").getByText("Eiger").click();
    // TODO: fix this test
    //await expect(page).toHaveURL(/\/things\?name=Eiger/);
    // things/1eea4257-a13d-6678-a017-c36f84bc5b3b"
    //await expect(page).toHaveURL(/\/things\/*$/);
  });
});
