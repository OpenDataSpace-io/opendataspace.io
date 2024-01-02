import { EditGuesser, type EditGuesserProps } from "@api-platform/admin";
import { TopToolbar } from 'react-admin';

import { Form } from "@/components/admin/thing/Form";
import { ShowButton } from "@/components/admin/thing/ShowButton";

// @ts-ignore
const Actions = ({ data }) => (
  <TopToolbar>
    <ShowButton record={data} />
  </TopToolbar>
);
export const Edit = (props: EditGuesserProps) => (
  // @ts-ignore
  <EditGuesser {...props} title="Edit thing" actions={<Actions/>}>
    <Form/>
  </EditGuesser>
);
