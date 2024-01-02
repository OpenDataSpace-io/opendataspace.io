import { FieldGuesser, type ListGuesserProps } from "@api-platform/admin";
import {
  TextInput,
  Datagrid,
  useRecordContext,
  type UseRecordContextParams,
  List as ReactAdminList,
  EditButton,
} from "react-admin";

import { ShowButton } from "@/components/admin/book/ShowButton";

const ConditionField = (props: UseRecordContextParams) => {
  const record = useRecordContext(props);

  return !!record && !!record.condition ? <span>{record.condition.replace(/https:\/\/schema\.org\/(.+)Condition$/, "$1")}</span> : null;
};
ConditionField.defaultProps = { label: "Condition" };

const filters = [
  <TextInput source="name" key="name"/>,
];

export const List = (props: ListGuesserProps) => (
  <ReactAdminList {...props} filters={filters} exporter={false} title="Books">
    <Datagrid>
      <FieldGuesser source="name"/>
      <ShowButton/>
      <EditButton/>
    </Datagrid>
  </ReactAdminList>
);
