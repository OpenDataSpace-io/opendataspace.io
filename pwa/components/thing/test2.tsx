import React from 'react';
import { type Session } from "next-auth";
import { fetchUtils, Admin, Resource, ListGuesser } from 'react-admin';
import { type DataProvider, Layout, type LayoutProps, localStorageStore, resolveBrowserLocale } from "react-admin";
import simpleRestProvider from 'ra-data-simple-rest';
import { useContext, useRef, useState } from "react";
import { ENTRYPOINT } from "@/config/entrypoint";
import { fetchHydra, HydraAdmin, hydraDataProvider, OpenApiAdmin, ResourceGuesser } from "@api-platform/admin";
import { parseHydraDocumentation } from "@api-platform/api-doc-parser";

const httpClient = (url, options = {}) => {
    return fetchUtils.fetchJson(url, options);
}

const apiDocumentationParser = (session: Session) => async () => {
    try {
      return await parseHydraDocumentation(ENTRYPOINT, {
          headers: {
              // @ts-ignore
              Authorization: `Bearer ${session?.accessToken}`,
          },
      });
    } catch (result) {
      // @ts-ignore
      const {api, response, status} = result;
      if (status !== 401 || !response) {
        throw result;
      }
  
      return {
        api,
        response,
        status,
      };
    }
  };

//const dataProvider = simpleRestProvider('./things.jsonld', httpClient);
const dataProvider = useRef<DataProvider>();
dataProvider.current = hydraDataProvider({
    entrypoint: ENTRYPOINT,
    httpClient: (url: URL, options = {}) => fetchHydra(url, {
      ...options,
      headers: {
        // @ts-ignore
        Authorization: `Bearer ${session?.accessToken}`,
      },
    }),
    apiDocumentationParser: apiDocumentationParser(session),
  });

const ThingAppTest2 = () => (
    <Admin dataProvider={dataProvider}>
        <Resource name="things" list={ListGuesser} />
    </Admin>
);

export default ThingAppTest2;