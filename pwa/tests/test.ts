import { expect, Page, test as playwrightTest } from "@playwright/test";

import { ThingPage } from "./pages/ThingPage";
import { UserPage } from "./pages/UserPage";

expect.extend({
  toBeOnLoginPage(page: Page) {
    if (page.url().match(/\/oidc\/realms\/demo\/protocol\/openid-connect\/auth/)) {
      return {
        message: () => "passed",
        pass: true,
      };
    }

    return {
      message: () => `toBeOnLoginPage() assertion failed.\nExpected "/oidc/realms/demo/protocol/openid-connect/auth", got "${page.url()}".`,
      pass: false,
    };
  },
});

type Test = {
  thingPage: ThingPage,
  userPage: UserPage,
}

export const test = playwrightTest.extend<Test>({
  thingPage: async ({ page }, use) => {
    await use(new ThingPage(page));
  },
  userPage: async ({ page }, use) => {
    await use(new UserPage(page));
  },
});

export { expect };
