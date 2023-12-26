import { expect, test } from "./test";

const totalThings = 35;

test.describe("Things list", () => {
  test.beforeEach(async ({ thingPage }) => {
    await thingPage.gotoList();
  });

  test("I can navigate through the list using the pagination @read", async ({ thingPage, page }) => {
    // test list display
    await expect(page).toHaveTitle("OpenDataSpace");
    await expect(page.getByTestId("nb-things")).toHaveText(`${totalThings} thing(s) found`);
    await expect(page.getByTestId("thing").or(page.getByTestId("loading"))).toHaveCount(30);

    const nbPages = Math.ceil(totalThings/30);

    // test pagination display
    await expect(page.getByTestId("pagination").locator("li a")).toHaveCount(nbPages+4);
    await expect(page.getByTestId("pagination").locator("li a").first()).toHaveAttribute("aria-label", "Go to first page");
    await expect(page.getByTestId("pagination").locator("li a").nth(1)).toHaveAttribute("aria-label", "Go to previous page");
    await expect(page.getByTestId("pagination").locator("li a").nth(nbPages+2)).toHaveAttribute("aria-label", "Go to next page");
    await expect(page.getByTestId("pagination").locator("li a").nth(nbPages+3)).toHaveAttribute("aria-label", "Go to last page");
    await expect(page.getByTestId("pagination").locator("li a.Mui-selected")).toHaveAttribute("aria-label", "page 1");
    await expect(page.getByLabel("Go to first page")).toBeDisabled();
    await expect(page.getByLabel("Go to previous page")).toBeDisabled();
    await expect(page.getByLabel("Go to next page")).toBeEnabled();
    await expect(page.getByLabel("Go to last page")).toBeEnabled();

    // navigate through pagination
    await page.getByLabel("Go to next page").click();
    await expect(page).toHaveURL(/\/things\?page=2$/);
    await expect(page.getByTestId("thing").or(page.getByTestId("loading"))).toHaveCount(30);
    await expect(await thingPage.getDefaultThing()).not.toBeVisible();
    await expect(page.getByTestId("pagination").locator("li a.Mui-selected")).toHaveAttribute("aria-label", "page 2");
    await expect(page.getByLabel("Go to first page")).toBeEnabled();
    await expect(page.getByLabel("Go to previous page")).toBeEnabled();
    await expect(page.getByLabel("Go to next page")).toBeEnabled();
    await expect(page.getByLabel("Go to last page")).toBeEnabled();

    /*await page.getByLabel("page 3").click();
    await expect(page).toHaveURL(/\/things\?page=3$/);
    await expect(page.getByTestId("thing").or(page.getByTestId("loading"))).toHaveCount(30);
    await expect(await thingPage.getDefaultThing()).not.toBeVisible();
    await expect(page.getByTestId("pagination").locator("li a.Mui-selected")).toHaveAttribute("aria-label", "page 3");
    await expect(page.getByLabel("Go to first page")).toBeEnabled();
    await expect(page.getByLabel("Go to previous page")).toBeEnabled();
    await expect(page.getByLabel("Go to next page")).toBeEnabled();
    await expect(page.getByLabel("Go to last page")).toBeEnabled();
    */

    await page.getByLabel("Go to previous page").click();
    await expect(page).toHaveURL(/\/things\?page=2$/);
    await expect(page.getByTestId("thing").or(page.getByTestId("loading"))).toHaveCount(30);
    await expect(await thingPage.getDefaultThing()).not.toBeVisible();
    await expect(page.getByTestId("pagination").locator("li a.Mui-selected")).toHaveAttribute("aria-label", "page 2");
    await expect(page.getByLabel("Go to first page")).toBeEnabled();
    await expect(page.getByLabel("Go to previous page")).toBeEnabled();
    await expect(page.getByLabel("Go to next page")).toBeEnabled();
    await expect(page.getByLabel("Go to last page")).toBeEnabled();

    await page.getByLabel("Go to last page").click();
    await expect(page).toHaveURL(new RegExp(`\/things\\?page=${nbPages}$`));
    await expect(page.getByTestId("thing").or(page.getByTestId("loading"))).not.toHaveCount(30);
    await expect(await thingPage.getDefaultThing()).not.toBeVisible();
    await expect(page.getByTestId("pagination").locator("li a.Mui-selected")).toHaveAttribute("aria-label", `page ${nbPages}`);
    await expect(page.getByLabel("Go to first page")).toBeEnabled();
    await expect(page.getByLabel("Go to previous page")).toBeEnabled();
    await expect(page.getByLabel("Go to next page")).toBeDisabled();
    await expect(page.getByLabel("Go to last page")).toBeDisabled();

    await page.getByLabel("Go to first page").click();
    await expect(page).toHaveURL(/\/things\?page=1$/);
    await expect(page.getByTestId("thing").or(page.getByTestId("loading"))).toHaveCount(30);
    await expect(await thingPage.getDefaultThing()).toBeVisible();
    await expect(page.getByTestId("pagination").locator("li a.Mui-selected")).toHaveAttribute("aria-label", "page 1");
    await expect(page.getByLabel("Go to first page")).toBeDisabled();
    await expect(page.getByLabel("Go to previous page")).toBeDisabled();
    await expect(page.getByLabel("Go to next page")).toBeEnabled();
    await expect(page.getByLabel("Go to last page")).toBeEnabled();

    // direct url should target to the right page
    await page.goto("/things?page=2");
    await page.waitForURL(/\/things\?page=2$/);
    await expect(page.getByTestId("thing").or(page.getByTestId("loading"))).toHaveCount(30);
    await expect(await thingPage.getDefaultThing()).not.toBeVisible();
    await expect(page.getByTestId("pagination").locator("li a.Mui-selected")).toHaveAttribute("aria-label", "page 2");
    await expect(page.getByLabel("Go to first page")).toBeEnabled();
    await expect(page.getByLabel("Go to previous page")).toBeEnabled();
    await expect(page.getByLabel("Go to next page")).toBeEnabled();
    await expect(page.getByLabel("Go to last page")).toBeEnabled();
  });

  test("I can filter the list @read", async ({ thingPage, page }) => {
    // filter by name
    await thingPage.filter({ name: "Eiger" });
    await expect(page).toHaveURL(/\/things\?name=Eiger/);
    await expect(page.getByTestId("nb-things")).toHaveText("1 thing(s) found");
    await expect(page.getByTestId("thing").or(page.getByTestId("loading"))).toHaveCount(1);
    await expect(page.getByTestId("pagination")).toHaveCount(0);
    await expect(await thingPage.getDefaultThing()).toBeVisible();

    // clear name field
    await page.getByTestId("filter-name").clear();
    await expect(page.getByTestId("filter-name")).toHaveValue("");
    await expect(page).toHaveURL(/\/things$/);
    await expect(page.getByTestId("nb-things")).toHaveText(`${totalThings} thing(s) found`);
    await expect(page.getByTestId("thing").or(page.getByTestId("loading"))).toHaveCount(30);

    // filtering must reset the pagination
    await page.getByLabel("Go to next page").click();
    await expect(page).toHaveURL(/\/things\?page=2$/);
    await expect(page.getByTestId("thing").or(page.getByTestId("loading"))).toHaveCount(30);
    await expect(await thingPage.getDefaultThing()).not.toBeVisible();
    await thingPage.filter({ name: "Eiger" });
    await expect(page).toHaveURL(/\/things\?name=Eiger/);
    await expect(page.getByTestId("nb-things")).toHaveText("1 thing(s) found");
    await expect(page.getByTestId("thing").or(page.getByTestId("loading"))).toHaveCount(1);
    await expect(page.getByTestId("pagination")).toHaveCount(0);
    await expect(await thingPage.getDefaultThing()).toBeVisible();

    // clear author field
    await page.getByTestId("filter-name").clear();
    await expect(page.getByTestId("filter-name")).toHaveValue("");
    await expect(page).toHaveURL(/\/things$/);
    await expect(page.getByTestId("nb-things")).toHaveText(`${totalThings} thing(s) found`);
    await expect(page.getByTestId("thing").or(page.getByTestId("loading"))).toHaveCount(30);
  });

  test("I can sort the list @read", async ({ thingPage, page }) => {
    // sort by title asc
    await thingPage.filter({ order: "Name ASC" });
    await expect(page).toHaveURL(/\/thing\?order%5Btitle%5D=asc$/);
    await expect(await thingPage.getDefaultThing()).not.toBeVisible()

    // sort by title desc
    await thingPage.filter({ order: "Name DESC" });
    await expect(page).toHaveURL(/\/things\?order%5Btitle%5D=desc$/);
    await expect(await thingPage.getDefaultThing()).not.toBeVisible()

    // sort by default (relevance)
    await thingPage.filter({ order: "Relevance" });
    await expect(page).toHaveURL(/\/things$/);
    await expect(await thingPage.getDefaultThing()).toBeVisible()

    // direct url should apply the sort
    await page.goto("/things?order%5Btitle%5D=asc");
    await expect(page.getByTestId("sort")).toHaveText("Name ASC");
    await expect(await thingPage.getDefaultThing()).not.toBeVisible()
  });
});
