import React from 'react';
import { fetchUtils, Admin, Resource, ListGuesser } from 'react-admin';
import simpleRestProvider from 'ra-data-simple-rest';
import { type DataProvider, Layout, type LayoutProps, localStorageStore, resolveBrowserLocale } from "react-admin";
import { fetchHydra, HydraAdmin, hydraDataProvider, OpenApiAdmin } from "@api-platform/admin";
import { AdminGuesser, ResourceGuesser } from "@api-platform/admin";
import { ENTRYPOINT } from "@/config/entrypoint";

import { useContext, useRef, useState } from "react";

const httpClient = (url, options = {}) => {
    return fetchUtils.fetchJson(url, options);
}

const dataProvider = simpleRestProvider('http://localhost/', httpClient);
/*const dataProvider = useRef<DataProvider>();
dataProvider.current = hydraDataProvider({
    entrypoint: ENTRYPOINT,
    httpClient: (url: URL, options = {}) => fetchHydra(url, {
      ...options,
    }),
    //apiDocumentationParser: apiDocumentationParser(session),
  });
*/


const ThingsApp = () => (
    <Admin dataProvider={dataProvider}>
        <Resource name="dashboard/things" list={ListGuesser} />
    </Admin>
);

// Admin by api-platform/admin
/*const ThingsApp = () => (
    <AdminGuesser dataProvider={dataProvider}>
        <ResourceGuesser name="things" list={ListGuesser} />
    </AdminGuesser>
);*/

export default ThingsApp;