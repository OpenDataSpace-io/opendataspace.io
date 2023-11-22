import { AdminGuesser, ResourceGuesser } from "@api-platform/admin";
//import simpleRestProvider from 'ra-data-simple-rest';

//const dataProvider = simpleRestProvider('http://path.to.my.api/');
const entrypoint = process.env.NEXT_PUBLIC_ENTRYPOINT;

const Things = () => (
  <AdminGuesser
    entrypoint={entrypoint}
    //dataProvider={dataProvider}
   >
     <ResourceGuesser name="things" />
  </AdminGuesser>
);

export default Things;