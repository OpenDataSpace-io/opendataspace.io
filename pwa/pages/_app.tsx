import { type AppProps } from "next/app";
import { type Session } from "next-auth";
import { SessionProvider } from "next-auth/react";
import type { DehydratedState } from "react-query";
import { Layout } from "@/components/common/Layout";
import "@/styles/globals.css";
import "@fontsource/poppins";
import "@fontsource/poppins/600.css";
import "@fontsource/poppins/700.css";
//import nextI18NextConfig from '../next-i18next.config.js';
import { appWithTranslation } from 'next-i18next';

const App = ({ Component, pageProps }: AppProps<{
  dehydratedState: DehydratedState,
  session: Session,
}>) => {
  return (
    <SessionProvider session={pageProps.session}>
      <Layout dehydratedState={pageProps.dehydratedState}>
        <Component {...pageProps}/>
      </Layout>
    </SessionProvider>
  );
}

export default appWithTranslation(App/*, nextI18NextConfig*/);
