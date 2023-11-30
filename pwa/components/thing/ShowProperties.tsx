import { Show, SimpleShowLayout, TextField, FunctionField } from 'react-admin';

const ShowProperties = (props) => (
    <Show {...props}>
        <SimpleShowLayout>
            <TextField source="id" />
            <FunctionField source="data" render={record => JSON.stringify(record.data, null, 2)} />
        </SimpleShowLayout>
    </Show>
);

export default ShowProperties;