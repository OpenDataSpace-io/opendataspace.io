import { required } from "react-admin";
import { DateTimeInput } from 'react-admin';
import { TextInput } from 'react-admin';

import { ThingInput } from "@/components/dashboard/thing/ThingInput";

export const Form = () => (
  <>
    <TextInput source="@context" />
    <TextInput source="@id" validate={required()}/>
    <TextInput source="@type" validate={required()}/>
    <TextInput source="name" validate={required()}/>
    <DateTimeInput source="dateCreated" validate={required()} />
    <DateTimeInput source="dateModified" validate={required()}/>
  </>
);
