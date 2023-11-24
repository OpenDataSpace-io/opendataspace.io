import { CreateGuesser, type CreateGuesserProps } from "@api-platform/admin";

import { Form } from "@/components/admin/thing/Form";

export const Create = (props: CreateGuesserProps) => (
  <CreateGuesser {...props} title="Create thing">
    <Form/>
  </CreateGuesser>
);
