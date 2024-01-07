import { AbstractPage } from "./AbstractPage";

interface FiltersProps {
  name?: string | undefined;
  dateCreated?: string | undefined;
  dateModified?: string | undefined;
}

export class ThingPage extends AbstractPage {
  public async gotoList() {
    await this.page.goto("/admin");
    await this.login();
    await this.page.waitForURL(/\/admin#\/admin/);
    await this.page.locator(".RaSidebar-fixed").getByText("Things").click();

    return this.page;
  }

  public async getDefaultThing() {
    return this.page.locator(".datagrid-body tr").filter({ hasText: "Eiger" });
  }

  public async filter(filters: FiltersProps) {
    if (filters.name) {
      await this.page.getByLabel("Add filter").click();
      await this.page.getByRole("menu").getByText("Name").waitFor({ state: "visible" });
      await this.page.getByRole("menu").getByText("Name").click();
      await this.page.getByLabel("Name").fill(filters.name);
      await this.page.waitForResponse(/\/things/);
    }

    return this.page;
  }
}
