//import Form from "@rjsf/core";
import Form from '@rjsf/mui';
import { useTranslation } from 'next-i18next';
const CustomForm = (props:any) => {
  const { schema, uiSchema, formData, onSubmit } = props;
  const { t } = useTranslation('common');

  return (
      <Form {...props}>
        <div>
          <button className="px-10 py-4 font-semibold text-sm bg-cyan-500 text-white rounded shadow-sm mx-auto" type="submit">{t('things.edit.save')}</button>
        </div>
      </Form>
  );
};

export default CustomForm;