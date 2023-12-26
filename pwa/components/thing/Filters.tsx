import {Formik} from "formik";
import {type FunctionComponent} from "react";
import {type UseMutationResult} from "react-query";
import {Checkbox, debounce, FormControlLabel, FormGroup, TextField, Typography} from "@mui/material";

import {type FiltersProps} from "@/utils/thing";
import {type FetchError, type FetchResponse} from "@/utils/dataAccess";
import {type PagedCollection} from "@/types/collection";
import {type Thing} from "@/types/Thing";

interface Props {
  filters: FiltersProps | undefined;
  mutation: UseMutationResult<FetchResponse<PagedCollection<Thing>>>;
}

export const Filters: FunctionComponent<Props> = ({ filters, mutation }) => (
  <Formik
    initialValues={filters ?? {}}
    enableReinitialize={true}
    onSubmit={(values, { setSubmitting, setStatus, setErrors }) => {
      mutation.mutate(
        values,
        {
          onSuccess: () => {
            setStatus({
              isValid: true,
            });
          },
          // @ts-ignore
          onError: (error: Error | FetchError) => {
            setStatus({
              isValid: false,
              msg: error.message,
            });
            if ("fields" in error) {
              setErrors(error.fields);
            }
          },
          onSettled: () => {
            setSubmitting(false);
          },
        }
      );
    }}
  >
    {({
      values,
      handleChange,
      handleSubmit,
      submitForm,
    }) => (
      <form onSubmit={handleSubmit}>
        <FormGroup className="mb-4">
          <FormControlLabel name="name" labelPlacement="top" className="!m-0" label={
            <Typography className="font-semibold w-full">Name</Typography>
          } control={
            <TextField value={values?.name ?? ""} placeholder="Search by name..." type="search"
                       data-testid="filter-name" variant="standard" className="w-full" onChange={(e) => {
                         handleChange(e);
                         debounce(submitForm, 1000)();
                       }}
            />
          }/>
        </FormGroup>
      </form>
    )}
  </Formik>
);