import Head from "next/head";

import { HydraAdmin, fetchHydra, hydraDataProvider, ResourceGuesser } from "@api-platform/admin";
import { parseHydraDocumentation } from "@api-platform/api-doc-parser";

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

      <HydraAdmin
        dataProvider={dataProvider}
        entrypoint={entrypoint}>
          <ResourceGuesser name="things" />
        </HydraAdmin>
    </div>
  );
};

export default ThingsPage;