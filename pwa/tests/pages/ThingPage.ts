import { Locator } from "@playwright/test";

import { type FiltersProps } from "@/utils/thing";
import { AbstractPage } from "./AbstractPage";

export class ThingPage extends AbstractPage {
  public async filter(filters: FiltersProps) {
    if (filters.name) {
      await this.page.getByTestId("filter-name").fill(filters.name);
    }

    return this.page;
  }

  public async gotoList(filters: URLSearchParams | undefined = undefined) {
    await this.registerMock();

    await this.page.goto(`/things${filters && filters.size > 0 ? `?${filters.toString()}` : ""}`);
    await this.page.waitForURL(/\/things/);
    await this.waitForDefaultBookToBeLoaded();

    return this.page;
  }
}
