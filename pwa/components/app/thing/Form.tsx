import { 
  DateTimeInput, 
  TextInput, 
  ArrayInput, 
  SimpleFormIterator,
  NumberInput,
  required 
} from "react-admin";
import JsonTextInput from './JsonTextInput';

import { ThingInput } from "@/components/app/thing/ThingInput";

export const Form = () => (
  <>
    <TextInput source="@context" />
    <TextInput source="@id" validate={required()}/>
    <TextInput source="@type" validate={required()}/>
    <TextInput source="name" validate={required()}/>
    <DateTimeInput source="dateCreated" validate={required()} />
    <DateTimeInput source="dateModified" validate={required()}/>
    <JsonTextInput label="My JSON Field" multiline fullWidth/>
  </>
);