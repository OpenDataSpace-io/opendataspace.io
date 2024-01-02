import { required } from "react-admin";
import { TextInput } from "react-admin";
import { DateTimeInput } from 'react-admin';

export const Form = () => (
  <>
    <TextInput source="name" validate={[required()]} fullWidth />
    <DateTimeInput source="dateCreated" validate={[required()]} fullWidth />
    <DateTimeInput source="dateModified" validate={[required()]} fullWidth />
  </>
);
