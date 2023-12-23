import { test as playwrightTest } from "@playwright/test";

import { expect } from "../test";
//import { BookPage } from "./pages/BookPage";

type Test = {
  //bookPage: BookPage,
}

export const test = playwrightTest.extend<Test>({
  /*bookPage: async ({ page }, use) => {
    await use(new BookPage(page));
  }*/
});

export { expect };
