import { expect, test } from "./test";

test.describe("Edit a thing @admin", () => {
  test.beforeEach(async ({ thingPage, page }) => {
    await thingPage.gotoList();
    await page.locator(".datagrid-body tr").last().getByRole("link", { name: "Edit", exact: true }).click();
  });

  test("I can edit a thing @write", async ({ page }) => {
    // fill in Name
    await page.getByLabel("Name").fill("Thing");
    await page.getByRole("listbox").getByText("Thing", { exact: true }).waitFor({ state: "visible" });
    await page.getByRole("listbox").getByText("Thing", { exact: true }).click();
    await expect(page.getByRole("listbox")).not.toBeAttached();
    await expect(page.getByLabel("Name")).toHaveValue("Thing");

    // submit form
    await page.getByRole("button", { name: "Save", exact: true }).click();
    await expect(page.getByText("Element updated")).toBeVisible();
  });

  test("I can delete a thing @write", async ({ page }) => {
    await expect(page.getByRole("button", { name: "Delete" })).toBeVisible();
    await page.getByRole("button", { name: "Delete" }).click();
    await expect(page.getByRole("button", { name: "Confirm" })).toBeVisible();
    await page.getByRole("button", { name: "Confirm" }).click();
    await page.getByRole("button", { name: "Confirm" }).waitFor({ state: "detached" });
    await expect(page.getByText("Element deleted")).toBeVisible();
  });
});
