import { test as playwrightTest } from "@playwright/test";

import { expect } from "../test";
import { ThingPage } from "./pages/ThingPage";

type Test = {
  thingPage: ThingPage,
}

export const test = playwrightTest.extend<Test>({
  thingPage: async ({ page }, use) => {
    await use(new ThingPage(page));
  }
});

export { expect };
