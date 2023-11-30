import polyglotI18nProvider from 'ra-i18n-polyglot';
import {
    Admin,
    CustomRoutes,
    Resource,
    localStorageStore,
    useStore,
    StoreContextProvider,
    fetchUtils,
} from 'react-admin';
import { Route } from 'react-router';

import authProvider from '@/components/authProvider';
//import { Dashboard } from './dashboard';
//import dataProviderFactory from './dataProvider';
import englishMessages from '@/i18n/en';
//import { Layout, Login } from './layout';
import simpleRestProvider from 'ra-data-simple-rest';

const httpClient = (url, options = {}) => {
    return fetchUtils.fetchJson(url, options);
}

const dataProvider = simpleRestProvider('./things.jsonld', httpClient);

const i18nProvider = polyglotI18nProvider(
    locale => {
        if (locale === 'fr') {
            return import('@/i18n/fr').then(messages => messages.default);
        }

        // Always fallback on english
        return englishMessages;
    },
    'en',
    [
        { locale: 'en', name: 'English' },
        { locale: 'fr', name: 'Français' },
    ]
);

const store = localStorageStore(undefined, 'ECommerce');

const App = () => {
    const [themeName] = useStore<ThemeName>('themeName', 'soft');
    const lightTheme = themes.find(theme => theme.name === themeName)?.light;
    const darkTheme = themes.find(theme => theme.name === themeName)?.dark;
    return (
        <Admin
            title=""
            dataProvider={dataProvider}
            //store={store}
            //authProvider={authProvider}
            //dashboard={Dashboard}
            //loginPage={Login}
            //layout={Layout}
            i18nProvider={i18nProvider}
            disableTelemetry
            lightTheme={lightTheme}
            darkTheme={darkTheme}
            defaultTheme="light"
        >
            <Resource name="things" />
        </Admin>
    );
};

const AppWrapper = () => (
    <StoreContextProvider value={store}>
        <App />
    </StoreContextProvider>
);

export default AppWrapper;