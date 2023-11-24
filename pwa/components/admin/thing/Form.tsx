import { required } from "react-admin";

import { BookInput } from "@/components/admin/thing/ThingInput";

export const Form = () => (
  <>
    <ThingInput source="thing" validate={required()}/>
  </>
);
