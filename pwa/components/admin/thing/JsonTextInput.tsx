import React from 'react';
import { TextInput } from 'react-admin';

const JsonTextInput = props => (
    <TextInput {...props} format={v => JSON.stringify(v, null, 2)} parse={v => JSON.parse(v)} />
);

export default JsonTextInput;