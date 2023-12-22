import { expect, test } from "./test";

test.describe("Create a thing @admin", () => {
  test.beforeEach(async ({ thingPage, page }) => {
    await thingPage.gotoList();
    await page.getByRole("link", { name: "Create", exact: true }).click();
  });

  test("I can create a thing @write", async ({ thingPage, page }) => {
    // fill in Name
    await page.getByLabel("Name").fill("Thing");
    await page.getByRole("listbox").getByText("Thing", { exact: true }).waitFor({ state: "visible" });
    await page.getByRole("listbox").getByText("Thing", { exact: true }).click();
    await expect(page.getByRole("listbox")).not.toBeAttached();
    await expect(page.getByLabel("Name")).toHaveValue("Thing");

    // submit form
    await page.getByRole("button", { name: "Save", exact: true }).click();
    await expect(page.getByText("Element created")).toBeVisible();
  });
});
