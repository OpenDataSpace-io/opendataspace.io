import { SyntheticEvent, useMemo, useRef, useState } from "react";
import Autocomplete from "@mui/material/Autocomplete";
import { debounce } from "@mui/material";
import { TextInput, type TextInputProps, useInput } from "react-admin";
import { useQuery } from "react-query";
import { useWatch } from "react-hook-form";

interface Result {
  name: string;
}

interface ThingInputProps extends TextInputProps {
  name?: string;
}

export const ThingInput = (props: ThingInputProps) => {
  const { field: { ref, ...field} } = useInput(props);
  const name = useWatch({ name: "name" });
  const controller = useRef<AbortController | undefined>();
  const [searchQuery, setSearchQuery] = useState<string>("");
  const [value, setValue] = useState<Result | null | undefined>(
    !!name && !!field.value ? { name: name, value: field.value } : undefined
  );
  const { isLoading, data, isFetched } = useQuery<Result[]>(
    ["search", searchQuery],
    async () => {
      if (controller.current) {
        controller.current.abort();
      }
      controller.current = new AbortController();

      return await data;
    },
    {
      enabled: !!searchQuery,
    }
  );
  const onInputChange = useMemo(() =>
      debounce((event: SyntheticEvent, value: string) => setSearchQuery(value), 400),
      []
  );
  const onChange = (event: SyntheticEvent, value: Result | null | undefined) => {
    field.onChange(value?.value);
    setValue(value);
  };

  return <Autocomplete
      value={value}
      options={!isFetched ? (!!value ? [value] : []) : (data ?? [])}
      isOptionEqualToValue={(option, val) => option?.value === (val?.value || value?.value)}
      onChange={onChange}
      onInputChange={onInputChange}
      getOptionLabel={(option: Result | undefined) => !!option ? `${option.name}` : "No options"}
      style={{ width: 500 }}
      loading={isLoading}
      renderInput={(params) => (
        <TextInput {...params} {...field} {...props}/>
      )}
  />;
};
ThingInput.displayName = "ThingInput";
ThingInput.defaultProps = { label: "Name Thing" };
