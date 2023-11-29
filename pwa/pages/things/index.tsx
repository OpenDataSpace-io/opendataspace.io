import React from 'react';
import { fetchUtils, Admin, Resource, ListGuesser } from 'react-admin';
import simpleRestProvider from 'ra-data-simple-rest';

const httpClient = (url, options = {}) => {
    return fetchUtils.fetchJson(url, options);
}

const dataProvider = simpleRestProvider('/things', httpClient);

const App = () => (
    <Admin dataProvider={dataProvider}>
        <Resource name="posts" list={ListGuesser} />
    </Admin>
);

export default App;