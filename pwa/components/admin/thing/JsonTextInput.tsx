import React from 'react';
import { TextInput } from 'react-admin';

const JsonTextInput = (props: object) => (
    <TextInput {...props} format={(v: any) => JSON.stringify(v, null, 2)} parse={(v: any) => JSON.parse(v)} />
);

export default JsonTextInput;