import { Locator } from "@playwright/test";

import { type FiltersProps } from "@utils/thing";
import { AbstractPage } from "./AbstractPage";

export class ThingPage extends AbstractPage {
  public async filter(filters: FiltersProps) {
    if (filters.name) {
      await this.page.getByTestId("filter-name").fill(filters.name);
    }

    if (filters.order) {
      await this.page.getByTestId("sort").click();
      await this.page.getByText(filters.order).waitFor({ state: "visible" });
      await this.page.getByText(filters.order).click();
    }

    return this.page;
  }

  public async gotoList(filters: URLSearchParams | undefined = undefined) {
    await this.page.goto(`/things${filters && filters.size > 0 ? `?${filters.toString()}` : ""}`);
    await this.page.waitForURL(/\/things/);

    return this.page;
  }

  public async gotoDefaultThing() {
    await this.gotoList(new URLSearchParams("name=Eiger"));
    await (await this.getDefaultThing()).getByText("Eiger").first().click();
    await this.page.waitForURL(/\/things\/.*\/*/);

    return this.page;
  }
}
