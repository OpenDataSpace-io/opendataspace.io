//import Form from "@rjsf/core";
import Form from '@rjsf/mui';

const CustomForm = (props) => {
  const { schema, uiSchema, formData, onSubmit } = props;

  return (
    <form onSubmit={event => {event.preventDefault(); onSubmit({formData})}}>
      <button className="px-10 py-4 font-semibold text-sm bg-cyan-500 text-white rounded shadow-sm mx-auto" type="submit">Submit</button>
      <Form {...props} />
      <button className="px-10 py-4 font-semibold text-sm bg-cyan-500 text-white rounded shadow-sm mx-auto" type="submit" variant="contained">Submit</button>
    </form>
  );
};

export default CustomForm;