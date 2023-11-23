import Head from "next/head";

import { HydraAdmin, fetchHydra, hydraDataProvider, ResourceGuesser } from "@api-platform/admin";
import { parseHydraDocumentation } from "@api-platform/api-doc-parser";
import { Admin, Resource } from 'react-admin';

//const entrypoint = process.env.NEXT_PUBLIC_ENTRYPOINT;
const entrypoint: string = typeof window === "undefined" ? process.env.NEXT_PUBLIC_ENTRYPOINT : window.origin;

const dataProvider = hydraDataProvider({
  entrypoint,
  httpClient: fetchHydra,
  apiDocumentationParser: parseHydraDocumentation,
  mercure: true,
  useEmbedded: false,
})

const ThingsPage = () => {
  return (
    <div>
      <Head>
        <title>OpenDataSpace.io - Things</title>
      </Head>
      <h1>ThingsPage</h1>

      <Admin dataProvider={dataProvider}>
        <Resource name="things" />
      </Admin>
    </div>
  );
};

export default ThingsPage;