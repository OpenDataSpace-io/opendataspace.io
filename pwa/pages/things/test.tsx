import Head from "next/head";
import { useEffect, useState } from "react";
import authProvider from '@/components/admin/authProvider';
import { Datagrid } from "react-admin";
import polyglotI18nProvider from 'ra-i18n-polyglot';
import englishMessages from 'ra-language-english';

const i18nProvider = polyglotI18nProvider(
  locale => {

      // Always fallback on english
      return englishMessages;
  },
  'en',
  [
      { locale: 'en', name: 'English' },
      { locale: 'es', name: 'Español' },
      { locale: 'de', name: 'Detutsch'},
      { locale: 'fr', name: 'Français' },
  ]
);

const AdminTest = () => {
  // Load the admin client-side
  const [DynamicAdmin, setDynamicAdmin] = useState(<p>Loading...</p>);
  useEffect(() => {
    (async () => {
      const HydraAdmin = (await import("@api-platform/admin")).HydraAdmin;
      const ResourceGuesser = (await import("@api-platform/admin")).ResourceGuesser;
      const FieldGuesser = (await import("@api-platform/admin")).FieldGuesser;

      setDynamicAdmin(<HydraAdmin 
        entrypoint={window.origin}
        //authProvider={authProvider}
        i18nProvider={i18nProvider}
        >
          <ResourceGuesser name="things">
          </ResourceGuesser>
        </HydraAdmin>);
    })();
  }, []);

  return (
    <>
      <Head>
        <title>API Platform Admin</title>
      </Head>

      {DynamicAdmin}
    </>
  );
};
export default AdminTest;
