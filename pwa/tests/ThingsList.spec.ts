import { expect, test } from "./test";

const totalThings = 179;

test.describe("Things list", () => {
  test.beforeEach(async ({ thingPage }) => {
    await thingPage.gotoList();
  });

  test("I can navigate through the list using the pagination @read", async ({ thingPage, page }) => {
    // test list display
    await expect(page).toHaveTitle("OpenDataSpace - Things");
    await expect(page.getByTestId("nb-things")).toHaveText(`${totalThings} thing(s) found`);

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
    await expect(await thingPage.getDefaultThing()).not.toBeVisible();
    await expect(page.getByTestId("pagination").locator("li a.Mui-selected")).toHaveAttribute("aria-label", "page 2");
    await expect(page.getByLabel("Go to first page")).toBeEnabled();
    await expect(page.getByLabel("Go to previous page")).toBeEnabled();
    await expect(page.getByLabel("Go to next page")).toBeEnabled();
    await expect(page.getByLabel("Go to last page")).toBeEnabled();

    await page.getByLabel("page 3").click();
    await expect(page).toHaveURL(/\/things\?page=3$/);
    //await expect(page.getByTestId("thing").or(page.getByTestId("loading"))).toHaveCount(totalThings);
    await expect(await thingPage.getDefaultThing()).not.toBeVisible();
    await expect(page.getByTestId("pagination").locator("li a.Mui-selected")).toHaveAttribute("aria-label", "page 3");
    await expect(page.getByLabel("Go to first page")).toBeEnabled();
    await expect(page.getByLabel("Go to previous page")).toBeEnabled();
    await expect(page.getByLabel("Go to next page")).toBeEnabled();
    await expect(page.getByLabel("Go to last page")).toBeEnabled();

    await page.getByLabel("Go to previous page").click();
    await expect(page).toHaveURL(/\/things\?page=2$/);
    await expect(await thingPage.getDefaultThing()).not.toBeVisible();
    await expect(page.getByTestId("pagination").locator("li a.Mui-selected")).toHaveAttribute("aria-label", "page 2");
    await expect(page.getByLabel("Go to first page")).toBeEnabled();
    await expect(page.getByLabel("Go to previous page")).toBeEnabled();
    await expect(page.getByLabel("Go to next page")).toBeEnabled();
    await expect(page.getByLabel("Go to last page")).toBeEnabled();

    await page.getByLabel("Go to last page").click();
    await expect(page).toHaveURL(new RegExp(`\/things\\?page=${nbPages}$`));
    //await expect(page.getByTestId("thing").or(page.getByTestId("loading"))).not.toHaveCount(30);
    await expect(await thingPage.getDefaultThing()).not.toBeVisible();
    await expect(page.getByTestId("pagination").locator("li a.Mui-selected")).toHaveAttribute("aria-label", `page ${nbPages}`);
    await expect(page.getByLabel("Go to first page")).toBeEnabled();
    await expect(page.getByLabel("Go to previous page")).toBeEnabled();
    await expect(page.getByLabel("Go to next page")).toBeDisabled();
    await expect(page.getByLabel("Go to last page")).toBeDisabled();

    await page.getByLabel("Go to first page").click();
    await expect(page).toHaveURL(/\/things\?page=1$/);
    await expect(await thingPage.getDefaultThing()).toBeVisible();
    await expect(page.getByTestId("pagination").locator("li a.Mui-selected")).toHaveAttribute("aria-label", "page 1");
    await expect(page.getByLabel("Go to first page")).toBeDisabled();
    await expect(page.getByLabel("Go to previous page")).toBeDisabled();
    await expect(page.getByLabel("Go to next page")).toBeEnabled();
    await expect(page.getByLabel("Go to last page")).toBeEnabled();

    // direct url should target to the right page
    await page.goto("/things?page=2");
    await page.waitForURL(/\/things\?page=2$/);
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
    //await expect(page.getByTestId("thing").or(page.getByTestId("loading"))).toHaveCount(1);
    await expect(page.getByTestId("pagination")).toHaveCount(0);
   // await expect(await thingPage.getDefaultThing()).toBeVisible();

    // clear name field
    await page.getByTestId("filter-name").clear();
    await expect(page.getByTestId("filter-name")).toHaveValue("");
    await expect(page).toHaveURL(/\/things$/);
    await expect(page.getByTestId("nb-things")).toHaveText(`${totalThings} thing(s) found`);

    // filtering must reset the pagination
    await page.getByLabel("Go to next page").click();
    await expect(page).toHaveURL(/\/things\?page=2$/);
    await expect(await thingPage.getDefaultThing()).not.toBeVisible();
    await thingPage.filter({ name: "Eiger" });
    await expect(page).toHaveURL(/\/things\?page=2&name=Eiger/);
    await expect(page.getByTestId("nb-things")).toHaveText("1 thing(s) found");
    //await expect(page.getByTestId("thing").or(page.getByTestId("loading"))).toHaveCount(1);
    await expect(page.getByTestId("pagination")).toHaveCount(0);
    //await expect(await thingPage.getDefaultThing()).toBeVisible();

    // clear name field
    await page.getByTestId("filter-name").clear();
    await expect(page.getByTestId("filter-name")).toHaveValue("");
    await expect(page).toHaveURL(/\/things\?page=2$/);
    await expect(page.getByTestId("nb-things")).toHaveText(`${totalThings} thing(s) found`);
  });

  test("I can sort the list @read", async ({ thingPage, page }) => {
    // sort by name asc
    await thingPage.filter({ order: "Name ASC" });
    await expect(page).toHaveURL(/\/things\?order%5Bname%5D=asc$/);
    await expect(page.getByTestId("sort")).toHaveText("Name ASC");
    //await expect(await thingPage.getDefaultThing()).not.toBeVisible()

    // sort by name desc
    await thingPage.filter({ order: "Name DESC" });
    await expect(page).toHaveURL(/\/things\?order%5Bname%5D=desc$/);
    await expect(page.getByTestId("sort")).toHaveText("Name DESC");
    //await expect(await thingPage.getDefaultThing()).not.toBeVisible()

    // sort by default (relevance)
    await thingPage.filter({ order: "Relevance" });
    await expect(page).toHaveURL(/\/things$/);
    await expect(page.getByTestId("sort")).toHaveText("Relevance");
    //await expect(await thingPage.getDefaultThing()).toBeVisible()

    // direct url should apply the sort
    await page.goto("/things?order%5Bname%5D=asc");
    await expect(page.getByTestId("sort")).toHaveText("Name ASC");
    await expect(await thingPage.getDefaultThing()).not.toBeVisible()
  });
});
