import { FieldGuesser, type ListGuesserProps } from "@api-platform/admin";
import {
  TextInput,
  Datagrid,
  useRecordContext,
  type UseRecordContextParams,
  List as ReactAdminList,
  EditButton,
} from "react-admin";

import { ShowButton } from "@/components/app/thing/ShowButton";

const filters = [
  <TextInput source="name" key="name"/>,
];

export const List = (props: ListGuesserProps) => (
  <ReactAdminList {...props} filters={filters} exporter={false} title="Things">
    <Datagrid>
      <FieldGuesser source="name"/>
      <FieldGuesser source="dateCreated"/>
      <FieldGuesser source="dateModified"/>
      <ShowButton/>
      <EditButton/>
    </Datagrid>
  </ReactAdminList>
);
