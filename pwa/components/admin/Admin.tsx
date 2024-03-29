import Head from "next/head";
import { type Session } from "next-auth";
import { useContext, useRef, useState } from "react";
import { type DataProvider, Layout, type LayoutProps, localStorageStore, resolveBrowserLocale } from "react-admin";
import { signIn, useSession } from "next-auth/react";
import SyncLoader from "react-spinners/SyncLoader";
import polyglotI18nProvider from "ra-i18n-polyglot";
import englishMessages from "ra-language-english";
import frenchMessages from "ra-language-french";
import { fetchHydra, HydraAdmin, hydraDataProvider, OpenApiAdmin, ResourceGuesser } from "@api-platform/admin";
import { parseHydraDocumentation } from "@api-platform/api-doc-parser";

import DocContext from "@/components/admin/DocContext";
import authProvider from "@/components/admin/authProvider";
import AppBar from "@/components/admin/AppBar";
import Menu from "@/components/admin/Menu";
import { ENTRYPOINT } from "@/config/entrypoint";
import { type Thing } from "@/types/Thing";
import { List as ThingsList } from "@/components/admin/thing/List";
import { Create as ThingsCreate } from "@/components/admin/thing/Create";
import { Edit as ThingsEdit } from "@/components/admin/thing/Edit";

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

const messages = {
  fr: frenchMessages,
  en: englishMessages,
};
const i18nProvider = polyglotI18nProvider(
  // @ts-ignore
  (locale) => (messages[locale] ? messages[locale] : messages.en),
  resolveBrowserLocale(),
);

const MyLayout = (props: React.JSX.IntrinsicAttributes & LayoutProps) => <Layout {...props} appBar={AppBar} menu={Menu}/>;

const AdminUI = ({ session, children }: { session: Session, children?: React.ReactNode | undefined }) => {
  // @ts-ignore
  const dataProvider = useRef<DataProvider>();
  const { docType } = useContext(DocContext);

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

  return docType === "hydra" ? (
    <HydraAdmin
      requireAuth
      authProvider={authProvider}
      // @ts-ignore
      dataProvider={dataProvider.current}
      entrypoint={window.origin}
      i18nProvider={i18nProvider}
      layout={MyLayout}
    >
      {!!children && children}
    </HydraAdmin>
  ) : (
    <OpenApiAdmin
      requireAuth
      authProvider={authProvider}
      // @ts-ignore
      dataProvider={dataProvider.current}
      entrypoint={window.origin}
      docEntrypoint={`${window.origin}/docs.json`}
      i18nProvider={i18nProvider}
      layout={MyLayout}
    >
      {!!children && children}
    </OpenApiAdmin>
  );
};

const store = localStorageStore();
const AdminWithContext = ({ session }: { session: Session }) => {
  const [docType, setDocType] = useState(
    store.getItem<string>("docType", "hydra"),
  );

  return (
    <DocContext.Provider
      value={{
        docType,
        setDocType,
      }}>
      <AdminUI session={session}>
        <ResourceGuesser name="admin/things" list={ThingsList} create={ThingsCreate} edit={ThingsEdit} hasShow={false}
                         recordRepresentation={(record: Thing) => record.name}/>
      </AdminUI>
    </DocContext.Provider>
  );
};

const AdminWithOIDC = () => {
  // Can't use next-auth/middleware because of https://github.com/nextauthjs/next-auth/discussions/7488
  const { data: session, status } = useSession();

  if (status === "loading") {
    return <SyncLoader size={8} color="#46B6BF"/>;
  }

  // @ts-ignore
  if (!session || session?.error === "RefreshAccessTokenError") {
    (async() => await signIn("keycloak"))();

    return;
  }

  return <AdminWithContext session={session}/>;
};

const Admin = () => (
  <>
    <Head>
      <title>API Platform Admin</title>
    </Head>

    {/*@ts-ignore*/}
    <AdminWithOIDC/>
  </>
);

export default Admin;
