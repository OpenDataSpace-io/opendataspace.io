import { required } from "react-admin";

import { ThingInput } from "@/components/admin/thing/ThingInput";

export const Form = () => (
  <>
    <ThingInput source="thing" validate={required()}/>
  </>
);
